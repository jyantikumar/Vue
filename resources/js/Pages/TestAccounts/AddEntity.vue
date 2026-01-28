<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    accounts: Array, // This should now be the list of active account categories
});

const form = useForm({
    name: '',
    entity_type: '', // Vendor, Client, Subscription
    identifier: '',  // TIN, Account Number, etc.
    status: 'active',
    description: '',
    notes: '',
    account_ids: [], // Array to hold multiple account bindings
});

const entityTypes = ['Vendor', 'Client', 'Subscription', 'Utility', 'Tax Authority'];

const submit = () => {
    form.post(route('entities.store'), {
        onSuccess: () => {
            form.reset();
            alert('Entity Created Successfully!');
        },
    });
};
</script>

<template>
        <Head title="Create Entity" />
        <h1 class="text-lg font-bold mb-4">Create New Entity</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="name" value="Entity Name" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="entity_type" value="Entity Type" />
                    <select id="entity_type" v-model="form.entity_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="" disabled>Select Type</option>
                        <option v-for="type in entityTypes" :key="type" :value="type">{{ type }}</option>
                    </select>
                    <InputError :message="form.errors.entity_type" />
                </div>

                <div>
                    <InputLabel for="identifier" value="Identifier (ID/TIN/Acc No)" />
                    <TextInput id="identifier" type="text" class="mt-1 block w-full" v-model="form.identifier" required />
                    <InputError :message="form.errors.identifier" />
                </div>
            </div>

            <div>
                <InputLabel value="Bind to Account Categories" />
                <div class="mt-2 grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-3 border rounded-md bg-gray-50">
                    <label v-for="account in accounts" :key="account.id" class="flex items-center space-x-2">
                        <input 
                            type="checkbox" 
                            :value="account.id" 
                            v-model="form.account_ids" 
                        />
                        <span class="text-sm text-gray-700">{{ account.full_account_name }}</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Select one or more categories associated with this entity.</p>
                <InputError :message="form.errors.account_ids" />
            </div>

            <div>
                <InputLabel for="description" value="Description" />
                <textarea id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="form.description"></textarea>
            </div>

            <div class="flex items-center justify-end">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create Entity
                </PrimaryButton>
            </div>
        </form>
</template>