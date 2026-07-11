import FlagChip from '@/components/shared/FlagChip';
import LiveDot from '@/components/shared/LiveDot';
import type { WarBoardEntry } from '@/components/war/types';
import { formatPoints } from '@/lib/format';
import { flagCssFor } from '@/lib/nations';

export default function WarTicker({ ticker }: { ticker: WarBoardEntry[] }) {
    const entries = [...ticker, ...ticker];

    return (
        <div className="relative flex h-12 items-center overflow-hidden border-t border-line-1 bg-ink-950">
            <div className="relative z-2 flex h-full shrink-0 items-center gap-2 border-r border-line-1 bg-ink-950 px-5">
                <LiveDot />
                <span className="font-mono text-[11px] font-bold tracking-caps text-fg-2">
                    THE WAR
                </span>
            </div>
            <div className="flex-1 overflow-hidden">
                {ticker.length === 0 ? (
                    <span className="pl-11 font-mono text-xs text-fg-4">
                        No war points yet this week — the first push draws first
                        blood.
                    </span>
                ) : (
                    <div className="flex w-max animate-al-marquee gap-11 pl-11">
                        {entries.map((entry, index) => (
                            <div
                                key={`${entry.code}-${index}`}
                                className="flex items-center gap-2.5 whitespace-nowrap"
                            >
                                <span
                                    className={
                                        entry.rank === 1
                                            ? 'font-mono text-xs font-bold text-cue-500'
                                            : 'font-mono text-xs font-bold text-fg-1'
                                    }
                                >
                                    {String(entry.rank).padStart(2, '0')}
                                </span>
                                <FlagChip
                                    flagCss={flagCssFor(entry.code)}
                                    width={22}
                                    height={14}
                                />
                                <span className="font-display text-[13px] font-semibold tracking-[0.05em]">
                                    {entry.code}
                                </span>
                                <span className="font-mono text-[13px] text-fg-2">
                                    {formatPoints(entry.points)}
                                </span>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
