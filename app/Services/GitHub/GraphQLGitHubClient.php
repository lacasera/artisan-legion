<?php

declare(strict_types=1);

namespace App\Services\GitHub;

use App\Data\GitHubProfileData;
use Illuminate\Http\Client\ConnectionException;
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
            }
        }
        GRAPHQL;

    public function fetchProfile(string $username): ?GitHubProfileData
    {
        $response = Http::withToken((string) config('services.github.token'))
            ->timeout(15)
            ->connectTimeout(5)
            ->retry(2, 500, fn (\Throwable $exception) => $exception instanceof ConnectionException, throw: false)
            ->post(self::ENDPOINT, [
                'query' => self::QUERY,
                'variables' => ['login' => $username],
            ]);

        if ($response->status() === 403 || $response->status() === 429) {
            throw new GitHubRateLimitedException("GitHub rate limit hit while fetching {$username}.");
        }

        $response->throw();

        if ($this->isRateLimitedGraphQLError($response->json('errors', []))) {
            throw new GitHubRateLimitedException("GitHub GraphQL rate limit hit while fetching {$username}.");
        }

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
            totalContributions: (int) data_get($user, 'contributionsCollection.contributionCalendar.totalContributions', 0),
            totalStars: (int) collect($repositories)->sum('stargazerCount'),
            languages: $this->aggregateLanguages($repositories),
        );
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
