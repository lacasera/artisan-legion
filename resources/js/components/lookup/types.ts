export type StepPhase = 'done' | 'active' | 'pending';

export interface EnlistStep {
    label: string;
    desc: string;
    phase: StepPhase;
}

export interface LogLine {
    text: string;
    tone: 'faint' | 'mid' | 'bright';
}
