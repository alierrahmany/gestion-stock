<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:3000'], // 2048 KB = 2MB
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'different:current_password'],
            'password_confirmation' => ['nullable', 'same:new_password'],
        ]);

        $data = $request->only(['name', 'email']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists and not default
            if ($user->image && $user->image !== 'no_image.jpg') {
                Storage::disk('public')->delete('profile_images/' . $user->image);
            }

            // Store new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('profile_images', $imageName, 'public');
            $data['image'] = $imageName;
        }

        // Handle password update
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
            }
            $data['password'] = Hash::make($request->new_password);
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}