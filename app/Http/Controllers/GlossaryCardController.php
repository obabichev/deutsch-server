<?php

namespace App\Http\Controllers;

use App\GlossaryCard;
use Illuminate\Http\Request;

class GlossaryCardController extends Controller
{
    public function index()
    {
        return GlossaryCard::query()
            ->limit(100)
            ->get();
    }

    public function show(GlossaryCard $glossaryCard)
    {
        return $glossaryCard;
    }

    public function store(Request $request)
    {
        $glossaryCard = new GlossaryCard();
        $data = $request->all();

        $glossaryCard->word()->associate($data['word']);
        $glossaryCard->translation()->associate($data['translation']);
        $glossaryCard->glossary()->associate($data['glossary']);

        $glossaryCard->save();
        $glossaryCard->load(['word', 'translation']);

        return response()->json($glossaryCard, 201);
    }

    public function delete($id)
    {
        GlossaryCard::find($id)->delete();

        return response()->json(null, 204);
    }
}