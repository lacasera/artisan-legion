import { Link } from '@inertiajs/react';
import ChevronLogo from '@/components/shared/ChevronLogo';
import { home } from '@/routes';

export default function GhostCardState({ username }: { username: string }) {
    return (
        <div className="flex flex-col items-center gap-6">
            <div className="relative flex h-[560px] w-[400px] origin-top scale-[0.85] flex-col items-center justify-center gap-5 overflow-hidden rounded-xl border border-dashed border-ink-600 bg-ink-900 not-sm:-mb-[84px] sm:scale-100">
                <div className="absolute inset-0 chevron-field opacity-40" />
                <div className="relative flex flex-col items-center gap-5 px-10 text-center">
                    <ChevronLogo size={72} color="#2A3040" strokeWidth={1.6} />
                    <span className="font-mono text-[11px] font-bold tracking-caps text-fg-3">
                        NOT ENOUGH SIGNAL
                    </span>
                    <span className="font-display text-2xl leading-snug font-bold text-fg-2 uppercase">
                        No card struck for @{username}
                    </span>
                    <span className="text-sm leading-relaxed text-fg-3">
                        Not enough public activity to rate. Push some public
                        code, then come back — the legion will be waiting.
                    </span>
                </div>
                <span className="absolute bottom-3 font-mono text-[10px] tracking-widest text-fg-4">
                    ARTISAN LEGION · S03 · UNRATED
                </span>
            </div>
            <Link
                href={home()}
                className="rounded-sm bg-signal-500 px-6 py-3 text-sm font-semibold text-white hover:bg-signal-600"
            >
                Try another handle
            </Link>
        </div>
    );
}
