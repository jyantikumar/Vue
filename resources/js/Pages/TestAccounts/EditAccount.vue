<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue'; // Ensure this is imported
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    accounts: Array,
    account: Object,          // The account being edited
    hasTransactions: Boolean, // Passed from the controller
});

const form = useForm({
    account_name: props.account?.account_name ?? '',
    parent_id: props.account?.parent_id ?? '',
    is_deactivated: !!props.account?.is_deactivated, // Convert to boolean
});

const submit = () => {
    // Switch to patch for updates
    form.patch(route('accounts.update', props.account.id), {
        onSuccess: () => alert('Account Updated Successfully!'),
        onError: (errors) => console.log("Update failed:", errors)
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Edit Account Details" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="account_name" value="Account Name" />
                <TextInput
                    id="account_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.account_name"
                    required
                />
                <InputError class="mt-2" :message="form.errors.account_name" />
            </div>

            <div class="mt-4">
                <div class="flex justify-between">
                    <InputLabel for="parent_id" value="Parent Account" />
                    <span v-if="hasTransactions" class="text-xs text-amber-600 font-medium">
                        Locked: Account has transaction history
                    </span>
                </div>

                <select
                    id="parent_id"
                    v-model="form.parent_id"
                    :disabled="hasTransactions"
                    :class="{ 'bg-gray-100 cursor-not-allowed': hasTransactions }"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700"
                >
                    <option value="">None (Top Level)</option>
                    <option 
                        v-for="parent in accounts" 
                        :key="parent.id" 
                        :value="parent.id"
                        v-show="parent.id !== props.account.id" 
                    >
                        {{ parent.account_name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.parent_id" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox 
                        name="is_deactivated" 
                        :checked="form.is_deactivated" 
                        @update:checked="(val) => form.is_deactivated = val" 
                    />
                    <span class="ms-2 text-sm text-gray-600">Deactivate Account</span>
                </label>
                <InputError class="mt-2" :message="form.errors.is_deactivated" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Update Account
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
