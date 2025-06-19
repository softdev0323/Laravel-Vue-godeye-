<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 px-4 max-w-6xl mx-auto mt-10">
        <div v-for="(item, index) in mediaItems" :key="index" 
            class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 flex flex-col">
            <div class="relative h-64 overflow-hidden">
                <img
                    :src="item.cover_image"
                    :alt="item.title"
                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                />
            </div>
            <div class="p-8 flex flex-col flex-grow">
                <h2 class="text-2xl font-semibold mb-2 text-gray-800">{{ item.title }}</h2>
                <div class="border-t border-b border-gray-200 my-4 py-4">
                    <p class="text-gray-700">
                        {{ getTextPreview(item.text) }}
                        <a :href="`/journal/${item.id}`" 
                           class="text-indigo-600 hover:text-indigo-800 underline inline-flex items-center">
                           read more
                        </a>
                    </p>
                </div>
                <div class="mt-auto">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ formatDate(item.created_at) }}</span>
                        <div class="flex items-center space-x-3">
                            <!-- Facebook Share -->
                            <a :href="getFacebookShareUrl(item)" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 transition-colors"
                               title="Share on Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                                </svg>
                            </a>
                            <!-- Twitter Share -->
                            <a :href="getTwitterShareUrl(item)"
                               target="_blank"
                               class="text-sky-500 hover:text-sky-700 transition-colors"
                               title="Share on Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.58v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/>
                                </svg>
                            </a>
                            <!-- Instagram Share -->
                            <a :href="getInstagramShareUrl(item)"
                               target="_blank"
                               class="text-pink-600 hover:text-pink-800 transition-colors"
                               title="Share on Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <!-- Copy Link -->
                            <button @click="copyLink(item)"
                                    class="text-gray-600 hover:text-gray-800 transition-colors"
                                    title="Copy link">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast for Copy Link -->
    <div v-if="showCopyToast" 
         class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transition-opacity duration-300"
         :class="{ 'opacity-0': !showCopyToast }">
        Link copied to clipboard!
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

// Format date
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
};

// Extract and format text preview from HTML or JSON content
const getTextPreview = (content) => {
    if (!content) return 'No content available... ';
    
    try {
        let text = '';
        // Check if content is HTML
        if (typeof content === 'string' && content.includes('<')) {
            // Create a temporary div to parse HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            text = tempDiv.textContent || tempDiv.innerText || '';
        }
        // Check if content is JSON
        else if (typeof content === 'string' && (content.startsWith('{') || content.startsWith('['))) {
            const jsonContent = JSON.parse(content);
            if (jsonContent.ops) {
                // Handle Quill Delta format
                text = jsonContent.ops
                    .map(op => op.insert)
                    .filter(text => typeof text === 'string')
                    .join('');
            } else {
                return 'Content available... ';
            }
        } else {
            // Plain text
            text = content;
        }
        
        // Return truncated text without ellipsis (since we'll add read more link)
        return text.length > 150 ? text.slice(0, 150) + '... ' : text + ' ';
    } catch (e) {
        // If there's an error parsing, return a default
        return 'Content available... ';
    }
};

// Watch for new items
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

const showCopyToast = ref(false);

// Updated social media sharing URLs with better metadata
const getFacebookShareUrl = (item) => {
    const url = encodeURIComponent(`${window.location.origin}/journal/${item.id}`);
    return `https://www.facebook.com/sharer/sharer.php?u=${url}`;
};

const getTwitterShareUrl = (item) => {
    const url = encodeURIComponent(`${window.location.origin}/journal/${item.id}`);
    const text = encodeURIComponent(`${item.title} - ${item.subtitle}`);
    const via = encodeURIComponent('YourTwitterHandle'); // Replace with your Twitter handle
    return `https://twitter.com/intent/tweet?url=${url}&text=${text}&via=${via}`;
};

const getInstagramShareUrl = (item) => {
    // Instagram doesn't have a direct share URL API, but we can open the app
    return `instagram://camera`;
};

const copyLink = async (item) => {
    const url = `${window.location.origin}/journal/${item.id}`;
    try {
        await navigator.clipboard.writeText(url);
        showCopyToast.value = true;
        setTimeout(() => {
            showCopyToast.value = false;
        }, 2000);
    } catch (err) {
        console.error('Failed to copy link:', err);
    }
};
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Add smooth transition for toast */
.fixed {
    transition: opacity 0.3s ease-in-out;
}
</style>
