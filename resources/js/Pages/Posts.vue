<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import FillScrNav from "@/Components/FillScrNav.vue";
import Footer from '@/Components/Footer.vue';
import JournalBanner from "@/Components/JournalBanner.vue";
import { onMounted, computed } from 'vue';

const props = defineProps({
    post: Object
});

const getDescription = (content) => {
    if (!content) return '';
    
    try {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;
        const firstParagraph = tempDiv.querySelector('p');
        return firstParagraph ? 
            (firstParagraph.textContent || '').slice(0, 160) + '...' : 
            content.slice(0, 160) + '...';
    } catch (e) {
        return content.slice(0, 160) + '...';
    }
};

// Computed property for the full page URL
const pageUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return `${window.location.origin}/journal/${props.post.id}`;
    }
    // Fallback or default value if window is not defined (though for og:url, a full URL is needed)
    // Inertia's usePage().url might give a relative path. We need the origin.
    // If your app has a canonical URL set in config, you could use that as a base.
    // For now, let's rely on client-side execution for this meta tag or it might be empty/incorrect during SSR.
    const { url } = usePage(); // This gives the current path, e.g., /journal/1
    // Attempt to get origin from props if passed by controller, or set a default.
    const baseUrl = props.ziggy?.url || 'https://yourdomain.com'; // Replace with your actual domain or how you get it
    return `${baseUrl}${url}`;
});
</script>

<template>
    <Head>
        <title>{{ post.title }}</title>
        <meta name="description" :content="getDescription(post.text)" />
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="article" />
        <meta property="og:title" :content="post.title" />
        <meta property="og:description" :content="getDescription(post.text)" />
        <meta property="og:image" :content="post.cover_image" />
        <meta property="og:url" :content="pageUrl" />
        <meta property="og:site_name" content="Your Site Name" />
        
        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="post.title" />
        <meta name="twitter:description" :content="getDescription(post.text)" />
        <meta name="twitter:image" :content="post.cover_image" />
        <meta name="twitter:site" content="@YourTwitterHandle" />
        
        <!-- Article specific -->
        <meta property="article:published_time" :content="post.created_at" />
        <meta property="article:author" content="Your Name" />
        <meta property="article:section" content="Journal" />
    </Head>
    <FillScrNav />
    <JournalBanner background-style=""/>
    
    <div class="items-center justify-center flex flex-col">
        <span class="my-b mt-10 uppercase text-xl text-gray-500">Journal</span>
        
        <div class="max-w-6xl mx-auto px-4 py-8">
            <!-- Post Content Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                <!-- Hero Banner -->
                <div class="relative h-80 overflow-hidden">
                    <img :src="post.cover_image" :alt="post.title" class="w-full h-full object-cover">
                </div>
                
                <div class="p-8">
                    <!-- Header -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ post.title }}</h1>
                        <div class="text-sm text-gray-500 mt-2">
                            {{ new Date(post.created_at).toLocaleDateString('en-US', { 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            }) }}
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="prose" v-html="post.text"></div>
                    </div>
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="flex justify-center mt-8">
                <a href="/journal" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors shadow-md">
                    Back to Journal
                </a>
            </div>
        </div>
    </div>
    
    <Footer />
</template>

<style>
/* Additional styling for the content */
.prose {
    font-size: 1.125rem;
    line-height: 1.4;
    color: #374151;
}

.prose p {
    margin-bottom: 0.75rem;
}

.prose p:last-child {
    margin-bottom: 0;
}

.prose img {
    border-radius: 0.5rem;
    margin: 1.5rem auto;
}

.prose h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #1F2937;
}

/* Override Tailwind prose defaults */
:deep(.prose) {
    max-width: none;
}

:deep(.prose p) {
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
}

:deep(.prose > p) {
    margin: 0 0 0.75rem 0;
}

:deep(.prose br) {
    display: none;
}

:deep(.prose > p:last-child) {
    margin-bottom: 0;
}
</style> 