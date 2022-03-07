<?php

namespace App\Http\Controllers;

use GoDaddy;
use App\Services\GoDaddyService;
use GoDaddyDomainsClient\Api\VdomainsApi;
use GoDaddyDomainsClient\ApiClient;
use GoDaddyDomainsClient\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{

    public function index()
    {
        $user    = Auth::user();
        $domains = $user->sites()->select('domain')->orderBy('id', 'desc')->get();
        $goDaddyDomains = [];
        foreach ($domains as $key =>$domain) {
            try {
                (new GoDaddyService())->domainDetails($domain->domain);
                $goDaddyDomains[$key] = $domain;
            } catch (\Exception $exception){
                //
            }
        }
        return view('domains.index')->with('domains', $goDaddyDomains);
    }

    public function edit($domain)
    {

        $domain = (new GoDaddyService())->domainDetails($domain);

        $domain                   = collect($domain)->first();
        $domain['created_at']     = formatted_date(collect($domain['created_at'])->first());
        $domain['expires']        = formatted_date(collect($domain['expires'])->first());
        $domain['renew_deadline'] = formatted_date(collect($domain['renew_deadline'])->first());
        return view('domains.edit')->with('domain', $domain);
    }

    public function update(Request $request, $domain)
    {
        $body = [
            "locked"    => $request->locked == "on" ? true : false,
            "renewAuto" => $request->renew_auto == "on" ? true : false
        ];
        (new GoDaddyService())->domainUpdate($domain, $body);
        session()->flash('status', 'Domain Successfully Updated.You will See the change 4 or 5 minutes later');
        return redirect()->route('domains.edit', $domain);
    }

    public function dns($domain)
    {

        $domain = collect((new GoDaddyService())->domainDetails($domain))->first();

        return view('domains.dns')->with('domain', $domain);
    }

    public function check_domain(Request $request)
    {
        $this->validate($request, [
            'query' => 'required',
        ], [
            'required' => 'The domain field is required.'
        ]);
        if ($request->get('query')) {
            $query = $request->get('query');
            $query = str_replace(['http://', 'https://'], '', $query);
            $query = explode('/', $query);
            $query = $query[0];

            $gd = GoDaddy::suggest($query);
            $result = [];
            foreach ($gd as $domain) {
                $result[$domain['domain']] = true;
            }

            if (strpos($query, '.') !== false && !array_key_exists($query, $result)) {
                $result = array_merge(array($query => false), $result);
            }

            return json_encode(compact('result'));
        } else {
            abort(400);
        }

    }

    public function get_domain_price(Request $request)
    {
        $domain = $request->get('domain');
        $price = (new GoDaddyService())->domainPriceCheck($domain);
        return json_encode($price);
    }
}
