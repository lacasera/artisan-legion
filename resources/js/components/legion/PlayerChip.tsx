import type { PlayerChipProps } from '@/components/legion/types';
import { cn } from '@/lib/utils';

export default function PlayerChip({ player, onSelect }: PlayerChipProps) {
    const clickable = player.breakdown !== null && onSelect !== undefined;

    return (
        <button
            type="button"
            disabled={!clickable}
            onClick={clickable ? () => onSelect(player) : undefined}
            className={cn(
                'relative flex w-24 flex-col items-center gap-1.5 rounded-sm border px-2 py-3 text-left lg:w-32',
                player.captain
                    ? 'border-cue-500/45 bg-cue-500/6'
                    : 'border-line-2 bg-ink-800',
                clickable &&
                    'cursor-pointer transition-colors hover:border-live-500/60 hover:bg-ink-700',
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
            <span className="max-w-20 truncate font-mono text-[11px] text-fg-1 lg:max-w-28">
                @{player.handle}
            </span>
            <span className="font-mono text-[10px] tracking-[0.06em] text-fg-4">
                {player.topLanguage}
            </span>
        </button>
    );
}
