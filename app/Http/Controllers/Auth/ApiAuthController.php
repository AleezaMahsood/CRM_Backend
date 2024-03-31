<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ApiAuthController extends Controller
{
    // public function register (Request $request) {
    //    $validator = Validator::make($request->all(), [
    //        'name' => 'required|string|max:255',
    //        'email' => 'required|string|email|max:255|unique:users',
    //        'password' => 'required|string|min:6|confirmed',
    //    ]);
    //    if ($validator->fails())
    //    {
    //        return response(['errors'=>$validator->errors()->all()], 422);
    //    }
    //    $request['password']=Hash::make($request['password']);
    //    $request['remember_token'] = Str::random(10);
    //    $user = User::create($request->toArray());
     //   $token = $user->createToken('Laravel Password Grant Client')->accessToken;
     //   $response = ['token' => $token];
      //  return response($response, 200);
    //}
    
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'gender' => ['required', Rule::in(User::GENDER)],
        'location' => ['required', Rule::in(User::LOCATIONS)],
        'department' => ['required', Rule::in(User::DEPARTMENTS)],
        'designation' => ['required', Rule::in(User::DESIGNATIONS)],
        'team' => ['required', Rule::in(User::TEAMS)],
        'role' => ['required', Rule::in(User::ROLES)],
    ]);

    if ($validator->fails()) {
        return response(['errors' => $validator->errors()->all()], 422);
    }

    $data = $request->only(['firstName', 'lastName', 'email', 'password']);
    $data['password'] = Hash::make($data['password']);
    $data['remember_token'] = Str::random(10);

    // Add additional user attributes
    $data['gender'] = $request->gender;
    $data['location'] = $request->location;
    $data['department'] = $request->department;
    $data['designation'] = $request->designation;
    $data['team'] = $request->team;
    $data['role'] = $request->role;

    $user = User::create($data);

    $token = $user->createToken('Laravel Password Grant Client')->accessToken;

    $response = ['token' => $token];

    return response($response, 200);
}
   
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        $user->last_login_time = now();
        $user->save();
    
        // Update status to active
        $user->status = 'active';
        $user->save();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $token,
            'message' => 'Login successful',
        ], status:200);
    }


    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
    public function getEnums()
{
    return response()->json([
        'gender' => User::GENDER,
        'locations' => User::LOCATIONS,
        'departments' => User::DEPARTMENTS,
        'designations' => User::DESIGNATIONS,
        'teams' => User::TEAMS,
        'roles' => User::ROLES,
    ]);
}

public function index()
{
    $users = User::all()->map(function ($user) {
        return collect($user->toArray())->except('password', 'remember_token','created_at','updated_at','email_verified_at');
    });

    return response()->json(['users' => $users], 200);
}  
    
}
