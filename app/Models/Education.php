<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resume_id',
        'degree',
        'school_location',
        'school',
        'school_start_date',
        'school_end_date',
        'currently_studying',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'currently_studying' => 'boolean',
    ];

    /**
     * Get the resume that owns the education.
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    
}