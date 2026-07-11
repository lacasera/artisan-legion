import { Head } from '@inertiajs/react';
import { useState } from 'react';
import CaptainCard from '@/components/legion/CaptainCard';
import EnlistmentsPanel from '@/components/legion/EnlistmentsPanel';
import LegionHeader from '@/components/legion/LegionHeader';
import PitchFormation from '@/components/legion/PitchFormation';
import PlayerBreakdownDialog from '@/components/legion/PlayerBreakdownDialog';
import ReservesList from '@/components/legion/ReservesList';
import type { Legion, LegionPlayer } from '@/components/legion/types';
import TopNav from '@/components/shared/TopNav';
import { flagCssFor, nationNameFor } from '@/lib/nations';

export default function LegionsShow({ legion }: { legion: Legion }) {
    const name = nationNameFor(legion.code);
    const [selected, setSelected] = useState<LegionPlayer | null>(null);

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
                    <PitchFormation legion={legion} onSelect={setSelected} />
                    <div className="flex flex-col gap-7 p-6 lg:px-10 lg:pt-10 lg:pb-12">
                        {legion.captain && (
                            <CaptainCard captain={legion.captain} />
                        )}
                        <ReservesList
                            reserves={legion.reserves}
                            onSelect={setSelected}
                        />
                        <EnlistmentsPanel
                            recentEnlistments={legion.recentEnlistments}
                        />
                    </div>
                </div>
            </div>
            {selected && (
                <PlayerBreakdownDialog
                    player={selected}
                    onClose={() => setSelected(null)}
                />
            )}
        </>
    );
}
