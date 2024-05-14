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
        'campaign',
        'date',
        'user_id',
        'lead_date',
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
        'budget',
        'employees',
        'rating',
        'revenue',
        'skype',
        'remarks',
        'project_id'

    ];
    const STATUS = [
        "New",
        "Pending",
        "Not Responding",
        "Not Answering",
        "Meeting Scheduled",
        "Not Interested",
        "Interested",
        "Converted",
        "Rejected",
        "Invalid"
      ];
      public function project()
    {
        return $this->belongsTo(Projects::class);
    }
      
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
