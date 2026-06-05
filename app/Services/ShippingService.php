<?php

namespace App\Services;

class ShippingService
{
    private $apiKey;
    private $baseUrl;
    private static $apiFailed = false; // Circuit breaker for timeouts

    public function __construct()
    {
        $this->apiKey = $_ENV['RAJAONGKIR_API_KEY'] ?? '';
        $this->baseUrl = $_ENV['RAJAONGKIR_BASE_URL'] ?? 'https://api.rajaongkir.com/starter';
    }

    public function getCost($origin, $destination, $weight, $courier)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . "/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$origin&destination=$destination&weight=$weight&courier=$courier",
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded",
                "key: " . $this->apiKey
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["error" => $err];
        } else {
            return json_decode($response, true);
        }
    }

    public function getProvinces()
    {
        $mockProvinces = [
            ['province_id' => '1', 'province' => 'Bali'],
            ['province_id' => '2', 'province' => 'Bangka Belitung'],
            ['province_id' => '3', 'province' => 'Banten'],
            ['province_id' => '4', 'province' => 'Bengkulu'],
            ['province_id' => '5', 'province' => 'DI Yogyakarta'],
            ['province_id' => '6', 'province' => 'DKI Jakarta'],
            ['province_id' => '7', 'province' => 'Gorontalo'],
            ['province_id' => '8', 'province' => 'Jambi'],
            ['province_id' => '9', 'province' => 'Jawa Barat'],
            ['province_id' => '10', 'province' => 'Jawa Tengah'],
            ['province_id' => '11', 'province' => 'Jawa Timur'],
            ['province_id' => '12', 'province' => 'Kalimantan Barat'],
            ['province_id' => '13', 'province' => 'Kalimantan Selatan'],
            ['province_id' => '14', 'province' => 'Kalimantan Tengah'],
            ['province_id' => '15', 'province' => 'Kalimantan Timur'],
            ['province_id' => '16', 'province' => 'Kalimantan Utara'],
            ['province_id' => '17', 'province' => 'Kepulauan Riau'],
            ['province_id' => '18', 'province' => 'Lampung'],
            ['province_id' => '19', 'province' => 'Maluku'],
            ['province_id' => '20', 'province' => 'Maluku Utara'],
            ['province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)'],
            ['province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)'],
            ['province_id' => '23', 'province' => 'Nusa Tenggara Timur (NTT)'],
            ['province_id' => '24', 'province' => 'Papua'],
            ['province_id' => '25', 'province' => 'Papua Barat'],
            ['province_id' => '26', 'province' => 'Riau'],
            ['province_id' => '27', 'province' => 'Sulawesi Barat'],
            ['province_id' => '28', 'province' => 'Sulawesi Selatan'],
            ['province_id' => '29', 'province' => 'Sulawesi Tengah'],
            ['province_id' => '30', 'province' => 'Sulawesi Tenggara'],
            ['province_id' => '31', 'province' => 'Sulawesi Utara'],
            ['province_id' => '32', 'province' => 'Sumatera Barat'],
            ['province_id' => '33', 'province' => 'Sumatera Selatan'],
            ['province_id' => '34', 'province' => 'Sumatera Utara']
        ];

        if (empty($this->apiKey))
            return $mockProvinces;

        $response = null;
        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->baseUrl . "/province",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ["key: " . $this->apiKey],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 5,
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
        } else {
            $opts = [
                "http" => [
                    "method" => "GET",
                    "header" => "key: " . $this->apiKey . "\r\n",
                    "ignore_errors" => true,
                    "timeout" => 5
                ],
                "ssl" => ["verify_peer" => false, "verify_peer_name" => false]
            ];
            $context = stream_context_create($opts);
            $response = @file_get_contents($this->baseUrl . "/province", false, $context);
        }

        $data = json_decode($response, true);
        $results = $data['rajaongkir']['results'] ?? [];

        return !empty($results) ? $results : $mockProvinces;
    }

    public function getCities($provinceId)
    {
        // Try to load from local JSON first (reliable)
        $localFile = __DIR__ . '/../Data/cities.json';
        if (file_exists($localFile)) {
            $allCities = json_decode(file_get_contents($localFile), true);
            $filtered = array_filter($allCities, function ($c) use ($provinceId) {
                return (string) $c['province_id'] === (string) $provinceId;
            });
            if (!empty($filtered))
                return array_values($filtered);
        }

        // Fallback to manual mock if file missing
        $mockCities = [
            '1' => [['city_id' => '114', 'city_name' => 'Denpasar', 'type' => 'Kota'], ['city_id' => '17', 'city_name' => 'Badung', 'type' => 'Kabupaten']],
            '6' => [['city_id' => '152', 'city_name' => 'Jakarta Pusat', 'type' => 'Kota']],
            '9' => [['city_id' => '22', 'city_name' => 'Bandung', 'type' => 'Kota']]
        ];

        return $mockCities[$provinceId] ?? [
            ['city_id' => '152', 'city_name' => 'Jakarta Pusat', 'type' => 'Kota'],
            ['city_id' => '444', 'city_name' => 'Surabaya', 'type' => 'Kota']
        ];
    }

    public function calculateCost($origin, $destination, $weight, $courier)
    {
        $origin = $origin ?: ($_ENV['RAJAONGKIR_ORIGIN_CITY'] ?? 152);

        // If previous call in this request failed/timed out, don't wait again
        if (self::$apiFailed || empty($this->apiKey) || empty($destination)) {
            return $this->getMockCosts($courier, $weight, $destination, $origin);
        }

        $response = null;
        try {
            if (function_exists('curl_init')) {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $this->baseUrl . "/cost",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => "origin=$origin&destination=$destination&weight=$weight&courier=$courier",
                    CURLOPT_HTTPHEADER => ["key: " . $this->apiKey, "content-type: application/x-www-form-urlencoded"],
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_TIMEOUT => 2, // Reduced from 5s
                ]);
                $response = curl_exec($curl);
                curl_close($curl);
            } else {
                $opts = [
                    "http" => [
                        "method" => "POST",
                        "header" => "key: " . $this->apiKey . "\r\nContent-Type: application/x-www-form-urlencoded\r\n",
                        "content" => "origin=$origin&destination=$destination&weight=$weight&courier=$courier",
                        "ignore_errors" => true,
                        "timeout" => 2 // Reduced from 5s
                    ],
                    "ssl" => ["verify_peer" => false, "verify_peer_name" => false]
                ];
                $context = stream_context_create($opts);
                $response = @file_get_contents($this->baseUrl . "/cost", false, $context);
            }
        } catch (\Exception $e) {
            $response = null;
        }

        $data = json_decode($response, true);
        $costs = $data['rajaongkir']['results'][0]['costs'] ?? [];

        if (empty($costs)) {
            self::$apiFailed = true; // Mark as failed to skip future API waits in this request
            return $this->getMockCosts($courier, $weight, $destination, $origin);
        }

        return $costs;
    }

    /**
     * Get island group for a province_id.
     * Returns: 'jawa', 'sumatera', 'kalimantan', 'sulawesi', 'bali_nusra', 'maluku', 'papua'
     */
    private function getIslandGroup(int $provinceId): string
    {
        $map = [
            // Sumatera
            2 => 'sumatera', // Bangka Belitung
            4 => 'sumatera', // Bengkulu
            8 => 'sumatera', // Jambi
            17 => 'sumatera', // Kepulauan Riau
            18 => 'sumatera', // Lampung
            21 => 'sumatera', // NAD / Aceh
            26 => 'sumatera', // Riau
            32 => 'sumatera', // Sumatera Barat
            33 => 'sumatera', // Sumatera Selatan
            34 => 'sumatera', // Sumatera Utara
            // Jawa
            3 => 'jawa', // Banten
            5 => 'jawa', // DI Yogyakarta
            6 => 'jawa', // DKI Jakarta
            9 => 'jawa', // Jawa Barat
            10 => 'jawa', // Jawa Tengah
            11 => 'jawa', // Jawa Timur
            // Kalimantan
            12 => 'kalimantan', // Kalimantan Barat
            13 => 'kalimantan', // Kalimantan Selatan
            14 => 'kalimantan', // Kalimantan Tengah
            15 => 'kalimantan', // Kalimantan Timur
            16 => 'kalimantan', // Kalimantan Utara
            // Sulawesi
            7 => 'sulawesi', // Gorontalo
            27 => 'sulawesi', // Sulawesi Barat
            28 => 'sulawesi', // Sulawesi Selatan
            29 => 'sulawesi', // Sulawesi Tengah
            30 => 'sulawesi', // Sulawesi Tenggara
            31 => 'sulawesi', // Sulawesi Utara
            // Bali & Nusa Tenggara
            1 => 'bali_nusra', // Bali
            22 => 'bali_nusra', // NTB
            23 => 'bali_nusra', // NTT
            // Maluku
            19 => 'maluku', // Maluku
            20 => 'maluku', // Maluku Utara
            // Papua
            24 => 'papua', // Papua
            25 => 'papua', // Papua Barat
        ];
        return $map[$provinceId] ?? 'jawa';
    }

    /**
     * Get province_id for a given city_id from local JSON.
     */
    private function getProvinceForCity(int $cityId): int
    {
        $localFile = __DIR__ . '/../Data/cities.json';
        if (!file_exists($localFile))
            return 6; // default Jakarta
        static $allCities = null;
        if ($allCities === null) {
            $allCities = json_decode(file_get_contents($localFile), true) ?? [];
        }
        foreach ($allCities as $city) {
            if ((int) $city['city_id'] === $cityId) {
                return (int) $city['province_id'];
            }
        }
        return 6; // default DKI Jakarta
    }

    /**
     * Cross-island base price table (per 1 kg, in Rupiah).
     * Matrix is symmetric. Based on approximate real-world JNE REG rates.
     * Keys: jawa, sumatera, kalimantan, sulawesi, bali_nusra, maluku, papua
     */
    private function getCrossIslandRate(string $from, string $to): array
    {
        // [base_price, etd_days]
        $matrix = [
            // From JAWA
            'jawa' => [
                'jawa' => [11000, '1-2'],   // Same island
                'sumatera' => [16000, '2-4'],   // Relatively close, via darat/ferry
                'kalimantan' => [22000, '3-5'],
                'sulawesi' => [27000, '4-6'],
                'bali_nusra' => [17000, '2-4'],
                'maluku' => [37000, '5-8'],
                'papua' => [52000, '7-12'],  // Furthest from Jawa
            ],
            // From SUMATERA
            'sumatera' => [
                'jawa' => [16000, '2-4'],
                'sumatera' => [12000, '1-3'],   // Same island
                'kalimantan' => [25000, '3-6'],
                'sulawesi' => [32000, '5-7'],
                'bali_nusra' => [23000, '3-5'],
                'maluku' => [45000, '6-10'],
                'papua' => [68000, '9-14'],  // Very far: ~4500km
            ],
            // From KALIMANTAN
            'kalimantan' => [
                'jawa' => [22000, '3-5'],
                'sumatera' => [25000, '3-6'],
                'kalimantan' => [15000, '1-3'],   // Same island
                'sulawesi' => [21000, '3-5'],
                'bali_nusra' => [24000, '3-5'],
                'maluku' => [34000, '5-8'],
                'papua' => [46000, '7-11'],
            ],
            // From SULAWESI (Makassar dll)
            'sulawesi' => [
                'jawa' => [27000, '4-6'],
                'sumatera' => [32000, '5-7'],   // Jauh dari Sulawesi
                'kalimantan' => [21000, '3-5'],
                'sulawesi' => [11000, '1-3'],   // Same island
                'bali_nusra' => [21000, '3-5'],
                'maluku' => [26000, '4-6'],
                'papua' => [37000, '5-9'],   // ~1500km, jauh lebih dekat dari Sumatera
            ],
            // From BALI / NUSA TENGGARA
            'bali_nusra' => [
                'jawa' => [17000, '2-4'],
                'sumatera' => [23000, '3-5'],
                'kalimantan' => [24000, '3-5'],
                'sulawesi' => [21000, '3-5'],
                'bali_nusra' => [12000, '1-3'],   // Same island
                'maluku' => [32000, '5-8'],
                'papua' => [43000, '7-11'],
            ],
            // From MALUKU
            'maluku' => [
                'jawa' => [37000, '5-8'],
                'sumatera' => [45000, '6-10'],
                'kalimantan' => [34000, '5-8'],
                'sulawesi' => [26000, '4-6'],
                'bali_nusra' => [32000, '5-8'],
                'maluku' => [15000, '1-3'],   // Same island
                'papua' => [24000, '3-6'],   // Tetangga Papua
            ],
            // From PAPUA
            'papua' => [
                'jawa' => [52000, '7-12'],
                'sumatera' => [68000, '9-14'],  // Sangat jauh ~4500km
                'kalimantan' => [46000, '7-11'],
                'sulawesi' => [37000, '5-9'],   // ~1500km, wajar lebih murah dari Sumatera
                'bali_nusra' => [43000, '7-11'],
                'maluku' => [24000, '3-6'],   // Tetangga terdekat Papua
                'papua' => [17000, '2-4'],   // Same island
            ],
        ];

        return $matrix[$from][$to] ?? [25000, '4-7'];
    }

    private function getMockCosts($courier, $weight, $destination, $origin = 152)
    {
        // Resolve city IDs → province IDs → island groups
        $originProvince = $this->getProvinceForCity((int) $origin);
        $destProvince = $this->getProvinceForCity((int) $destination);
        $originIsland = $this->getIslandGroup($originProvince);
        $destIsland = $this->getIslandGroup($destProvince);

        [$baseRate, $etd] = $this->getCrossIslandRate($originIsland, $destIsland);

        $weightFactor = max(1, ceil($weight / 1000));

        // JNE REG
        $regPrice = $baseRate * $weightFactor;
        // JNE OKE (economy: ~20% cheaper, +1-2 days)
        $okePrice = (int) ($regPrice * 0.8);
        // POS Kilat Khusus (~10% cheaper)
        $posPrice = (int) ($regPrice * 0.9);
        // TIKI REG (~10% more expensive)
        $tikiPrice = (int) ($regPrice * 1.1);

        // ETD: parse and add extra days for economy
        [$etdMin, $etdMax] = explode('-', $etd);
        $etdOke = ($etdMin + 1) . '-' . ($etdMax + 2);
        $etdPos = $etdMin . '-' . ($etdMax + 1);

        $mocks = [
            'jne' => [
                ['service' => 'REG', 'description' => 'Layanan Reguler', 'cost' => [['value' => $regPrice, 'etd' => $etd, 'note' => '']]],
                ['service' => 'OKE', 'description' => 'Ongkos Kirim Ekonomis', 'cost' => [['value' => $okePrice, 'etd' => $etdOke, 'note' => '']]],
            ],
            'pos' => [
                ['service' => 'Kilat Khusus', 'description' => 'Pos Kilat Khusus', 'cost' => [['value' => $posPrice, 'etd' => $etdPos, 'note' => '']]],
            ],
            'tiki' => [
                ['service' => 'REG', 'description' => 'Regular Service', 'cost' => [['value' => $tikiPrice, 'etd' => $etd, 'note' => '']]],
            ],
        ];

        return $mocks[strtolower($courier)] ?? [];
    }
}
