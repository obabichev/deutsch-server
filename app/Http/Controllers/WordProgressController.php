<?php

namespace App\Http\Controllers;

use App\WordProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return WordProgress::where('user_id', $user->id)
            ->limit(100)
            ->get();
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $wordId = $data['word_id'];

        $existed = WordProgress::where('user_id', $user->id)
            ->where('word_id', $wordId)->first();

        if (!empty($existed)) {
            throw new \Exception('WordProgress instance already exists for this user and word');
        }

        $wordProgress = new WordProgress();
        $wordProgress->user()->associate($user);
        $wordProgress->word()->associate($wordId);


        if (isset($data['learned'])) {
            $wordProgress->learned = $data['learned'];
        }


        $wordProgress->save();
        $wordProgress->refresh();

        return response()->json($wordProgress, 201);
    }
}