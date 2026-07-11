# Artisan Legion — Build Tasks

Layer 1 is sacred: ship it polished no matter what. Each layer is the fallback for the one above it.
Stack: Laravel 13 + Inertia v3 + React 19 + Wayfinder (repo stack, not the PRD's stale Livewire table).
Approach: design every screen against mock data first (Phase 1), then replace mocks with real data layer by layer (Phases 2–4). Deployment to Laravel Cloud is deliberately deferred to Sunday evening (Phase 5).

## Phase 0 — Decisions & dependencies

- [ ] Confirm stack: Inertia/React (repo) over PRD's Livewire/Volt table
- [x] PNG renderer installed: `spatie/browsershot` + `puppeteer` (chrome-headless-shell v150 in `~/.cache/puppeteer`)
- [x] Backend deps installed: `spatie/laravel-data`, `predis`
- [x] ~~laravel/reverb + laravel-echo/pusher-js~~ — DECISION (Sat): no websockets; the live board uses Inertia `usePoll` instead (https://inertiajs.com/docs/v3/data-props/polling)
- [ ] `@tanstack/react-query` — install only if a real client-side fetch need appears (none so far; Inertia props cover everything)
- [ ] Create GitHub personal access token (server-side, public data only) and add to `.env`

## Phase 1 — Design everything with mock data (Sat AM)

Every screen is built against DTO contracts fed by a mock provider. Later phases swap in real data behind the same contracts without touching the UI.

- [x] Type contracts for card/legion/war data (TS `types.ts` per module; PHP `app/Data/` DTOs land in Phase 2 with spatie/laravel-data)
- [x] Mock data fixtures (`resources/js/lib/mock/`) — swapped for Inertia props from the real client in Phase 2
- [x] Fixture devs covering the rating range: gold 90+ (taylorotwell), mid-tier (rauchg), monoglot specialist (fabpot), free agent/no-country (0xkofi), deterministic pseudo-dev for any other username
- [x] Landing page: single username input → lookup → card (plus lookup/reveal screen: terminal log, sealed-card scan beam, enlistment steps)
- [x] Bento card UI: hero tile (OVR chip, name, handle, position chip), 4 language stat tiles, footer tile (flag, country, national rank)
- [x] Gold treatment at OVR 90+ only (animated foil); vermilion below
- [x] Load display/mono fonts (Space Grotesk, Inter, JetBrains Mono via bunny)
- [x] Edge-case states from mocks: monoglot SPECIALIST tile, free-agent no-country card
- [x] Edge-case state: ghost account "not enough public activity" screen (any `ghost*` username in mock phase)
- [x] Legion page (flag, standing, war points, pitch XI, captain, reserves, pushing-now) from mock roster
- [x] Legions index page (ranked list, flags, soldier counts, war points; TopNav "Legions" links here)
- [x] Weekly war leaderboard page from mock scores, live-ticking with gain flashes and countdown
- [x] Card → PNG rendering + OG meta tags (Browsershot + Puppeteer; `/cards/{username}/card.png`, daily-cached in `storage/app/cards`; og:image/twitter:card served from Blade — verified with a real render of taylorotwell)

## Phase 2 — Layer 1: real card data (Sat PM) — PROTECT

### GitHub data
- [x] GraphQL client (`GitHubClient` interface + `GraphQLGitHubClient`): one query for `contributionsCollection`, per-repo `languages`, stars, followers, location; timeouts, connection retry, rate-limit detection (HTTP 403/429 + GraphQL `RATE_LIMITED`)
- [x] Caching contract via `Cache` facade (database store locally, Redis on Cloud): hits cached daily, misses (ghost/unknown/API failure) cached 1h as back-off; rate-limited refreshes fall back to the persisted card
- [x] Feature tests for the GraphQL client (mocked HTTP) and cache behavior

### Rating engine
- [x] Per-language score (0–99): volume (bytes) + impact (stars on primary repos) + recency (pushed <90d)
- [x] Log curve + clamp: activity-based anchor, #1 language lands the anchor, tail decays into the 60s–70s
- [x] OVR (40–99): weighted blend of top-language scores + activity + impact; gold reachable only for heavyweights
- [x] Position lookup from language mix (≥2500 contributions → ST, infra-heavy → GK, backend → CDM, frontend → LW, breadth → CAM)
- [x] Tuned against 10 real handles incl. `taylorotwell` (gold 92 ST) and `lacasera` (67 CM): followers added to the activity anchor so org-hosted maintainers aren't star-starved; ST threshold raised to 5000 contributions for position variety; US "City, ST" locations resolve to USA
- [x] Unit tests for rating math (ghost detection, curve ordering/range, gold vs modest, position table)

### Data model & wiring
- [x] `devs` migration + model (username, name, avatar, location, nation, ovr, position, raw_stats json, last_refreshed_at, `public_id` ULID via `HasPublicId`, SoftDeletes)
- [x] `dev_languages` migration + model (dev_id, language, score, rank)
- [x] Factories for both models (`gold()`, `freeAgent()` states)
- [x] Card generation flow: lookup dispatches queued `StrikeCardJob` (unique per username) to warm the cache; card page reads through `DevCardService`
- [x] Swap mock provider for real data on the card page (`dev` Inertia prop, ULID as `id`; ghost/free-agent/specialist states now data-driven)
- [x] Verified PNG + OG output with real cards (fresh gold taylorotwell render, USA · NAT #1)
- [x] Feature tests: card show (struck card, ghost, unknown user, OG tags), lookup job dispatch, service persistence/caching/degradation

## Phase 3 — Layer 2: real legions ✅

- [x] Location parser: `CountryResolver` (Phase 2) buckets free-text locations incl. US "City, ST"; no location → World XI (`WLD`)
- [x] Auto-roster: every struck card persists a `Dev` with nation — rostering is implicit
- [x] `LegionService` + DTOs: top XI by OVR with greedy 4–3–3 formation slotting (natural rows by position, overflow defense-first), captain = highest OVR, up to 6 reserves, avg OVR, legion rank, 24h enlistment count
- [x] Legion page on real data: adaptive formation label, real captain card, reserves, FRESH ENLISTMENTS panel (replaces mock "pushing now" until Phase 4)
- [x] Legions index on real data: ranked by top-XI average OVR, World XI bucket, empty state; 404 for unmustered legions
- [x] `nations.ts`: single source for ~27 nation names + CSS flags + World XI; mock flags/squad files deleted
- [x] National rank on card footer wired to real data (Phase 2's `rankLabelFor`)
- [x] `DevSeeder`: ~52 local-only fake devs for visual verification (`php artisan db:seed`, local env only)
- [x] Feature tests: formation slotting, captain/reserves, World XI fallback, ranking, empty states, lowercase codes (51 tests green)

## Phase 4 — Layer 3: real weekly war ✅

- [x] `weekly_scores` migration (dev_id, nation, week, points, per-day counters, unique dev+week) + polling columns on devs (additive migration); DB-backed board with a 5s cache (store-agnostic — Redis on Cloud, database locally)
- [x] Scheduler: `war:poll` every 5 minutes (`withoutOverlapping`) → dispatches `PollDevCommitsJob` per dev, staggered 2s apart to spread the GraphQL budget; jobs unique per dev
- [x] Polling job: cheap `fetchContributionCount` GraphQL query → award commit delta; first poll sets baseline only; rate-limit failures degrade silently
- [x] Anti-cheese from day one: 30 full-value commits/day, 31–100 at quarter value, >100 worth nothing; every point OVR-weighted (`ovr/50`)
- [x] Weekly reset: week keys roll on Sunday 00:00 UTC (implicit reset) + scheduled `war:reset` retires finished weeks Sunday 00:05
- [x] Live leaderboard via Inertia `usePoll` (10s partial reload of board/pushingCount), client-side countdown + gain-flash diffing; rank movement vs start of day; landing ticker + soldier count now real; `weekLabel` shared Inertia prop everywhere (nav, card edition strip)
- [x] Feature tests: delta scoring, diminishing cap, ceiling, OVR weight, board ranking/movement/World XI, job baseline/delta/rate-limit, both commands, controller props (70 tests green)

## Phase 5 — Deploy, ship & submit (Sun PM)

- [ ] Full test suite green; `vendor/bin/pint --dirty`; `npm run types:check` + `npm run lint`
- [ ] Push repo to Laravel Cloud; green pipeline
- [ ] Provision managed Postgres + Redis on Cloud; wire connections
- [ ] Confirm queues and scheduler running on Cloud
- [ ] Verify card → PNG rendering (headless Chrome) works on Cloud
- [ ] Confirm scale-to-zero enabled (part of the submission story)
- [ ] Vanity `*.laravel.cloud` subdomain
- [ ] Sweep: rate-limit back-off verified, OG unfurl checked in a real chat app
- [ ] Record 30–45s clip: `taylorotwell` gold card → Ghana XI squad page → leaderboard ticking live
- [ ] Clip caption names: scale-to-zero ($0 idle, ~500ms wake), managed queues polling commits, scheduler running the weekly league (Reverb dropped — don't claim it in the caption)
- [ ] Post submission

## Ship-readiness (Saturday, pre-deploy) ✅

- [x] Abuse hardening: GitHub-username + legion-code route patterns (404 before controllers), per-IP rate limits (lookup 10/min, cards 30/min)
- [x] Git repo pushed to `lacasera/artisan-legion` (Cloud deploys from GitHub)
- [x] Themed error pages: `error.tsx` (404/419/429 always, 500/503 in production) via `withExceptions` respond hook
- [x] Mobile sweep at 390px: card/sealed/ghost scale wrappers, pitch + chips resize, war board 4-column mobile grid, hero headline/form fixes
- [x] PNG insurance: `og-fallback.png` sealed-card banner served when Browsershot fails; `BROWSERSHOT_NO_SANDBOX` env flag for Cloud
- [x] Contribution counting: use the GitHub calendar total as-is (private counts included only when the dev opted in — honours their choice; we never see private code). Copy says generic "contributions". Ghost detection is byte-based so a real public codebase is never ghosted.

## Stretch (only if everything above is done)

- [x] Framework inference from dependency manifests (`composer.json`/`package.json` blobs in the same GraphQL query) → up to 3 display chips on the card (LARAVEL/REACT/VUE/TAILWIND/…); display-only, never rating input
