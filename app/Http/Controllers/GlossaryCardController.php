<?php

namespace App\Http\Controllers;

use App\Glossary;
use App\GlossaryCard;
use App\Translation;
use App\Word;
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

    /**
     * @OA\Post(
     *      path="/api/glossarycard",
     *      operationId="glossarycardPost",
     *      tags={"Glossary card"},
     *      summary="Create glossary card",
     *      description="Create glossary card",
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="word",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="translation",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="glossary",
     *                     type="integer"
     *                 ),
     *                 example={
     *                      "word": 100,
     *                      "translation": 100,
     *                      "glossary": 4
     *                  }
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *     @OA\Response(response=404, description="Resource not found"),
     *   security={{"api_key":{}}}
     *     )
     */
    public function store(Request $request)
    {
        $glossaryCard = new GlossaryCard();
        $data = $request->all();

        Word::findOrFail($data['word']);
        Translation::findOrFail($data['translation']);
        Glossary::findOrFail($data['glossary']);

        $glossaryCard->word()->associate($data['word']);
        $glossaryCard->translation()->associate($data['translation']);
        $glossaryCard->glossary()->associate($data['glossary']);

        $glossaryCard->save();
        $glossaryCard->load(['word', 'translation']);

        return response()->json($glossaryCard, 201);
    }

    /**
     * @OA\Delete(
     *      path="/api/glossarycard/{glossarycardId}",
     *      operationId="delGlossaryCard",
     *      tags={"Glossary card"},
     *      summary="Delete glossary card",
     *      description="Delete glossary card",
     *      @OA\Parameter(
     *         name="glossarycardId",
     *         in="path",
     *         description="ID of glossary card",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Response(
     *          response=204,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   @OA\Response(response=404, description="Resource not found"),
     *   security={{"api_key":{}}}
     *     )
     */
    public function delete($id)
    {
        $glossaryCard = GlossaryCard::findOrFail($id);
        $glossaryCard->delete();

        return response()->json(null, 204);
    }
}