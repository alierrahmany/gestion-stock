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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|different:current_password',
            'password_confirmation' => 'nullable|same:new_password',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|in:active,inactive'
        ]);
    
        // Rest of your method remains the same...
        $data = $request->only(['name', 'email', 'status']);
    
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            
            $data['password'] = Hash::make($request->new_password);
        }
    
        if ($request->hasFile('image')) {
            if ($user->image && $user->image != 'no_image.jpg') {
                Storage::delete('public/profile_images/'.$user->image);
            }
            
            $imageName = time().'.'.$request->image->extension();
            $request->image->storeAs('public/profile_images', $imageName);
            $data['image'] = $imageName;
        }
    
        $user->update($data);
    
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}