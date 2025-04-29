// ...existing code...

public function updateImage(Request $request, User $user)
{
    // Allow admin or the user themselves to modify the image
    if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
        abort(403, 'You can only modify your own profile image.');
    }

    $request->validate([
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('profile_image')) {
        // Delete old image if exists
        if ($user->profile_image && file_exists(public_path('storage/profile_images/'.$user->profile_image))) {
            unlink(public_path('storage/profile_images/'.$user->profile_image));
        }

        $imageName = time().'.'.$request->profile_image->extension();
        $request->profile_image->storeAs('public/profile_images', $imageName);

        $user->update(['profile_image' => $imageName]);
    }

    return back()->with('success', 'Profile image updated successfully');
}
// ...existing code...
