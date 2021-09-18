<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpansiveCost as ExpansiveResource;
use App\ItemCost;
use App\ExpansiveCost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Translation\DataCollectorTranslator;

class ExpansiveCostController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/ExpansiveCost/addExpansiveCost",
     *   tags={"ExpansiveCosts"},
     *   summary="add for new ExpansiveCost ",
     *   operationId="1-addExpansiveCost",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="itemCode", type="string", example="10k1"),
     *       @OA\Property(property="name", type="string", example="A water bill"),
     *       @OA\Property(property="paidValue", type="number", example=20200),
     *       @OA\Property(property="date", type="date", example="2020/01/20"),
     *       @OA\Property(property="state", type="string", example=1),
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
    public function addExpansiveCost(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'itemCode' => 'required',
                'name' => 'required',
                'paidValue' => 'required',
                'state' => 'required',
                'date' => 'dateFormat:'. config('app.dateFormat')
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $itemCode = ItemCost::where('code',$request->itemCode)->first();
        if (is_null($itemCode)) {
            return $this->sendError('itemCode Not Found ');
        } else {
            $expansiveCost = new ExpansiveCost;
            $expansiveCost->itemCode = $request->itemCode;
            $expansiveCost->name = $request->name;
            $expansiveCost->paidValue = $request->paidValue;
            $expansiveCost->state = $request->state;
            $expansiveCost->crater = request()->user()->id;

            if(isset($request->date)){
                $expansiveCost->date = $request->date;
                $date = Carbon::createFromFormat(config('app.dateFormat') , $request->date)->format('Y-m-d');
                $expansiveCost->monthDate = Carbon::parse($date)->month;
                $expansiveCost->yearDate = Carbon::parse($date)->year;
            }else{
                $date = Carbon::now();
                $expansiveCost->date = $date;
                $expansiveCost->monthDate = Carbon::parse($date)->month;
                $expansiveCost->yearDate = Carbon::parse($date)->year;
            }
            $expansiveCost->save();

            return $this->sendResponse(new ExpansiveResource($expansiveCost), 'Add ItemCost successfully.');
        }
    }

    /**
     * @OA\put(
     ** path="/api/ExpansiveCost/editExpansiveCost",
     *   tags={"ExpansiveCosts"},
     *   summary="edit for new ExpansiveCost ",
     *   operationId="2-editExpansiveCost",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="itemCode", type="string", example="10k1"),
     *       @OA\Property(property="name", type="string", example="A water bill"),
     *       @OA\Property(property="paidValue", type="number", example=20200),
     *       @OA\Property(property="date", type="date", example="2020/01/20"),
     *       @OA\Property(property="state", type="string", example=1),
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
    public function editExpansiveCost(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required',
                'itemCode' => 'required',
                'name' => 'required',
                'paidValue' => 'required',
                'state' => 'required',
                'date' => 'dateFormat:'.config('app.dateFormat')
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

//        $expansive = ExpansiveCost::where('name', $request->name)->first();
        $expansive = ExpansiveCost::find($request->id);
        if (is_null($expansive)) {
            return $this->sendError('Not Found ExpansiveCost.');
        } else {
            $expansive->itemCode = $request->itemCode;
            $expansive->name = $request->name;
            $expansive->paidValue = $request->paidValue;
            $expansive->state = $request->state;

            if(isset($request->date)){
                $expansive->date = $request->date;
                $date = Carbon::createFromFormat(config('app.dateFormat') , $request->date)->format('Y-m-d');
                $expansive->monthDate = Carbon::parse($date)->month;
                $expansive->yearDate = Carbon::parse($date)->year;
            }else{
                $date = Carbon::now();
                $expansive->date = $date;
                $expansive->monthDate = Carbon::parse($date)->month;
                $expansive->yearDate = Carbon::parse($date)->year;
            }

            $expansive->save();
            return $this->sendResponse(new ExpansiveResource($expansive), 'Edit ExpansiveCost successfully.');
        }
    }

    /**
     * @OA\delete(
     ** path="/api/ExpansiveCost/deleteExpansiveCost/{id}",
     *   tags={"ExpansiveCosts"},
     *   summary="delet from ExpansiveCosts by id",
     *   operationId="3-deleteExpansiveCostQuestion",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
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
    public function deleteExpansiveCost($id)
    {
        $expansive = ExpansiveCost::find($id);
        if (is_null($expansive)) {
            return $this->sendError('Not Found ExpansiveCost.');
        }
        $expansive->delete();
        return $this->sendSuccess('delete ExpansiveCost successfully.');

    }


    /**
     * @OA\Get(
     ** path="/api/ExpansiveCost/showExpansiveCostById/{id}",
     *   tags={"ExpansiveCosts"},
     *   summary="show from ExpansiveCost By ID",
     *   operationId="4-showExpansiveCostById",
     *   description="Returns info spcific ExpansiveCost",
     *   security={{ "bearer_token":{} }},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
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
    public function showExpansiveCostById($id)
    {
        $expansive = ExpansiveCost::find($id);
        if (is_null($expansive))
            return $this->sendError('Not Found ExpansiveCost');
        return $this->sendResponse($expansive, 'Found it');
    }

    /**
     * @OA\Get(
     *      path="/api/ExpansiveCost/showItemsByExpansiveCode/{code}",
     *      operationId="5-showItemsByExpansiveCode",
     *      tags={"ExpansiveCosts"},
     *      security={{ "bearer_token":{} }},
     *      summary="show list of ItemCost",
     *      description="Returns list of ItemCost",
     *
     *     @OA\Parameter(
     *          name="code",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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

    public function showExpansiveCostByItemCostCode($code)
    {
        $expansives = ExpansiveCost::where('itemCode', $code)->get();
        if (is_null($expansives)) {
            return $this->sendError('Not Found ExpansiveCost');
        }
        return $this->sendResponse(ExpansiveResource::collection($expansives), 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/ExpansiveCost/showAllExpansiveCost",
     *      operationId="6-showAllExpansiveCost",
     *      tags={"ExpansiveCosts"},
     *      security={{ "bearer_token":{} }},
     *      summary="show list of ExpansiveCost",
     *      description="Returns list of all ExpansiveCost",
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
    public function showAllExpansiveCost()
    {
        $expansive = ExpansiveCost::all();

        return $this->sendResponse(ExpansiveResource::collection($expansive), 'ExpansiveCost retrieved successfully.');
    }


    /**
     * @OA\put(
     *      path="/api/ExpansiveCost/changeState/{id}/{state}",
     *      operationId="7-changeStateExpansiveCost",
     *      tags={"ExpansiveCosts"},
     *      security={{ "bearer_token":{} }},
     *      summary="changeState from ExpansiveCost",
     *      description="change state  ExpansiveCost to any state (1-paid or 2-unpaid)",
     *
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="state",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
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
    public function changeState($id, $state)
    {
        $expansive = ExpansiveCost::find($id);
        if (is_null($expansive)) {
            return $this->sendError('Not Found ExpansiveCost.');
        } else {
            $expansive->state = $state;
            $expansive->save();

            return $this->sendResponse(new ExpansiveResource($expansive), 'Edit ExpansiveCost successfully.');
        }
    }

    /**
     * @OA\post(
     *      path="/api/ExpansiveCost/endPointMonth",
     *      operationId="8-GetSumExpansiveCostOfMonth",
     *      tags={"ExpansiveCosts"},
     *      security={{ "bearer_token":{} }},
     *      summary="Get Sum Expansive Cost Of Month",
     *      description=" ",
     *
     *     @OA\Parameter(
     *      name="monthDate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
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

    public function endPointMonth(Request $request)
    {
        $monthDate = $request->monthDate;

        $date = Carbon::now();
        $yearDate = Carbon::parse($date)->year;

        if(is_null($monthDate)){
            $monthDate = Carbon::parse($date)->month;
        }

        $expansive = ExpansiveCost::where('monthDate' , $monthDate)->where('yearDate' , $yearDate)->get();

        if (is_null($expansive)) {
            return $this->sendError('Not Found ExpansiveCost');
        } else {

            $totalCosts = $expansive->sum('paidValue');

        }
        return $this->sendResponse($totalCosts, 'Found successfully.');
    }

    /**
     * @OA\post(
     *      path="/api/ExpansiveCost/endPointYear",
     *      operationId="9-GetSumExpansiveCostOfYear",
     *      tags={"ExpansiveCosts"},
     *      security={{ "bearer_token":{} }},
     *      summary="Get Sum Expansive Cost Of Year",
     *      description=" ",
     *
     *     @OA\Parameter(
     *      name="yearDate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
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

    public function endPointYear(Request $request)
    {
        $yearDate = $request->yearDate;
        if(is_null($yearDate)){
            $date = Carbon::now();
            $yearDate = Carbon::parse($date)->year;
        }

        $expansive = ExpansiveCost::where('yearDate' , $yearDate)->get();


        if (is_null($expansive)) {
            return $this->sendError('Not Found ExpansiveCost.');
        } else {

            $totalCosts = $expansive->sum('paidValue');

        }

        return $this->sendResponse($totalCosts, 'Found successfully.');
    }


    /**
     * @OA\post(
     *      path="/api/ExpansiveCost/endPointAllMonthInYear",
     *      operationId="10-GetSumExpansiveCostAllMonthInYear",
     *      tags={"ExpansiveCosts"},
     *      security={{ "bearer_token":{} }},
     *      summary="Get Sum Expansive Cost All Month In Year",
     *      description=" ",
     *
     *     @OA\Parameter(
     *      name="yearDate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
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

    public function endPointAllMonthInYear(Request $request)
    {
        $yearDate = $request->yearDate;

        if(is_null($yearDate)){
            $date = Carbon::now();
            $yearDate = Carbon::parse($date)->year;
        }

        $allMonth = ExpansiveCost::where('yearDate', $yearDate)
            ->orderBy('monthDate')
            ->get()
            ->groupBy('monthDate');

        $costAllMonth = [];
        foreach ($allMonth as $key => $value) {
            $costAllMonth[$key] = $allMonth->get($key)->sum('paidValue');
        }

        return $this->sendResponse($costAllMonth, 'Found successfully.');

    }

}
