<template>
    <div class="container">
        <h2>Purchase Page</h2>
        <form @submit.prevent="submitPayment" id="payment-form">
            <div id="card-element">
              <!-- Elements will create input elements here -->
            </div>
            
            <button id="submit">Submit Payment</button>
        </form>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { loadStripe } from '@stripe/stripe-js';

// Replace 'your_stripe_public_key' with your actual Stripe public key
const stripePromise = loadStripe('pk_test_51P1yu2F36K5xfyWFtv94srspMsWi9QXJjbRkoJ7JBpDnxpV22qYK7Bs32TqDxYiLXQRl4WlMRjKQQjSyMevjRWLs00RHdx78kF');

let card;

onMounted(async () => {
    const stripe = await stripePromise;
    const elements = stripe.elements();
    card = elements.create('card');
    card.mount('#card-element');
});

const submitPayment = async () => {
    const stripe = await stripePromise;
    const result = await stripe.createToken(card);
    if (result.error) {
        console.error(result.error.message);
    } else {
        stripeTokenHandler(result.token);
    }
};

const stripeTokenHandler = async (token) => {
    try {
        // Define the payload to send to your server
        const payload = {
            paymentMethodId: token.id,
            // Include any other data your server might need
        };

        // Make a POST request to your Laravel backend
        const response = await axios.post('/purchase', payload);

        // Handle success
        console.log('Payment successful:', response.data);
        alert('Payment successful!'); // Replace this with your success handling logic
    } catch (error) {
        // Handle errors
        console.error('Payment failed:', error.response ? error.response.data : error.message);
        alert('Payment failed. Please try again.'); // Replace this with your error handling logic
    }
};
</script>
