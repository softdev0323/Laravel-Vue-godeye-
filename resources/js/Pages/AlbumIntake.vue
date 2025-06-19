<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    title: "",
    description: "",
    year: "",
    cover: "",
    completed: false,
});

const submit = () => {
    form.post(route("album.store"), {
        onSuccess: () => {
            alert("Album created successfully!");
            form.reset();
        },
    });
};
</script>

<template>
    <div class="max-w-2xl mx-auto bg-white p-6 shadow-lg rounded-lg mt-10">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Create New Album</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Title Field -->
            <div>
                <label for="title" class="block text-gray-700 font-medium">Title</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    required
                />
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-gray-700 font-medium">Description</label>
                <textarea
                    v-model="form.description"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    rows="3"
                    required
                ></textarea>
            </div>

            <!-- Year Field -->
            <div>
                <label for="year" class="block text-gray-700 font-medium">Year</label>
                <input
                    v-model="form.year"
                    type="number"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    required
                />
            </div>

            <!-- Completed Checkbox -->
            <div class="flex items-center">
                <input
                    v-model="form.completed"
                    type="checkbox"
                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <label for="completed" class="ml-2 text-gray-700">Completed</label>
            </div>

            <div>
                <label for="image_url" class="block text-gray-700 font-medium">Image URL</label>
                <input
                    v-model="form.cover"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    required
                />
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200"
                    :disabled="form.processing"
                >
                    Save Album
                </button>
            </div>
        </form>
    </div>
</template>
