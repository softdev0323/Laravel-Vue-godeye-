<script setup>
import { ref, watchEffect, watch, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import FillScrNav from "@/Components/FillScrNav.vue";
import AlbumGrid from '@/Components/AlbumGrid.vue';
import Banner from "@/Components/Banner.vue";
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    album: Array
});


</script>

<template>
    <Head title="Collections" />
    <FillScrNav/>
    <Banner background-style=""/>
    <div class="items-center justify-center flex flex-col">
        <span class="my-b mt-10 uppercase text-xl text-gray-500">Collections</span>
        <AlbumGrid :items="album"/>
    </div>
    <Footer />
</template>

<style scoped>

</style>
