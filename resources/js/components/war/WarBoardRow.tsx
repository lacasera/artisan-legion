import FlagChip from '@/components/shared/FlagChip';
import type { WarBoardRowProps } from '@/components/war/types';
import { formatPoints } from '@/lib/format';
import { githubUrl } from '@/lib/github';
import { flagCssFor, nationNameFor } from '@/lib/nations';
import { cn } from '@/lib/utils';

export default function WarBoardRow({ entry, gain }: WarBoardRowProps) {
    return (
        <div className="relative grid grid-cols-[52px_40px_1fr_90px] items-center gap-3 border-b border-line-1 px-4 py-[15px] hover:bg-ink-850 lg:grid-cols-[72px_56px_1fr_260px_150px_110px] lg:gap-4 lg:px-12">
            <div className="flex items-center gap-2">
                <span
                    className={cn(
                        'font-display text-[22px] font-bold',
                        entry.rank === 1 ? 'text-cue-500' : 'text-fg-1',
                    )}
                >
                    {String(entry.rank).padStart(2, '0')}
                </span>
                <span
                    className={cn(
                        'font-mono text-[11px] font-bold',
                        entry.move.includes('▲')
                            ? 'text-live-500'
                            : entry.move.includes('▼')
                              ? 'text-danger-500'
                              : 'text-fg-4',
                    )}
                >
                    {entry.move}
                </span>
            </div>
            <FlagChip flagCss={flagCssFor(entry.code)} />
            <div className="flex min-w-0 flex-col gap-px">
                <div className="flex items-center gap-2.5">
                    <span className="truncate font-display text-[17px] font-bold tracking-[0.03em] uppercase">
                        {nationNameFor(entry.code)}
                    </span>
                    {entry.pushing && (
                        <span className="size-1.5 animate-al-pulse rounded-full bg-live-500" />
                    )}
                </div>
                <span className="font-mono text-[11px] text-fg-4">
                    top soldier{' '}
                    <a
                        href={githubUrl(entry.topSoldier)}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="hover:text-fg-2"
                    >
                        @{entry.topSoldier}
                    </a>
                </span>
            </div>
            <div className="hidden h-[5px] overflow-hidden rounded-full bg-ink-700 lg:block">
                <div
                    className="h-full rounded-full bg-signal-500 transition-[width] duration-900 ease-out"
                    style={{ width: `${entry.pct}%` }}
                />
            </div>
            <span className="text-right font-mono text-lg font-bold text-fg-1">
                {formatPoints(entry.points)}
            </span>
            <div className="hidden items-center justify-end gap-2 lg:flex">
                {gain && (
                    <span
                        key={gain.key}
                        className="animate-al-gain font-mono text-xs font-bold text-live-500"
                    >
                        +{gain.amount}
                    </span>
                )}
                <span className="font-mono text-xs text-fg-3">
                    +{formatPoints(entry.dayPoints)}
                </span>
            </div>
        </div>
    );
}
