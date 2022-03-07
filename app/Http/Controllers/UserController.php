<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfile;
use App\UserInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
        $user = Auth::user();

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserProfile  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserProfile $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->new_password){
            $user->password = Hash::make($request->new_password);
        }
        $user->{'2fa'} = $request->{'2fa'} =="on" ? 1 : 0;
        if ($user->save()){
            $info = UserInfo::where('user_id',$user->id)->exists() ? UserInfo::firstWhere('user_id',$user->id) : new UserInfo;
            $info->user_id = $user->id;
            $info->phone = $request->phone;
            $info->street_address = $request->street_address;
            $info->city = $request->city;
            $info->state = $request->state;
            $info->zip = $request->zip;
            if ($info->save()){
                return redirect()->back()->with('success', 'Your information has been updated!');
            }
        }
    }

}
