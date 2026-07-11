import type { PlayerChipProps } from '@/components/legion/types';
import { cn } from '@/lib/utils';

export default function PlayerChip({ player }: PlayerChipProps) {
    return (
        <div
            className={cn(
                'relative flex w-32 flex-col items-center gap-1.5 rounded-sm border px-2 py-3',
                player.captain
                    ? 'border-cue-500/45 bg-cue-500/6'
                    : 'border-line-2 bg-ink-800',
            )}
        >
            {player.captain && (
                <span className="absolute -top-2.5 -right-2.5 flex size-6 items-center justify-center rounded-full bg-cue-500 font-display text-[13px] font-bold text-ink-950">
                    C
                </span>
            )}
            <div className="flex items-baseline gap-1.5">
                <span
                    className={cn(
                        'font-mono text-xl font-bold',
                        player.ovr >= 90 ? 'text-cue-500' : 'text-fg-1',
                    )}
                >
                    {player.ovr}
                </span>
                <span className="font-mono text-[10px] font-semibold tracking-[0.08em] text-fg-3">
                    {player.pos}
                </span>
            </div>
            <span className="max-w-28 truncate font-mono text-[11px] text-fg-1">
                @{player.handle}
            </span>
            <span className="font-mono text-[10px] tracking-[0.06em] text-fg-4">
                {player.topLanguage}
            </span>
        </div>
    );
}
