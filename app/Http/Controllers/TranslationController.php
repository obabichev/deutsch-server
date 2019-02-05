<?php

namespace App\Http\Controllers;


use App\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/translation/search",
     *      operationId="searchTranslation",
     *      tags={"Translation"},
     *      summary="Search translation by wordId and value",
     *      description="Search translation by wordId and value",
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="val",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="word_id",
     *                     type="integer"
     *                 ),
     *                 example={"word_id": 403102, "val": ""}
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
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
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
