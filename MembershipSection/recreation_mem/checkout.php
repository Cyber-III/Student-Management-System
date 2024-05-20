<?php
require __DIR__ . '/vendor/autoload.php';

$stripe_secret_key = 'sk_test_51PIMdPDwJDfpiSSr04muva7l4XmHisSOvB1AKimDn25sT7tkMB5TRWvAt7we5h3xMMpL6zjAAas2J7ktFAoERJ4600kydtwfzm';
\Stripe\Stripe::setApiKey($stripe_secret_key);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];

    // Start a new Stripe Checkout session
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Yearly Play Area Subscription',
                ],
                'unit_amount' => 1000,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/MembershipSection/recreation_mem/request_rec.php?username=' . urlencode($username) . '&student_name=' . urlencode($student_name) . '&email=' . urlencode($email),
        'cancel_url' => 'http://localhost:3000/MembershipSection/recreation_mem/recreation_mem.php',
    ]);

    header('HTTP/1.1 303 See Other');
    header('Location: ' . $checkout_session->url);
    exit();
} else {
    echo 'Invalid request method.';
}
?>
