<script lang="ts">
    import Progress from '../ui/progress/progress.svelte';

    let { timer } = $props();
</script>

<div class="flex flex-col items-center justify-center gap-4 p-6">
    <p class="text-5xl font-bold">{timer.currentTimeDisplay}</p>
    <p class="text-3xl">{timer.roundType === 'work' ? 'Focus' : 'Break'}</p>
    <Progress value={timer.progressValue} class="max-w-4xl" />
    <p class="text-lg">{timer.roundDisplay} rounds</p>
</div>
