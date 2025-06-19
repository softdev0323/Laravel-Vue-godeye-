<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {faBars, faTimes} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {ref} from "vue";
const showNav = ref(false);
</script>

<template>
    <Head title="Welcome" />
    <div class=" main-header align-middle flex justify-between bg-black text-black/50 dark:bg-black dark:text-white/50 opacity-25">
        <div class="brand-name">
            <Link href="/">
                <strong> Emeka Onyejesi ™ </strong>
            </Link>
        </div>
        <nav class="nav align-middle text-center justify-center" :class="{ show: showNav, hide: !showNav }">
            <FontAwesomeIcon v-if="!showNav" :icon="faTimes" @click="showNav = !showNav" :class="['cross']"/>
            <ul>
                <a class="nav-link" aria-current="page" href="#">Home</a>
                <a class="nav-link" href="#">Collections</a>
                <a class="nav-link" href="#">Publications</a>
                <a class="nav-link" href="#">Diary</a>
                <a class="nav-link" href="#">Contact</a>
            </ul>
        </nav>
        <FontAwesomeIcon v-if="!showNav" :icon="faBars" @click="showNav = !showNav"/>

    </div>

</template>

<style scoped>

    .nav{
        z-index: 5;
        top: 0;
    }
    .brand-name{
        font-size: 20px;
        padding-left: 1rem;
    }

    .main-header
    {
        height: 30px;
        width: 100%;
        padding: 3rem;
        z-index:2;
        /*background-color: transparent;*/
        /* background: rgba(14, 13, 13, 0.43); */
        position: absolute;
        color: whitesmoke;
        border-bottom: 3px solid transparent ;
        transition: border-bottom 1s;
    }

    nav ul li{
        list-style: none;
        margin : 35px 0;
    }

    nav ul a {
        text-decoration: none;
        font-size: 40px;
        color: #fff;
        padding: 10px;
        letter-spacing: 5px;
        position: relative;
    }

    .main-header:hover{
        border-color: white;
    }

    nav ul a::after{
        content:'';
        height: 3px;
        width: 0;
        background: lightgreen;
        position: absolute;
        bottom: 0;
        left : 0;
        transition: width 0.5s;
    }

    nav ul a:hover::after{
        width: 100%;

    }

    nav {
        position: absolute;
        width: 100%;
        height: 100vh;
        background: #000;
        opacity: 0.20;
        top: 0;
        left: 0;
        transition: 1s;
    }

    .cross{
        position : absolute;
        color: #fff;
        top: 35px;
        right: 40px;
        font-size: 70px;
        cursor: pointer;

    }
    .show {
        top: -100vh;
    }
    .hide{
        top: 0;
    }
</style>
