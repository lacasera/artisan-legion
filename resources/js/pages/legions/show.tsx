import { Head } from '@inertiajs/react';
import CaptainCard from '@/components/legion/CaptainCard';
import EnlistmentsPanel from '@/components/legion/EnlistmentsPanel';
import LegionHeader from '@/components/legion/LegionHeader';
import PitchFormation from '@/components/legion/PitchFormation';
import ReservesList from '@/components/legion/ReservesList';
import type { Legion } from '@/components/legion/types';
import TopNav from '@/components/shared/TopNav';
import { flagCssFor, nationNameFor } from '@/lib/nations';

export default function LegionsShow({ legion }: { legion: Legion }) {
    const name = nationNameFor(legion.code);

    return (
        <>
            <Head title={`${name} legion`} />
            <div className="min-h-screen bg-ink-900 font-sans text-fg-1">
                <TopNav />
                <LegionHeader
                    code={legion.code}
                    name={name}
                    soldierCount={legion.soldierCount}
                    rank={legion.rank}
                    averageOvr={legion.averageOvr}
                    flagCss={flagCssFor(legion.code)}
                />
                <div className="grid lg:grid-cols-[1fr_420px]">
                    <PitchFormation legion={legion} />
                    <div className="flex flex-col gap-7 p-6 lg:px-10 lg:pt-10 lg:pb-12">
                        {legion.captain && (
                            <CaptainCard captain={legion.captain} />
                        )}
                        <ReservesList reserves={legion.reserves} />
                        <EnlistmentsPanel
                            recentEnlistments={legion.recentEnlistments}
                        />
                    </div>
                </div>
            </div>
        </>
    );
}
