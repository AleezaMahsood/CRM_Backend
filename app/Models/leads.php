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
        'campaign_id',
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
        'budget',
        'employees',
        'rating',
        'revenue',
        'skype',
        'remarks',
        'project_id',
        'created_by'

    ];
    const STATUS = [
        "New",
        "Pending",
        "Follow_Ups",
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
     public function campaign()
    {
        return $this->belongsTo(Campaigns::class);
    }
}
