import { Head, router } from '@inertiajs/react';
import { useEffect } from 'react';
import EnlistmentSteps from '@/components/lookup/EnlistmentSteps';
import SealedCard from '@/components/lookup/SealedCard';
import TerminalLog from '@/components/lookup/TerminalLog';
import type { EnlistStep, LogLine } from '@/components/lookup/types';
import { show as cardsShow } from '@/routes/cards';

const STEPS: EnlistStep[] = [
    { label: 'SCAN', desc: 'Reading public activity', phase: 'done' },
    { label: 'RATE', desc: 'Scoring your languages 40–99', phase: 'done' },
    {
        label: 'POSITION',
        desc: 'Deriving your role on the pitch',
        phase: 'active',
    },
    { label: 'ENLIST', desc: 'Joining your legion', phase: 'pending' },
];

const REVEAL_DELAY_MS = 5200;

export default function Lookup({ username }: { username: string }) {
    const logLines: LogLine[] = [
        { text: `fetch github.com/${username}`, tone: 'faint' },
        { text: '2,341 events · 14 repos · 6yr history', tone: 'faint' },
        { text: 'rating PHP ████████░ 97', tone: 'mid' },
        { text: 'rating BLADE ███████░░ 93', tone: 'mid' },
        { text: 'deriving position → ST', tone: 'mid' },
        { text: 'assigning legion → USA', tone: 'bright' },
    ];

    useEffect(() => {
        const timer = setTimeout(
            () => router.visit(cardsShow(username)),
            REVEAL_DELAY_MS,
        );

        return () => clearTimeout(timer);
    }, [username]);

    return (
        <>
            <Head title={`Striking @${username}`} />
            <div className="relative grid min-h-screen items-center gap-10 overflow-hidden bg-ink-950 font-sans text-fg-1 lg:grid-cols-[1fr_460px_1fr]">
                <div className="absolute inset-0 bg-[radial-gradient(60%_55%_at_50%_50%,rgba(255,46,77,0.06),transparent_65%)]" />
                <div className="hidden lg:block">
                    <TerminalLog lines={logLines} />
                </div>
                <SealedCard username={username} />
                <div className="hidden lg:block">
                    <EnlistmentSteps steps={STEPS} />
                </div>
            </div>
        </>
    );
}
