<?php

namespace App\Http\Controllers\UserDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDetails;

class UserDetailsController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'resume_id' => 'required|integer',
            'given_name' => 'required|max:255',
            'family_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8',
            'address' => 'max:255',
            'town' => 'max:255',
            'summary' => 'required',
        ]);

        $userDetails = UserDetails::updateOrCreate(
            ['resume_id' => $validatedData['resume_id']],
            $validatedData
        );

        return response()->json(['message' => 'User details created successfully!']);
    }
}
