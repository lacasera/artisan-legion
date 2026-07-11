import BreakdownLanguageRow from '@/components/card/BreakdownLanguageRow';
import type { RatingBreakdown as RatingBreakdownProps } from '@/components/card/types';
import { formatPoints } from '@/lib/format';

const TOTALS: {
    key: 'contributions' | 'stars' | 'followers';
    label: string;
}[] = [
    { key: 'contributions', label: 'OPEN-SOURCE COMMITS · YR' },
    { key: 'stars', label: 'STARS' },
    { key: 'followers', label: 'FOLLOWERS' },
];

export default function RatingBreakdown({
    breakdown,
}: {
    breakdown: RatingBreakdownProps;
}) {
    return (
        <div className="flex w-full max-w-[400px] flex-col gap-4">
            <div className="flex items-center gap-3">
                <div className="h-px flex-1 bg-line-2" />
                <span className="font-mono text-[11px] font-bold tracking-caps text-fg-3">
                    HOW THIS CARD WAS STRUCK
                </span>
                <div className="h-px flex-1 bg-line-2" />
            </div>

            <div className="grid grid-cols-3 overflow-hidden rounded-lg border border-line-1 bg-ink-850">
                {TOTALS.map((total) => (
                    <div
                        key={total.key}
                        className="flex flex-col gap-1 border-r border-line-1 px-3 py-3 text-center last:border-r-0"
                    >
                        <span className="font-mono text-lg font-bold text-fg-1">
                            {formatPoints(breakdown[total.key])}
                        </span>
                        <span className="font-mono text-[8px] font-semibold tracking-widest text-fg-4">
                            {total.label}
                        </span>
                    </div>
                ))}
            </div>

            <div className="rounded-lg border border-line-1 bg-ink-850 px-4 py-1">
                {breakdown.languages.map((language) => (
                    <BreakdownLanguageRow
                        key={language.name}
                        language={language}
                    />
                ))}
            </div>

            <div className="flex items-center gap-3 rounded-lg border border-line-1 bg-ink-850 px-4 py-3">
                <span className="rounded-xs border border-line-2 bg-white/3 px-2.5 py-1 font-mono text-[13px] font-bold tracking-widest text-fg-1">
                    {breakdown.position}
                </span>
                <span className="text-xs leading-relaxed text-fg-3">
                    {breakdown.positionRule}
                </span>
            </div>

            <div className="flex flex-col gap-2 rounded-lg border border-line-1 bg-ink-850 px-4 py-3">
                <span className="font-mono text-[9px] font-bold tracking-caps text-fg-4">
                    OVR FORMULA
                </span>
                <span className="font-mono text-[11px] leading-relaxed text-fg-2">
                    75% language blend ({breakdown.languageBlend}) · 15%
                    activity ({breakdown.activityScore}) · 10% impact (
                    {breakdown.impactScore}) ={' '}
                    <span className="font-bold text-fg-1">
                        OVR {breakdown.ovr}
                    </span>
                </span>
                <span className="font-mono text-[10px] text-fg-4">
                    activity level: {breakdown.activityPct}% of the log-scale
                    ceiling
                </span>
            </div>
        </div>
    );
}
