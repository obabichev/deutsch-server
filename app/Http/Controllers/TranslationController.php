<?php

namespace App\Http\Controllers;


use App\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function search(Request $request)
    {
        $query = Translation::query();

        if ($request->has('word_id')) {
            $query->where('word_id', $request->input('word_id'));
        }

        if ($request->has('val')) {
            $val = $request->input('val') . '%';
            $query->where('val', 'ilike', $val);
        }

        return $query->limit(15)
            ->orderByRaw('CHAR_LENGTH(val)')
            ->get();
    }
}
