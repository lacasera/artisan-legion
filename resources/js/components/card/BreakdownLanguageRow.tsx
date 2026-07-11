import type { LanguageBreakdown } from '@/components/card/types';
import { formatPoints } from '@/lib/format';

export default function BreakdownLanguageRow({
    language,
}: {
    language: LanguageBreakdown;
}) {
    return (
        <div className="flex flex-col gap-1.5 border-b border-line-1 py-3 last:border-b-0">
            <div className="flex items-baseline justify-between gap-2">
                <div className="flex items-center gap-2">
                    <span className="font-mono text-[11px] font-semibold tracking-widest text-fg-1">
                        {language.name}
                    </span>
                    {language.recent && (
                        <span className="rounded-xs border border-live-500/40 px-1.5 py-px font-mono text-[9px] font-bold tracking-widest text-live-400">
                            RECENT
                        </span>
                    )}
                </div>
                <span className="font-mono text-sm font-bold text-fg-1">
                    {language.score}
                </span>
            </div>
            <div className="flex items-center gap-3">
                <div className="h-1 flex-1 overflow-hidden rounded-full bg-ink-700">
                    <div
                        className="h-full rounded-full bg-signal-500"
                        style={{ width: `${Math.max(2, language.sharePct)}%` }}
                    />
                </div>
                <span className="w-24 text-right font-mono text-[10px] text-fg-4">
                    {language.sharePct}% · ★ {formatPoints(language.stars)}
                </span>
            </div>
        </div>
    );
}
