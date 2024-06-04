<?php

namespace App\Http\Controllers\Resume;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Resume;

class ResumeController extends Controller
{
    public function index()
    {
        return view('app');
    }
    public function createOrUpdate(Request $request)
    {
        $request->validate([
            'resume_name' => 'required|string|max:255',
        ]);

        if ($request->id) {
            $resume = Resume::find($request->id);
            $resume->name = $request->resume_name;
            $resume->save();
        } else {
            $resume = Resume::create([
                'name' => $request->resume_name,
            ]);
        }

        return response()->json(['id' => $resume->id, 'name' => $resume->name]);
    }
    public function getAllResume()
    {
        $resumes = Resume::orderBy('created_at', 'desc')->get();
        return response()->json($resumes);
    }
    public function delete($id)
    {
        $resume = Resume::find($id);
        if (!$resume) {
            throw ValidationException::withMessages(['resume' => 'CV not found']);
        }
    
        // Delete the resume
        $resume->delete();
    
        return response()->json(['message' => 'CV deleted successfully']);
    }
    public function resumeData($id)
    {
        $resume = Resume::find($id);
        if (!$resume) {
            throw ValidationException::withMessages(['resume' => 'CV not found']);
        }
        $resume->load('userDetails');
        $resume->load('educations');
        $resume->load('experiences');
        $resume->load('skills');

        $skills = $resume->skills->pluck('skill_name')->toArray();

        $sortedEducations = $resume->educations->sortByDesc(function ($education) {
            return [$education->currently_studying ? '0' : '1', $education->school_end_date, $education->school_start_date];
        })->values()->all();

        $sortedExperiences = $resume->experiences->sortByDesc(function ($experience) {
            return [$experience->is_current_job ? '0' : '1', $experience->end_date, $experience->start_date];
        })->values()->all();

        $response = $resume->toArray();
        $response['skills'] = $skills;
        $response['educations'] = $sortedEducations;
        $response['experiences'] = $sortedExperiences;

        return response()->json($response);
    }

    public function finish(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:resumes,id',
        ]);

        $resume = Resume::find($request->id);
        if (!$resume) {
            throw ValidationException::withMessages(['resume' => 'CV not found']);
        }

        $resume->completed = true;
        $resume->save();

        return response()->json(['message' => 'CV completed successfully']);
    }
}
