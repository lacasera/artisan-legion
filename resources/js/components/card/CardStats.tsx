import type { CardStatsProps } from '@/components/card/types';
import { cn } from '@/lib/utils';

export default function CardStats({
    stats,
    isGold,
    specialist,
}: CardStatsProps) {
    return (
        <div className="relative flex min-h-[132px] flex-col gap-[11px] px-6">
            {stats.map((stat) => (
                <div key={stat.name} className="flex flex-col gap-1">
                    <div className="flex items-baseline justify-between">
                        <span className="font-mono text-[11px] font-medium tracking-widest text-fg-2">
                            {stat.name}
                        </span>
                        <span className="font-mono text-[15px] font-bold text-fg-1">
                            {stat.val}
                        </span>
                    </div>
                    <div className="h-[5px] overflow-hidden rounded-full bg-ink-700">
                        <div
                            className={cn(
                                'h-full rounded-full',
                                isGold ? 'bg-cue-500' : 'bg-signal-500',
                            )}
                            style={{
                                width: `${Math.max(6, Math.min(99, stat.val))}%`,
                            }}
                        />
                    </div>
                </div>
            ))}
            {specialist && (
                <div className="mt-1 flex items-center gap-2.5 rounded-sm border border-line-1 bg-white/2 px-3 py-2.5">
                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                        stroke="#FFD23F"
                        strokeWidth="1.5"
                    >
                        <path d="M3 10 L8 6 L13 10" />
                        <path d="M3 13.5 L8 9.5 L13 13.5" />
                    </svg>
                    <div className="flex flex-col gap-px">
                        <span className="font-mono text-[10px] font-bold tracking-caps text-cue-500">
                            SPECIALIST
                        </span>
                        <span className="text-xs text-fg-3">
                            One language on record. Depth over breadth.
                        </span>
                    </div>
                </div>
            )}
        </div>
    );
}
