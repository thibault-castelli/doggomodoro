<script lang="ts">
    import { getTimerContext } from '@/contexts/timerContext.svelte';
    import Progress from '../ui/progress/progress.svelte';

    const timer = getTimerContext();
</script>

<div class="flex flex-col items-center justify-center gap-4 p-6">
    <p class="text-5xl font-bold">{timer.currentTimeDisplay}</p>
    <p class="text-3xl">{timer.roundType === 'work' ? 'Focus' : 'Break'}</p>
    <Progress value={timer.progressValue} class="max-w-4xl" />
    <p class="text-lg">{timer.roundDisplay} rounds</p>
</div>
