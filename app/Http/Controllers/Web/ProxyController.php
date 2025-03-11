<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function proxy(Request $request)
    {
        $apiUrl = 'https://web.sprintpay.online/api/resolve-bank';
        $queryParams = $request->query();
        $client = new Client();

        try {
            // Make a request to the external API
            $response = $client->get($apiUrl, [
                'query' => $queryParams,
            ]);

            // Return the API response
            return response($response->getBody(), $response->getStatusCode())
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
