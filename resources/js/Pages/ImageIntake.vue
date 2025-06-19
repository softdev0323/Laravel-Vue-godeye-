<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    album_id: "",
    caption: "",
    image_url: "",
    print: false,
});

const submit = () => {
    form.post(route("image.store"), {
        onSuccess: () => {
            alert("Image created successfully!");
            form.reset();
        },
    });
};
</script>

<template>
    <div class="max-w-2xl mx-auto bg-white p-6 shadow-lg rounded-lg mt-10">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Create New Image</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Title Field -->
            <div>
                <label for="album_id" class="block text-gray-700 font-medium">Album ID</label>
                <input
                    v-model="form.album_id"
                    type="number"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    required
                />
            </div>

            <!-- Description Field -->
            <div>
                <label for="caption" class="block text-gray-700 font-medium">Caption</label>
                <textarea
                    v-model="form.caption"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    rows="3"
                    required
                ></textarea>
            </div>

            <!-- Year Field -->
            <div>
                <label for="image_url" class="block text-gray-700 font-medium">Image URL</label>
                <input
                    v-model="form.image_url"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    required
                />
            </div>

            <!-- Completed Checkbox -->
            <div class="flex items-center">
                <input
                    v-model="form.print"
                    type="checkbox"
                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <label for="print" class="ml-2 text-gray-700">Print?</label>
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200"
                    :disabled="form.processing"
                >
                    Save Image
                </button>
            </div>
        </form>
    </div>
</template>
