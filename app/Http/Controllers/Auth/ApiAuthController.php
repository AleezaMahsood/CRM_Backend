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
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{ 
    public function __construct()
    {
        $this->middleware('custom.auth', ['except' => ['login', 'register', 'getEnums', 'index']]);
    }

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
    //$data['remember_token'] = Str::random(10);

    // Add additional user attributes
    $data['gender'] = $request->gender;
    $data['location'] = $request->location;
    $data['department'] = $request->department;
    $data['designation'] = $request->designation;
    $data['team'] = $request->team;
    $data['role'] = $request->role;

    $user = User::create($data);
    $token = Auth::login($user);
    return response()->json([
        'status' => 'success',
        'message' => 'User created successfully',
        'user' => $user,
        'authorisation' => [
            'token' => $token,
            'type' => 'bearer',
        ]
    ]);

   // $token = $user->createToken('Laravel Password Grant Client')->accessToken;

   // $response = ['token' => $token];

   // return response($response, 200);
}
   
    public function login(Request $request)
    {

         $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]); 

        $user = User::where('email', $credentials['email'])->first();
       // $user=Auth::user();
       // $id=user()->id;
       if (! $token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
        
       // $credentials = request(['email', 'password']);
       $user->last_login_time = now();
       $user->save();
   
       // Update status to active
       $user->status = 'active';
       $user->save();

       

        return $this->respondWithToken($token,$user);
       
       
        //$token = $user->createToken('authToken')->plainTextToken;

      //  return response()->json([
       //     'success' => true,
       //     'user' => $user,
        //    'id'=>$id,
       //     'access_token' => $token,
       //     'message' => 'Login successful',
      //  ], status:200);
    }
    
       /*
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        # Here we just get information about current user
        //return response()->json(auth()->user());
        if(auth()->check()){

            return response()->json(auth()->user());
        } else{
            return response()->json(['error' => 'Unauthorized: User has logged out or token is not valid'], 401);
        }
    
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout(); # This is just logout function that will destroy access token of current user

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        # When access token will be expired, we are going to generate a new one wit this function 
        # and return it here in response
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$user)
    {
        $defaultTTL = auth()->factory()->getTTL();
        $expiration = max($defaultTTL * 60, 20 * 60); // Use the higher of default and 20 minutes
        # This function is used to make JSON response with new
        # access token of current user
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'role'=>$user->role,
           // 'expires_in' => auth()->factory()->getTTL() * 60
           'expires_in' => $expiration,
        ]);
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

    return response()->json($users, 200, [], JSON_PRETTY_PRINT);
} 
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->designation = $request->input('designation');
        $user->team = $request->input('team');
        $user->department = $request->input('department');

        if ($request->input('newPassword') && $request->input('confirmPassword')) {
            if ($request->input('newPassword') === $request->input('confirmPassword')) {
                if (Hash::check($request->input('password'), $user->password)) {
                    $user->password = Hash::make($request->input('newPassword'));
                } else {
                    return response()->json(['message' => 'Old password is incorrect'], 400);
                }
            } else {
                return response()->json(['message' => 'New password and confirmation do not match'], 400);
            }
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }
    public function showUser($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    return response()->json($user);
}
    
}
