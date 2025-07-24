<script lang="ts">
    import InputError from '@/components/InputError.svelte';
    import TextLink from '@/components/TextLink.svelte';
    import { Button } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import AuthBase from '@/layouts/AuthLayout.svelte';
    import { mapZodErrosToFormErrors } from '@/lib/formValidationUtils';
    import { useForm } from '@inertiajs/svelte';
    import { LoaderCircle } from 'lucide-svelte';
    import { z } from 'zod';

    interface Props {
        status?: string;
        canResetPassword: boolean;
    }

    const loginSchema = z.object({
        email: z.string().email(),
        password: z.string().min(1, 'Password is required'),
        remember: z.boolean(),
    });
    const emailSchema = loginSchema.shape.email;
    const passwordSchema = loginSchema.shape.password;

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const validateForm = () => {
        const formatedData = {
            email: $form.email,
            password: $form.password,
            remember: Boolean($form.remember),
        };

        const validation = loginSchema.safeParse(formatedData);
        if (!validation.success) {
            $form.errors = mapZodErrosToFormErrors(validation.error);
            return false;
        }

        $form.errors = {};
        return true;
    };

    const validateField = (fieldName: 'email' | 'password', schema: z.ZodTypeAny) => {
        const value = $form[fieldName];

        const validation = schema.safeParse(value);
        if (!validation.success) {
            $form.errors[fieldName] = validation.error.format()._errors[0];
            return false;
        }

        $form.errors[fieldName] = '';
        return true;
    };

    // Do not validate on first blur because email input is autofocused.
    let isFirstBlurOnEmail = $state(true);
    const validateEmail = () => {
        if (isFirstBlurOnEmail) {
            isFirstBlurOnEmail = false;
            return true;
        }

        return validateField('email', emailSchema);
    };

    const validatePassword = () => validateField('password', passwordSchema);

    let { status, canResetPassword }: Props = $props();

    const submit = (e: Event) => {
        e.preventDefault();

        if (!validateForm()) return;

        $form.post(route('login'), {
            onFinish: () => $form.reset('password'),
        });
    };
</script>

<svelte:head>
    <title>Login</title>
</svelte:head>

<AuthBase title="Log in to your account">
    {#if status}
        <div class="mb-4 text-center text-sm font-medium text-green-600">
            {status}
        </div>
    {/if}

    <form onsubmit={submit} class="flex flex-col gap-6">
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email">Email address</Label>
                <Input
                    id="email"
                    type="email"
                    autofocus
                    tabindex={1}
                    autocomplete="email"
                    bind:value={$form.email}
                    placeholder="email@example.com"
                    onblur={validateEmail}
                />
                <InputError message={$form.errors.email} />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Password</Label>
                    {#if canResetPassword}
                        <TextLink href={route('password.request')} class="text-sm" tabindex={5}>Forgot password?</TextLink>
                    {/if}
                </div>
                <Input
                    id="password"
                    type="password"
                    tabindex={2}
                    autocomplete="current-password"
                    bind:value={$form.password}
                    placeholder="Password"
                    onblur={validatePassword}
                />
                <InputError message={$form.errors.password} />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" bind:checked={$form.remember} tabindex={3} />
                    <span>Remember me</span>
                </Label>
            </div>

            <Button type="submit" class="mt-4 w-full" tabindex={4} disabled={$form.processing}>
                {#if $form.processing}
                    <LoaderCircle class="h-4 w-4 animate-spin" />
                {/if}
                Log in
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Don't have an account?
            <TextLink href={route('register')} tabindex={5}>Sign up</TextLink>
        </div>
    </form>
</AuthBase>
