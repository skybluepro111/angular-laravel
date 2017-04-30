<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use App\Models\Post;

use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function getSettings() {
        return view('pages.users.user-settings')
            ->with('user', Auth::user())
            ->with('postsThisMonth', Post::whereUserId(Auth::user()->getAuthIdentifier())->count());
    }

    public function postSettings(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'image' => 'image',
        ]);

        $user = Auth::user();

        if($request->file('image') && $request->file('image')->isValid()) {
            $filename = str_random(10) . '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->resize(47,47)->save(public_path() . '/user_avatars/' . $filename);
            $user->image = 'http://' . config('custom.app-domain') . '/user_avatars/' . $filename;
        }

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->save();

        return view('pages.users.user-settings')
            ->with('user', $user)
            ->with('message', 'success|Settings updated successfully!');
    }

    public function postUpdatePassword(Request $request) {
        $this->validate($request, [
            'password' => 'required|min:1|max:100',
            'new_password' => 'required|min:6|max:100|confirmed',
            'new_password_confirmation' => 'required|min:6|max:100',
        ]);

        $user = Auth::user();

        if(!Auth::validate(['email' => $user->email, 'password' => $request->input('new_password')]))
            return view('pages.users.user-settings')
                ->with('user', $user)
                ->with('message', 'danger|Please enter your current password correctly.');

        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        return view('pages.users.user-settings')
            ->with('user', $user)
            ->with('message', 'success|Password updated successfully!');
    }
}
