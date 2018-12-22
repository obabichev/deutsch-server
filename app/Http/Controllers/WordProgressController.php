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

        $wordProgress = WordProgress::where('user_id', $user->id)
            ->where('word_id', $wordId)->first();

        if (empty($wordProgress)) {
            $wordProgress = new WordProgress();
            $wordProgress->user()->associate($user);
            $wordProgress->word()->associate($wordId);
        }

        if (isset($data['learned'])) {
            $wordProgress->learned = $data['learned'];
            if ($wordProgress->learned) {
                $date = new \DateTime();
                $date->modify('+5 minutes');
                $wordProgress->repeat = $date;
            }
        }


        $wordProgress->save();
        $wordProgress->refresh();

        return response()->json($wordProgress, 201);
    }

    public function update(Request $request, WordProgress $wordProgress)
    {
        $action = $request->query->get('action');
        if ($action === 'repeat') {
            $this->repeatAction($wordProgress, $request->all());
        }
        return response()->json($wordProgress, 200);
    }

    protected function repeatAction(WordProgress $wordProgress, array $data)
    {
        $success = $data['success'];
        if ($success) {
            $wordProgress->successes = $wordProgress->successes + 1;
        } else {
            $wordProgress->mistakes = $wordProgress->mistakes + 1;
        }

        $wordProgress->setRepeat($this->calculateRepeatDate($wordProgress));
        $wordProgress->save();
    }

    protected function calculateRepeatDate(WordProgress $wordProgress)
    {
        if (empty($wordProgress->repeat)) {
            return null;
        }

        $repeat = new \DateTime();
        $successes = $wordProgress->successes;


        if ($successes === 0) {
            $repeat->modify('+5 minutes');
            return $repeat;
        }

        if ($successes <= 3) {
            $repeat->modify("+$successes days");
            return $repeat;
        }

        if ($successes <= 7) {
            $weeks = $successes - 3;
            $repeat->modify("+$weeks weeks");
            return $repeat;
        }

        $months = $successes - 7;
        $repeat->modify("+$months months");
        return $repeat;
    }
}