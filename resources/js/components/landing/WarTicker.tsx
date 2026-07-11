import FlagChip from '@/components/shared/FlagChip';
import LiveDot from '@/components/shared/LiveDot';
import { formatPoints } from '@/lib/format';
import { FLAGS } from '@/lib/mock/flags';
import { WAR_NATIONS } from '@/lib/mock/war';

export default function WarTicker() {
    const ranked = [...WAR_NATIONS].sort((a, b) => b.pts - a.pts);
    const entries = [...ranked, ...ranked];

    return (
        <div className="relative flex h-12 items-center overflow-hidden border-t border-line-1 bg-ink-950">
            <div className="relative z-2 flex h-full shrink-0 items-center gap-2 border-r border-line-1 bg-ink-950 px-5">
                <LiveDot />
                <span className="font-mono text-[11px] font-bold tracking-caps text-fg-2">
                    THE WAR
                </span>
            </div>
            <div className="flex-1 overflow-hidden">
                <div className="flex w-max animate-al-marquee gap-11 pl-11">
                    {entries.map((nation, index) => (
                        <div
                            key={`${nation.code}-${index}`}
                            className="flex items-center gap-2.5 whitespace-nowrap"
                        >
                            <span
                                className={
                                    index % ranked.length === 0
                                        ? 'font-mono text-xs font-bold text-cue-500'
                                        : 'font-mono text-xs font-bold text-fg-1'
                                }
                            >
                                {String((index % ranked.length) + 1).padStart(
                                    2,
                                    '0',
                                )}
                            </span>
                            <FlagChip
                                flagCss={FLAGS[nation.code]}
                                width={22}
                                height={14}
                            />
                            <span className="font-display text-[13px] font-semibold tracking-[0.05em]">
                                {nation.code}
                            </span>
                            <span className="font-mono text-[13px] text-fg-2">
                                {formatPoints(nation.pts)}
                            </span>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
