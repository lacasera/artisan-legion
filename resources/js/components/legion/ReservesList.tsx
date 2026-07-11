import type { KeyboardEvent } from 'react';
import type { LegionPlayer } from '@/components/legion/types';
import { githubUrl } from '@/lib/github';
import { cn } from '@/lib/utils';

export default function ReservesList({
    reserves,
    onSelect,
    selectedId,
}: {
    reserves: LegionPlayer[];
    onSelect: (player: LegionPlayer) => void;
    selectedId: string | null;
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
                        <div
                            key={player.id}
                            role={clickable ? 'button' : undefined}
                            tabIndex={clickable ? 0 : undefined}
                            onClick={
                                clickable ? () => onSelect(player) : undefined
                            }
                            onKeyDown={
                                clickable
                                    ? (
                                          event: KeyboardEvent<HTMLDivElement>,
                                      ) => {
                                          if (
                                              event.key === 'Enter' ||
                                              event.key === ' '
                                          ) {
                                              event.preventDefault();
                                              onSelect(player);
                                          }
                                      }
                                    : undefined
                            }
                            className={cn(
                                'grid grid-cols-[44px_1fr_52px_80px] items-center gap-2 border-b border-line-1 px-4 py-[11px] text-left',
                                clickable && 'cursor-pointer hover:bg-ink-800',
                                player.id === selectedId && 'bg-ink-700',
                            )}
                        >
                            <span className="font-mono text-[15px] font-bold text-fg-1">
                                {player.ovr}
                            </span>
                            <a
                                href={githubUrl(player.handle)}
                                target="_blank"
                                rel="noopener noreferrer"
                                onClick={(event) => event.stopPropagation()}
                                className="block truncate font-mono text-xs text-fg-2 hover:text-live-400"
                            >
                                @{player.handle}
                            </a>
                            <span className="font-mono text-[11px] font-semibold tracking-[0.06em] text-fg-3">
                                {player.pos}
                            </span>
                            <span className="text-right font-mono text-[11px] text-fg-4">
                                {player.topLanguage}
                            </span>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
