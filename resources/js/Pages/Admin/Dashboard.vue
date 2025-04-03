<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

// Knives management
const knives = ref([]);
const newKnife = ref({ name: '', description: '', price: '', image_url: '' });
const editingKnife = ref(null);
const knifeStatus = ref('');

const loadKnives = () => {
    knives.value = usePage().props.knives || [];
};

onMounted(loadKnives);
onMounted(() => {
    console.log("usePage().props.knives:", usePage().props.knives);
    knives.value = usePage().props.knives || [];
});

const addKnife = async () => {
    if (!validateKnife(newKnife.value)) return;

    try {
        await router.post(route('knives.store'), newKnife.value);
        knives.value.push({ ...newKnife.value });
        knifeStatus.value = 'Knife added successfully!';
        resetNewKnife();
    } catch (error) {
        knifeStatus.value = 'Error adding knife.';
    }
};

const saveKnife = async () => {
    if (!validateKnife(editingKnife.value)) return;

    try {
        await router.put(route('knives.update', editingKnife.value.id), editingKnife.value);
        const index = knives.value.findIndex(k => k.id === editingKnife.value.id);
        if (index !== -1) knives.value[index] = { ...editingKnife.value };
        knifeStatus.value = 'Knife updated successfully!';
        editingKnife.value = null;
    } catch (error) {
        knifeStatus.value = 'Error updating knife.';
    }
};

const deleteKnife = (id) => {
    if (confirm('Are you sure you want to delete this knife?')) {
        router.delete(route('knives.delete', id), {
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

// Users management
const users = ref([]);
const userStatus = ref('');
const editingUser = ref(null);

const loadUsers = () => {
    users.value = usePage().props.users || [];
    userStatus.value = usePage().props.status || '';
};

onMounted(loadUsers);

const startEditingUser = (user) => {
    editingUser.value = { ...user, is_admin: user.is_admin };
};

const cancelEditingUser = () => {
    editingUser.value = null;
};

const saveUser = async () => {
    try {
        await router.put(route('admin.users.update', editingUser.value.id), editingUser.value);
        const index = users.value.findIndex(u => u.id === editingUser.value.id);
        if (index !== -1) users.value[index] = { ...editingUser.value };
        userStatus.value = 'User updated successfully!';
        editingUser.value = null;
    } catch (error) {
        userStatus.value = 'Error updating user.';
    }
};

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('admin.users.delete', id), {
            onSuccess: () => {
                users.value = users.value.filter(user => user.id !== id);
                userStatus.value = 'User deleted successfully!';
            },
            onError: () => userStatus.value = 'Error deleting user.'
        });
    }
};
</script>

<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Users Management -->
            <div class="mt-8 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 mb-4">Manage Users</h2>
                    <p v-if="userStatus" class="text-green-500 mb-4">{{ userStatus }}</p>
                    <table v-if="users.length" class="w-full border-collapse border border-gray-300">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Name</th>
                            <th class="border p-2">Email</th>
                            <th class="border p-2">Role</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td class="border p-2">{{ user.id }}</td>
                            <td class="border p-2">
                                <input v-if="editingUser && editingUser.id === user.id" v-model="editingUser.name" class="border p-1" />
                                <span v-else>{{ user.name }}</span>
                            </td>
                            <td class="border p-2">
                                <input v-if="editingUser && editingUser.id === user.id" v-model="editingUser.email" class="border p-1" />
                                <span v-else>{{ user.email }}</span>
                            </td>
                            <td class="border p-2">
                                <input type="checkbox" v-model="editingUser.is_admin" v-if="editingUser && editingUser.id === user.id" class="mr-2" />
                                <span v-else>{{ user.is_admin ? 'Admin' : 'User' }}</span>
                            </td>
                            <td class="border p-2">
                                <button v-if="editingUser && editingUser.id === user.id" @click="saveUser" class="bg-green-500 text-white px-2 py-1 rounded">Save</button>
                                <button v-if="editingUser && editingUser.id === user.id" @click="cancelEditingUser" class="bg-gray-500 text-white px-2 py-1 rounded ml-2">Cancel</button>
                                <button v-else @click="startEditingUser(user)" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</button>
                                <button @click="deleteUser(user.id)" class="bg-red-500 text-white px-2 py-1 rounded ml-2">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
