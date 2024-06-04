<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use Illuminate\Validation\Rule;


class ExperienceController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'sometimes|integer|exists:experiences,id',
            'resume_id' => 'required|integer|exists:resumes,id',
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'job_description' => 'required|array',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
                Rule::requiredIf(!$request->is_current_job),
                'before_or_equal:today'
            ],
            'is_current_job' => 'boolean',
            'is_remote' => 'boolean',
        ]);
    
        if ($validatedData['end_date'] === '') {
            $validatedData['end_date'] = null;
        }
    
        $validatedData['is_current_job'] = $request->is_current_job ? true : false;
        $validatedData['is_remote'] = $request->is_remote ? true : false;
    
        if ($request->id && $experience = Experience::find($request->id)) {
            $experience->update($validatedData);
            $message = 'Experience updated successfully';
        } else {
            $experience = Experience::create($validatedData);
            $message = 'Experience created successfully';
        }
    
        return response()->json(['message' => $message, 'experience' => $experience], 201);
    }

    public function delete($id)
    {
        $experience = Experience::find($id);
        if (!$experience) {
            return response()->json(['message' => 'Experience not found'], 404);
        }

        $experience->delete();

        return response()->json(['message' => 'Experience deleted successfully']);
    }
}