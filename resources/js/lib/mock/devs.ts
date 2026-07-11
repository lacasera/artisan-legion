import type { CardDev } from '@/components/card/types';
import { flagCssFor } from '@/lib/nations';

/**
 * Display fixture for the landing hero card only — real cards come from the API.
 */
export const MOCK_DEVS: Record<string, CardDev> = {
    taylorotwell: {
        name: 'TAYLOR OTWELL',
        handle: 'taylorotwell',
        avatar: 'https://github.com/taylorotwell.png?size=240',
        ovr: 92,
        pos: 'ST',
        nation: 'USA',
        flagCss: flagCssFor('USA'),
        rankLabel: 'USA · NAT #1',
        serial: '00002',
        stats: [
            { name: 'PHP', val: 97 },
            { name: 'JAVASCRIPT', val: 94 },
            { name: 'BLADE', val: 81 },
            { name: 'CSS', val: 74 },
        ],
        frameworks: ['LARAVEL', 'TAILWIND'],
    },
};
