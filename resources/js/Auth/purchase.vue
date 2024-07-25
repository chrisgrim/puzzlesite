<template>
    <div class="w-full flex p-8 max-w-screen-lg relative m-auto">
        <div class="w-5/6 mt-40">
            <div class="w-60 border-b bg-black h-1"></div>
            <div class="my-10 mb-40">
                <h2 class="text-8xl">Before We Begin</h2>
            </div>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum. {{randomPart}}</p>
                <br>
            </div>
            <div class="my-10">
                <input @input="clear" class="p-4 text-black" type="text" v-model="couponCode" placeholder="Discount Code" />
                <p class="text-md">A little hint. Discount codes are unique to every single player. You don't need to look any further than this page to learn it. </p>
            </div>

            <form>
                <div id="card-element" class="p-3 bg-white rounded-md shadow border border-gray-300">
                    <!-- Stripe Elements will create input elements here -->
                </div>
                <div class="mb-4">
                    <div v-if="stripeError.length > 0">
                        <p class="text-red-500 text-xl">{{ stripeError }}</p>
                    </div>
                    <div v-if="Object.keys(errors).length > 0">
                        <div v-for="(errorMessage, fieldName) in errors" :key="fieldName">
                            <p class="text-red-500 text-xl" v-for="(error, index) in errorMessage" :key="index">{{ error }}</p>
                        </div>
                    </div>
                </div>
                <button @click.prevent="handlePayment" class="mt-4" type="submit">Submit Payment</button>
            </form>

        </div>
        <div class="w-1/6 border-l border-black">
            <Sidebar></Sidebar>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import Sidebar from './purchase-side.vue';

const props = defineProps({
    user: {
        type: [Object, null],
        default: () => null,
    },
});

const stripePromise = loadStripe('pk_test_51P1yu2F36K5xfyWFtv94srspMsWi9QXJjbRkoJ7JBpDnxpV22qYK7Bs32TqDxYiLXQRl4WlMRjKQQjSyMevjRWLs00RHdx78kF');
const couponCode = ref('');

const errors = ref({});
const stripeError = ref({});
const randomPart = ref('');
const generateDiscountCode = (email) => {
    const firstTwoLetters = email.substring(0, 2);  // Extracts the first two letters of the email
    const randomPart = Math.floor(10 + Math.random() * 90);  // Generates a two-digit random number
    const fullCode = `${firstTwoLetters}${randomPart}`;
    return { fullCode, randomPart};
};
const generatedCode = ref('');


let stripe, elements, card;

onMounted(async () => {
    stripe = await stripePromise;
    elements = stripe.elements();
    const style = {
        base: {
            color: '#333', // Use the same color scheme as your project
            fontFamily: 'Arial, sans-serif', // Tailwind doesn't apply to Stripe Elements by default
            fontSmoothing: 'antialiased',
            fontSize: '16px', // Match with your form's font size
            '::placeholder': {
                color: '#a0aec0', // Tailwind gray-400
            },
        },
        invalid: {
            color: '#e53e3e', // Tailwind red-600
            iconColor: '#e53e3e',
        }
    };
    card = elements.create('card');
    card.mount('#card-element');
    if (props.user && props.user.email) {
        const codeData = generateDiscountCode(props.user.email);
        generatedCode.value = codeData.fullCode;
        randomPart.value = codeData.randomPart;  // Save the random part
    }
});

const handlePayment = async () => {
    const { token, error } = await stripe.createToken(card);

    if (error) {
        stripeError.value = error.message;
    } else {
        processPayment(token.id);
    }
};

function clear() {
    errors.value = {}
}

const processPayment = async (token) => {

    errors.value = {}
    stripeError.value = {}

    let coupon = false;

    if (couponCode.value.trim() !== "") {
        if (couponCode.value === generatedCode.value) {
            coupon = true;  // Coupon code is valid, apply discount
        } else {
            errors.value = { coupon: ["Invalid discount code. Please check and try again."] };
            return; // Stop further execution if the code does not match
        }
    }


    try {
        const response = await axios.post('/process-payment', {
            token,
            coupon,
        });

        errors.value = '';
        window.location.href = '/';
    } catch (err) {
        console.log(err);
        // Here, we check if the error response exists and has the expected structure
        if (err.response && err.response.data && err.response.data.errors) {
            errors.value = err.response.data.errors;
        } else {
            // Fallback or generic error message if the response doesn't match expected structure
            errors.value = { general: ["An unexpected error occurred. Please try again."] };
        }
    }
};
</script>