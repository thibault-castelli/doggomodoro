import { UserTimerPreset } from '@/types';
import axios from 'axios';
import { secondsToTime } from '../utils/timeConverter';
import { showRoundEndNotification } from '../utils/timerNotifier.svelte';

const ONE_SECOND = 1000;
const MINUTES_TO_SECONDS = 60;

export class Timer {
    constructor(preset: UserTimerPreset) {
        const { work_duration, break_duration, long_break_duration, long_break_interval, auto_play, notifications } = preset;

        for (let i = 0; i < long_break_interval; i++) {
            this.rounds.push(work_duration);
            this.totalWorkTime += work_duration;

            if (i < long_break_interval - 1) {
                this.rounds.push(break_duration);
                this.totalBreakTime += break_duration;
            } else {
                this.rounds.push(long_break_duration);
                this.totalBreakTime += long_break_duration;
            }
        }

        this.currentTime = this.rounds.length > 0 ? this.rounds[0] * MINUTES_TO_SECONDS : 0;
        this.autoPlay = auto_play || false;
        this.showNotifications = notifications || false;
    }

    private rounds: number[] = $state([]);
    private interval: NodeJS.Timeout | undefined = $state(undefined);
    private currentRoundIndex: number = $state(0);
    private currentTime: number = $state(0);
    private readonly autoPlay: boolean = false;
    private readonly showNotifications: boolean = false;

    private readonly totalWorkTime: number = 0;
    private readonly totalBreakTime: number = 0;

    readonly isTimerOn: boolean = $derived(this.interval !== undefined);
    readonly currentTimeDisplay: string = $derived(secondsToTime(this.currentTime));
    readonly progressValue: number = $derived((this.currentTime / (this.rounds[this.currentRoundIndex] * MINUTES_TO_SECONDS)) * 100);
    readonly roundDisplay: string = $derived(`${this.currentRoundIndex + 1} / ${this.rounds.length}`);
    readonly roundType: 'work' | 'break' = $derived(this.currentRoundIndex % 2 === 0 ? 'work' : 'break');

    startTimer = () => {
        this.currentTime--;

        this.interval = setInterval(() => {
            this.currentTime -= 1;
            if (this.currentTime <= 0) this.goToNextRound();
        }, ONE_SECOND);
    };

    pauseTimer = () => {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = undefined;
        }
    };

    stopTimer = () => {
        this.pauseTimer();
        this.resetCurrentTime();
    };

    goToNextRound = async () => {
        this.pauseTimer();

        const isSessionDone = this.currentRoundIndex >= this.rounds.length - 1;
        if (isSessionDone) {
            this.currentRoundIndex = 0;
            await axios.put(route('timerStats.update'), {
                total_work_time: this.totalWorkTime,
                total_break_time: this.totalBreakTime,
                total_rounds_completed: this.rounds.length,
            });
        } else {
            this.currentRoundIndex += 1;
        }

        this.resetCurrentTime();

        if (this.showNotifications) showRoundEndNotification(isSessionDone ? 'longBreak' : this.currentRoundIndex % 2 === 0 ? 'work' : 'shortBreak');

        if (this.autoPlay && this.currentRoundIndex !== 0) this.startTimer();
    };

    resetCurrentTime = () => {
        this.currentTime = this.rounds[this.currentRoundIndex] * MINUTES_TO_SECONDS;
    };

    resetSession = () => {
        this.pauseTimer();
        this.currentRoundIndex = 0;
        this.resetCurrentTime();
    };
}
