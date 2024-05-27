<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-semibold mb-4">Your email address is not verified.</h1>
            <p class="mb-4">Please check your email for a verification link.</p>
            <p class="mb-4">If you didn't receive the email, click the button below to resend the verification email.</p>
            <button
                @click="resendVerificationEmail"
                :disabled="loading"
                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                Resend Verification Email
            </button>
            <p v-if="message" class="mt-4 text-green-500">{{ message }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const loading = ref(false);
const message = ref('');

const resendVerificationEmail = async () => {
    loading.value = true;
    message.value = '';
    try {
        const response = await axios.post('/email/verification-notification');
        message.value = response.data.message || 'Verification link sent!';
    } catch (error) {
        message.value = 'There was an error resending the email.';
    } finally {
        loading.value = false;
    }
};
</script>

