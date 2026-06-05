<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Services\ShippingService;

class ShippingApiController extends Controller {
    private $shippingService;

    public function __construct() {
        $this->shippingService = new ShippingService();
    }

    public function provinces() {
        $provinces = $this->shippingService->getProvinces();
        return $this->json($provinces);
    }

    public function cities(Request $request) {
        $provinceId = $request->input('province');
        $cities = $this->shippingService->getCities($provinceId);
        return $this->json($cities);
    }

    public function calculate(Request $request) {
        $data = $request->getBody();
        $destination = $data['destination'] ?? null;
        $weight = $data['weight'] ?? 1000;
        
        $couriers = ['jne', 'pos', 'tiki'];
        $allCosts = [];
        
        foreach ($couriers as $courier) {
            $costs = $this->shippingService->calculateCost(
                $data['origin'] ?? ($_ENV['RAJAONGKIR_ORIGIN_CITY'] ?? 152),
                $destination,
                $weight,
                $courier
            );
            
            if (!empty($costs)) {
                $allCosts[] = [
                    'courier' => strtoupper($courier),
                    'costs' => $costs
                ];
            }
        }
        
        return $this->json($allCosts);
    }
}
