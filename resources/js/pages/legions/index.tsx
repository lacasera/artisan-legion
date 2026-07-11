import { Head, Link } from '@inertiajs/react';
import type { LegionSummary } from '@/components/legion/types';
import FlagChip from '@/components/shared/FlagChip';
import TopNav from '@/components/shared/TopNav';
import { formatPoints } from '@/lib/format';
import { flagCssFor, nationNameFor } from '@/lib/nations';
import { cn } from '@/lib/utils';
import { show as legionsShow } from '@/routes/legions';

export default function LegionsIndex({
    legions,
}: {
    legions: LegionSummary[];
}) {
    return (
        <>
            <Head title="Legions" />
            <div className="min-h-screen bg-ink-900 font-sans text-fg-1">
                <TopNav />
                <div className="flex flex-col gap-1.5 border-b border-line-1 px-6 py-9 lg:px-12">
                    <span className="font-mono text-xs font-semibold tracking-caps text-fg-3">
                        SEASON 03 · {legions.length}{' '}
                        {legions.length === 1 ? 'LEGION' : 'LEGIONS'} MUSTERED
                    </span>
                    <h2 className="font-display text-4xl leading-none font-bold tracking-[-0.01em] uppercase lg:text-5xl">
                        Legions
                    </h2>
                </div>
                {legions.length === 0 ? (
                    <div className="px-6 py-16 text-center lg:px-12">
                        <span className="font-mono text-sm text-fg-3">
                            No legions mustered yet. Strike the first card and
                            found one.
                        </span>
                    </div>
                ) : (
                    <div className="flex flex-col">
                        {legions.map((legion, index) => (
                            <Link
                                key={legion.code}
                                href={legionsShow(legion.code)}
                                className="grid grid-cols-[56px_56px_1fr_120px] items-center gap-4 border-b border-line-1 px-6 py-4 hover:bg-ink-850 lg:grid-cols-[72px_56px_1fr_170px_150px] lg:px-12"
                            >
                                <span
                                    className={cn(
                                        'font-display text-[22px] font-bold',
                                        index === 0
                                            ? 'text-cue-500'
                                            : 'text-fg-1',
                                    )}
                                >
                                    {String(index + 1).padStart(2, '0')}
                                </span>
                                <FlagChip flagCss={flagCssFor(legion.code)} />
                                <div className="flex min-w-0 flex-col gap-px">
                                    <span className="truncate font-display text-[17px] font-bold tracking-[0.03em] uppercase">
                                        {nationNameFor(legion.code)}
                                    </span>
                                    <span className="font-mono text-[11px] text-fg-4">
                                        top soldier @{legion.topSoldier}
                                    </span>
                                </div>
                                <span className="hidden text-right font-mono text-xs text-fg-3 lg:block">
                                    {formatPoints(legion.soldiers)}{' '}
                                    {legion.soldiers === 1
                                        ? 'soldier'
                                        : 'soldiers'}
                                </span>
                                <span className="text-right font-mono text-lg font-bold text-fg-1">
                                    {legion.averageOvr}
                                </span>
                            </Link>
                        ))}
                    </div>
                )}
                <div className="px-6 py-4 lg:px-12">
                    <span className="font-mono text-xs text-fg-4">
                        ranked by top-XI average OVR · war points arrive with
                        the weekly war
                    </span>
                </div>
            </div>
        </>
    );
}
