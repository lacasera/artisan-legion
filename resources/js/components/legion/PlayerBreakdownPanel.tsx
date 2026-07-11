import { Link } from '@inertiajs/react';
import type { LegionPlayer } from '@/components/legion/types';
import { formatPoints } from '@/lib/format';
import { githubUrl } from '@/lib/github';
import { cn } from '@/lib/utils';
import { show as cardsShow } from '@/routes/cards';

const TOTALS = [
    ['contributions', 'COMMITS'],
    ['stars', 'STARS'],
    ['followers', 'FOLLOWERS'],
] as const;

export default function PlayerBreakdownPanel({
    player,
}: {
    player: LegionPlayer;
}) {
    const breakdown = player.breakdown;

    if (breakdown === null) {
        return null;
    }

    return (
        <div className="flex flex-col gap-3.5">
            <span className="font-mono text-xs font-semibold tracking-caps text-fg-2">
                SOLDIER STATS
            </span>
            <div className="flex flex-col gap-4 rounded-lg border border-line-2 bg-ink-850 p-4">
                <div className="flex items-center justify-between">
                    <div className="flex flex-col gap-0.5">
                        <span
                            className={cn(
                                'font-display text-4xl leading-none font-bold',
                                breakdown.ovr >= 90
                                    ? 'text-cue-500'
                                    : 'text-fg-1',
                            )}
                        >
                            {breakdown.ovr}
                        </span>
                        <span className="font-mono text-xs text-live-500">
                            <a
                                href={githubUrl(player.handle)}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="hover:text-live-400"
                            >
                                @{player.handle}
                            </a>{' '}
                            · {breakdown.position}
                        </span>
                    </div>
                    <Link
                        href={cardsShow(player.handle)}
                        className="font-mono text-[11px] text-fg-3 hover:text-fg-1"
                    >
                        full card →
                    </Link>
                </div>

                <div className="grid grid-cols-3 overflow-hidden rounded-lg border border-line-1 bg-ink-900">
                    {TOTALS.map(([key, label]) => (
                        <div
                            key={key}
                            className="flex flex-col gap-1 border-r border-line-1 px-2 py-3 text-center last:border-r-0"
                        >
                            <span className="font-mono text-base font-bold text-fg-1">
                                {formatPoints(breakdown[key])}
                            </span>
                            <span className="font-mono text-[8px] font-semibold tracking-widest text-fg-4">
                                {label}
                            </span>
                        </div>
                    ))}
                </div>

                <div className="flex flex-col gap-2">
                    {breakdown.languages.map((language) => (
                        <div
                            key={language.name}
                            className="flex flex-col gap-1"
                        >
                            <div className="flex items-baseline justify-between">
                                <span className="font-mono text-[11px] tracking-widest text-fg-2">
                                    {language.name}
                                </span>
                                <span className="font-mono text-xs font-bold text-fg-1">
                                    {language.score}
                                </span>
                            </div>
                            <div className="h-1 overflow-hidden rounded-full bg-ink-700">
                                <div
                                    className="h-full rounded-full bg-signal-500"
                                    style={{
                                        width: `${Math.max(2, language.sharePct)}%`,
                                    }}
                                />
                            </div>
                        </div>
                    ))}
                </div>

                <div className="flex items-center gap-3 rounded-lg border border-line-1 bg-ink-900 px-3 py-2.5">
                    <span className="rounded-xs border border-line-2 bg-white/3 px-2 py-1 font-mono text-xs font-bold tracking-widest text-fg-1">
                        {breakdown.position}
                    </span>
                    <span className="text-[11px] leading-relaxed text-fg-3">
                        {breakdown.positionRule}
                    </span>
                </div>
            </div>
        </div>
    );
}
