<?php

declare(strict_types=1);

namespace App\Services\GitHub;

use App\Data\GitHubProfileData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GraphQLGitHubClient implements GitHubClient
{
    private const string ENDPOINT = 'https://api.github.com/graphql';

    private const string QUERY = <<<'GRAPHQL'
        query DevCard($login: String!) {
            user(login: $login) {
                login
                name
                avatarUrl
                location
                followers { totalCount }
                contributionsCollection {
                    restrictedContributionsCount
                    contributionCalendar { totalContributions }
                }
                repositories(first: 100, ownerAffiliations: OWNER, isFork: false, orderBy: {field: STARGAZERS, direction: DESC}) {
                    nodes {
                        stargazerCount
                        pushedAt
                        languages(first: 10, orderBy: {field: SIZE, direction: DESC}) {
                            edges {
                                size
                                node { name }
                            }
                        }
                    }
                }
                manifests: repositories(first: 8, ownerAffiliations: OWNER, isFork: false, orderBy: {field: STARGAZERS, direction: DESC}) {
                    nodes {
                        composerJson: object(expression: "HEAD:composer.json") {
                            ... on Blob { text }
                        }
                        packageJson: object(expression: "HEAD:package.json") {
                            ... on Blob { text }
                        }
                    }
                }
            }
        }
        GRAPHQL;

    private const array FRAMEWORK_MAP = [
        'laravel/framework' => 'LARAVEL',
        'livewire/livewire' => 'LIVEWIRE',
        'symfony/framework-bundle' => 'SYMFONY',
        'react' => 'REACT',
        'vue' => 'VUE',
        'next' => 'NEXT.JS',
        'nuxt' => 'NUXT',
        'svelte' => 'SVELTE',
        '@angular/core' => 'ANGULAR',
        'tailwindcss' => 'TAILWIND',
        'express' => 'EXPRESS',
        'alpinejs' => 'ALPINE',
    ];

    private const string CONTRIBUTIONS_QUERY = <<<'GRAPHQL'
        query DevContributions($login: String!) {
            user(login: $login) {
                contributionsCollection {
                    restrictedContributionsCount
                    contributionCalendar { totalContributions }
                }
            }
        }
        GRAPHQL;

    public function fetchProfile(string $username): ?GitHubProfileData
    {
        $response = $this->query(self::QUERY, $username);

        $user = $response->json('data.user');

        if ($user === null) {
            return null;
        }

        $repositories = (array) data_get($user, 'repositories.nodes', []);

        return new GitHubProfileData(
            login: (string) data_get($user, 'login'),
            name: data_get($user, 'name'),
            avatarUrl: data_get($user, 'avatarUrl'),
            location: data_get($user, 'location'),
            followers: (int) data_get($user, 'followers.totalCount', 0),
            totalContributions: $this->publicContributions($user),
            totalStars: (int) collect($repositories)->sum('stargazerCount'),
            languages: $this->aggregateLanguages($repositories),
            frameworks: $this->inferFrameworks($user),
        );
    }

    /**
     * Frameworks are display chips only — never rating inputs. Each counts
     * once per repo; the three most widespread win.
     *
     * @return list<string>
     */
    private function inferFrameworks(mixed $user): array
    {
        $counts = [];

        foreach ((array) data_get($user, 'manifests.nodes', []) as $node) {
            $dependencies = [];

            foreach (['composerJson', 'packageJson'] as $key) {
                $text = data_get($node, $key.'.text');

                if (! is_string($text)) {
                    continue;
                }

                $manifest = json_decode($text, true);

                if (! is_array($manifest)) {
                    continue;
                }

                foreach (['require', 'require-dev', 'dependencies', 'devDependencies'] as $section) {
                    $dependencies = [...$dependencies, ...array_keys((array) ($manifest[$section] ?? []))];
                }
            }

            $repoFrameworks = [];

            foreach ($dependencies as $dependency) {
                $label = self::FRAMEWORK_MAP[$dependency] ?? null;

                if ($label !== null) {
                    $repoFrameworks[$label] = true;
                }
            }

            foreach (array_keys($repoFrameworks) as $label) {
                $counts[$label] = ($counts[$label] ?? 0) + 1;
            }
        }

        arsort($counts);

        return array_slice(array_keys($counts), 0, 3);
    }

    public function fetchContributionCount(string $username): ?int
    {
        $response = $this->query(self::CONTRIBUTIONS_QUERY, $username);

        $user = $response->json('data.user');

        if ($user === null) {
            return null;
        }

        return $this->publicContributions($user);
    }

    /**
     * The calendar total includes anonymized private contributions for users
     * who opted in — subtract them so ratings and war points count open
     * source only, identically for everyone.
     */
    private function publicContributions(mixed $user): int
    {
        $total = (int) data_get($user, 'contributionsCollection.contributionCalendar.totalContributions', 0);
        $restricted = (int) data_get($user, 'contributionsCollection.restrictedContributionsCount', 0);

        return max(0, $total - $restricted);
    }

    private function query(string $query, string $username): Response
    {
        $response = Http::withToken((string) config('services.github.token'))
            ->timeout(15)
            ->connectTimeout(5)
            ->retry(2, 500, fn (\Throwable $exception) => $exception instanceof ConnectionException, throw: false)
            ->post(self::ENDPOINT, [
                'query' => $query,
                'variables' => ['login' => $username],
            ]);

        if ($response->status() === 403 || $response->status() === 429) {
            throw new GitHubRateLimitedException("GitHub rate limit hit while fetching {$username}.");
        }

        $response->throw();

        if ($this->isRateLimitedGraphQLError($response->json('errors', []))) {
            throw new GitHubRateLimitedException("GitHub GraphQL rate limit hit while fetching {$username}.");
        }

        return $response;
    }

    /**
     * @param  array<int|string, mixed>  $repositories
     * @return array<string, array{bytes: int, stars: int, recent: bool}>
     */
    private function aggregateLanguages(array $repositories): array
    {
        $recencyCutoff = now()->subDays(90);
        $languages = [];

        foreach ($repositories as $repository) {
            $pushedAt = data_get($repository, 'pushedAt');
            $isRecent = is_string($pushedAt) && now()->parse($pushedAt)->isAfter($recencyCutoff);
            $edges = (array) data_get($repository, 'languages.edges', []);
            $primary = data_get($edges, '0.node.name');

            foreach ($edges as $edge) {
                $name = data_get($edge, 'node.name');

                if (! is_string($name)) {
                    continue;
                }

                $languages[$name] ??= ['bytes' => 0, 'stars' => 0, 'recent' => false];
                $languages[$name]['bytes'] += (int) data_get($edge, 'size', 0);
                $languages[$name]['recent'] = $languages[$name]['recent'] || $isRecent;

                if ($name === $primary) {
                    $languages[$name]['stars'] += (int) data_get($repository, 'stargazerCount', 0);
                }
            }
        }

        uasort($languages, fn (array $a, array $b) => $b['bytes'] <=> $a['bytes']);

        return $languages;
    }

    /**
     * @param  list<array<string, mixed>>  $errors
     */
    private function isRateLimitedGraphQLError(array $errors): bool
    {
        return collect($errors)->contains(fn (array $error) => ($error['type'] ?? null) === 'RATE_LIMITED');
    }
}
