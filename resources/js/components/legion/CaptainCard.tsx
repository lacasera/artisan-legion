import type { LegionCaptain } from '@/components/legion/types';
import { githubUrl } from '@/lib/github';

export default function CaptainCard({ captain }: { captain: LegionCaptain }) {
    return (
        <div className="flex flex-col gap-3.5">
            <span className="font-mono text-xs font-semibold tracking-caps text-fg-2">
                CAPTAIN
            </span>
            <div className="flex items-center gap-4 rounded-lg border border-cue-500/35 bg-ink-850 p-4">
                <div className="flex size-14 items-center justify-center rounded-sm bg-ink-700 font-display text-xl font-bold tracking-[0.04em] text-cue-500">
                    {captain.initials}
                </div>
                <div className="flex flex-1 flex-col gap-[3px]">
                    <span className="font-display text-[17px] font-bold tracking-[0.02em]">
                        {captain.name}
                    </span>
                    <span className="font-mono text-xs text-live-500">
                        <a
                            href={githubUrl(captain.handle)}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="hover:text-live-400"
                        >
                            @{captain.handle}
                        </a>{' '}
                        · {captain.pos}
                    </span>
                    <span className="font-mono text-[11px] text-fg-4">
                        highest-rated soldier in the legion
                    </span>
                </div>
                <span className="font-display text-[40px] font-bold text-cue-500">
                    {captain.ovr}
                </span>
            </div>
        </div>
    );
}
