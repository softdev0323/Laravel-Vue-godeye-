<script setup>

import {Head} from "@inertiajs/vue3";
import FillScrNav from "@/Components/FillScrNav.vue";
import Footer from "@/Components/Footer.vue";
</script>

<template>
    <Head title="About" />
    <FillScrNav/>
    <div class="w-full flex flex-row pt-4 body">
        <img class="profile" src="https://i.ibb.co/B21hWq5f/maninthemirror.jpg" alt="self portrait of Emeka ">
        <p class="describe">
            Chukwuemeka Onyejesi is a prolific, versatile photographer; a creative director,  writer, healer and next in line to be the three-eyed raven.

            he is, somehow, yet to have an exhibition.; but that's only because he recently found out what those were.
            Now he has to compile a list of strong-ish images and come up with a back story (other than the fact that creating these photos literally
            save his life each time; giving him the daily bread required to not drive off say... 3rd mainland bridge.)<br>
            <br>
            sha sha..<br>
            <br>
            He might tell you about how he emerged finalist in the second edition of the Fashola Photographic Foundation (FPF)
            because he feels that if he doesn’t raise his shoulder small, who will raise it in this current clime of shoulder-raising
            shutter-nutters?<br>

            Untill tomorrow, Emeka does not know precisely what makes him pull the proverbial trigger. This is because his art is a study of his own impulses, energy, mood, tastes, encounters with other souls.
            and their palm-prints on his.  He wants to know how these little things link a person to a certain time, place but more interestingly,  to other people.  He challenges the idea of his own uniquess
            while still searching for what makes him just like everyone else.  Emeka seeks to understand HIS human condition first of all (as that might lead to understanding others.  A bet.
            it does give an introspective tone to his language though.)<br>

            Emeka combines low key photography with a characteristic lack of identity when approaching subjects. as if to ask: "if all was stripped away <em>from</em> you, what will remain<em> of </em> you?",
            and then answer it, or hope to. Art photography that also combines elements of street photography and portraiture are the things he enjoys.<br>
            Emeka likes to cook, film, game, laugh, and ask too many questions during workshops (apologies in advance).<br>
            Emeka likes Anime, Emeka likes to read fiction, Emeka loves music, Emeka's fitfam.
            Emeka majored in Computer Science.  He is also a Web Developer that built this beautiful site
        </p>
    </div>
    <Footer />
</template>

<style scoped>

.profile{
    padding: 15px;
    max-width: 34%;
    max-height: 70vh;
    padding-top: 8em;

}
.describe{
    padding: 10px;
    padding-top: 10em;
    max-height: 80%;
    font-size: 0.85rem;
    justify-self: center;
    color: greenyellow;
    mix-blend-mode: difference;
}

.body {
    background-color: black;
    background-size: cover;
}
</style>
