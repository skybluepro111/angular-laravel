<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use File;
use Image;
use Log;
use Auth;
use Html;
use Symfony\Component\HttpFoundation\Request;

class ManageUserController extends Controller
{
    public function __construct() {
        if(Auth::user()->type == 0) return redirect()->to('dashboard');
    }

    public function getAddEditUser($userId = null)
    {
        $user = User::whereId($userId)->first();
        return view('pages.admin.add-edit-user', compact('user'));
    }

    public function postSaveUser(Request $request, $userId = null)
    {
        if(Auth::user()->type != 1)
        return view('pages.admin.add-edit-user')
            ->with('message', 'danger|Must be an administrator to edit users.');

        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'password' => 'min:6|max:100',
            'image' => 'image',
        ]);

        if($userId) {
            $user = User::find($userId);

            if (!$user)
                return view('pages.admin.add-edit-user')
                    ->with('message', 'danger|The user you are trying to edit does not exist.');
        }
        else {
            $emailExists = User::whereEmail($request->get('email'))->first();
            if($emailExists) {
                return view('pages.admin.add-edit-user')
                    ->with('message', 'danger|A user already exists with this email address.');
            }

            $user = new User();
        }

        if($request->file('image') && $request->file('image')->isValid()) {
            $filename = str_random(10) .  '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->resize(47,47)->save(public_path() . '/user_avatars/' . $filename);
            $user->image = 'http://' . config('custom.app-domain') . '/user_avatars/' . $filename;
        }

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->type = 0;
        $user->save();

        return view('pages.admin.add-edit-user')
            ->with('user', $user)
            ->with('message', 'success|User updated successfully');
    }

    public function getUserList()
    {
        return view('pages.admin.user-list')
            ->with('users', User::paginate(30));
    }
}


