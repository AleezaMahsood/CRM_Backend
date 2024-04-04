<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    use HasFactory;
    
    protected $table="campaigns";
        protected $fillable=[
        'campaign_name',
        'description',
        'start_date',
        'end_date',
        'expected_revenue',
        'actual_cost'];
}
