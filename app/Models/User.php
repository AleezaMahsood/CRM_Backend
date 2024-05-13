<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use app\Models\leads; 
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'phone',
        'email',
        'password',
        'gender',
        'location',
        'department',
        'designation',
        'team',
        'role'
    ];
    public function leads()
    {
        return $this->hasMany(leads::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    const GENDER = ['male', 'female'];
    const LOCATIONS = ['Islamabad','Peshawar','Quetta','Multan','Rawalpindi', 'Karachi', 'Lahore'];
    const DEPARTMENTS = ['Testers', 'Technical', 'Sales','Marketing','HR'];
    const DESIGNATIONS = ['Senior Manager','Sales Person','Project Manager', 'Manager', 'Executive Manager'];
    const TEAMS = ['Francis', 'Koderz', 'Falcon','Knight','Tech','Creative'];
    const ROLES = ['user', 'admin'];
    
    public static function updateStatus()
    {
        $inactivePeriod = now()->subMinutes(1); // Adjust the period as needed (e.g., 3 months)

        // Get users who haven't logged in since the inactive period
        $inactiveUsers = self::where('last_login_time', '<', $inactivePeriod)
                             ->where('status', 'active')
                             ->get();

        // Update their status to inactive
        foreach ($inactiveUsers as $user) {
            $user->status = 'inactive';
            $user->save();
        }
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
