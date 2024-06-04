<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resume_id',
        'given_name',
        'family_name',
        'email',
        'phone',
        'address',
        'town',
        'summary',
    ];

    /**
     * Get the resume that owns the user detail.
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}