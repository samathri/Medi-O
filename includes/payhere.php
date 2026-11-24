<?php
/**
 * PayHere (Sri Lanka) sandbox integration — form-post checkout + IPN.
 * Docs: https://support.payhere.lk/api-&-mobile-sdk/checkout-api
 * Without merchant credentials the app falls back to a built-in demo
 * gateway (payment-demo.php) so checkout is always testable for free.
 */

function payhere_configured(): bool
{
    return PAYHERE_MERCHANT_ID !== '' && PAYHERE_MERCHANT_SECRET !== '';
}

/** Can customers choose "Pay online"? Real PayHere keys, or the pretend gateway when explicitly allowed for testing. */
function online_payment_available(): bool
{
    return payhere_configured() || ALLOW_DEMO_PAYMENTS === true;
}

function payhere_checkout_url(): string
{
    return PAYHERE_SANDBOX
        ? 'https://sandbox.payhere.lk/pay/checkout'
        : 'https://www.payhere.lk/pay/checkout';
}

/** Signature sent with the checkout form. */
function payhere_hash(string $orderNo, float $amount, string $currency = 'LKR'): string
{
    return strtoupper(md5(
        PAYHERE_MERCHANT_ID
        . $orderNo
        . number_format($amount, 2, '.', '')
        . $currency
        . strtoupper(md5(PAYHERE_MERCHANT_SECRET))
    ));
}

/** Verify the md5sig of an IPN notification. */
function payhere_verify_ipn(array $post): bool
{
    $local = strtoupper(md5(
        ($post['merchant_id'] ?? '')
        . ($post['order_id'] ?? '')
        . ($post['payhere_amount'] ?? '')
        . ($post['payhere_currency'] ?? '')
        . ($post['status_code'] ?? '')
        . strtoupper(md5(PAYHERE_MERCHANT_SECRET))
    ));
    return hash_equals($local, strtoupper($post['md5sig'] ?? ''));
}
