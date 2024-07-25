<template>
	<div class="p-8">
		<div>
			<h3>The Story</h3>
			<p>$20</p>
		</div>
		<div class="border-t border-black">
			<div class="my-10">
                <input @input="clear" class="p-4 text-black" type="text" v-model="couponCode" placeholder="Discount Code" />
                <p class="text-md">A little hint. Discount codes are unique to every single player. You don't need to look any further than this page to learn it. </p>
            </div>
		</div>
	</div>
</template>