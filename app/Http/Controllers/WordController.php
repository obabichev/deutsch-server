<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;


class WordController extends Controller
{

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

    /**
     * @OA\Post(
     *      path="/api/words/search",
     *      operationId="searchWords",
     *      tags={"Words"},
     *      summary="Search words by value",
     *      description="Search words by value",
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="val",
     *                     type="string"
     *                 ),
     *                 example={"val": "test"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   security={{"api_key":{}}}
     *     )
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
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
