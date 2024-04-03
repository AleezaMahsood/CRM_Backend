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

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
