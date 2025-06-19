<template>
    <footer class=" mx-auto px-4 py-10 text-center body">
      <!-- Mailing List Section -->
      <div class="mb-12">
<!--        <h2 class="text-2xl font-normal mb-6">JOIN OUR MAILING LIST</h2>-->

<!--        <div class="flex justify-center max-w-xl mx-auto mb-5">-->
<!--          <input -->
<!--            type="email" -->
<!--            v-model="email" -->
<!--            placeholder="Email" -->
<!--            class="flex-grow border border-gray-300 px-4 py-3 text-base"-->
<!--          />-->
<!--          <button -->
<!--            @click="subscribe" -->
<!--            class="bg-white border border-gray-300 px-8 py-3 text-base uppercase hover:bg-gray-50"-->
<!--          >-->
<!--            SUBSCRIBE-->
<!--          </button>-->
<!--        </div>-->

        <p class="text-sm mb-10">
<!--          By signing up to receive emails, you agree to our-->
          <a href="/privacy" class="underline">privacy policy.</a>
        </p>
      </div>

      <!-- Navigation Links -->
      <nav class="mb-16 w-100">
        <ul class="flex flex-wrap justify-center gap-x-6 gap-y-2">





          <li><a href="/" class="hover:underline">home</a></li>
          <li><a href="/about" class="hover:underline">about</a></li>
          <li><a href="/albums" class="hover:underline">collections</a></li>

        </ul>
      </nav>

      <!-- Copyright Section -->
      <div>
        <p class="text-lg">© EMEKA ONYEJESI</p>
      </div>
    </footer>
  </template>

  <script setup>
  import { ref } from 'vue';

  // State
  const email = ref('');

  // Methods
  const subscribe = () => {
    if (email.value) {
      // Add your subscription logic here
      console.log('Subscribing email:', email.value);
      // Reset the field after subscription
      email.value = '';
      // You might want to add success/error handling here
    }
  };
  </script>

<style scoped>
.body{
    width: 100%;
    background-color: transparent;
    color: grey;
    mix-blend-mode: difference;
}
</style>
