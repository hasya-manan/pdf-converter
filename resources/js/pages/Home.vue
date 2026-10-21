
<script setup>
import { ref, computed } from 'vue';

const fileInput = ref(null);
const file = ref(null);
const isDragging = ref(false);
const isUploading = ref(false);
const message = ref('');
const isError = ref(false);

// Dropdown logic
const formatKey = ref('pdf-to-word');
const formats = {
    'pdf-to-word': { from: 'PDF', to: 'W', label: 'PDF to Word' },
    'word-to-pdf': { from: 'W', to: 'PDF', label: 'Word to PDF' }
};
const selectedFormat = computed(() => formats[formatKey.value]);

// Trigger hidden input
const triggerFileInput = () => {
    fileInput.value.click();
};

// Handle File selection via input
const handleFileChange = (e) => {
    const selected = e.target.files[0];
    if (selected) processFile(selected);
};

// Handle Drag & Drop
const handleDrop = (e) => {
    isDragging.value = false;
    const droppedFile = e.dataTransfer.files[0];
    if (droppedFile) processFile(droppedFile);
};

// Validate and set file
const processFile = (selectedFile) => {
    message.value = '';
    const ext = selectedFile.name.split('.').pop().toLowerCase();
    
    // Simple validation
    if (formatKey.value === 'pdf-to-word' && ext !== 'pdf') {
        isError.value = true;
        message.value = 'Please upload a PDF file.';
        return;
    }
    if (formatKey.value === 'word-to-pdf' && ext !== 'docx' && ext !== 'doc') {
        isError.value = true;
        message.value = 'Please upload a Word file (.docx or .doc).';
        return;
    }
    
    file.value = selectedFile;
};

// Submit (We will connect this to Laravel next)
// Submit to Laravel Backend
const submitFile = async () => {
    if (!file.value) return;
    
    isUploading.value = true;
    message.value = '';
    
    const formData = new FormData();
    formData.append('file', file.value);
    formData.append('format', formatKey.value);

    try {
        // 1. Get the CSRF token from the meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // 2. Change URL to /convert and add the CSRF header
        const response = await fetch('/convert', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken 
            }
        });

        const data = await response.json();

        if (response.ok) {
            isError.value = false;
            message.value = data.message; 
        } else {
            isError.value = true;
            message.value = data.message || 'Validation error occurred.';
        }
    } catch (error) {
        isError.value = true;
        message.value = 'Failed to connect to the backend.';
        console.error(error);
    } finally {
        isUploading.value = false;
    }
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-white flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-lg w-full bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 border border-gray-100">
            
            <!-- Dropzone Area -->
            <div 
                class="relative border-2 border-dashed rounded-xl p-10 flex flex-col items-center justify-center text-center transition-colors cursor-pointer"
                :class="isDragging ? 'border-orange-400 bg-orange-50' : 'border-gray-300 hover:bg-gray-50'"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="handleDrop"
                @click="triggerFileInput"
            >
                <input 
                    type="file" 
                    ref="fileInput" 
                    class="hidden" 
                    accept=".pdf,.docx,.doc" 
                    @change="handleFileChange"
                >

                <!-- Icon (Document) -->
                <div class="mb-4 text-orange-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fed7aa"/>
                        <path d="M14 2v6h6" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <!-- Text -->
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    {{ file ? file.name : 'Drag & Drop files here' }}
                </h3>
                <p class="text-sm text-gray-500 mb-6">or</p>
                
                <!-- Button -->
                <button type="button" class="px-6 py-2.5 bg-orange-100 text-orange-700 font-medium rounded-lg hover:bg-orange-200 transition-colors">
                    Choose file
                </button>
            </div>

            <!-- Conversion Type Selector -->
            <div class="mt-6 border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-white cursor-pointer hover:border-gray-300 transition-colors relative">
                
                <div class="flex items-center space-x-3">
                    <!-- Source Format Icon -->
                    <div 
                        class="w-8 h-8 flex items-center justify-center rounded font-bold text-xs"
                        :class="{
                            'bg-red-100 text-red-600': selectedFormat.from === 'PDF',
                            'bg-blue-100 text-blue-600': selectedFormat.from === 'W'
                        }"
                    >
                        {{ selectedFormat.from }}
                    </div>
                    
                    <!-- Arrow -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>

                    <!-- Target Format Icon -->
                    <div 
                        class="w-8 h-8 flex items-center justify-center rounded font-bold text-xs"
                        :class="{
                            'bg-red-100 text-red-600': selectedFormat.to === 'PDF',
                            'bg-blue-100 text-blue-600': selectedFormat.to === 'W' || selectedFormat.to === 'Word' // just in case
                        }"
                    >
                        {{ selectedFormat.to }}
                    </div>

                    <span class="font-medium text-gray-700">
                        {{ selectedFormat.label }}
                    </span>
                </div>

                <!-- Dropdown Arrow -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>

                <!-- Hidden Select -->
                <select v-model="formatKey" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <option value="pdf-to-word">PDF to Word</option>
                    <option value="word-to-pdf">Word to PDF</option>
                </select>
            </div>

            <!-- Action Button -->
            <button 
                @click="submitFile"
                :disabled="!file || isUploading"
                class="mt-6 w-full py-3 px-4 rounded-xl text-white font-semibold text-lg transition-all
                       disabled:bg-gray-300 disabled:cursor-not-allowed disabled:shadow-none
                       bg-orange-500 hover:bg-orange-600 shadow-lg shadow-orange-500/30"
            >
                <span v-if="isUploading">Converting...</span>
                <span v-else>Convert Now</span>
            </button>

            <!-- Message -->
            <div v-if="message" class="mt-4 p-3 rounded-lg text-sm text-center" :class="isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'">
                {{ message }}
            </div>
        </div>
    </div>
</template>
