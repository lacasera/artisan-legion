import { Link } from '@inertiajs/react';
import { useEffect } from 'react';
import type { LegionPlayer } from '@/components/legion/types';
import { formatPoints } from '@/lib/format';
import { cn } from '@/lib/utils';
import { show as cardsShow } from '@/routes/cards';

export default function PlayerBreakdownDialog({
    player,
    onClose,
}: {
    player: LegionPlayer;
    onClose: () => void;
}) {
    useEffect(() => {
        const onKey = (event: KeyboardEvent) => {
            if (event.key === 'Escape') {
                onClose();
            }
        };
        document.addEventListener('keydown', onKey);

        return () => document.removeEventListener('keydown', onKey);
    }, [onClose]);

    const breakdown = player.breakdown;

    if (breakdown === null) {
        return null;
    }

    return (
        <div
            role="dialog"
            aria-modal="true"
            onClick={onClose}
            className="fixed inset-0 z-50 flex items-center justify-center bg-ink-950/80 px-6 py-10"
        >
            <div
                onClick={(event) => event.stopPropagation()}
                className="flex max-h-full w-full max-w-[420px] flex-col gap-4 overflow-y-auto rounded-xl border border-line-2 bg-ink-900 p-6 shadow-[0_24px_48px_rgba(0,0,0,0.55)]"
            >
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
                            @{player.handle} · {breakdown.position}
                        </span>
                    </div>
                    <button
                        type="button"
                        onClick={onClose}
                        aria-label="Close"
                        className="font-mono text-lg text-fg-4 hover:text-fg-1"
                    >
                        ✕
                    </button>
                </div>

                <div className="grid grid-cols-3 overflow-hidden rounded-lg border border-line-1 bg-ink-850">
                    {(
                        [
                            ['contributions', 'COMMITS'],
                            ['stars', 'STARS'],
                            ['followers', 'FOLLOWERS'],
                        ] as const
                    ).map(([key, label]) => (
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

                <div className="flex items-center gap-3 rounded-lg border border-line-1 bg-ink-850 px-3 py-2.5">
                    <span className="rounded-xs border border-line-2 bg-white/3 px-2 py-1 font-mono text-xs font-bold tracking-widest text-fg-1">
                        {breakdown.position}
                    </span>
                    <span className="text-[11px] leading-relaxed text-fg-3">
                        {breakdown.positionRule}
                    </span>
                </div>

                <Link
                    href={cardsShow(player.handle)}
                    className="rounded-sm bg-signal-500 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-signal-600"
                >
                    View full card →
                </Link>
            </div>
        </div>
    );
}
