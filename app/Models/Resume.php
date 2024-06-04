<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'completed',
    ];

    public function userDetails()
    {
        return $this->hasOne('App\Models\UserDetails');
    }

    public function experiences()
    {
        return $this->hasMany('App\Models\Experience');
    }

    public function skills()
    {
        return $this->hasMany('App\Models\Skill');
    }

    public function educations()
    {
        return $this->hasMany('App\Models\Education');
    }

}
