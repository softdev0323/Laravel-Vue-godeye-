<script setup>
import { ref, watchEffect, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import FillScrNav from "@/Components/FillScrNav.vue";
import ImageGrid2 from '@/Components/ImageGrid2.vue';
import Footer from '@/Components/Footer.vue';
import Hero from "@/Components/Hero.vue";

// ✅ Receive `mediaData` from the backend as a prop
const props = defineProps({
    mediaData: Object
});

// ✅ Store all media items
const mediaItems = ref([...props.mediaData.data]);

// ✅ Track pagination details
const currentPage = ref(props.mediaData.current_page);
const nextPageUrl = ref(props.mediaData.next_page_url);

// ✅ Keep track of new items that should be added to the grid
const newItemsToAdd = ref(null);

// ✅ Loading state
const isLoading = ref(false);

// Video state
const isVideoLoading = ref(true);
const onVideoLoad = () => {
    isVideoLoading.value = false;
};

// ** Watcher to detect new data and process it **
watch(
    () => props.mediaData,
    (newData) => {
        if (newData.data) {
            newItemsToAdd.value = newData.data;

            // Update pagination info
            currentPage.value = newData.current_page;
            nextPageUrl.value = newData.next_page_url;

            // Set loading to false when data arrives
            isLoading.value = false;
        }
    },
    { deep: true }
);

// After we've pushed the items to the child component, reset newItemsToAdd
const onItemsAdded = () => {
    newItemsToAdd.value = null;
};

// Function to fetch next page
const fetchNextPage = () => {
    if (!nextPageUrl.value || isLoading.value) {
        return;
    }

    // Set loading state to true before fetching
    isLoading.value = true;

    router.get(nextPageUrl.value, {}, {
        preserveState: true,
        preserveScroll: true,
        only: ["mediaData"],
        onSuccess: () => {
            // Loading state will be set to false when the watcher detects new data
        },
        onError: () => {
            // Make sure to set loading to false even on error
            isLoading.value = false;
        }
    });
};

// ✅ Infinite Scroll - Detect when user scrolls near bottom
const handleScroll = () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
        fetchNextPage();
    }
};

// ✅ Attach event listener for scrolling
watchEffect(() => {
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <Head title="Media Gallery" />
    <FillScrNav/>
    <Hero background-style=""/>

    <!-- Video Section -->
    <div class="video-container my-16 relative">
        <!-- Loading Overlay -->
        <div v-if="isVideoLoading" class="absolute inset-0 bg-gray-100/80 backdrop-blur-sm flex items-center justify-center rounded-xl">
            <div class="spinner"></div>
        </div>

        <div class="video-wrapper">
            <div class="video-decorative-element"></div>
            <div class="aspect-video max-w-4xl mx-auto rounded-xl overflow-hidden shadow-2xl relative">
                <iframe
                    class="w-full h-full"
                    src="https://www.youtube.com/embed/_6kTGB1kJxo?autoplay=0"
                    title="YouTube video"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    @load="onVideoLoad"
                ></iframe>
            </div>
        </div>
    </div>

    <div class="items-center justify-center flex flex-col">
        <span class="my-b mt-10 uppercase text-xl text-gray-500">Aesthetic Intelligence</span>
        <span class="my-b mt-2 text-md text-gray-500">A Living MoodBoard</span>
        <ImageGrid2
            :items="mediaItems"
            :new-items="newItemsToAdd"
            @items-added="onItemsAdded"
        />

        <!-- Loading spinner -->
        <div v-if="isLoading" class="spinner-container">
            <div class="spinner"></div>
            <p class="mt-3 text-gray-500">Loading more images...</p>
        </div>

        <!-- Only show load more button when not loading -->
        <button v-if="nextPageUrl && !isLoading" @click="fetchNextPage" class="load-more">
            Load More
        </button>
    </div>
    <Footer />
</template>

<style scoped>
.video-container {
    width: 100%;
    padding: 0 1rem;
    position: relative;
}

.video-wrapper {
    position: relative;
    z-index: 1;
}

.video-decorative-element {
    position: absolute;
    top: -1rem;
    right: -1rem;
    width: 100%;
    height: 100%;
    border: 2px solid #333;
    border-radius: 1rem;
    z-index: -1;
    opacity: 0.1;
    transform: rotate(-2deg);
    transition: all 0.3s ease;
}

.video-wrapper:hover .video-decorative-element {
    transform: rotate(0deg);
    opacity: 0.2;
}

.video-wrapper::before {
    content: '';
    position: absolute;
    top: 1rem;
    left: -1rem;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.05) 0%, transparent 100%);
    border-radius: 1rem;
    z-index: -1;
}

/* Enhanced spinner for video loading */
.spinner {
    border: 3px solid rgba(0, 0, 0, 0.05);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border-left-color: #333;
    animation: spin 1s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Responsive padding adjustments */
@media (max-width: 768px) {
    .video-container {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .video-decorative-element {
        display: none;
    }
}

/* Keep existing styles */
.load-more {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #333;
    color: white;
    border: none;
    cursor: pointer;
}

.spinner-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 20px 0;
}
</style>
