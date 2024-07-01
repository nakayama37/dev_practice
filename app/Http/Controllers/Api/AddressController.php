<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    /**
     * 住所取得
     * 
     * @return $fullAddress
     */
    public function getAddress($postcode)
    {
        $response = Http::get('http://zipcloud.ibsnet.co.jp/api/search', [
            'zipcode' => $postcode
        ]);

        if ($response->successful() && $response['results']) {
            $result = $response['results'][0];
            $prefecture = $result['address1'];
            $city = $result['address2'];
            $street = $result['address3'];
          
            return response()->json([
                'status' => 200,
                'results' => [
                    'prefecture' => $prefecture,
                    'city' => $city,
                    'street' => $street
                ]
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Address not found'
            ]);
        }
    }
}
