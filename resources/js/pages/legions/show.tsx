import { Head } from '@inertiajs/react';
import CaptainCard from '@/components/legion/CaptainCard';
import LegionHeader from '@/components/legion/LegionHeader';
import PitchFormation from '@/components/legion/PitchFormation';
import PushingNowPanel from '@/components/legion/PushingNowPanel';
import ReservesList from '@/components/legion/ReservesList';
import TopNav from '@/components/shared/TopNav';
import { formatPoints } from '@/lib/format';
import { FLAGS } from '@/lib/mock/flags';
import { GHANA_SQUAD } from '@/lib/mock/squad';
import { WAR_NATIONS } from '@/lib/mock/war';

export default function LegionsShow({ code }: { code: string }) {
    const ranked = [...WAR_NATIONS].sort((a, b) => b.pts - a.pts);
    const standing = ranked.findIndex((nation) => nation.code === code);
    const nation = standing >= 0 ? ranked[standing] : ranked[3];
    const rank = standing >= 0 ? standing + 1 : 4;
    const ahead = rank > 1 ? ranked[rank - 2] : null;
    const behindText = ahead
        ? `${formatPoints(ahead.pts - nation.pts)} pts behind #${rank - 1} ${ahead.name.toUpperCase()}`
        : 'Leading the war';

    return (
        <>
            <Head title={`${nation.name} legion`} />
            <div className="min-h-screen bg-ink-900 font-sans text-fg-1">
                <TopNav />
                <LegionHeader
                    code={nation.code}
                    name={nation.name}
                    soldierCount={formatPoints(nation.soldiers)}
                    standing={rank}
                    standingMove={nation.move}
                    points={nation.pts}
                    behindText={behindText}
                    flagCss={FLAGS[nation.code]}
                />
                <div className="grid lg:grid-cols-[1fr_420px]">
                    <PitchFormation squad={GHANA_SQUAD} />
                    <div className="flex flex-col gap-7 p-6 lg:px-10 lg:pt-10 lg:pb-12">
                        <CaptainCard captain={GHANA_SQUAD.captain} />
                        <ReservesList bench={GHANA_SQUAD.bench} />
                        <PushingNowPanel summary="38 soldiers active · +214 pts in the last hour" />
                    </div>
                </div>
            </div>
        </>
    );
}
