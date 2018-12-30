<?php

namespace App\Http\Controllers;

use App\WordProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordProgressController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $filter = $request->query->get('filter');

        $query = WordProgress::where('user_id', $user->id);

        if ($filter === 'repeat') {
            $query->where('repeat', '<', new \DateTime());
        }

        $progresses = $query->limit(100)
            ->get();

        $progresses->load(['word', 'translation']);
        return $progresses;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $wordId = $data['word_id'];
        $translation_id = $data['translation_id'];

        $wordProgress = WordProgress::where('user_id', $user->id)
            ->where('word_id', $wordId)
            ->where('translation_id', $translation_id)
            ->first();

        if (empty($wordProgress)) {
            $wordProgress = new WordProgress();
            $wordProgress->user()->associate($user);
            $wordProgress->word()->associate($wordId);
            $wordProgress->translation()->associate($translation_id);
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
            $wordProgress->setRepeat($this->calculateRepeatDate($wordProgress));
        } else {
            $wordProgress->mistakes = $wordProgress->mistakes + 1;
        }

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