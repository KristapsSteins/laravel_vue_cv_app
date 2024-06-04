<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Education\EducationController;
use App\Http\Controllers\Resume\ResumeController;
use App\Http\Controllers\UserDetails\UserDetailsController;
use App\Http\Controllers\Experience\ExperienceController;
use App\Http\Controllers\Skill\SkillController;

Route::get('/api/resume/list', [ResumeController::class, 'getAllResume']);
Route::get('/api/resume/preview/{id}', [ResumeController::class, 'resumeData']);
Route::get('/api/resume/data/{id}', [ResumeController::class, 'resumeData']);

Route::post('/api/resume/create', [ResumeController::class, 'createOrUpdate']);
Route::post('/api/user-details/create', [UserDetailsController::class, 'create']);
Route::post('/api/education/create', [EducationController::class, 'createOrUpdate']);
Route::post('/api/experience/create', [ExperienceController::class, 'createOrUpdate']);
Route::post('/api/resume/skill/create', [SkillController::class, 'create']);
Route::post('/api/resume/finish', [ResumeController::class, 'finish']);

Route::delete('/api/education/{id}', [EducationController::class, 'delete']);
Route::delete('/api/resume/{id}', [ResumeController::class, 'delete']);
Route::delete('/api/experience/{id}', [ExperienceController::class, 'delete']);
Route::delete('/api/resume/skill/{id}', [SkillController::class, 'delete']);

Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
