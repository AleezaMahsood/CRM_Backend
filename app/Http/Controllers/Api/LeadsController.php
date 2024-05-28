<?php

namespace App\Http\Controllers\Api;
use App\Models\leads;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class LeadsController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = leads::with('User')->get();

        // Modify the leads collection to include the user's department directly
        $leads->transform(function ($lead) {
            $lead->department = $lead->User->department;
            $lead->firstName=$lead->User->firstName;
            $lead->lastName=$lead->User->lastName;
            unset($lead->User); // Optionally remove the user object if it's not needed
            return $lead;
        });
        return response()->json($leads, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     */
// function to create leads for a particular user.
     public function store(Request $request)
    {
            $validated = Validator::make($request->all(), [
                'leadName' => 'required|string|max:255',
                'job_title' => 'nullable|string|max:255', // Added job_title attribute
                'phoneNumber' => 'required|string|max:255',      // Added phone attribute
                'mobile' => 'nullable|string|max:255',     // Added mobile attribute
                'whatsapp' => 'nullable|string|max:255',   // Added whatsapp attribute
                'source' => 'nullable|string|max:255',     // Added source attribute
                'industry' => 'nullable|string|max:255',   // Added industry attribute
                'company' => 'nullable|string|max:255',    // Added company attribute
                'email' => 'nullable|string|email|max:255',// Added email attribute
                'fax' => 'nullable|string|max:255',        // Added fax attribute
                'website' => 'nullable|string|url|max:255',// Added website attribute
                'status' => ['required',Rule::in(leads::STATUS) ],   // Ensure status is required
                'employees' => 'nullable|integer',          // Added employees attribute
                'rating' => 'nullable|string|max:100',             // Added rating attribute
                'project_id' => 'nullable|exists:projects,id',
                'campaign' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'remarks' => 'nullable|string',
                'revenue' => 'nullable|numeric',
                'budget'=>'nullable|numeric',
                'lead_date' => 'nullable|date',
                'skype' => 'nullable|string|max:255',
                'user_id' => 'exists:users,id',
                'created_by'=>'exists:users,id'
                
            ]);
        
            if ($validated->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validated->messages()
                ], 422);
            } else {
                
                //$userWithLeastLeads = User::withCount('leads')->orderBy('leads_count', 'asc')->first();
             
                $leads = leads::create([
                    'leadName' => $request->leadName,
                    'job_title' => $request->job_title,     // Added job_title
                    'phoneNumber' => $request->phoneNumber,             // Added phone
                    'mobile' => $request->mobile,           // Added mobile
                    'whatsapp' => $request->whatsapp,       // Added whatsapp
                    'source' => $request->source,           // Added source
                    'industry' => $request->industry,       // Added industry
                    'company' => $request->company,         // Added company
                    'email' => $request->email,             // Added email
                    'fax' => $request->fax,                 // Added fax
                    'website' => $request->website,         // Added website
                    'status' => $request->status,
                    'employees' => $request->employees,     // Added employees
                    'rating' => $request->rating,           // Added rating
                    'remarks' => $request->remarks,
                    'revenue' => $request->revenue,
                    'skype' => $request->skype,
                    'project_id' => $request->project_id,
                    'budget'=>$request->budget,
                    'campaign' => $request->campaign,
                    'date' => $request->date,
                    'lead_date'=>$request->lead_date,
                    'user_id' => $request->user_id,
                    'created_by'=>$request->created_by
                ]);
            }
           
        
            if ($leads) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lead created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong'
                ], 500);
            }
    }
     //Function to add leads by admin
     public function adminStore(Request $request)
     {
             $validated = Validator::make($request->all(), [
                 'leadName' => 'required|string|max:255',
                 'phoneNumber' => 'required|string|max:255',      // Added phone attribute
                 'status' => ['required',Rule::in(leads::STATUS) ],   // Ensure status is required
                 'project_id' => 'nullable|exists:projects,id',
                 'campaign' => 'nullable|string|max:255',
                 'date' => 'nullable|date',
                 'remarks' => 'nullable|string',
                 'user_id' => 'exists:users,id',
                 'created_by'=>'exists:users,id'              
             ]);
         
             if ($validated->fails()) {
                 return response()->json([
                     'status' => 422,
                     'error' => $validated->messages()
                 ], 422);
             } else {
                 
                 //$userWithLeastLeads = User::withCount('leads')->orderBy('leads_count', 'asc')->first();
                 $userWithLeastLeads = User::where('role', 'user')
    ->leftJoin('leads', 'users.id', '=', 'leads.user_id')
    ->select('users.id', DB::raw('COUNT(leads.id) as lead_count'))
    ->groupBy('users.id')
    ->orderBy('lead_count')
    ->first();
             
                 $leads = leads::create([
                     'leadName' => $request->leadName,
                     'phoneNumber' => $request->phoneNumber,             // Added phone
                     'project_id' => $request->project_id,
                     'campaign' => $request->campaign,
                     'status'=>$request->status,
                     'date' => $request->date,
                     'user_id' => $userWithLeastLeads->id,
                     'created_by'=>$request->created_by,
                     'remarks'=>$request->remarks
                 ]);
             }
            
         
             if ($leads) {
                 return response()->json([
                     'status' => 200,
                     'message' => 'Lead created successfully'
                 ], 200);
             } else {
                 return response()->json([
                     'status' => 500,
                     'message' => 'Something went wrong'
                 ], 500);
             }
     }   



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = leads::findOrFail($id);
        return response()->json($article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, leads $lead)
    {
        //
        $lead->update($request->all());

        return response()->json($lead, 200);
    }

public function getEnums()
{
    return response()->json([
        'statuses' => leads::STATUS,
        // 'projects' =>leads::PROJECT,
    ]);
    
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(leads $lead)
    {
        //
        $lead->delete();

        return response()->json(null, 204);
    }
}
