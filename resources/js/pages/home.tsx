import { Head } from '@inertiajs/react';
import HeroSection from '@/components/landing/HeroSection';
import WarTicker from '@/components/landing/WarTicker';
import TopNav from '@/components/shared/TopNav';
import type { WarBoardEntry } from '@/components/war/types';

interface HomeProps {
    ticker: WarBoardEntry[];
    soldierCount: number;
}

export default function Home({ ticker, soldierCount }: HomeProps) {
    return (
        <>
            <Head title="Your commits have a rating" />
            <div className="relative min-h-screen overflow-hidden bg-ink-900 font-sans text-fg-1">
                <div className="absolute inset-0 chevron-field" />
                <div className="absolute inset-0 bg-[radial-gradient(90%_70%_at_72%_30%,rgba(255,46,77,0.07),transparent_60%)]" />
                <div className="relative flex min-h-screen flex-col">
                    <TopNav />
                    <div className="flex-1">
                        <HeroSection soldierCount={soldierCount} />
                    </div>
                    <WarTicker ticker={ticker} />
                </div>
            </div>
        </>
    );
}
