<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class PaymentService {
    public function __construct() {
        Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
        Config::$isProduction = $_ENV['MIDTRANS_IS_PRODUCTION'] === 'true';
        Config::$isSanitized = $_ENV['MIDTRANS_IS_SANITIZED'] === 'true';
        Config::$is3ds = $_ENV['MIDTRANS_IS_3DS'] === 'true';
    }

    public function createTransaction($orderId, $amount, $customerDetails) {
        if (empty(Config::$serverKey)) {
            throw new \Exception("Midtrans Server Key is not configured in .env file.");
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => $customerDetails,
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            // Rethrow or handle so the controller can catch it
            throw new \Exception("Midtrans Error: " . $e->getMessage());
        }
    }

    public function verifyWebhook($payload) {
        $serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
        $signatureKey = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);
        
        return $signatureKey === $payload['signature_key'];
    }
}
