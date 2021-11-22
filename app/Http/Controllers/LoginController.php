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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'unique' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'dob' => 'required|string|max:255',
            'gender' => 'required|string|max:255', 
            'password' => 'required|string|min:6',
        ]);
       
        if (Patient::where('unique', '=', $attr['unique'])->exists()) {
            $data = ([
                'proceed' => 1,
                'message' => 'A User with the same unique already exist'
            ]);
            return $this->successResponse("success", $data);
        }
        if (Patient::where('email', '=', $attr['email'])->exists()) {
            $data = ([
                'proceed' => 1,
                'message' => 'A User with the same email address already exist'
            ]);
            return $this->successResponse("success", $data);
        }

        $patient = Patient::create([
            'firstname' => $attr['firstname'],
            'lastname' => $attr['lastname'],
            'unique' => $attr['unique'], 
            'dob' => $attr['dob'], 
            'gender' => $attr['gender'], 
            'email' => $attr['email'],
        ]);
        if ($patient) {
            $user = User::create([
                'name' => $attr['firstname'] . $attr['lastname'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email'],
            ]);
        }
        $data = ([
            'proceed' => 0,
            'message' => 'Account creation Successfull'
        ]);

        return $this->successResponse("success", $data);
    }

    public function signin(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if (Patient::where('email', $attr['email'])->exists()) {
            $patient = Patient::where('email', $attr['email'])->first();
            $data = ['email' => $patient->email, 'password' => $request->password];

            if (!Auth::attempt($data)) {

                return $this->errorResponse("Credentials not match");
            }

            $user =  User::where('email', '=', $patient->email)->first();
            $token = $user->createToken('tokens')->plainTextToken;

            $data = ([
                'id' => $patient->id,
                'unique' => $patient->unique,
                'firstname' => $patient->firstname,
                'lastname' => $patient->lastname,
                'email' => $patient->email,  
                'dob'=>$patient->dob,
                'gender'=>$patient->gender,
                'updated_at' => $patient->updated_at,
                'created_at' => $patient->created_at,
                'access_token' => $token,

            ]);

            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("Account Not Found");
        }
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
