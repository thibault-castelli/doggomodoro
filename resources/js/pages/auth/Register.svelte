<script lang="ts">
    import InputError from '@/components/InputError.svelte';
    import TextLink from '@/components/TextLink.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import AuthBase from '@/layouts/AuthLayout.svelte';
    import { mapZodErrosToFormErrors } from '@/lib/formValidationUtils';
    import { useForm } from '@inertiajs/svelte';
    import axios from 'axios';
    import { LoaderCircle } from 'lucide-svelte';
    import { z } from 'zod';

    const nameSchema = z.string().min(1, 'Name is required');
    const emailSchema = z.string().email();
    const passwordSchema = z.string().min(1, 'Password is required');

    const passwordConfirmationSchema = z
        .object({
            password: z.string().min(1, 'Password is required'),
            password_confirmation: z.string().min(1, 'Confirm password is required'),
        })
        .refine((data) => data.password === data.password_confirmation, {
            message: 'Passwords do not match',
            path: ['password_confirmation'],
        });

    const registerSchema = z
        .object({
            name: nameSchema,
            email: emailSchema,
            password: passwordSchema,
            password_confirmation: z.string().min(1, 'Confirm password is required'),
        })
        .refine((data) => data.password === data.password_confirmation, {
            message: 'Passwords do not match',
            path: ['password_confirmation'],
        });

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const validateForm = async () => {
        const formatedData = {
            name: $form.name,
            email: $form.email,
            password: $form.password,
            password_confirmation: $form.password_confirmation,
        };

        const validation = registerSchema.safeParse(formatedData);
        if (!validation.success) {
            $form.errors = mapZodErrosToFormErrors(validation.error);
            await validateEmailExists();
            return false;
        }

        if (!(await validateEmailExists())) return false;

        $form.errors = {};
        return true;
    };

    const validateField = async (fieldName: 'name' | 'email' | 'password', schema: z.ZodTypeAny, remoteValidation?: () => Promise<boolean>) => {
        const value = $form[fieldName];

        const validation = schema.safeParse(value);
        if (!validation.success) {
            $form.errors[fieldName] = validation.error.format()._errors[0];
            return false;
        }

        if (remoteValidation) {
            if (!(await remoteValidation())) return false;
        }

        $form.errors[fieldName] = '';
        return true;
    };

    // Do not validate on first blur because name input is autofocused.
    let isFirstBlurOnName = $state(true);
    const validateName = () => {
        if (isFirstBlurOnName) {
            isFirstBlurOnName = false;
            return true;
        }

        return validateField('name', nameSchema);
    };

    const validateEmailExists = async () => {
        const checkEmailResponse = await axios.post<{ exists: boolean }>(route('checkEmail'), { email: $form.email });
        if (checkEmailResponse.data.exists) {
            $form.errors.email = 'This email is already registered';
            return false;
        }

        return true;
    };

    const validateEmail = async () => await validateField('email', emailSchema, validateEmailExists);

    const validatePassword = () => {
        if ($form.password_confirmation) validatePasswordConfirmation();
        return validateField('password', passwordSchema);
    };

    const validatePasswordConfirmation = () => {
        const formatedData = {
            password: $form.password,
            password_confirmation: $form.password_confirmation,
        };

        const validation = passwordConfirmationSchema.safeParse(formatedData);
        if (!validation.success) {
            $form.errors.password_confirmation = validation.error.format().password_confirmation?._errors[0];
            return false;
        }
        $form.errors.password_confirmation = '';
        return true;
    };

    const submit = async (e: Event) => {
        e.preventDefault();

        if (!(await validateForm())) return;

        $form.post(route('register'), {
            onFinish: () => $form.reset('password', 'password_confirmation'),
        });
    };
</script>

<svelte:head>
    <title>Register</title>
</svelte:head>

<AuthBase title="Create an account">
    <form onsubmit={submit} class="flex flex-col gap-6">
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    type="text"
                    autofocus
                    tabindex={1}
                    autocomplete="name"
                    bind:value={$form.name}
                    placeholder="Full name"
                    onblur={validateName}
                />
                <InputError message={$form.errors.name} />
            </div>

            <div class="grid gap-2">
                <Label for="email">Email address</Label>
                <Input
                    id="email"
                    type="email"
                    tabindex={2}
                    autocomplete="email"
                    bind:value={$form.email}
                    placeholder="email@example.com"
                    onblur={validateEmail}
                />
                <InputError message={$form.errors.email} />
            </div>

            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <Input
                    id="password"
                    type="password"
                    tabindex={3}
                    autocomplete="new-password"
                    bind:value={$form.password}
                    placeholder="Password"
                    onblur={validatePassword}
                />
                <InputError message={$form.errors.password} />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm password</Label>
                <Input
                    id="password_confirmation"
                    type="password"
                    tabindex={4}
                    autocomplete="new-password"
                    bind:value={$form.password_confirmation}
                    placeholder="Confirm password"
                    onblur={validatePasswordConfirmation}
                />
                <InputError message={$form.errors.password_confirmation} />
            </div>

            <Button type="submit" class="mt-2 w-full" tabindex={5} disabled={$form.processing}>
                {#if $form.processing}
                    <LoaderCircle class="h-4 w-4 animate-spin" />
                {/if}
                Create account
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink href={route('login')} class="underline underline-offset-4" tabindex={6}>Log in</TextLink>
        </div>
    </form>
</AuthBase>
