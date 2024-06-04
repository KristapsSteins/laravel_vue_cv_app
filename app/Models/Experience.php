<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'job_title',
        'company_name',
        'job_description',
        'start_date',
        'end_date',
        'is_current_job',
        'is_remote',
    ];

    protected $casts = [
        'job_description' => 'array',
        'is_current_job' => 'boolean',
        'is_remote' => 'boolean',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}