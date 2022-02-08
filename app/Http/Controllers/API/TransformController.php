<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Pomelo PHP Laravel Challenge",
 *      @OA\Contact(
 *          name="Sébastien Alfaiate",
 *          email="s.alfaiate@webarea.fr"
 *      )
 * )
 */
class TransformController extends Controller
{
    /**
     * Transform input data.
     *
     * @OA\Post(
     *      path="/api/transform",
     *      tags={"Transform"},
     *      summary="Transform input data",
     *      description="Returns transformed data with items moved into parent `children` key.",
     *      @OA\RequestBody(
     *          description="Input data.",
     *          required=true,
     *          @OA\JsonContent(example="{""0"":[{""id"":10,""title"":""House"",""level"":0,""children"":[],""parent_id"":null}],""1"":[{""id"":12,""title"":""Red Roof"",""level"":1,""children"":[],""parent_id"":10},{""id"":18,""title"":""Blue Roof"",""level"":1,""children"":[],""parent_id"":10},{""id"":13,""title"":""Wall"",""level"":1,""children"":[],""parent_id"":10}],""2"":[{""id"":17,""title"":""Blue Window"",""level"":2,""children"":[],""parent_id"":12},{""id"":16,""title"":""Door"",""level"":2,""children"":[],""parent_id"":13},{""id"":15,""title"":""Red Window"",""level"":2,""children"":[],""parent_id"":12}]}")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(example="[{""id"":10,""title"":""House"",""level"":0,""children"":[{""id"":12,""title"":""Red Roof"",""level"":1,""children"":[{""id"":17,""title"":""Blue Window"",""level"":2,""children"":[],""parent_id"":12},{""id"":15,""title"":""Red Window"",""level"":2,""children"":[],""parent_id"":12}],""parent_id"":10},{""id"":18,""title"":""Blue Roof"",""level"":1,""children"":[],""parent_id"":10},{""id"":13,""title"":""Wall"",""level"":1,""children"":[{""id"":16,""title"":""Door"",""level"":2,""children"":[],""parent_id"":13}],""parent_id"":10}],""parent_id"":null}]")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"
     *       )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transform(Request $request)
    {
        $validator = Validator::make($request->all(), array_map(function () { return 'required|array'; }, $request->all()));

        if ($validator->fails()) {
            return response('Invalid input.', Response::HTTP_BAD_REQUEST);
        }

        $references = [];
        $tree = [];

        foreach ($validator->validated() as $items) {
            foreach ($items as $item) {
                $references[$item['id']] = $item;
                if (!isset($item['parent_id'])) {
                    $tree[] = &$references[$item['id']];
                } elseif (isset($references[$item['parent_id']]['children'])) {
                    $references[$item['parent_id']]['children'][] = &$references[$item['id']];
                } else {
                    return response("Parent item {$item['parent_id']} doesn't exist for item {$item['id']}.", Response::HTTP_BAD_REQUEST);
                }
            }
        }

        return response($tree);
    }
}
