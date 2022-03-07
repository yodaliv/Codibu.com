<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Socialite;
use GuzzleHttp\Client;

class SocialLoginController extends Controller
{
    /**
     *  user login/signup using facebook it is redirect to facebook login page if user is not login in
     *
     * @param $provider
     * @return mixed
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * response of facebook/google login/signup from api and redirect user to specific home page if user registration
     */
    public function callback($provider)
    {
        try {
            $getInfo = Socialite::driver(str_replace( "_login",'',"$provider"))->user();
            $user    = $this->createUser($getInfo, str_replace( "_login",'',"$provider"));
            session()->flash('success', __('Login successful!'));
            auth()->login($user, true);
            return redirect()->to('/home');
        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('danger', __($e->getMessage()));
            return redirect('login');
        }
    }

    /**
     * response of social login/signup from api and add user social details into database then
     *
     * @param $getInfo
     * @param $provider
     * @return mixed
     */
    public function createUser($getInfo, $provider)
    {
        if ($provider == 'instagram') {
            $name = $email = $getInfo->username;
        } else {
            $name  = $getInfo->name;
            $email = $getInfo->email;
        }
        return User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'provider' => $provider, 'provider_id' => $getInfo->id]
        );
    }

    /**
     * Redirect To Instagram Provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToInstagramProvider()
    {
        $appId       = config('services.instagram.client_id');     // change this to your appId
        $redirectUri = config('services.instagram.redirect');      // change this to your redirectUri
        return redirect()->to("https://api.instagram.com/oauth/authorize?app_id={$appId}&redirect_uri={$redirectUri}&scope=user_profile&response_type=code");
    }

    /**
     * Call back to IG
     *
     */
    public function instagramProviderCallback(Request $request)
    {
        $code = $request->code;
        if (empty($code)) {
            return redirect()->route('home')->with('error', 'Failed to login with Instagram.');
        }

        $appId       = config('services.instagram.client_id');     // change this to your appId
        $secret      = config('services.instagram.client_secret'); // change this to your appId
        $redirectUri = config('services.instagram.redirect');      // change this to your redirectUri
        try {
            $client = new Client();
            // Get access token
            $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
                'form_params' => [
                    'app_id'       => $appId,
                    'app_secret'   => $secret,
                    'grant_type'   => 'authorization_code',
                    'redirect_uri' => $redirectUri,
                    'code'         => $code,
                ],
            ]);
            if ($response->getStatusCode() == 200) {
                try {
                    $content     = $response->getBody()->getContents();
                    $content     = json_decode($content);
                    $accessToken = $content->access_token;
                    // Get user info
                    $response =
                        $client->request('GET', "https://graph.instagram.com/me?fields=id,username,account_type&access_token={$accessToken}");
                    if ($code = $response->getStatusCode() == 200) {
                        $content = $response->getBody()->getContents();
                        $oAuth   = json_decode($content);
                        $user    = $this->createUser($oAuth, 'instagram');
                        if ($user) {
                            auth()->login($user, true);
                            return redirect()->to('/home');
                        } else {
                            return redirect('login');
                        }
                    }
                } catch (\Exception $e) {
                    return redirect('login');
                    session()->flash('danger', __('Oops! Login Failed!'));
                }
            }
        } catch (\Exception $e) {
            if ($response->getStatusCode() != 200) {
                session()->flash('danger', __('Unauthorized login to Instagram.'));
                return redirect('login');
            }
        }
    }
}
