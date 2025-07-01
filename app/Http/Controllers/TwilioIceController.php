<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TwilioIceController extends Controller
{
    public function getIceServers(Request $request)
    {
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $url = "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Tokens.json";

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post($url);

        if ($response->failed()) {
            return response()->json(['error' => 'Twilio API error'], 500);
        }

        $data = $response->json();
        return response()->json($data['ice_servers'] ?? []);
    }
} 