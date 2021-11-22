<?php

namespace App\Http\Controllers;

use App\Models\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
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
        $customers = DB::table('patients')->get();

        return $this->successResponse("success", $customers);
    }
    public function add_vital(Request  $request)
    {
        $attr = $request->validate([
            'visit_date' => 'required|string',
            'height' => 'required|string',
            'weight' => 'required|string',           
            'bmi' => 'required|string',   
            'patient_id' => 'required|string',   
        ]);
        $bmi=$attr['bmi'];
        $vital = Vital::create([
            'visit_date' => $attr['visit_date'],
            'height' => $attr['height'],
            'weight' => $attr['weight'],
            'bmi' => $bmi,
            'patient_id' => $attr['patient_id'],
        ]);


        if($vital){
            if($bmi<=25){
                $data = ([
                    'slug' => 1,
                    'message' => "Vital Added Successfully",
                ]);
            }else{
                $data = ([
                    'slug' => 0,
                    'message' => "Vital Added Successfully",
                ]);
            }
           
            return $this->successResponse("success", $data);
        }else{
            return $this->errorResponse("Request Failed");
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
