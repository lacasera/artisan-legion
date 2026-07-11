import { Head, Link } from '@inertiajs/react';
import FlagChip from '@/components/shared/FlagChip';
import LiveDot from '@/components/shared/LiveDot';
import TopNav from '@/components/shared/TopNav';
import { formatPoints } from '@/lib/format';
import { FLAGS } from '@/lib/mock/flags';
import { MY_LEGION_CODE, WAR_NATIONS } from '@/lib/mock/war';
import { cn } from '@/lib/utils';
import { show as legionsShow } from '@/routes/legions';

export default function LegionsIndex() {
    const ranked = [...WAR_NATIONS].sort((a, b) => b.pts - a.pts);

    return (
        <>
            <Head title="Legions" />
            <div className="min-h-screen bg-ink-900 font-sans text-fg-1">
                <TopNav />
                <div className="flex flex-col gap-1.5 border-b border-line-1 px-6 py-9 lg:px-12">
                    <span className="font-mono text-xs font-semibold tracking-caps text-fg-3">
                        SEASON 03 · 128 LEGIONS AT WAR
                    </span>
                    <h2 className="font-display text-4xl leading-none font-bold tracking-[-0.01em] uppercase lg:text-5xl">
                        Legions
                    </h2>
                </div>
                <div className="flex flex-col">
                    {ranked.map((nation, index) => (
                        <Link
                            key={nation.code}
                            href={legionsShow(nation.code)}
                            className={cn(
                                'grid grid-cols-[56px_56px_1fr_120px] items-center gap-4 border-b border-line-1 px-6 py-4 hover:bg-ink-850 lg:grid-cols-[72px_56px_1fr_170px_150px] lg:px-12',
                                nation.code === MY_LEGION_CODE &&
                                    'bg-live-500/4 shadow-[inset_0_0_0_1px_rgba(33,224,196,0.35)]',
                            )}
                        >
                            <span
                                className={cn(
                                    'font-display text-[22px] font-bold',
                                    index === 0 ? 'text-cue-500' : 'text-fg-1',
                                )}
                            >
                                {String(index + 1).padStart(2, '0')}
                            </span>
                            <FlagChip flagCss={FLAGS[nation.code]} />
                            <div className="flex min-w-0 flex-col gap-px">
                                <div className="flex items-center gap-2.5">
                                    <span className="font-display text-[17px] font-bold tracking-[0.03em] uppercase">
                                        {nation.name}
                                    </span>
                                    {nation.code === MY_LEGION_CODE && (
                                        <span className="rounded-xs border border-live-500/40 px-[7px] py-0.5 font-mono text-[10px] font-bold tracking-widest text-live-400">
                                            YOUR LEGION
                                        </span>
                                    )}
                                    {nation.pushing && <LiveDot />}
                                </div>
                                <span className="font-mono text-[11px] text-fg-4">
                                    top soldier @{nation.top}
                                </span>
                            </div>
                            <span className="hidden text-right font-mono text-xs text-fg-3 lg:block">
                                {formatPoints(nation.soldiers)} soldiers
                            </span>
                            <span className="text-right font-mono text-lg font-bold text-fg-1">
                                {formatPoints(nation.pts)}
                            </span>
                        </Link>
                    ))}
                </div>
                <div className="px-6 py-4 lg:px-12">
                    <span className="font-mono text-xs text-fg-4">
                        Ranked by war points · week 27 · every carded dev is
                        auto-rostered to their country
                    </span>
                </div>
            </div>
        </>
    );
}
