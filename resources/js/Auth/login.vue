<template>
    <div class="w-full relative border-y min-h-screen">
        <div class="flex items-center justify-center m-auto h-screen relative">
            <div class="max-w-screen-lg flex flex-col h-full justify-center w-1/2 z-10 rounded">
            	<div class="max-w-[50rem] border-l-[2rem] border-black h-full">
            		<div class="flex flex-col h-full justify-center">
            			<div class="ml-10">
	            			<h2 class="mb-8 mt-4 text-9xl leading-[6rem]">THE OVERLAP</h2>
		                	<p>Login or Create a new user to enjoy the overlap.</p>
	            		</div>
		                <div class="login-form">
		                    <div class="mt-10 border-b border-black">
		                        <input
		                            id="email"
		                            type="email"
		                            class="w-full ml-6"
		                            v-model="form.user.email"
		                            :class="{ 'error': v$.user.email.$error }"
		                            @input="clearServerError('email')"
		                            @keyup.enter="onSubmit"
		                            required
		                            placeholder="email"
		                            autofocus>
		                    </div>
		                    <div class="mt-10 border-b border-black">
		                        <input
		                            id="password"
		                            class="w-full ml-6"
		                            :type="form.isVisible ? 'text' : 'password'"
		                            v-model="form.user.password"
		                            :class="{'error': v$.user.password.$error || !v$.user.email.serverFailed }"
		                            @input="clearServerError('password')"
		                            @keyup.enter="onSubmit"
		                            required
		                            placeholder="password">
		                    </div>
		                    <p v-if="v$.user.email.$dirty && v$.user.email.required.$invalid" class="text-red-500 text-lg">The email is required</p>
		                    <p v-if="v$.user.email.$dirty && v$.user.email.email.$invalid" class="text-red-500 text-lg">Must be an email</p>
		                    <p v-if="v$.user.password.$dirty && v$.user.password.required.$invalid" class="text-red-500 text-lg">The password is required.</p>
		                    <p v-if="v$.user.password.$dirty && v$.user.password.minLength.$invalid" class="text-red-500 text-lg">The password must be at least 8 characters long.</p>
		                    <div v-if="Object.keys(form.errors).length > 0">
		                        <div v-for="(errorMessages, fieldName) in form.errors" :key="fieldName">
		                            <p class="text-red-500 text-xl" v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
		                        </div>
		                    </div>
		                    <div @click="onSubmit" :disabled="form.isDisabled" class="bg-black p-8 flex justify-center">
		                        <h2 class="text-4xl text-white cursor-pointer">Enter The Story</h2>
		                    </div>
		                    <div class="field mt-2">
		                        <template v-if="true">
		                            <button
		                                @click="forgotPassword"
		                                class="underline border-none underline text-xl mb-8"
		                                :class="{ inprogress: form.disabled }">
		                                Forgot your password?
		                            </button>
		                        </template>
		                        <template v-else>
		                            <div class="">
		                                <button
		                                    class=""
		                                    @click="forget=false">
		                                    <svg class="w-8 h-8 fill-white hover:fill-black">
		                                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
		                                    </svg>
		                                </button>
		                                <h3 class="text-white">We have emailed you a reset password link.</h3>
		                                <p class="text-white">Please check your email.</p>
		                            </div>
		                        </template>
		                    </div>
		                </div>
            		</div>
            	</div>
            </div>
            <div class="w-full h-screen absolute inset-0 overflow-hidden">
            	<div class="w-screen h-screen">
            		<SVG class="translate-x-[35%] opacity-20" />
            	</div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { reactive, toRefs, getCurrentInstance } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email, minLength } from '@vuelidate/validators';
import SVG from '@/Global/SVG/spinning-circles.vue';

const form = reactive({
    user: {
        email: '',
        password: '',
    },
    isVisible: false,
    isDisabled: false,
    disabled: false,
    errors: [],
});

const rules = {
    user: {
        email: {
            required,
            email,
        },
        password: {
            required,
            minLength: minLength(8),
        },
    },
};

const v$ = useVuelidate(rules, form);

const onSubmit = async () => {
    form.errors = {};
    const isFormValid = await v$.value.$validate();
    if (!isFormValid) return;

    try {
        const res = await axios.post(`/authenticate`, form.user);
        console.log(res);
        window.location.href = '/';
    } catch (err) {
        console.log(err);
        if (err.response.data && typeof err.response.data === 'object') {
            form.errors = err.response.data.errors;
        } else {
            form.errors = { general: ['An unexpected error occurred.'] };
        }
    }
};

const forgotPassword = async () => {
    form.errors = {};

    await v$.value.user.email.$validate();
    if (v$.value.user.email.$invalid) return;

    try {
        const res = await axios.post('/forgot-password', { email: form.user.email });
        alert("Reset link sent! Check your email.");
    } catch (err) {
        if (err.response.data && typeof err.response.data === 'object') {
            form.errors = err.response.data.errors;
        } else {
            form.errors = { general: ['An unexpected error occurred.'] };
        }
    }
};

const { emit } = getCurrentInstance();

const closeWindow = () => {
    emit('close', false);
};

const clearServerError = (fieldName) => {
    if (form.errors[fieldName]) {
        form.errors[fieldName] = null;
    }
};
</script>
