<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function chat($context, $question)
    {
        $apiKey = env('GEMINI_API_KEY');

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}",
            [
                "contents" => [
                    [
                        "parts" => [
                            [
                                "text" =>
                                    "You are a helpful assistant. Use ONLY the given context.\n\n" .
                                    "Context:\n{$context}\n\n" .
                                    "Question:\n{$question}\n\n" .
                                    "Answer clearly:"
                            ]
                        ]
                    ]
                ]
            ]
        );

        if (!$response->successful()) {
            return "Gemini API Error: " . $response->body();
        }

        return $response->json()['candidates'][0]['content']['parts'][0]['text']
            ?? 'No response from Gemini';
    }
}