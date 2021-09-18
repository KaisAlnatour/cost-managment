<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;


class UserController extends BaseController
{

    /**
     * @OA\Get(
     *      path="/api/all-user",
     *      operationId="3-getUserList",
     *      tags={"Users"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of users",
     *      description="Returns list of users",
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
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getAllUser()
    {
        $products = User::all();

        return $this->sendResponse(UserResource::collection($products), 'user retrieved successfully.');
    }


    /**
     * @OA\Post(
     ** path="/api/register",
     *   tags={"Users"},
     *   summary="Register for new user ",
     *   operationId="1-register",
     *
     *
     *      @OA\RequestBody(
     *    required=false,
     *    description="Pass Login Requests data",
     *    @OA\JsonContent(
     *      @OA\property(property="name", type="string", example="kais"),
     *       @OA\Property(property="email", type="string", example="kais4@gmail.com"),
     *       @OA\Property(property="password", type="string", example=123456789),
     *       @OA\Property(property="c_password", type="string", example=123456789),
     *       @OA\Property(property="year", type="number", example=3),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="image", type="string", example="jrygfuyg43"),
     *       @OA\Property(property="specialization", type="string", example="Web Developer"),
     *      @OA\Property(property="university", type="string", example="DamasFactory"),
     *
     *    ),
     * ),
     *
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

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $messages = [
            'unique' => 'The :attribute field already taken.',
        ];
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|unique:users|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
//        return $this->sendResponse(new UserResource($user), 'User register successfully.');
        $success['token'] = $user->createToken('PTC_API_BACKEND')->accessToken;
        if ($success['token'] != null) {
            return $this->sendResponse(new UserResource($user), 'User register successfully.');
        } else {
            return $this->sendError('error.', [], 403);
        }
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="2-authLogin",
     * tags={"Users"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="kais4@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456789"),
     *    ),
     * ),
     *  @OA\Response(
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
     * )
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if ($request->email) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('COST_API_BACKEND')->accessToken;
                $success['user'] = new UserResource($user);
                return $this->sendResponse($success, 'User login successfully.');

            } else {
                return $this->sendError('Email or Password uncorrect.', [], 500);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'user_name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
                $user = Auth::user();

                $success['token'] = $user->createToken('PTC_API_BACKEND')->accessToken;
                $success['user'] = $user;
                return $this->sendResponse($success, 'User login successfully.');

            } else {
                return $this->sendError('User Name or Password uncorrect.', [], 500);
            }
        }
    }

}
