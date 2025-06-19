<script setup>
import {computed} from "vue";

const bgImg = computed(() => ({
    backgroundRepeat:"no-repeat",
    backgroundSize:"cover",
    backgroundAttachment:"fixed",
    backgroundPosition:"center",
}));
</script>

<template>
    <div class="landing w-full" :style="bgImg">
    </div>
</template>

<style scoped>
.landing{
    height: 95vh;
    background-image: url("https://i.ibb.co/jJ2vN9B/IMG-3769.gif");
}
</style>
