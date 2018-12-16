<?php

namespace App\Http\Controllers;

use App\Glossary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GlossaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Glossary::where('owner_id', $user->id)
            ->limit(100)
            ->with(['cards.word', 'cards.translation'])
            ->get();
    }

    public function show(Glossary $glossary)
    {
        $glossary->load(['cards.word', 'cards.translation']);
        return $glossary;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $glossary = new Glossary([
            'title' => $data['title']
        ]);
        $glossary->owner()->associate($user);
        $glossary->save();
        $glossary->load(['cards.word', 'cards.translation']);

        return response()->json($glossary, 201);
    }

    public function update(Request $request, Glossary $glossary)
    {
        $glossary->update($request->all());
        $glossary->load(['cards.word', 'cards.translation']);

        return response()->json($glossary, 200);
    }

    public function delete($id)
    {
        Glossary::find($id)->delete();

        return response()->json(null, 204);
    }
}