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
            ->get();
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

        return response()->json($glossary, 201);
    }

    public function update(Request $request, Glossary $glossary)
    {
        $glossary->update($request->all());

        return response()->json($glossary, 200);
    }

    public function delete(Glossary $glossary)
    {
        $glossary->delete();

        return response()->json(null, 204);
    }
}