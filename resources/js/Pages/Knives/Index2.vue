<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const knives = ref([]);
const newKnife = ref({ name: '', description: '', price: '', image_url: '' });
const editingKnife = ref(null);
const knifeStatus = ref('');

const loadKnives = () => {
    knives.value = usePage().props.knives || [];
};

onMounted(loadKnives);

const addKnife = async () => {
    if (!validateKnife(newKnife.value)) return;

    try {
        await router.post(route("knives.store"), newKnife.value);
        knifeStatus.value = "Knife added successfully!";
        resetNewKnife();
    } catch (error) {
        knifeStatus.value = "Error adding knife.";
    }
};

const saveKnife = async () => {
    if (!editingKnife.value) return;
    if (!validateKnife(editingKnife.value)) return;

    try {
        await router.put(route("knives.update", { knife: editingKnife.value.id }), editingKnife.value);
        knifeStatus.value = "Knife updated successfully!";
        editingKnife.value = null;
    } catch (error) {
        knifeStatus.value = "Error updating knife.";
    }
};

const deleteKnife = (id) => {
    if (confirm('Are you sure you want to delete this knife?')) {
        router.delete(route('knives.delete', { id }), {
            onSuccess: () => {
                knives.value = knives.value.filter(knife => knife.id !== id);
                knifeStatus.value = 'Knife deleted successfully!';
            },
            onError: () => knifeStatus.value = 'Error deleting knife.'
        });
    }
};

const startEditingKnife = (knife) => {
    editingKnife.value = { ...knife };
};

const cancelEditingKnife = () => {
    editingKnife.value = null;
};

const validateKnife = (knife) => {
    return knife.name && knife.description && knife.price > 0;
};

const resetNewKnife = () => {
    newKnife.value = { name: '', description: '', price: '', image_url: '' };
};
</script>

<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mt-8 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 mb-4">Add Knife</h2>
                    <p v-if="knifeStatus" class="text-green-500 mb-4">{{ knifeStatus }}</p>
                    <form @submit.prevent="addKnife">
                        <input v-model="newKnife.name" placeholder="Name" class="border p-2 mb-2 block w-full" required />
                        <textarea v-model="newKnife.description" placeholder="Description" class="border p-2 mb-2 block w-full" required></textarea>
                        <input v-model="newKnife.price" type="number" placeholder="Price" class="border p-2 mb-2 block w-full" required />
                        <input v-model="newKnife.image_url" type="url" placeholder="Image URL" class="border p-2 mb-2 block w-full" required />
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Knife</button>
                    </form>
                </div>
            </div>

            <div class="mt-8 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 mb-4">Manage Knives</h2>
                    <p v-if="knifeStatus" class="text-green-500 mb-4">{{ knifeStatus }}</p>
                    <table v-if="knives.length" class="w-full border-collapse border border-gray-300">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Name</th>
                            <th class="border p-2">Description</th>
                            <th class="border p-2">Price</th>
                            <th class="border p-2">Image</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="knife in knives" :key="knife.id">
                            <td class="border p-2">{{ knife.name }}</td>
                            <td class="border p-2">{{ knife.description }}</td>
                            <td class="border p-2">{{ knife.price }}$</td>
                            <td class="border p-2">
                                <img :src="knife.image_url" alt="Knife Image" class="w-16 h-16 object-cover rounded" />
                            </td>
                            <td class="border p-2">
                                <button @click="startEditingKnife(knife)" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</button>
                                <button @click="deleteKnife(knife.id)" class="bg-red-500 text-white px-2 py-1 rounded ml-2">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="editingKnife" class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-4 rounded">
                    <h2 class="text-xl font-semibold">Edit Knife</h2>
                    <input v-model="editingKnife.name" placeholder="Name" class="border p-2 mb-2 block w-full" required />
                    <textarea v-model="editingKnife.description" placeholder="Description" class="border p-2 mb-2 block w-full" required></textarea>
                    <input v-model="editingKnife.price" type="number" placeholder="Price" class="border p-2 mb-2 block w-full" required />
                    <input v-model="editingKnife.image_url" type="url" placeholder="Image URL" class="border p-2 mb-2 block w-full" required />
                    <button @click="saveKnife" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                    <button @click="cancelEditingKnife" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>
