import { z } from 'zod';

const mapZodErrosToFormErrors = (zodError: z.ZodError) => {
    const fieldErrors = zodError.flatten().fieldErrors;
    return Object.fromEntries(Object.entries(fieldErrors).map(([key, value]) => [key, value?.join(' ') ?? '']));
};

export { mapZodErrosToFormErrors };
