<?php

namespace App\Http\Controllers\Api;
use App\Models\leads;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
    $validated = Validator::make($request->all(),[
        'leadName' => 'required|string|max:255',
        'phoneNumber' => 'required|string|max:255',
        'project' => 'nullable|string|max:255',
        'campaign' => 'nullable|string|max:255',
        'project_cost' => 'nullable|numeric',
        'date' => 'required|date',
        //'campaigns' => 'required|array', // Ensure categories is an array
        //'campaigns.*' => 'exists:campaigns,id'
    ]);
    if($validated->fails()){
        return response()->json([
            'status'=>422,
            'error'=>$validated->messages()
        ],422);
    }else{
        //$leads = leads::create($validated);
        $leads = leads::create([
            'leadName' => $request->leadName,
            'phoneNumber' => $request->phoneNumber,
            'project' => $request->project,
            'campaign' => $request->campaign,
            'project_cost' => $request->project_cost,
            'date' => $request->date
        ]); 
        
    }
    if($leads){
        //$article->category()->attach($request->categories);
        return response()->json([
            'status'=>200,
            'message'=>'Lead created successfully'
        ]
        ,200);
    }else{
        return response()->json([
            'status'=>500,
            'message'=>'something went wrong'
        ]
        ,500);    
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
