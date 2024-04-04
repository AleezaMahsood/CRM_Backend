<?php
 namespace App\Http\Controllers\Api;
 use Illuminate\Http\Request;
 use App\Models\Projects;
 use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
        {
            $projects = projects::all();
            return response()->json($projects, 200, [], JSON_PRETTY_PRINT);
        }
    
        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            $validated = Validator::make($request->all(),[
                    'project_name' => 'required|string|max:150',
                    'project_location' => 'required|in:lahore,karachi,quetta,rawalpindi|max:255', 
                    'project_type' => 'required|in:environmental,healthcare,IT,event_management|max:255', 
                    'min_price' => 'required|string|max:255',
                    'max_price' => 'required|string|max:255|gt:min_price'
                ]);
              if($validated->fails()){
                  return response()->json([
                      'status'=>422,
                      'error'=>$validated->messages()
                  ],422);
              }else{
                  $campaigns = projects::create([
                      'project_name' => $request->project_name,
                      'project_location' => $request->project_location,
                      'project_type' => $request->project_type,
                      'min_price' => $request->min_price,
                      'max_price' => $request->max_price
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
                    $article = projects::findOrFail($id);
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
                public function update(Request $request,projects $project)
                {
                    $project->update($request->all());
                    return response()->json($project, 200);
                }
            
                /**
                 * Remove the specified resource from storage.
                 */
                public function destroy(projects $project)
                {
                    $project->delete();
                    return response()->json(null, 204);
                }
            }
