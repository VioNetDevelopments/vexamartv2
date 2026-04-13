<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->message;
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            \Log::error('Gemini API Key missing in .env');
            return response()->json(['reply' => 'Waduh, kuncinya nggak ada nih. Hubungi admin ya! 😊']);
        }

        $systemPrompt = "Kamu adalah Vexa Assistant, asisten belanja online yang ramah dan cerdas. 
        Toko ini bernama: " . (Setting::get('store_name') ?? 'VexaMart') . ".
        Layanan: Info produk, bantuan checkout, rekomendasi belanja.
        Gaya: Ramah, pake emoji 😊, asik kayak temen.";

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;
            
            $response = Http::timeout(15)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nUser Question: " . $userMessage]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                    return response()->json(['reply' => $reply]);
                }
                
                \Log::error('Gemini Format Error: ' . json_encode($data));
                return response()->json(['reply' => 'Aku lagi mikir keras, tanya lagi ya! 😊']);
            }

            \Log::error('Gemini API Fail: ' . $response->body());
            return response()->json(['reply' => 'Maaf ya, server ku lagi istirahat. Coba lagi nanti! 😄']);

        } catch (\Exception $e) {
            \Log::error('Chat Exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Ada gangguan sinyal nih, tapi aku tetap di sini! 😊']);
        }
    }
}