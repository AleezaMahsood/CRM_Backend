<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class leads extends Model
{
    use HasFactory;
    protected $table="leads";
    protected $fillable=[
        'leadName',
        'phoneNumber',
        'project',
        'campaign',
        'project_cost',
        'date',
        'user_id',
        'job_title',
        'mobile',
        'whatsapp',
        'source',
        'industry',
        'company',
        'email',
        'fax',
        'website',
        'status',
        'employees',
        'rating',
        'revenue',
        'skype',
        'remarks',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
