<template>
    <div>
        <button @click="handleBitcoinPayment" class="mt-4" type="button">Pay with Bitcoin</button>

        <div v-if="bitcoinAddress">
            <p>Send Bitcoin to the following address:</p>
            <p class="text-xl font-bold">{{ bitcoinAddress }}</p>
            <p>Amount: {{ bitcoinAmount }} BTC</p>
            <div v-html="qrCodeSvg"></div>
            <div v-if="countdown > 0">
                <p>Your transaction amount will refresh in {{ countdown }} seconds.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import qrcode from 'qrcode-generator';

const bitcoinAddress = ref('');
const bitcoinAmount = ref(0);
const countdown = ref(100); // Set countdown to 5 seconds for testing purposes
const countdownInterval = ref(null);
const errors = ref({});
const pendingOrder = ref(null); // Variable to store the pending order

const qrCodeSvg = computed(() => {
    if (bitcoinAddress.value && bitcoinAmount.value > 0) {
        const bitcoinURI = `bitcoin:${bitcoinAddress.value}?amount=${bitcoinAmount.value}`;
        const qr = qrcode(0, 'L');
        qr.addData(bitcoinURI);
        qr.make();
        return qr.createSvgTag({ cellSize: 4, margin: 4 });
    }
    return '';
});

const handleBitcoinPayment = async () => {
    try {
        if (pendingOrder.value) {
            // Use the existing pending order details
            bitcoinAddress.value = pendingOrder.value.bitcoinTransaction.bitcoin_address;
            bitcoinAmount.value = pendingOrder.value.bitcoinTransaction.amount;
        } else {
            // Generate new Bitcoin address and create a new order
            const response = await axios.get('/bitcoin/generate-address');
            bitcoinAddress.value = response.data.bitcoin_address;
            await updateBitcoinAmount();
            await saveBitcoinTransaction();
        }

        // Start countdown timer
        startCountdown();
    } catch (err) {
        console.log(err);
        errors.value = { general: ["An error occurred while generating the Bitcoin address. Please try again."] };
    }
};

const saveBitcoinTransaction = async () => {
    try {
        const response = await axios.post('/api/create-bitcoin-order', {
            bitcoin_address: bitcoinAddress.value,
            amount: bitcoinAmount.value,
            description: 'Bitcoin payment for puzzle access'
        });
        console.log('Bitcoin order created:', response);
    } catch (err) {
        console.error('Error creating Bitcoin order:', err);
        errors.value = { general: ["An error occurred while creating the order. Please try again."] };
    }
};

const startCountdown = () => {
    clearCountdown(); // Clear any existing countdowns
    countdown.value = 100; // Reset countdown to 5 seconds

    countdownInterval.value = setInterval(async () => {
        countdown.value -= 1;

        if (countdown.value <= 0) {
            clearCountdown();
            await updateBitcoinAmount(); // Optionally refresh the Bitcoin amount here if needed
            startCountdown(); // Restart the countdown
        }
    }, 1000); // Decrease countdown every second
};

const clearCountdown = () => {
    if (countdownInterval.value) {
        clearInterval(countdownInterval.value);
        countdownInterval.value = null;
    }
};

const updateBitcoinAmount = async () => {
    try {
        const response = await axios.get('/api/bitcoin-price');
        if (response.status !== 200 || !response.data.bitcoin || !response.data.bitcoin.usd) {
            throw new Error(`Unexpected API response: ${response.status}`);
        }

        const rate = response.data.bitcoin.usd;

        // Validate the rate to ensure it's a positive number and not zero
        if (rate > 0) {
            bitcoinAmount.value = (20 / rate).toFixed(8); // Calculate $20 in BTC
        } else {
            throw new Error("Invalid rate received from the API.");
        }
    } catch (err) {
        console.error(err.message);
        errors.value = { general: ["An error occurred while fetching the Bitcoin rate. Please try again later."] };
    }
};

onMounted(async () => {
	updateBitcoinAmount();

    try {
        const response = await axios.get('/api/get-pending-order');
        if (response.status === 200 && response.data) {
            bitcoinAddress.value = response.data.order.bitcoin_transaction.bitcoin_address;
            startCountdown();
        }
    } catch (err) {
        console.error('Error fetching existing order:', err);
    }
});

onUnmounted(() => {
    clearCountdown(); // Clear countdown when the component is unmounted
});
</script>
