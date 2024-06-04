<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;
use Illuminate\Validation\ValidationException;

class SkillController extends Controller
{
    public function create(Request $request)
    {

        $validatedData = $request->validate([
            'resume_id' => 'required|exists:resumes,id',
            'skill_name' => 'required|string|max:255',
        ]);

        $skill = Skill::create($validatedData);

        return response()->json(['message' => 'Skill add successfully', 'skill' => $validatedData['skill_name']], 201);
    }

    public function delete(Request $request)
    {
        $validatedData = $request->validate([
            'resume_id' => 'required|integer|exists:resumes,id',
            'skill_name' => 'required|string',
        ]);
    
        $skill = Skill::where('resume_id', $validatedData['resume_id'])
                      ->where('skill_name', $validatedData['skill_name'])
                      ->first();
    
        if (!$skill) {
            throw ValidationException::withMessages(['skill' => 'Skill not found']);
        }
    
        $skill->delete();
    
        return response()->json(['message' => 'Skill deleted successfully']);
    }
}