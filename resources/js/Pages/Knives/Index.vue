<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const knives = ref([]);
const cart = ref([]); // 🛒 Корзина
const newKnife = ref({ name: '', description: '', price: '', image_url: '' });
const editingKnife = ref(null);
const knifeStatus = ref('');

const loadKnives = () => {
    knives.value = usePage().props.knives || [];
};

const loadCart = () => {
    const savedCart = localStorage.getItem('cart');
    cart.value = savedCart ? JSON.parse(savedCart) : [];
};

onMounted(() => {
    loadKnives();
    loadCart();
});

// 🛒 Добавить нож в корзину
const addToCart = (knife) => {
    if (!cart.value.some(item => item.id === knife.id)) {
        cart.value.push({ ...knife });
        saveCart();
    }
};

// 🛒 Удалить нож из корзины
const removeFromCart = (knifeId) => {
    cart.value = cart.value.filter(k => k.id !== knifeId);
    saveCart();
};

// 🛒 Очистить корзину
const clearCart = () => {
    cart.value = [];
    saveCart();
};

// 🛒 Сохранить корзину в LocalStorage
const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(cart.value));
};
</script>
<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- 🛒 Корзина -->
            <div class="mt-8 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Cart</h2>
                    <div v-if="cart.length">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="knife in cart" :key="knife.id" class="flex justify-between items-center border p-4 rounded-lg shadow-lg dark:bg-gray-700">
                                <div>
                                    <img :src="knife.image_url" alt="Knife Image" class="w-16 h-16 object-cover rounded-md" />
                                    <p class="text-sm mt-2">{{ knife.name }}</p>
                                    <p class="text-gray-500">{{ knife.price }}$</p>
                                </div>
                                <button @click="removeFromCart(knife.id)" class="bg-red-500 text-white px-4 py-2 rounded-md">Remove</button>
                            </div>
                        </div>
                        <button @click="clearCart" class="mt-4 bg-gray-500 text-white px-6 py-3 rounded-md">Clear Cart</button>
                    </div>
                    <p v-else class="text-gray-500">Cart is empty.</p>
                </div>
            </div>

            <!-- 🔪 Список ножей -->
            <div class="mt-8 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Available Knives</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div v-for="knife in knives" :key="knife.id" class="bg-white border rounded-lg shadow-lg dark:bg-gray-700 p-4">
                            <img :src="knife.image_url" alt="Knife Image" class="w-full h-48 object-cover rounded-md mb-4" />
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ knife.name }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ knife.description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ knife.price }}$</span>
                                <button @click="addToCart(knife)" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
