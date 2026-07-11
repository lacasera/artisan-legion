import CardEditionStrip from '@/components/card/CardEditionStrip';
import CardHeader from '@/components/card/CardHeader';
import CardStats from '@/components/card/CardStats';
import type { LegionCardProps } from '@/components/card/types';
import { cn } from '@/lib/utils';

export default function LegionCard({ dev, foil = true }: LegionCardProps) {
    const isGold = dev.ovr >= 90;
    const tier = isGold ? 3 : dev.ovr >= 78 ? 2 : 1;
    const chevronColor = isGold
        ? '#FFD23F'
        : dev.ovr >= 78
          ? '#A7AFBF'
          : '#565F75';

    return (
        <div
            className={cn(
                'relative h-[560px] w-[400px] overflow-hidden rounded-xl border bg-ink-850 tabular',
                'shadow-[inset_0_0_0_1px_rgba(255,255,255,0.04),0_24px_48px_rgba(0,0,0,0.55)]',
                isGold ? 'border-cue-500/45' : 'border-ink-600',
            )}
        >
            <div className="absolute inset-0 bg-[repeating-linear-gradient(135deg,rgba(255,255,255,0.016)_0_2px,transparent_2px_7px)]" />
            <div className="absolute inset-0 chevron-field opacity-50" />
            {isGold && (
                <div className="absolute inset-0 bg-[radial-gradient(120%_70%_at_50%_-10%,rgba(255,210,63,0.13),transparent_60%)]" />
            )}
            {isGold && foil && (
                <div className="pointer-events-none absolute inset-0 animate-al-foil bg-[linear-gradient(112deg,transparent_30%,rgba(255,210,63,0.09)_42%,rgba(255,255,255,0.15)_47%,rgba(255,210,63,0.07)_53%,transparent_66%)] bg-size-[260%_100%]" />
            )}

            <CardHeader dev={dev} isGold={isGold} />

            <div className="relative flex flex-col gap-[3px] px-6 pt-3.5 text-center">
                <span className="font-display text-[25px] leading-[1.05] font-bold tracking-[0.01em] text-fg-1 uppercase">
                    {dev.name}
                </span>
                <a
                    href={`https://github.com/${dev.handle}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="self-center font-mono text-[13px] font-medium text-live-500 hover:text-live-400"
                >
                    @{dev.handle}
                </a>
            </div>

            <div className="relative flex items-center px-6 pt-3.5 pb-3">
                <div className="h-px flex-1 bg-line-2" />
                <div
                    className={cn(
                        'size-[5px] rounded-full',
                        isGold ? 'bg-cue-500' : 'bg-signal-500',
                    )}
                />
                <div className="h-px flex-1 bg-line-2" />
            </div>

            <CardStats
                stats={dev.stats}
                isGold={isGold}
                specialist={Boolean(dev.specialist)}
            />

            <CardEditionStrip
                serial={dev.serial}
                tier={tier}
                chevronColor={chevronColor}
            />
        </div>
    );
}
