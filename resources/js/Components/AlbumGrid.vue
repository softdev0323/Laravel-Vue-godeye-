<template>
    <div class="flex w-full px-5 gap-5 mt-10">
        <div class="w-1/2 space-y-24 p-20">
            <div v-for="(item, index) in firstColumn" :key="'first-' + index" class="text-center">
                <img
                    :src="item.cover"
                    :alt="item.title"
                    class="rounded-lg shadow-2xl cursor-pointer hover:opacity-80 transition"
                    @click="openPreview(item)"  
                />
                <p class="mt-10 text-gray-500">{{ item.title }}</p>
            </div>
        </div>

        <div class="w-1/2 space-y-24 p-20">
            <div v-for="(item, index) in secondColumn" :key="'second-' + index" class="text-center">
                <img
                    :src="item.cover"
                    :alt="item.title"
                    class="w-full rounded-lg shadow-2xl cursor-pointer hover:opacity-80 transition"
                    @click="openPreview(item)"  
                />
                <p class="mt-2 text-gray-500">{{ item.title }}</p>
            </div>
        </div>
    </div>

    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="relative p-5 w-full max-w-2xl">
            <button @click="closePreview" class="absolute top-5 right-10 text-white text-3xl">&times;</button>

            <div class="relative group">
                <img :src="selectedImage" class="w-full h-full rounded-lg shadow-lg"/>
                
                <div v-if="selectedCaption" 
                    class="absolute inset-0 bg-black bg-opacity-70 opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out flex items-center justify-center p-8 rounded-lg">
                    <p class="text-white font-serif italic text-center overflow-y-auto max-h-full" style="font-family: 'Cormorant Garamond', serif; text-shadow: 0 0 10px rgba(255,255,255,0.3);">
                        {{ selectedCaption }}
                    </p>
                </div>
            </div>

            <button
                @click="prevImage"
                class="absolute left-7 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-gray-700 bg-opacity-50 p-2 rounded-full hover:bg-opacity-75 transition-colors duration-300"
                :class="{ 'opacity-50 cursor-not-allowed': currentImageIndex <= 0 && currentItemIndex <= 0 }">
                &#10094;
            </button>
            <button
                @click="nextImage"
                class="absolute right-7 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-gray-700 bg-opacity-50 p-2 rounded-full hover:bg-opacity-75 transition-colors duration-300"
                :class="{ 'opacity-50 cursor-not-allowed': !hasNextImage }">
                &#10095;
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";

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
const mediaItems = ref(props.items ?? []);

// Compute the items to display in each column
const firstColumn = computed(() => {
    return mediaItems.value.filter((_, i) => i % 2 === 0);
});

const secondColumn = computed(() => {
    return mediaItems.value.filter((_, i) => i % 2 === 1);
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
const selectedCaption = ref(null);
const currentItemIndex = ref(0);
const currentImageIndex = ref(0);
const isOpen = ref(false);

// Computed property to check if there's a next image
const hasNextImage = computed(() => {
    const currentItem = mediaItems.value[currentItemIndex.value];
    // If we're viewing the item's cover or it has no images array
    if (currentImageIndex.value === -1 || !currentItem.images) {
        return currentItemIndex.value < mediaItems.value.length - 1;
    }
    // If we're viewing an image from the images array
    return currentImageIndex.value < currentItem.images.length - 1 || currentItemIndex.value < mediaItems.value.length - 1;
});

// Open the image preview modal
const openPreview = (item) => {
    // Find the index of the clicked item in mediaItems
    currentItemIndex.value = mediaItems.value.findIndex(i => i.id === item.id);
    
    // Start with the first image from the images array if it exists
    if (item.images && item.images.length > 0) {
        currentImageIndex.value = 0;
        selectedImage.value = item.images[0].image_url;
        selectedCaption.value = item.images[0].caption;
    } else {
        // Otherwise use the cover image
        currentImageIndex.value = -1; // -1 indicates we're showing the cover
        selectedImage.value = item.cover;
        selectedCaption.value = item.description;
    }
    
    isOpen.value = true;
};

// Close the modal
const closePreview = () => {
    isOpen.value = false;
    selectedImage.value = null;
    selectedCaption.value = null;
};

// Navigate to previous image or item
const prevImage = () => {
    if (currentImageIndex.value <= 0 && currentItemIndex.value <= 0) return;
    
    const currentItem = mediaItems.value[currentItemIndex.value];
    
    // If we're viewing an image from the images array (not the cover)
    if (currentImageIndex.value > 0) {
        currentImageIndex.value--;
        selectedImage.value = currentItem.images[currentImageIndex.value].image_url;
        selectedCaption.value = currentItem.images[currentImageIndex.value].caption;
    } 
    // If we're viewing the first image in the array, go to the cover
    else if (currentImageIndex.value === 0) {
        currentImageIndex.value = -1;
        selectedImage.value = currentItem.cover;
        selectedCaption.value = currentItem.description;
    }
    // If we're viewing the cover, go to the previous item
    else if (currentImageIndex.value === -1 && currentItemIndex.value > 0) {
        currentItemIndex.value--;
        const prevItem = mediaItems.value[currentItemIndex.value];
        
        if (prevItem.images && prevItem.images.length > 0) {
            currentImageIndex.value = prevItem.images.length - 1;
            selectedImage.value = prevItem.images[currentImageIndex.value].image_url;
            selectedCaption.value = prevItem.images[currentImageIndex.value].caption;
        } else {
            currentImageIndex.value = -1;
            selectedImage.value = prevItem.cover;
            selectedCaption.value = prevItem.description;
        }
    }
};

// Navigate to next image or item
const nextImage = () => {
    if (!hasNextImage.value) return;
    
    const currentItem = mediaItems.value[currentItemIndex.value];
    
    // If we're viewing the cover or no images array exists
    if (currentImageIndex.value === -1 || !currentItem.images) {
        // If there are images, go to the first one
        if (currentItem.images && currentItem.images.length > 0) {
            currentImageIndex.value = 0;
            selectedImage.value = currentItem.images[0].image_url;
            selectedCaption.value = currentItem.images[0].caption;
        } 
        // Otherwise go to the next item
        else if (currentItemIndex.value < mediaItems.value.length - 1) {
            currentItemIndex.value++;
            const nextItem = mediaItems.value[currentItemIndex.value];
            currentImageIndex.value = -1;
            selectedImage.value = nextItem.cover;
            selectedCaption.value = nextItem.description;
        }
    }
    // If we're viewing an image from the images array
    else if (currentItem.images) {
        // If there are more images in this item
        if (currentImageIndex.value < currentItem.images.length - 1) {
            currentImageIndex.value++;
            selectedImage.value = currentItem.images[currentImageIndex.value].image_url;
            selectedCaption.value = currentItem.images[currentImageIndex.value].caption;
        }
        // Otherwise go to the next item
        else if (currentItemIndex.value < mediaItems.value.length - 1) {
            currentItemIndex.value++;
            const nextItem = mediaItems.value[currentItemIndex.value];
            currentImageIndex.value = -1;
            selectedImage.value = nextItem.cover;
            selectedCaption.value = nextItem.description;
        }
    }
};

// Handle keyboard events
const handleKeyDown = (event) => {
    if (event.key === "Escape") closePreview();
    if (event.key === "ArrowLeft") prevImage();
    if (event.key === "ArrowRight") nextImage();
};

onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
    
    // Dynamically add the elegant font for captions if needed
    const fontLink = document.createElement('link');
    fontLink.rel = 'stylesheet';
    fontLink.href = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;1,300&display=swap';
    document.head.appendChild(fontLink);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
});
</script>