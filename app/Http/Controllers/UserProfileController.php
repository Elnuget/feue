<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function create()
    {
        return view('user_profiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            // ...other validation rules...
        ]);

        UserProfile::create($request->all());

        return redirect()->route('user_profiles.show', $request->user_id);
    }

    public function show($id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();
        return view('user_profiles.show', compact('profile'));
    }

    public function edit($id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();
        return view('user_profiles.edit', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();

        $request->validate([
            // ...validation rules...
        ]);

        $profile->update($request->all());

        return redirect()->route('user_profiles.show', $id);
    }

    public function destroy($id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();
        $profile->delete();

        return redirect()->route('users.index');
    }
}