<?php
require __DIR__ . '/../vendor/autoload.php';

$stripe_secret_key = 'sk_test_51PIMdPDwJDfpiSSr04muva7l4XmHisSOvB1AKimDn25sT7tkMB5TRWvAt7we5h3xMMpL6zjAAas2J7ktFAoERJ4600kydtwfzm';
\Stripe\Stripe::setApiKey($stripe_secret_key);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $amount = $_POST['amount'];
    $record_id = $_POST['record_id'];

    // Start a new Stripe Checkout session
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'lkr',
                'product_data' => [
                    'name' => 'Monthly Course Fee',
                ],
                'unit_amount' => $amount * 100, // Convert amount to cents (or smallest currency unit)
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/PaymentSection/make_payment/make_payment.php?success=1&username=' . urlencode($username) . '&record_id=' . urlencode($record_id) . '&amount=' . urlencode($amount),
        'cancel_url' => 'http://localhost:3000/PaymentSection/make_payment/make_payment.php',
    ]);

    header('HTTP/1.1 303 See Other');
    header('Location: ' . $checkout_session->url);
    exit();
} else {
    echo 'Invalid request method.';
}
?>
