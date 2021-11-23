<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use App\Models\Vital;
use Carbon\Carbon;
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
                    'id' => $vital->id,
                    'slug' => 1,
                    'message' => "Vital Added Successfully",
                ]);
            } else {
                $data = ([
                    'id' => $vital->id,
                    'slug' => 0,
                    'message' => "Vital Added Successfully",
                ]);
            }

            return $this->successResponse("success", $data);
        } else {
            return $this->errorResponse("Request Failed");
        }
    }

    public function view_visits(Request $request)
    {
        $attr = $request->validate([
            'visit_date' => 'required|string',

        ]);
        $visit_date = $attr['visit_date'];


        $visits = Visit::where('visit_date', $visit_date)->orderBy('created_at', 'desc')->get();
        $dataSet = [];
        if ($visits) {
            foreach ($visits as $visit) {

                $patient = Patient::where('id', $visit->patient_id)->first();
                $vital = Vital::where('id', $visit->patient_id)->first();
                if ($patient) {

                    $name = $patient->firstname;
                    $dob =  $patient->dob;
                    $bmi = $vital->bmi;

                    $date_of_birth = Carbon::createFromFormat('Y-m-d', $dob);

                    $age = Carbon::parse($date_of_birth)->diff(Carbon::now())->y;
                    if ($bmi < 18.5) {
                        $status = "Underweight";
                    }
                    if ($bmi < 18.5) {
                        $status = "Underweight";
                    } else if ($bmi > 18.5 and $bmi<25) {
                        $status = "Normal";
                    } else if ($bmi >= 25) {
                        $status = "Overweight";
                    }
                    $dataSet[] = [
                        'name' => $name,
                        'age' => $age,
                        'status' => $status
                    ];
                }
            }
            return $this->successResponse("success", $dataSet);
        } else {
            return $this->errorResponse("No Data Found");
        }
    }

    public function add_visits(Request  $request)
    {
        $attr = $request->validate([
            'general_health' => 'required|string',
            'on_diet' => 'required|string',
            'on_drugs' => 'required|string',
            'comments' => 'required|string',
            'visit_date' => 'required|string',
            'patient_id' => 'required|string',
        ]);

        $diet = $request->on_diet === 'true' ? true : false;
        $drugs = $request->on_drugs === 'true' ? true : false;

        if (Visit::where(['patient_id' => $attr['patient_id'], 'visit_date' => $attr['visit_date']])->exists()) {
            $data = ([
                'slug' => 1,
                'message' => "The patient has existing Visit for today",
            ]);
            return $this->successResponse("success", $data);
        }
        $visits = Visit::create([
            'general_health' => $attr['general_health'],
            'on_diet' => $diet,
            'on_drugs' => $drugs,
            'visit_date' => $attr['visit_date'],
            'comments' => $attr['comments'],
            'patient_id' => $attr['patient_id'],
        ]);
        if ($visits) {
            $data = ([
                'slug' => 0,
                'message' => "Visit Added Successfully",
            ]);
            return $this->successResponse("success", $data);
        } else {
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
            'reg_date' => 'required|string|max:255',

        ]);

        if (Patient::where('unique', '=', $attr['unique'])->exists()) {
            $data = ([
                'proceed' => 1,
                'message' => 'A User with the same unique already exist'
            ]);
            return $this->successResponse("success", $data);
        }
        $formattedDate = Carbon::parse($attr['reg_date'])->format('Y-m-d');
        $patient = Patient::create([
            'firstname' => $attr['firstname'],
            'lastname' => $attr['lastname'],
            'unique' => $attr['unique'],
            'dob' => $attr['dob'],
            'gender' => $attr['gender'],
            'reg_date' => $formattedDate

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
