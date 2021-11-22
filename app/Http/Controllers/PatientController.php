<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
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
        $patient = DB::table('patients')->get();

        return $this->successResponse("success", $patient);
    }

public function test_sample()
{
    # code...
    $patient= Patient::where('gender', 'Male')->orderBy('created_at', 'desc')->get();
    return $this->successResponse("success", $patient);
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
        $bmi = $attr['bmi'];
        $vital = Vital::create([
            'visit_date' => $attr['visit_date'],
            'height' => $attr['height'],
            'weight' => $attr['weight'],
            'bmi' => $bmi,
            'patient_id' => $attr['patient_id'],
        ]);


        if ($vital) {
            if ($bmi <= 25) {
                $data = ([
                    'slug' => 1,
                    'message' => "Vital Added Successfully",
                ]);
            } else {
                $data = ([
                    'slug' => 0,
                    'message' => "Vital Added Successfully",
                ]);
            }

            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("Request Failed");
        }
    }

public function view_visits()
{
    $visits= Visit::where('on_diet', true)->orderBy('created_at', 'desc')->get();
    $name=$visits->patients->firstname; ##from patients table
    $age=""; ##from patients table
    $bmi="";  ## from vitals table
    return $this->successResponse("success", $name);
}

    public function add_visits(Request  $request)
    {
        $attr = $request->validate([
            'general_health' => 'required|string',
            'on_diet' => 'required|boolean',
            'on_drugs' => 'required|boolean',
            'comments' => 'required|string',
            'patient_id' => 'required|string',
        ]);
        
        $visits = Visit::create([
            'general_health' => $attr['general_health'],
            'on_diet' => $attr['on_diet'],
            'on_drugs' => $attr['on_drugs'],
            'comments' => $attr['comments'],
            'patient_id' => $attr['patient_id'],
        ]);
        if($visits){
            $data = ([
                'slug' => 0,
                'message' => "Visit Added Successfully",
            ]);
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

        $attr = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'unique' => 'required|string|max:255',
            'dob' => 'required|string|max:255',
            'gender' => 'required|string|max:255',

        ]);

        if (Patient::where('unique', '=', $attr['unique'])->exists()) {
            $data = ([
                'proceed' => 1,
                'message' => 'A User with the same unique already exist'
            ]);
            return $this->successResponse("success", $data);
        }

        $patient = Patient::create([
            'firstname' => $attr['firstname'],
            'lastname' => $attr['lastname'],
            'unique' => $attr['unique'],
            'dob' => $attr['dob'],
            'gender' => $attr['gender'],

        ]);
        if ($patient) {
            $data = ([
                'proceed' => 0,
                'message' => 'Patient Added successfully'
            ]);
            return $this->successResponse("success", $data);
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
