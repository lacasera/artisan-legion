import { Link, usePage } from '@inertiajs/react';
import ChevronLogo from '@/components/shared/ChevronLogo';
import LiveDot from '@/components/shared/LiveDot';
import { home, war } from '@/routes';
import { index as legionsIndex } from '@/routes/legions';
import type { SharedProps } from '@/types';

export default function TopNav() {
    const { weekLabel } = usePage<SharedProps>().props;

    return (
        <div className="relative flex h-16 items-center justify-between border-b border-line-1 px-6 lg:px-10">
            <Link href={home()} className="flex items-center gap-3 text-fg-1">
                <ChevronLogo />
                <span className="font-display text-[17px] font-bold tracking-[0.06em]">
                    ARTISAN LEGION
                </span>
            </Link>
            <div className="flex items-center gap-7">
                <Link
                    href={war()}
                    className="hidden text-sm font-medium text-fg-2 hover:text-fg-1 sm:block"
                >
                    The war
                </Link>
                <Link
                    href={legionsIndex()}
                    className="hidden text-sm font-medium text-fg-2 hover:text-fg-1 sm:block"
                >
                    Legions
                </Link>
                <div className="flex items-center gap-2 rounded-full border border-line-2 px-3.5 py-[5px]">
                    <LiveDot />
                    <span className="font-mono text-xs font-medium text-live-400">
                        WEEK {weekLabel.replace(/^W/, '')} LIVE
                    </span>
                </div>
            </div>
        </div>
    );
}
