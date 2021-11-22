<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function responseJson($message, $statusCode, $data, $isSuccess = true)
    {
        if ($isSuccess)
            return response()->json([
                "message" => $message,
                "success" => true,
                "code" => $statusCode,
                "data" => $data
            ], $statusCode);

        return response()->json([
            "message" => $message,
            "success" => false,
            "code" => $statusCode
        ], $statusCode);
    }

    public function successResponse($message, $data)
    {
        return $this->responseJson($message, 200, $data);
    }

    public function errorResponse($message)
    {
        return $this->responseJson($message, 400, null, false);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $attr = $request->validate([
            'email' => 'required|string|email|unique:users,email',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $attr['firstname'] . $attr['lastname'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email'],
        ]);
        if ($user) {

            $data = ([
                'proceed' => 0,
                'message' => 'Account creation Successfull'
            ]);

            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("Request Failed");
        }
    }

    public function signin(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        $data = ['email' => $request->email, 'password' => $request->password];

        if (!Auth::attempt($data)) {

            return $this->errorResponse("Credentials not match");
        }

        $user =  User::where('email', '=', $request->email)->first();
        $token = $user->createToken('tokens')->plainTextToken;

        $data = ([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'updated_at' => $user->updated_at,
            'created_at' => $user->created_at,
            'access_token' => $token,

        ]);

        return $this->successResponse("success", $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
