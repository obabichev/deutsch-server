<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;


class WordController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/words",
     *      operationId="getRandomWord",
     *      tags={"Words"},
     *      summary="Get random word",
     *      description="Returns random word",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *   security={{"api_key":{}}}
     *     )
     *
     * Returns list of projects
     */
    public function index()
    {
        return Word::find(1);
    }

    public function show(Word $word)
    {
        return $word;
    }

    public function store(Request $request)
    {
        $word = Word::create($request->all());

        return response()->json($word, 201);
    }

    public function search(Request $request)
    {
        $query = Word::query();

        if ($request->has('val')) {
            $val = $request->input('val') . '%';
            $query->where('val', 'ilike', $val);
        }

        return $query->limit(15)
            ->orderByRaw('CHAR_LENGTH(val)')
            ->get();
    }

    public function update(Request $request, Word $word)
    {
        $word->update($request->all());

        return response()->json($word, 200);
    }

    public function delete(Word $word)
    {
        $word->delete();

        return response()->json(null, 204);
    }
}
