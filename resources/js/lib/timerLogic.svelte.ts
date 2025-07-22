import { UserTimerPreset } from "@/types";
import { secondsToTime } from "./timeConverterUtils";
import { showRoundEndNotification } from "./timerNotifier.svelte";

const ONE_SECOND = 1000;
const MINUTES_TO_SECONDS = 60;

function createRoundsFromUserTimerSettings(settings: UserTimerPreset): number[] {
	const {work_duration, break_duration, long_break_duration, long_break_interval} = settings;
	const rounds = [];

	for (let i = 0; i < long_break_interval; i++) {
		rounds.push(work_duration);
		if (i < long_break_interval - 1) 
			rounds.push(break_duration);
		else 
			rounds.push(long_break_duration);
	}

	return rounds;
}

export class Timer {
	constructor(settings: UserTimerPreset) {
		this.rounds = createRoundsFromUserTimerSettings(settings);
		this.currentTime = this.rounds.length > 0 ? this.rounds[0] * MINUTES_TO_SECONDS : 0;
		this.autoPlay = settings.auto_play || false;
	}

	private rounds: number[] = $state([]);
	private interval: NodeJS.Timeout | undefined = $state(undefined);
	private currentRoundIndex: number = $state(0);
	private currentTime: number = $state(0);
	private autoPlay: boolean = false;

	readonly isTimerOn: boolean = $derived(this.interval !== undefined);
	readonly currentTimeDisplay: string = $derived(secondsToTime(this.currentTime));
	readonly progressValue: number = $derived(this.currentTime / (this.rounds[this.currentRoundIndex] * MINUTES_TO_SECONDS) * 100);
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

	goToNextRound = () => {
		this.pauseTimer();

		const isSessionDone = this.currentRoundIndex >= this.rounds.length - 1;
		if (isSessionDone) {
			this.currentRoundIndex = 0;
		} else {
			this.currentRoundIndex += 1;
		}

		this.resetCurrentTime();
		showRoundEndNotification(isSessionDone ? 'longBreak' : this.currentRoundIndex % 2 === 0 ? 'work' : 'shortBreak');
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