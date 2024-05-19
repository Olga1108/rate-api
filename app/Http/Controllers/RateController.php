<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RateController extends Controller
{
    public function index()
    {
        $req_url = 'https://v6.exchangerate-api.com/v6/cfd1e3042fba93ce4da3bc25/latest/USD';
        $response_json = file_get_contents($req_url);
        if ($response_json) {
            try {
                $response = json_decode($response_json);
                if ('success' === $response->result) {
                     $UAH_price = $response->conversion_rates->UAH;
                    //  dd( $UAH_price);
                     return response()->json($UAH_price, 200);
                } else {
                    return 'error';
                }
            } catch (\Exception $e) {
                return $e;
            }
        }
        return response()->json(['error' => 'Unable to fetch rate'], 400);
    }
}


