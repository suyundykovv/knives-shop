<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const knives = ref([]);
const page = usePage();

onMounted(() => {
    knives.value = page.props.knives || [];
});
</script>

<template>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Каталог ножей</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="knife in knives" :key="knife.id" class="bg-white shadow-lg rounded-lg p-4">
                <img :src="knife.image_url" alt="Knife Image" class="w-full h-48 object-cover rounded-md" v-if="knife.image_url">
                <h2 class="text-xl font-semibold mt-4">{{ knife.name }}</h2>
                <p class="text-gray-600">{{ knife.description }}</p>
                <p class="text-lg font-bold mt-2">Цена: {{ knife.price }}$</p>
            </div>
        </div>
    </div>
</template>
