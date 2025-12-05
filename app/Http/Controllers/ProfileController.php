<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('profile.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name'   => 'required|string|max:255',
            'number' => 'required|digits:10',
        ]);

        $user = User::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $user->image = $imagePath;
        }

        $user->name = $validated['name'];
        $user->number = $validated['number'];

        $user->save();

        return redirect()->route('profile.show', $user->id)
                         ->with('success', 'Profile updated successfully!');
    }
}