<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ItemCost;
use App\Http\Resources\ItemCost as ExpansiveResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Example;

class ItemCostController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/ItemCost/addItemCost",
     *   tags={"ItemCosts"},
     *   summary="add for new ItemCost ",
     *   operationId="1-addItemCost",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="code", type="string", example="10k1"),
     *       @OA\Property(property="name", type="string", example="repairs")
     *    ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function addItemCost(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'code' => 'required|unique:item_costs',
                'name' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $Item = new ItemCost;
        $Item->code = $request->code;
        $Item->name = $request->name;
        $Item->save();

        return $this->sendResponse(new ExpansiveResource($Item), 'Add ItemCost successfully.');
    }

    /**
     * @OA\put(
     ** path="/api/ItemCost/editItemCost",
     *   tags={"ItemCosts"},
     *   summary="edit for new ItemCost ",
     *   operationId="2-editItemCost",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="code", type="string", example="10k1"),
     *       @OA\Property(property="name", type="string", example="")
     *    ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function editItemCost(Request $request)
    {
        $Item = ItemCost::where('code', $request->code)->first();
        if (is_null($Item)) {
            return $this->sendError('Not Found ItemCost.');
        } else {
            $Item->name = $request->name;
            $Item->save();
            return $this->sendResponse(new ExpansiveResource($Item), 'Edit ItemCost successfully.');
        }
    }

    /**
     * @OA\delete(
     ** path="/api/ItemCost/deleteItemCost/{code}",
     *   tags={"ItemCosts"},
     *   summary="delet from ItemCost by code",
     *   operationId="3-deleteItemCost",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function deleteItemCost($code)
    {
        $Item = ItemCost::where('code', $code)->first();
        if (is_null($Item)) {
            return $this->sendError('Not Found ItemCost.');
        } else {
            $Item->delete();
            return $this->sendSuccess('delete ItemCost successfully.');
        }
    }


    /**
     * @OA\Get(
     ** path="/api/ItemCost/showItemCostByCode/{code}",
     *   tags={"ItemCosts"},
     *   summary="show from ItemCost By code",
     *   operationId="4-showItemCostByCode",
     *   description="Returns info spcific ItemCost",
     *   security={{ "bearer_token":{} }},
     *
     *   @OA\Parameter(
     *      name="code",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *
     *)
     **/
    public function showItemCostByCode($code)
    {
        $Item = ItemCost::where('code', $code)->first();
        if (is_null($Item))
            return $this->sendError('Not Found ItemCost.');
        return $this->sendResponse($Item, 'Found it');
    }

    /**
     * @OA\Get(
     *      path="/api/ItemCost/showAllItemCost",
     *      operationId="5-showAllItemCost",
     *      tags={"ItemCosts"},
     *      security={{ "bearer_token":{} }},
     *      summary="show list of ItemCost",
     *      description="Returns list of all ItemCost",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *      ),
     *       @OA\Response(
     *      response=404,
     *      description="not found"
     *      ),
     *  )
     */
    public function showAllItemCost()
    {
        $products = ItemCost::all();

        return $this->sendResponse(ExpansiveResource::collection($products), 'ItemCost retrieved successfully.');
    }


}
