import type { CardDev } from '@/components/card/types';
import { FLAGS } from '@/lib/mock/flags';

export const MOCK_DEVS: Record<string, CardDev> = {
    taylorotwell: {
        name: 'TAYLOR OTWELL',
        handle: 'taylorotwell',
        avatar: 'https://github.com/taylorotwell.png?size=240',
        ovr: 94,
        pos: 'ST',
        nation: 'USA',
        flagCss: FLAGS.USA,
        rankLabel: 'USA · NAT #3',
        serial: '00007',
        stats: [
            { name: 'PHP', val: 97 },
            { name: 'BLADE', val: 93 },
            { name: 'JAVASCRIPT', val: 81 },
            { name: 'SHELL', val: 74 },
        ],
    },
    rauchg: {
        name: 'GUILLERMO RAUCH',
        handle: 'rauchg',
        avatar: 'https://github.com/rauchg.png?size=240',
        ovr: 86,
        pos: 'CAM',
        nation: 'ARG',
        flagCss: FLAGS.ARG,
        rankLabel: 'ARG · NAT #1',
        serial: '00214',
        stats: [
            { name: 'TYPESCRIPT', val: 92 },
            { name: 'JAVASCRIPT', val: 88 },
            { name: 'MDX', val: 70 },
            { name: 'RUST', val: 61 },
        ],
    },
    fabpot: {
        name: 'FABIEN POTENCIER',
        handle: 'fabpot',
        avatar: 'https://github.com/fabpot.png?size=240',
        ovr: 89,
        pos: 'CDM',
        nation: 'FRA',
        flagCss: FLAGS.FRA,
        rankLabel: 'FRA · NAT #2',
        serial: '00033',
        specialist: true,
        stats: [{ name: 'PHP', val: 98 }],
    },
    '0xkofi': {
        name: 'KOFI OWUSU',
        handle: '0xkofi',
        avatar: null,
        initials: 'KO',
        ovr: 67,
        pos: 'CB',
        nation: null,
        flagCss: '',
        rankLabel: 'GLOBAL #12,412',
        serial: '31877',
        stats: [
            { name: 'SOLIDITY', val: 71 },
            { name: 'TYPESCRIPT', val: 63 },
            { name: 'RUST', val: 52 },
        ],
    },
};

const LANGUAGE_POOLS = [
    ['PHP', 'BLADE', 'JAVASCRIPT', 'SHELL'],
    ['TYPESCRIPT', 'REACT', 'CSS', 'GO'],
    ['PYTHON', 'RUST', 'DOCKER', 'SQL'],
    ['RUBY', 'ELIXIR', 'JAVASCRIPT', 'YAML'],
    ['JAVA', 'KOTLIN', 'GRADLE', 'BASH'],
];

const POSITIONS = ['ST', 'CAM', 'CDM', 'CM', 'LW', 'RW', 'CB'];

const NATION_CODES = Object.keys(FLAGS);

function hashOf(input: string): number {
    let hash = 0;

    for (let index = 0; index < input.length; index++) {
        hash = (hash * 31 + input.charCodeAt(index)) >>> 0;
    }

    return hash;
}

export function mockDevFor(username: string): CardDev | null {
    const handle = username.toLowerCase();

    if (handle.startsWith('ghost')) {
        return null;
    }

    const known = MOCK_DEVS[handle];

    if (known) {
        return known;
    }

    const hash = hashOf(username.toLowerCase());
    const ovr = 55 + (hash % 34);
    const languages = LANGUAGE_POOLS[hash % LANGUAGE_POOLS.length];
    const nation = NATION_CODES[hash % NATION_CODES.length];
    const drop = [0, 7, 16, 24];

    return {
        name: username.replace(/[-_]/g, ' ').toUpperCase(),
        handle: username,
        avatar: `https://github.com/${username}.png?size=240`,
        ovr,
        pos: POSITIONS[hash % POSITIONS.length],
        nation,
        flagCss: FLAGS[nation],
        rankLabel: `${nation} · NAT #${(hash % 900) + 12}`,
        serial: String((hash % 90000) + 1000).padStart(5, '0'),
        stats: languages.map((name, index) => ({
            name,
            val: Math.max(40, ovr + 4 - drop[index]),
        })),
    };
}
