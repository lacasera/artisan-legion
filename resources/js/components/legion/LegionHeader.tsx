import type { LegionHeaderProps } from '@/components/legion/types';
import { formatPoints } from '@/lib/format';

const HEX_CLIP = 'polygon(50% 0, 100% 18%, 100% 68%, 50% 100%, 0 68%, 0 18%)';

export default function LegionHeader({
    code,
    name,
    soldierCount,
    rank,
    averageOvr,
    flagCss,
}: LegionHeaderProps) {
    return (
        <div className="relative flex flex-col gap-8 overflow-hidden border-b border-line-1 px-6 py-10 lg:flex-row lg:items-center lg:justify-between lg:px-12">
            <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(206,17,38,0.10),rgba(252,209,22,0.05)_40%,transparent_70%)]" />
            <div className="relative flex items-center gap-6">
                <div className="relative h-20 w-[72px]">
                    <div
                        className="absolute inset-0"
                        style={{ clipPath: HEX_CLIP, background: flagCss }}
                    />
                    {code === 'GHA' && (
                        <svg
                            className="absolute top-1/2 left-1/2 -translate-1/2"
                            width="26"
                            height="26"
                            viewBox="0 0 24 24"
                            fill="#0B0D10"
                        >
                            <path d="M12 1.8 L14.7 9 L22.2 9 L16.2 13.7 L18.5 21.3 L12 16.8 L5.5 21.3 L7.8 13.7 L1.8 9 L9.3 9 Z" />
                        </svg>
                    )}
                </div>
                <div className="flex flex-col gap-1">
                    <span className="font-mono text-xs font-semibold tracking-caps text-fg-3">
                        LEGION · {formatPoints(soldierCount)}{' '}
                        {soldierCount === 1 ? 'SOLDIER' : 'SOLDIERS'}
                    </span>
                    <h2 className="font-display text-4xl leading-none font-bold tracking-[-0.01em] uppercase lg:text-[52px]">
                        {name}
                    </h2>
                </div>
            </div>
            <div className="relative flex items-center gap-10">
                <div className="flex flex-col items-end gap-1">
                    <span className="font-mono text-[11px] font-semibold tracking-caps text-fg-3">
                        LEGION RANK
                    </span>
                    <span className="font-display text-5xl leading-none font-bold">
                        #{rank}
                    </span>
                </div>
                <div className="h-14 w-px bg-line-2" />
                <div className="flex flex-col items-end gap-1">
                    <span className="font-mono text-[11px] font-semibold tracking-caps text-fg-3">
                        AVG OVR · TOP XI
                    </span>
                    <span className="font-mono text-[34px] leading-tight font-bold">
                        {averageOvr}
                    </span>
                    <span className="font-mono text-xs text-fg-3">
                        war points arrive with the weekly war
                    </span>
                </div>
            </div>
        </div>
    );
}
