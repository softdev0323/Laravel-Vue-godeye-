<script setup>
import { ref, watchEffect, watch, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import FillScrNav from "@/Components/FillScrNav.vue";
import BlogPosts from '@/Components/BlogPosts.vue';
import JournalBanner from "@/Components/JournalBanner.vue";
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    journals: Array
});


</script>

<template>
    <Head title="Collections" />
    <FillScrNav/>
    <JournalBanner background-style=""/>
    <div class="items-center justify-center flex flex-col">
        <span class="my-b mt-10 uppercase text-xl text-gray-500">Journal</span>
        <BlogPosts :items="journals"/>
    </div>
    <Footer />
</template>

<style scoped>

</style>
