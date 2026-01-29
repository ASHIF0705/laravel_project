<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentence;
use App\Models\TypingResult;

class TypingController extends Controller
{
    public function startTest(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'time' => 'required|integer|min:1'
        ]);

        $sentence = Sentence::inRandomOrder()->first();

        return view('typing', [
            'sentence' => $sentence,
            'username' => $request->username,
            'time' => $request->time
        ]);
    }

    public function saveResult(Request $request)
    {
        $request->validate([
            'username'  => 'required|string|max:255',
            'typed_text' => 'required|string',
            'sentence'   => 'required|string',
            'time'       => 'required|integer|min:1',
            'wpm'        => 'required|integer|min:0',          
            'accuracy'   => 'required|integer|min:0|max:100',  
        ]);

        $wpm      = (int) $request->wpm;
        $accuracy = (int) $request->accuracy;

        TypingResult::create([
            'username'      => $request->username,
            'wpm'           => $wpm,
            'accuracy'      => $accuracy,
            'time_selected' => $request->time,
 
        ]);

        return redirect()->route('leaderboard');
    }

    public function leaderboard()
    {
        $leaders = TypingResult::orderBy('wpm', 'DESC')
                               ->limit(5)
                               ->get();

        return view('leaderboard', compact('leaders'));
    }

    public function getNewSentence()
    {
        $sentence = Sentence::inRandomOrder()->first();

        if ($sentence) {
            return response()->json([
                'sentence' => $sentence->sentence
            ]);
        }

        return response()->json([
            'sentence' => 'the quick brown fox jumps over the lazy dog'
        ], 404);
    }
}