import LegionCard from '@/components/card/LegionCard';
import UsernameForm from '@/components/landing/UsernameForm';
import ChevronLogo from '@/components/shared/ChevronLogo';
import { formatPoints } from '@/lib/format';
import { MOCK_DEVS } from '@/lib/mock/devs';

export default function HeroSection({
    soldierCount,
}: {
    soldierCount: number;
}) {
    return (
        <div className="relative grid items-center gap-10 px-6 py-16 lg:grid-cols-[1fr_560px] lg:px-10 lg:pt-[72px]">
            <div className="flex max-w-[620px] flex-col gap-7">
                <div className="flex items-center gap-2.5">
                    <span className="h-px w-6 bg-fg-3" />
                    <span className="-ml-2.5 size-[5px] rounded-full bg-signal-500" />
                    <span className="font-mono text-xs font-semibold tracking-caps text-fg-3">
                        SEASON 03 · {formatPoints(soldierCount)}{' '}
                        {soldierCount === 1 ? 'SOLDIER' : 'SOLDIERS'} ENLISTED
                    </span>
                </div>
                <h1 className="font-display text-6xl leading-[0.94] font-bold tracking-[-0.015em] uppercase lg:text-[88px]">
                    Your commits
                    <br />
                    have a rating.
                </h1>
                <p className="max-w-[460px] text-lg leading-relaxed text-fg-2">
                    Get your card. Join your country's legion. Every open-source
                    push this week is a point in the war.
                </p>
                <UsernameForm />
                <span className="font-mono text-xs tracking-[0.06em] text-fg-4">
                    40–99 OVR · four rated languages · one legion · free
                </span>
            </div>
            <div className="relative hidden h-[640px] lg:block">
                <div className="absolute top-[52px] left-[118px] flex h-[560px] w-[400px] rotate-9 items-center justify-center rounded-xl border border-ink-700 bg-ink-900">
                    <ChevronLogo size={84} color="#1F2430" strokeWidth={1.6} />
                </div>
                <div className="absolute top-2 left-7 -rotate-5 drop-shadow-[0_32px_48px_rgba(0,0,0,0.6)]">
                    <LegionCard dev={MOCK_DEVS.taylorotwell} />
                </div>
            </div>
        </div>
    );
}
