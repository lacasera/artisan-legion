import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import GhostCardState from '@/components/card/GhostCardState';
import LegionCard from '@/components/card/LegionCard';
import RatingBreakdown from '@/components/card/RatingBreakdown';
import type {
    RatingBreakdown as RatingBreakdownType,
    ServerCardDev,
} from '@/components/card/types';
import ChevronLogo from '@/components/shared/ChevronLogo';
import TopNav from '@/components/shared/TopNav';
import { flagCssFor } from '@/lib/nations';
import { home } from '@/routes';
import { show as legionsShow } from '@/routes/legions';

interface CardsShowProps {
    username: string;
    dev: ServerCardDev | null;
    breakdown: RatingBreakdownType | null;
}

export default function CardsShow({
    username,
    dev,
    breakdown,
}: CardsShowProps) {
    const card = dev ? { ...dev, flagCss: flagCssFor(dev.nation) } : null;
    const [copied, setCopied] = useState(false);

    function copyShareLink() {
        void navigator.clipboard.writeText(window.location.href);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    }

    return (
        <>
            <Head
                title={
                    card ? `@${card.handle} · OVR ${card.ovr}` : `@${username}`
                }
            />
            <div className="relative flex min-h-screen flex-col overflow-hidden bg-ink-950 font-sans text-fg-1">
                <div className="absolute inset-0 bg-[radial-gradient(60%_55%_at_50%_38%,rgba(255,46,77,0.06),transparent_65%)]" />
                <div className="relative">
                    <TopNav />
                </div>
                <div className="relative flex flex-1 flex-col items-center justify-center px-6 py-14">
                    {!card ? (
                        <GhostCardState username={username} />
                    ) : (
                        <div className="flex w-full flex-col items-center gap-10 lg:flex-row lg:items-start lg:justify-center">
                            <div className="flex w-full max-w-[400px] flex-col items-center gap-6">
                                <span className="font-mono text-xs font-semibold tracking-caps text-fg-3">
                                    {card.ovr >= 90
                                        ? 'GOLD STRIKE · ANIMATED FOIL'
                                        : 'STANDARD ISSUE'}
                                </span>
                                <div
                                    data-card-frame
                                    className="origin-top scale-[0.85] drop-shadow-[0_32px_48px_rgba(0,0,0,0.6)] not-sm:-mb-[84px] sm:scale-100"
                                >
                                    <LegionCard dev={card} />
                                </div>
                                <div className="flex items-center gap-2">
                                    <ChevronLogo size={14} />
                                    <span className="font-mono text-[11px] tracking-widest text-fg-3">
                                        artisanlegion.dev/{card.handle}
                                    </span>
                                </div>
                                <div className="flex items-center gap-3">
                                    <button
                                        type="button"
                                        onClick={copyShareLink}
                                        className="cursor-pointer rounded-sm bg-signal-500 px-6 py-3 text-sm font-semibold text-white hover:bg-signal-600"
                                    >
                                        {copied ? 'Copied!' : 'Copy share link'}
                                    </button>
                                    {card.nation && (
                                        <Link
                                            href={legionsShow(card.nation)}
                                            className="rounded-sm border border-line-2 px-6 py-3 text-sm font-medium text-fg-2 hover:text-fg-1"
                                        >
                                            View your legion
                                        </Link>
                                    )}
                                </div>
                                <Link
                                    href={home()}
                                    className="font-mono text-xs text-fg-4 hover:text-fg-2"
                                >
                                    Strike another card
                                </Link>
                            </div>
                            {breakdown && (
                                <div className="flex w-full max-w-[400px] justify-center lg:pt-7">
                                    <RatingBreakdown breakdown={breakdown} />
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
