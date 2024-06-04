<?php

namespace App\Http\Controllers\Education;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EducationController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        $request->validate([
            'degree' => 'required|string|max:255',
            'school_location' => 'nullable|string|max:255',
            'school' => 'required|string|max:255',
            'school_start_date' => 'required|date|before_or_equal:today',
            'school_end_date' => [
                'nullable',
                'date',
                'after_or_equal:school_start_date',
                Rule::requiredIf(!$request->currently_studying),
                'before_or_equal:today'
            ],
            'currently_studying' => 'boolean',
        ]);

        $school_end_date = $request->currently_studying ? null : $request->school_end_date;

        if ($request->id && $education = Education::find($request->id)) {
            $education->update([
                'degree' => $request->degree,
                'school_location' => $request->school_location,
                'school' => $request->school,
                'school_start_date' => $request->school_start_date,
                'school_end_date' => $school_end_date,
                'currently_studying' => $request->currently_studying ? true : false,
            ]);
    
            return response()->json([
                'message' => 'Education updated successfully',
                'education' => $education
            ], 200);
        } else {
            $education = Education::create([
                'resume_id' => $request->resume_id,
                'degree' => $request->degree,
                'school_location' => $request->school_location,
                'school' => $request->school,
                'school_start_date' => $request->school_start_date,
                'school_end_date' => $school_end_date,
                'currently_studying' => $request->currently_studying ? true : false,
            ]);
    
            return response()->json([
                'message' => 'Education created successfully',
                'education' => $education
            ], 201);
        }
    }

    public function delete($id)
    {
        $education = Education::find($id);

        if (!$education) {
            return response()->json([
                'message' => 'Education not found'
            ], 404);
        }

        $education->delete();

        return response()->json([
            'message' => 'Education deleted successfully'
        ]);
    }
}
