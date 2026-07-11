import { Head } from '@inertiajs/react';
import LiveDot from '@/components/shared/LiveDot';
import TopNav from '@/components/shared/TopNav';
import { useWarTicker } from '@/components/war/useWarTicker';
import WarBoardRow from '@/components/war/WarBoardRow';
import { formatCountdown, formatPoints } from '@/lib/format';

const COLUMN_HEADS = ['RANK', '', 'LEGION', 'VS LEADER', 'WAR POINTS', '24H'];

export default function War() {
    const { board, pushingCount, countdown } = useWarTicker();

    return (
        <>
            <Head title="The weekly war" />
            <div className="min-h-screen bg-ink-900 font-sans text-fg-1">
                <TopNav />
                <div className="flex flex-col gap-6 border-b border-line-1 px-6 py-9 lg:flex-row lg:items-center lg:justify-between lg:px-12">
                    <div className="flex flex-col gap-1.5">
                        <div className="flex items-center gap-2.5">
                            <LiveDot />
                            <span className="font-mono text-xs font-semibold tracking-caps text-live-400">
                                LIVE · {formatPoints(pushingCount)} DEVS PUSHING
                                NOW
                            </span>
                        </div>
                        <h2 className="font-display text-4xl leading-none font-bold tracking-[-0.01em] uppercase lg:text-5xl">
                            The weekly war
                        </h2>
                    </div>
                    <div className="flex flex-col gap-1 lg:items-end">
                        <span className="font-mono text-[11px] font-semibold tracking-caps text-fg-3">
                            WEEK 27 RESETS IN
                        </span>
                        <span className="font-mono tabular text-[34px] font-bold text-fg-1">
                            {formatCountdown(countdown)}
                        </span>
                    </div>
                </div>
                <div className="hidden grid-cols-[72px_56px_1fr_260px_150px_110px] items-center gap-4 border-b border-line-1 px-12 py-3 lg:grid">
                    {COLUMN_HEADS.map((head, index) => (
                        <span
                            key={head === '' ? `col-${index}` : head}
                            className={
                                index >= 4
                                    ? 'text-right font-mono text-[10px] font-bold tracking-caps text-fg-4'
                                    : 'font-mono text-[10px] font-bold tracking-caps text-fg-4'
                            }
                        >
                            {head}
                        </span>
                    ))}
                </div>
                {board.map((row, index) => (
                    <WarBoardRow
                        key={row.code}
                        row={row}
                        isLeader={index === 0}
                    />
                ))}
                <div className="flex flex-col gap-2 px-6 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-12">
                    <span className="font-mono text-xs text-fg-4">
                        128 legions at war · points are commits, PRs and
                        reviews, weighted
                    </span>
                    <span className="font-mono text-xs text-fg-3">
                        Week resets Sunday 00:00 UTC · ranks are wiped, cards
                        remain
                    </span>
                </div>
            </div>
        </>
    );
}
