import type { CardHeaderProps } from '@/components/card/types';
import { cn } from '@/lib/utils';

const HEX_CLIP = 'polygon(50% 0, 96% 24%, 96% 76%, 50% 100%, 4% 76%, 4% 24%)';

export default function CardHeader({ dev, isGold }: CardHeaderProps) {
    const initials =
        dev.initials ??
        dev.name
            .split(' ')
            .map((word) => word[0])
            .slice(0, 2)
            .join('');

    return (
        <div className="relative grid grid-cols-[128px_1fr] gap-3 px-6 pt-6">
            <div className="flex flex-col items-start gap-2">
                <div className="flex flex-col gap-0.5">
                    <span className="font-mono text-[10px] font-bold tracking-caps text-fg-3">
                        OVR
                    </span>
                    <span
                        className={cn(
                            'font-display text-[84px] leading-[0.88] font-bold tracking-[-0.03em]',
                            isGold ? 'text-cue-500' : 'text-fg-1',
                        )}
                    >
                        {dev.ovr}
                    </span>
                </div>
                <span className="rounded-xs border border-line-2 bg-white/3 px-2.5 py-1 font-mono text-[13px] font-bold tracking-widest text-fg-1">
                    {dev.pos}
                </span>
                {dev.nation ? (
                    <div className="mt-1.5 flex flex-col gap-[5px]">
                        <span
                            className="h-[22px] w-[34px] rounded-xs border border-white/14"
                            style={{ background: dev.flagCss }}
                        />
                        <span className="font-mono text-[11px] font-medium tracking-[0.08em] text-fg-2">
                            {dev.rankLabel}
                        </span>
                    </div>
                ) : (
                    <div className="mt-1.5 flex flex-col gap-[5px]">
                        <span className="flex h-[22px] w-[34px] items-center justify-center rounded-xs border border-dashed border-ink-500">
                            <svg
                                width="14"
                                height="14"
                                viewBox="0 0 14 14"
                                fill="none"
                                stroke="#565F75"
                                strokeWidth="1.2"
                            >
                                <circle cx="7" cy="7" r="5.6" />
                                <ellipse cx="7" cy="7" rx="2.6" ry="5.6" />
                                <path d="M1.4 7 H12.6" />
                            </svg>
                        </span>
                        <span className="font-mono text-[11px] font-medium tracking-[0.08em] text-fg-3">
                            FREE AGENT · {dev.rankLabel}
                        </span>
                    </div>
                )}
            </div>
            <div className="flex justify-center pt-1.5">
                <div className="relative h-[190px] w-[176px]">
                    <div
                        className="absolute inset-0"
                        style={{
                            clipPath: HEX_CLIP,
                            background: isGold
                                ? 'linear-gradient(180deg, #FFE680, #C99A00)'
                                : 'var(--color-ink-500)',
                        }}
                    />
                    <div
                        className="absolute inset-0.5 overflow-hidden bg-ink-900"
                        style={{ clipPath: HEX_CLIP }}
                    >
                        {dev.avatar ? (
                            <img
                                src={dev.avatar}
                                alt=""
                                className="size-full object-cover contrast-110 saturate-[0.72]"
                            />
                        ) : (
                            <div className="flex size-full items-center justify-center bg-ink-800 font-display text-5xl font-bold tracking-[0.04em] text-ink-300">
                                {initials}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
