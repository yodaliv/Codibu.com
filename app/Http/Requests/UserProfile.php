<?php

namespace App\Http\Requests;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {

        if(!empty($request->current_password) || !empty($request->new_password))
        {
            if (auth()->user()->password){
                $rules['current_password'] = ['bail | required', new MatchOldPassword];
            }
            $rules['new_password'] = 'bail | required';
            $rules['new_confirm_password'] = 'bail | same:new_password';
        }
        $rules['name'] = 'bail | required|min:3';
        $rules['email'] = 'bail | required|email|unique:users,email,'.auth()->id().',id';
        $rules['phone'] = '';
        $rules['street_address'] = '';
        $rules['city'] = '';
        $rules['state'] = '';
        $rules['zip'] = '';

        return $rules;
    }
}
