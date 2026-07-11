import { Head, usePage } from '@inertiajs/react';
import LiveDot from '@/components/shared/LiveDot';
import TopNav from '@/components/shared/TopNav';
import type { WarPageProps } from '@/components/war/types';
import { useWarBoard } from '@/components/war/useWarBoard';
import WarBoardRow from '@/components/war/WarBoardRow';
import { formatCountdown, formatPoints } from '@/lib/format';
import type { SharedProps } from '@/types';

const COLUMN_HEADS = ['RANK', '', 'LEGION', 'VS LEADER', 'WAR POINTS', '24H'];

export default function War({ board, pushingCount, resetsAt }: WarPageProps) {
    const { weekLabel } = usePage<SharedProps>().props;
    const { gains, countdown } = useWarBoard(board, resetsAt);

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
                                LIVE · {formatPoints(pushingCount)}{' '}
                                {pushingCount === 1 ? 'DEV' : 'DEVS'} PUSHING
                                NOW
                            </span>
                        </div>
                        <h2 className="font-display text-4xl leading-none font-bold tracking-[-0.01em] uppercase lg:text-5xl">
                            The weekly war
                        </h2>
                    </div>
                    <div className="flex flex-col gap-1 lg:items-end">
                        <span className="font-mono text-[11px] font-semibold tracking-caps text-fg-3">
                            WEEK {weekLabel.replace(/^W/, '')} RESETS IN
                        </span>
                        <span className="font-mono tabular text-[34px] font-bold text-fg-1">
                            {formatCountdown(countdown)}
                        </span>
                    </div>
                </div>
                {board.length === 0 ? (
                    <div className="px-6 py-16 text-center lg:px-12">
                        <span className="font-mono text-sm text-fg-3">
                            No war points yet this week. The first push draws
                            first blood.
                        </span>
                    </div>
                ) : (
                    <>
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
                        {board.map((entry) => (
                            <WarBoardRow
                                key={entry.code}
                                entry={entry}
                                gain={gains[entry.code]}
                            />
                        ))}
                    </>
                )}
                <div className="flex flex-col gap-2 px-6 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-12">
                    <span className="font-mono text-xs text-fg-4">
                        points are open-source commits, weighted by rating · 30
                        full-value commits a day, diminishing after
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
