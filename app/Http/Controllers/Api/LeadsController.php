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
class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = leads::all();
        return response()->json($leads, 200, [], JSON_PRETTY_PRINT);

    }


    /**
     * Store a newly created resource in storage.
     */
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
                'status' => 'required|string|max:255',     // Ensure status is required
                'employees' => 'nullable|integer',          // Added employees attribute
                'rating' => 'nullable|string|max:100',             // Added rating attribute
                'project' => 'nullable|string|max:255',
                'campaign' => 'nullable|string|max:255',
                'project_cost' => 'nullable|numeric',
                'date' => 'required|date',
                'remarks' => 'nullable|string',
                'revenue' => 'nullable|numeric',
                'skype' => 'nullable|string|max:255',
                'user_id' => 'exists:users,id'
            ]);
        
            if ($validated->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validated->messages()
                ], 422);
            } else {
                
                //$userWithLeastLeads = User::withCount('leads')->orderBy('leads_count', 'asc')->first();
                $userWithLeastLeads = User::leftJoin('leads', 'users.id', '=', 'leads.user_id')
                ->where('users.role', 'user')
                ->select('users.id', DB::raw('COUNT(leads.id) as lead_count'))
                ->groupBy('users.id')
                ->orderBy('lead_count')
                ->first();
            
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
                    'project' => $request->project,
                    'campaign' => $request->campaign,
                    'project_cost' => $request->project_cost,
                    'date' => $request->date,
                    'user_id' => $userWithLeastLeads->id,
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
    public function countLeadsByStatus($userId)
{
    $leadCounts = leads::select(
        DB::raw('SUM(CASE WHEN status = "new" THEN 1 ELSE 0 END) AS New'),
        DB::raw('SUM(CASE WHEN status = "converted" THEN 1 ELSE 0 END) AS Converted'),
        DB::raw('SUM(CASE WHEN status = "follow up" THEN 1 ELSE 0 END) AS Follow_Ups'),
        DB::raw('COUNT(*) AS Total_Leads')
    )
    ->where('user_id', $userId)
    ->groupBy('user_id')
    ->first();

// If no leads found for the user, set counts to 0
if (!$leadCounts) {
    $leadCounts = [
        'New' => 0,
        'Converted' => 0,
        'Follow_Ups' => 0,
        'Total_Leads' => 0,
    ];
}else{
    $leadCounts = $leadCounts->toArray();
}

return response()->json($leadCounts);
}
//Function to get leads for a specific user to evaluate his performance
public function fetchLeadsByAllStatuses($userId)
{
    $statuses = ['new', 'pending', 'valid', 'rejected', 'converted', 'follow up'];

    $labels = [];
    $totalLeads=[];
    foreach ($statuses as $status) {
        $labels[]=$status;
        $totalLeads[] = leads::where('user_id', $userId)->where('status', $status)->count();
    }

     return [
            'labels' => $labels,
            'totalLeads' => $totalLeads,
        ];
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
