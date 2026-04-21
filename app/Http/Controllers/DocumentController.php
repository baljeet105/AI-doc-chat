<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Chunk;
use App\Services\GeminiService;
use Smalot\PdfParser\Parser;
use App\Helpers\TextHelper;

class DocumentController extends Controller
{
    public function upload(Request $request, GeminiService $ai)
    {
        $file = $request->file('file');
        $path = $file->store('docs');

        $fullPath = str_replace('\\', '/', storage_path('app/private/' . $path));

        $doc = Document::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path
        ]);


        // Extract text
        $parser = new Parser();
        
        $pdf = $parser->parseFile($fullPath);
        $text = $pdf->getText();

        // Chunk
        $chunks = TextHelper::chunk($text);

        foreach ($chunks as $chunk) {

            Chunk::create([
                'document_id' => $doc->id,
                'content' => $chunk
            ]);
        }

        return back()->with('success', 'Uploaded & processed!');
    }

    public function ask(Request $request, GeminiService $ai)
    {
        $question = $request->input('question');

        $chunks = Chunk::all()->take(5);

        $context = implode("\n---\n", $chunks->pluck('content')->toArray());

        $answer = $ai->chat($context, $question);

        return response()->json([
            'answer' => $answer
        ]);
    }

    private function cosine($a, $b)
    {
        $dot = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($a); $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
