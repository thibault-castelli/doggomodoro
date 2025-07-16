import { Timer } from '@/contexts/timerLogic.svelte';
import { getContext, setContext } from 'svelte';

const key = {};

export function setTimerContext(timer: Timer) {
	setContext(key, timer);
}

export function getTimerContext() {
	return getContext(key) as Timer;
}