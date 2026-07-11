import LiveDot from '@/components/shared/LiveDot';

export default function EnlistmentsPanel({
    recentEnlistments,
}: {
    recentEnlistments: number;
}) {
    return (
        <div className="flex flex-col gap-2 rounded-lg border border-line-1 bg-live-500/3 p-4">
            <div className="flex items-center gap-2">
                <LiveDot />
                <span className="font-mono text-[11px] font-bold tracking-caps text-live-400">
                    FRESH ENLISTMENTS
                </span>
            </div>
            <span className="font-mono text-[13px] text-fg-2">
                {recentEnlistments === 0
                    ? 'No new soldiers in the last 24h — recruit some'
                    : `${recentEnlistments} ${recentEnlistments === 1 ? 'soldier' : 'soldiers'} enlisted in the last 24h`}
            </span>
        </div>
    );
}
