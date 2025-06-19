<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from "vue";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    newItems: {
        type: Array,
        default: null,
    }
});

const emit = defineEmits(['itemsAdded']);

// Store all media items
const mediaItems = ref([...props.items]);

// Compute the items to display in each column
const firstColumn = computed(() => {
    const items = mediaItems.value.filter((_, i) => i % 2 === 0);
    return items;
});

const secondColumn = computed(() => {
    const items = mediaItems.value.filter((_, i) => i % 2 === 1);
    return items;
});

watch(
    () => props.newItems,
    (newItems) => {

        if (newItems !== null) {
            const array = Object.keys(newItems).map(key => ({id: key, ...newItems[key]}));
            mediaItems.value = [...mediaItems.value, ...array];
            emit('itemsAdded');
        }
    },
    {immediate: true}
);

// Modal/preview functionality
const selectedImage = ref(null);
const currentIndex = ref(0);
const isOpen = ref(false);

// Open the image preview modal
const openPreview = (index) => {
    currentIndex.value = index;
    selectedImage.value = mediaItems.value[index].image_url;
    isOpen.value = true;
};

// Close the modal
const closePreview = () => {
    isOpen.value = false;
    selectedImage.value = null;
};

// Navigate to previous image
const prevImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
        selectedImage.value = mediaItems.value[currentIndex.value].image_url;
    }
};

// Navigate to next image
const nextImage = () => {
    if (currentIndex.value < mediaItems.value.length - 1) {
        currentIndex.value++;
        selectedImage.value = mediaItems.value[currentIndex.value].image_url;
    }
};

// Close modal on 'Escape' key press
const handleKeyDown = (event) => {
    if (event.key === "Escape") closePreview();
};

onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
});
</script>

<template>
    <div class="flex w-full px-5 gap-5 mt-10">
        <div class="w-1/2 space-y-24 p-20">
            <div v-for="(item, index) in firstColumn" :key="'first-' + index" class="text-center">
                <img
                    :src="item.image_url"
                    :alt="item.text"
                    class="rounded-lg shadow-2xl cursor-pointer hover:opacity-80 transition"
                    style="width: auto; height: auto; max-width: 100%; max-height: 100%;"
                    @click="openPreview(index * 2)"
                />
                <p class="mt-10 text-gray-500">{{ item.text }}</p>
            </div>
        </div>

        <div class="w-[4px] bg-gray-200"></div>

        <div class="w-1/2 space-y-24 p-20">
            <div v-for="(item, index) in secondColumn" :key="'second-' + index" class="text-center">
                <img
                    :src="item.image_url"
                    :alt="item.text"
                    class="w-full rounded-lg shadow-2xl cursor-pointer hover:opacity-80 transition"
                    @click="openPreview(index * 2 + 1)"
                />
                <p class="mt-2 text-gray-500">{{ item.text }}</p>
            </div>
        </div>
    </div>

    <!-- Image preview modal -->
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="relative p-5 w-full max-w-2xl">
            <button @click="closePreview" class="absolute top-5 right-10 text-white text-3xl">&times;</button>

            <img :src="selectedImage" class="w-full h-full rounded-lg shadow-lg"/>

            <button
                v-if="currentIndex > 0"
                @click="prevImage"
                class="absolute left-7 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-gray-700 bg-opacity-50 p-2 rounded-full hover:bg-opacity-75">
                &#10094;
            </button>
            <button
                v-if="currentIndex < mediaItems.length - 1"
                @click="nextImage"
                class="absolute right-7 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-gray-700 bg-opacity-50 p-2 rounded-full hover:bg-opacity-75">
                &#10095;
            </button>
        </div>
    </div>
</template>
