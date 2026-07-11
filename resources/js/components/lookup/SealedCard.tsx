import ChevronLogo from '@/components/shared/ChevronLogo';
import { githubUrl } from '@/lib/github';

export default function SealedCard({ username }: { username: string }) {
    return (
        <div className="relative flex flex-col items-center gap-6">
            <div
                data-sealed-card
                className="relative h-[560px] w-[400px] origin-top scale-[0.85] overflow-hidden rounded-xl border border-ink-600 bg-ink-900 shadow-[0_32px_64px_rgba(0,0,0,0.6)] not-sm:-mb-[84px] sm:scale-100"
            >
                <div className="absolute inset-0 chevron-field" />
                <div className="absolute inset-0 flex flex-col items-center justify-center gap-5">
                    <ChevronLogo size={96} strokeWidth={1.6} />
                    <span className="font-display text-xl font-bold tracking-[0.18em] text-fg-2">
                        ARTISAN LEGION
                    </span>
                    <span className="font-mono text-[11px] tracking-[0.12em] text-fg-4">
                        SEALED · S03
                    </span>
                </div>
                <div className="pointer-events-none absolute inset-x-0 h-[90px] animate-al-scan bg-[linear-gradient(180deg,transparent,rgba(33,224,196,0.10)_45%,rgba(33,224,196,0.55)_50%,rgba(33,224,196,0.10)_55%,transparent)]" />
                <div className="absolute inset-0 rounded-xl shadow-[inset_0_0_40px_rgba(33,224,196,0.06)]" />
            </div>
            <span className="font-mono text-xs tracking-[0.08em] text-fg-3">
                <a
                    href={githubUrl(username)}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="hover:text-fg-1"
                >
                    @{username}
                </a>{' '}
                · 2,341 events found
            </span>
        </div>
    );
}
