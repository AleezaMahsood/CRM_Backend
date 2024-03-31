<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
    const GENDER = ['male', 'female', 'other'];
    const LOCATIONS = ['location1', 'location2', 'location3'];
    const DEPARTMENTS = ['department1', 'department2', 'department3'];
    const DESIGNATIONS = ['designation1', 'designation2', 'designation3'];
    const TEAMS = ['team1', 'team2', 'team3'];
    const ROLES = ['role1', 'role2', 'role3'];
    
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
}
