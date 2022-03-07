<?php


namespace App\Services;


use Illuminate\Support\Facades\Http;
use mysql_xdevapi\Exception;

class CpanelApi
{
    public function createEmail($user)
    {
        try {
            Http::withHeaders([
                'Authorization' => 'cpanel '. config('services.cpanel.user') .':'.config('services.cpanel.token')
            ])->post(config('services.cpanel.host').':'.config('services.cpanel.port').'/execute/Email/add_pop', [
                'email'       =>$user->id,
                'password'    =>'secretMailPass'.$user->id
            ]);
        } catch (Exception $e){
            return $e;
        }
    }
}
