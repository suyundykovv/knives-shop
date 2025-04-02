<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const users = ref([]);
const page = usePage();

onMounted(() => {
    users.value = page.props.users;
});

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('admin.users.destroy', id));
    }
};
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Manage Users</h1>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2">ID</th>
                <th class="border border-gray-300 p-2">Name</th>
                <th class="border border-gray-300 p-2">Email</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id">
                <td class="border border-gray-300 p-2">{{ user.id }}</td>
                <td class="border border-gray-300 p-2">{{ user.name }}</td>
                <td class="border border-gray-300 p-2">{{ user.email }}</td>
                <td class="border border-gray-300 p-2">
                    <button @click="deleteUser(user.id)" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
