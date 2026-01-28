<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    accounts: Array,
});

const form = useForm({
    account_name: '',
    parent_id: '',
});

const submit = () => {
    form.post(route('accounts.store'), {
        onSuccess: () => {
            form.reset(); // Clear the form on success
            alert('Account Added!');
        },
        onError: (errors) => {
            console.log("Validation failed:", errors);
        }
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Add Account" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="account_name" value="Name" />

                <TextInput
                    id="account_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.account_name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.account_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="parent_id" value="Parent Account" />

                <select
                    id="parent_id"
                    v-model="form.parent_id"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700"
                    required
                >
                    <option value="">Select a parent account</option>
                    <option value="">None</option>
                    <option v-for="parent in accounts" :key="parent.id" :value="parent.id">
                        {{ parent.account_name }}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.parent_id" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
