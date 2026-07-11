import type { LegionPlayer } from '@/components/legion/types';
import { cn } from '@/lib/utils';

export default function ReservesList({
    reserves,
    onSelect,
}: {
    reserves: LegionPlayer[];
    onSelect: (player: LegionPlayer) => void;
}) {
    if (reserves.length === 0) {
        return null;
    }

    return (
        <div className="flex flex-col gap-2.5">
            <span className="font-mono text-xs font-semibold tracking-caps text-fg-2">
                RESERVES
            </span>
            <div className="flex flex-col overflow-hidden rounded-lg border border-line-1 bg-ink-850">
                {reserves.map((player) => {
                    const clickable = player.breakdown !== null;

                    return (
                        <button
                            key={player.id}
                            type="button"
                            disabled={!clickable}
                            onClick={
                                clickable ? () => onSelect(player) : undefined
                            }
                            className={cn(
                                'grid grid-cols-[44px_1fr_52px_80px] items-center gap-2 border-b border-line-1 px-4 py-[11px] text-left',
                                clickable && 'cursor-pointer hover:bg-ink-800',
                            )}
                        >
                            <span className="font-mono text-[15px] font-bold text-fg-1">
                                {player.ovr}
                            </span>
                            <span className="truncate font-mono text-xs text-fg-2">
                                @{player.handle}
                            </span>
                            <span className="font-mono text-[11px] font-semibold tracking-[0.06em] text-fg-3">
                                {player.pos}
                            </span>
                            <span className="text-right font-mono text-[11px] text-fg-4">
                                {player.topLanguage}
                            </span>
                        </button>
                    );
                })}
            </div>
        </div>
    );
}
