<template>
    <div class="w-full relative border-stone-400 border-y min-h-screen">
        <div class="flex-col items-center max-w-screen-lg m-auto border-l border-stone-400 h-full">
            <div class="max-w-[50rem] py-40 pl-20 h-full">
            	<div class="flex flex-col h-full justify-start p-8 w-full">
            		<h2 class="mb-8 mt-4 text-7xl text-stone-500">THE OVERLAP</h2>
            		<p>Create a new user or login below to enjoy the overlap. </p>
	                <div class="login-form">
				        <div class="mt-10">
				            <input 
				                id="email" 
				                type="email" 
				                class="border-stone-400 border-b"
				                v-model="user.email" 
				                :class="{ 'error': v$.user.email.$error }"
				                @input="clearServerError('email')"
				                @keyup.enter="onSubmit"
				                required
				                placeholder="email" 
				                autofocus>
				        </div>
				        <div class="mt-10">
				            <input 
				                id="password" 
				                class="border-stone-400 border-b"
				                :type="isVisible ? 'text' : 'password'" 
				                v-model="user.password"
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
			            <div v-if="Object.keys(errors).length > 0">
						    <div v-for="(errorMessages, fieldName) in errors" :key="fieldName">
						        <p class="text-red-500 text-xl" v-for="(error, index) in errorMessages" :key="index">{{ error }}</p>
						    </div>
						</div>
				        <div class="field mt-2">
				            
			                <template v-if="true">
			                    <button 
			                    	@click="forgotPassword"
			                        class="underline border-none underline text-xl mb-8"
			                        :class="{ inprogress: disabled}">
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
				        <div class="field">
				        	<h2 @click="onSubmit" :disabled="isDisabled"  class="mb-8 mt-4 text-6xl cursor-pointer">Enter the story</h2>
				        </div>
				    </div>
				    <!-- <div class="pt-8 mt-4">
				    	<a href="/auth/google">
				    		<button class="border border-black w-full flex items-center justify-center p-6 rounded-2xl mb-8">
			            		Continue with Google
			            	</button>
				    	</a>
		            </div> -->
	            </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email, minLength } from '@vuelidate/validators';

export default {
    setup (props, context) {

        const form = reactive({
            user: {
                email: '',
                password: '',
            },
            isVisible:false,
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
                // window.location.href = '/';
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
            if (v$.value.user.email.$invalid) return 


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

        const closeWindow = () => {
	        context.emit('close', false);
	    };

        const clearServerError = (fieldName) => {
            if (form.errors[fieldName]) {
                form.errors[fieldName] = null;
            }
        };

        return { ...toRefs(form), v$, onSubmit, forgotPassword, closeWindow, clearServerError };
    }
}
</script>