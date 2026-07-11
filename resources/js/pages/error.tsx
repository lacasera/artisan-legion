import { Head, Link } from '@inertiajs/react';
import ChevronLogo from '@/components/shared/ChevronLogo';
import { home } from '@/routes';

const MESSAGES: Record<number, { title: string; detail: string }> = {
    404: {
        title: 'No soldier found on this front',
        detail: 'The page you marched to does not exist. Check the handle and try again.',
    },
    419: {
        title: 'Orders expired',
        detail: 'This page sat idle too long. Head back and try again.',
    },
    429: {
        title: 'Easy, soldier — rate limited',
        detail: 'Too many requests from your position. Hold the line a minute, then retry.',
    },
    500: {
        title: 'The war room is down',
        detail: 'Something broke on our side. The engineers have been deployed.',
    },
    503: {
        title: 'Regrouping',
        detail: 'The legion is briefly down for maintenance. Back shortly.',
    },
};

export default function Error({ status }: { status: number }) {
    const message = MESSAGES[status] ?? MESSAGES[500];

    return (
        <>
            <Head title={`${status} · ${message.title}`} />
            <div className="relative flex min-h-screen flex-col items-center justify-center gap-6 overflow-hidden bg-ink-950 px-6 font-sans text-fg-1">
                <div className="absolute inset-0 chevron-field opacity-60" />
                <div className="absolute inset-0 bg-[radial-gradient(60%_55%_at_50%_45%,rgba(255,46,77,0.06),transparent_65%)]" />
                <div className="relative flex flex-col items-center gap-5 text-center">
                    <ChevronLogo size={44} strokeWidth={1.8} />
                    <span className="font-display text-[96px] leading-none font-bold tracking-[-0.02em] text-fg-1">
                        {status}
                    </span>
                    <span className="font-mono text-[11px] font-bold tracking-caps text-signal-500">
                        {message.title.toUpperCase()}
                    </span>
                    <p className="max-w-[380px] text-sm leading-relaxed text-fg-3">
                        {message.detail}
                    </p>
                    <Link
                        href={home()}
                        className="mt-2 rounded-sm bg-signal-500 px-6 py-3 text-sm font-semibold text-white hover:bg-signal-600"
                    >
                        Back to the front
                    </Link>
                </div>
                <span className="absolute bottom-6 font-mono text-[10px] tracking-widest text-fg-4">
                    ARTISAN LEGION · S03
                </span>
            </div>
        </>
    );
}
