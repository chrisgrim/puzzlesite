<!DOCTYPE html>
<html>
<head>
    <title>Purchase Confirmation</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Order ID: {{ $order->id }}</p>
    <p>Amount: ${{ number_format($order->amount / 100, 2) }}</p>
    <p>Description: {{ $order->description }}</p>
    <p>We appreciate your business!</p>
</body>
</html>
