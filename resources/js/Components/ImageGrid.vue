<template>
    <div class="image-grid">
        <div class="image-item" v-for="(item, index) in items" :key="index" >
            <img :src="item.image_url" />
            <p>{{ item.text }}</p>
        </div>

        <button v-if="nextPageUrl" @click="fetchNextPage">Load More</button>
    </div>


</template>

<script setup>

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

</script>

<style scoped>
.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

.image-item {
    text-align: center;
}

.image-item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.source {
    font-size: 0.9em;
    color: #666;
}
</style>
