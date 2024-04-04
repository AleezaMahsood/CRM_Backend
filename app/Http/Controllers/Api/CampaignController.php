<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Campaigns;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = campaigns::all();
        return response()->json($campaigns, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'campaign_name' => 'required|string|max:150',
            'description' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'expected_revenue' => 'required|string|max:255',
            'actual_cost' => 'required|string|max:255' ]);
          if($validated->fails()){
              return response()->json([
                  'status'=>422,
                  'error'=>$validated->messages()
              ],422);
          }else{
              $campaigns = campaigns::create([
                  'campaign_name' => $request->campaign_name,
                  'description' => $request->description,
                  'start_date' => $request->start_date,
                  'end_date' => $request->end_date,
                  'actual_cost' => $request->actual_cost,
                  'expected_revenue' => $request->expected_revenue
              ]); 
              
          }
          if($campaigns){
              //$article->category()->attach($request->categories);
              return response()->json([
                  'status'=>200,
                  'message'=>'Campaigns created successfully'
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
                $article = campaigns::findOrFail($id);
                return response()->json($article);
            }
        
            /**
             * Show the form for editing the specified resource.
             */
            public function edit(string $id)
            {
                
            }
        
            /**
             * Update the specified resource in storage.
             */
            public function update(Request $request,campaigns $campaign)
            {
                $campaign->update($request->all());
                return response()->json($campaign, 200);
            }
        
            /**
             * Remove the specified resource from storage.
             */
            public function destroy(Campaigns $campaign)
            {
                $campaign->delete();
                return response()->json(null, 204);
            }
        }
