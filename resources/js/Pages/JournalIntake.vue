<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

// Add this - keep a reference to the editor
const quillEditorRef = ref(null);

const form = useForm({
    title: "",
    subtitle: "",
    text: "",
    cover_image: "",
    html_content: "",
});

const onEditorChange = (content) => {
    form.text = content;
    
    if (quillEditorRef.value && quillEditorRef.value.getHTML) {
        form.html_content = quillEditorRef.value.getHTML();
        console.log('HTML content:', form.html_content);
    }
    
};

const submit = () => {
    
    form.text = form.html_content

    form.post(route("journal.store"), {
        onSuccess: () => {
            alert("Journal Entry created successfully!");
            form.reset();
        },
    });
};
</script>

<template>
    <div class="max-w-2xl mx-auto bg-white p-6 shadow-lg rounded-lg mt-10">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Create New Journal Entry</h2>

        <form @submit.prevent="" class="space-y-4">
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

            <div>
                <label for="subtitle" class="block text-gray-700 font-medium">Subtitle</label>
                <input
                    v-model="form.subtitle"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    required
                />
            </div>

            <!-- Journal Entry Field -->
            <div class="form-group">
                <label for="text" class="block text-gray-700 font-medium">Journal Entry</label>
                <QuillEditor
                    ref="quillEditorRef"
                    :content="form.text"
                    @update:content="onEditorChange"
                    theme="snow"
                    :toolbar="[
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        [{ font: [] }],
                        [{ size: ['small', false, 'large', 'huge'] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ color: [] }, { background: [] }],
                        [{ script: 'sub' }, { script: 'super' }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        [{ indent: '-1' }, { indent: '+1' }],
                        [{ align: [] }],
                        ['blockquote', 'code-block'],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]"
                    class="h-64"
                />
            </div>
            <div>
                <label for="cover" class="block text-gray-700 font-medium">Cover Image</label>
                <input
                    v-model="form.cover_image"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                    required
                />
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    @click="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200"
                    :disabled="form.processing"
                >
                    Save Journal Entry
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.ql-container {
    height: 200px;
}
</style>
