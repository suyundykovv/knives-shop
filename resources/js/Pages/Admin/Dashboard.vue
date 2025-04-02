<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const isAdmin = computed(() => page.props.auth.user?.is_admin);
</script>

<template>
    <div v-if="isAdmin">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a :href="route('admin.users.index')">Manage Users</a></li>
            </ul>
        </nav>
    </div>
    <div v-else>
        <h1>Access Denied</h1>
    </div>
</template>
