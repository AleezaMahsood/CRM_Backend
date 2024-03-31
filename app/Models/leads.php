<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class leads extends Model
{
    use HasFactory;
    protected $table="leads";
    protected $fillable=[
        'leadName',
        'phoneNumber',
        'project',
        'campaign',
        'lead_cost',
        'lead_date'

    ];
}
