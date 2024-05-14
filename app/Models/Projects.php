<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    
    protected $table="projects";
    protected $fillable=[
        'project_name',
        'project_location',
        'project_type',
        'min_price',
        'max_price'];

const PROJECT = [
    "Web Development ",
    "Data Science & Machine Learning",
    "Artificial Intelligence (AI)",
    "Cybersecurity",
    "Cloud Computing",
    "Internet of Things (IoT)",
    "Blockchain Technology",
    "Virtual Reality (VR) and Augmented Reality (AR)",
  ];
  const LOCATIONS = ['Islamabad','Peshawar','Quetta','Multan','Rawalpindi', 'Karachi', 'Lahore'];
  public function lead()
    {
        return $this->hasOne(leads::class);
    }

}
