import FlagChip from '@/components/shared/FlagChip';
import type { WarBoardRowProps } from '@/components/war/types';
import { formatPoints } from '@/lib/format';
import { FLAGS } from '@/lib/mock/flags';
import { cn } from '@/lib/utils';

export default function WarBoardRow({ row, isLeader }: WarBoardRowProps) {
    return (
        <div
            className={cn(
                'relative grid grid-cols-[72px_56px_1fr_150px_110px] items-center gap-4 border-b border-line-1 px-6 py-[15px] hover:bg-ink-850 lg:grid-cols-[72px_56px_1fr_260px_150px_110px] lg:px-12',
                row.isMine &&
                    'bg-live-500/4 shadow-[inset_0_0_0_1px_rgba(33,224,196,0.35)]',
            )}
        >
            <div className="flex items-center gap-2">
                <span
                    className={cn(
                        'font-display text-[22px] font-bold',
                        isLeader ? 'text-cue-500' : 'text-fg-1',
                    )}
                >
                    {row.rank}
                </span>
                <span
                    className={cn(
                        'font-mono text-[11px] font-bold',
                        row.move.includes('▲')
                            ? 'text-live-500'
                            : row.move.includes('▼')
                              ? 'text-danger-500'
                              : 'text-fg-4',
                    )}
                >
                    {row.move}
                </span>
            </div>
            <FlagChip flagCss={FLAGS[row.code]} />
            <div className="flex min-w-0 flex-col gap-px">
                <div className="flex items-center gap-2.5">
                    <span className="font-display text-[17px] font-bold tracking-[0.03em] uppercase">
                        {row.name}
                    </span>
                    {row.isMine && (
                        <span className="rounded-xs border border-live-500/40 px-[7px] py-0.5 font-mono text-[10px] font-bold tracking-widest text-live-400">
                            YOUR LEGION
                        </span>
                    )}
                    {row.pushing && (
                        <span className="size-1.5 animate-al-pulse rounded-full bg-live-500" />
                    )}
                </div>
                <span className="font-mono text-[11px] text-fg-4">
                    top soldier @{row.top}
                </span>
            </div>
            <div className="hidden h-[5px] overflow-hidden rounded-full bg-ink-700 lg:block">
                <div
                    className={cn(
                        'h-full rounded-full transition-[width] duration-900 ease-out',
                        row.isMine ? 'bg-live-500' : 'bg-signal-500',
                    )}
                    style={{ width: `${row.pct}%` }}
                />
            </div>
            <span className="text-right font-mono text-lg font-bold text-fg-1">
                {formatPoints(row.livePts)}
            </span>
            <div className="flex items-center justify-end gap-2">
                {row.gain > 0 && (
                    <span
                        key={row.gainKey}
                        className="animate-al-gain font-mono text-xs font-bold text-live-500"
                    >
                        +{row.gain}
                    </span>
                )}
                <span className="font-mono text-xs text-fg-3">
                    +{formatPoints(row.day)}
                </span>
            </div>
        </div>
    );
}
