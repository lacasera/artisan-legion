import type { LegionSquad } from '@/components/legion/types';

export const GHANA_SQUAD: LegionSquad = {
    formation: '4–3–3',
    averageOvr: '81.3',
    attack: [
        { handle: 'yaw-owusu', ovr: 85, pos: 'LW', lang: 'JAVASCRIPT' },
        { handle: 'efua-mensah', ovr: 88, pos: 'ST', lang: 'GO' },
        { handle: 'kofi-boateng', ovr: 84, pos: 'RW', lang: 'TYPESCRIPT' },
    ],
    midfield: [
        { handle: 'esi-arthur', ovr: 81, pos: 'CDM', lang: 'JAVA' },
        {
            handle: 'kwame-ansah',
            ovr: 91,
            pos: 'CAM',
            lang: 'PHP',
            captain: true,
        },
        { handle: 'nana-adjei', ovr: 82, pos: 'CM', lang: 'PYTHON' },
    ],
    defense: [
        { handle: 'adwoa-asante', ovr: 77, pos: 'LB', lang: 'RUBY' },
        { handle: 'fiifi-quartey', ovr: 76, pos: 'CB', lang: 'C#' },
        { handle: 'akosua-agyeman', ovr: 74, pos: 'CB', lang: 'KOTLIN' },
        { handle: 'jojo-hammond', ovr: 72, pos: 'RB', lang: 'DART' },
    ],
    goalkeeper: [
        { handle: 'kobby-annan', ovr: 78, pos: 'GK', lang: 'TERRAFORM' },
    ],
    bench: [
        { handle: 'ama-serwaa', ovr: 71, pos: 'CM', lang: 'PYTHON' },
        { handle: 'yoofi-baidoo', ovr: 70, pos: 'ST', lang: 'SWIFT' },
        { handle: 'kwesi-appiah', ovr: 69, pos: 'RB', lang: 'PHP' },
        { handle: 'abena-frimpong', ovr: 67, pos: 'CB', lang: 'RUST' },
        { handle: 'kojo-mettle', ovr: 65, pos: 'LW', lang: 'ELIXIR' },
        { handle: 'esi-nyarko', ovr: 62, pos: 'GK', lang: 'BASH' },
    ],
    captain: {
        name: 'KWAME ANSAH',
        handle: 'kwame-ansah',
        pos: 'CAM',
        ovr: 91,
        initials: 'KA',
        weeklySummary: '4,182 pts this week · top scorer',
    },
};
