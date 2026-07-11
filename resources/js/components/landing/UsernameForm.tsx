import { router } from '@inertiajs/react';
import { useState } from 'react';
import { lookup } from '@/routes';

export default function UsernameForm() {
    const [username, setUsername] = useState('');

    function submit(event: React.FormEvent) {
        event.preventDefault();
        const handle = username.trim();

        if (handle === '') {
            return;
        }

        router.visit(lookup(handle));
    }

    return (
        <form
            onSubmit={submit}
            className="flex w-full max-w-[520px] items-stretch overflow-hidden rounded-sm border border-line-2 bg-ink-850"
        >
            <span className="flex items-center pr-1 pl-4 font-mono text-[15px] text-fg-4">
                github.com/
            </span>
            <input
                value={username}
                onChange={(event) => setUsername(event.target.value)}
                placeholder="username"
                aria-label="GitHub username"
                className="min-w-0 flex-1 bg-transparent px-2 py-4 font-mono text-[15px] text-fg-1 outline-none placeholder:text-fg-4"
            />
            <button
                type="submit"
                className="flex cursor-pointer items-center gap-2 bg-signal-500 px-4 text-[15px] font-semibold text-white hover:bg-signal-600 sm:px-6"
            >
                Get rated <span className="font-mono">→</span>
            </button>
        </form>
    );
}
