<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const users = ref([]);
const status = ref('');
const editingUser = ref(null);
const page = usePage();

onMounted(() => {
    users.value = page.props.users || [];
    status.value = page.props.status || '';
});

const startEditing = (user) => {
    editingUser.value = { ...user, is_admin: user.is_admin }; // Ensure correct field name
};

const cancelEditing = () => {
    editingUser.value = null;
};

const saveUser = () => {
    router.put(route('admin.users.update', editingUser.value.id), editingUser.value, {
        onSuccess: () => {
            const index = users.value.findIndex(u => u.id === editingUser.value.id);
            if (index !== -1) {
                users.value[index] = { ...editingUser.value };
            }
            status.value = 'User updated successfully!';
            editingUser.value = null;
        },
        onError: () => {
            status.value = 'Error updating user.';
        },
    });
};

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('admin.users.delete', id), {
            onSuccess: () => {
                users.value = users.value.filter(user => user.id !== id);
                status.value = 'User deleted successfully!';
            },
            onError: (err) => {
                console.error(err);
                status.value = 'Error deleting user.';
            },
        });
    }
};

</script>

<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mt-8 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 mb-4">
                        Manage Users
                    </h2>
                    <p v-if="status" class="text-green-500 mb-4">{{ status }}</p>
                    <p v-if="users.length === 0" class="text-gray-500">No users found.</p>

                    <table v-if="users.length > 0" class="w-full border-collapse border border-gray-300">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2">ID</th>
                            <th class="border border-gray-300 p-2">Name</th>
                            <th class="border border-gray-300 p-2">Email</th>
                            <th class="border border-gray-300 p-2">Role</th>
                            <th class="border border-gray-300 p-2">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td class="border border-gray-300 p-2">{{ user.id }}</td>
                            <td class="border border-gray-300 p-2">
                                <input v-if="editingUser && editingUser.id === user.id" v-model="editingUser.name" class="border p-1" />
                                <span v-else>{{ user.name }}</span>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input v-if="editingUser && editingUser.id === user.id" v-model="editingUser.email" class="border p-1" />
                                <span v-else>{{ user.email }}</span>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <template v-if="editingUser && editingUser.id === user.id">
                                    <input type="checkbox" v-model="editingUser.is_admin" class="mr-2" />
                                    <span>{{ editingUser.is_admin ? 'Admin' : 'User' }}</span>
                                </template>
                                <span v-else>{{ user.is_admin ? 'Admin' : 'User' }}</span>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <button v-if="editingUser && editingUser.id === user.id" @click="saveUser" class="bg-green-500 text-white px-2 py-1 rounded">Save</button>
                                <button v-if="editingUser && editingUser.id === user.id" @click="cancelEditing" class="bg-gray-500 text-white px-2 py-1 rounded ml-2">Cancel</button>
                                <button v-else @click="startEditing(user)" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</button>
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
