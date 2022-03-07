<?php


namespace App\Services;

use App\Models\Site;
use GoDaddy;
use GoDaddyDomainsClient\Api\VdomainsApi;
use GoDaddyDomainsClient\ApiClient;
use GoDaddyDomainsClient\Configuration;
use GoDaddyDomainsClient\Model\DNSRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoDaddyService
{
    /**
     * @var VdomainsApi
     */
    protected $api;

    /**
     * @var
     */
    protected $xShopperId;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $configuration = new Configuration();

        if (config('services.godaddy.testing') === true) {
            $configuration->setHost('https://api.ote-godaddy.com');
        }else {
            $configuration->setHost('https://api.godaddy.com');
        }
        $configuration->addDefaultHeader("Authorization", "sso-key " . config('services.godaddy.key') . ":" . config('services.godaddy.secret'));

        $apiClient = new ApiClient($configuration);
        $this->api = new VdomainsApi($apiClient);
    }

    public function domainPriceCheck($domain)
    {
            $availability = GoDaddy::available($domain);
            return $availability->getPrice() / pow(10,6);
    }

    public function purchaseDomain(Site $site)
    {

        if($site->domain_type === 'purchase_request') {
            $availability = GoDaddy::available($site->domain);

            $price = $availability->getPrice() / pow(10,6);
            if($availability->getAvailable() == true && $price < 10){
                try {
                    GoDaddy::purchase($site->domain, 1);
                    $this->deleteDnsRecords($site);
                } catch (\Throwable $th) {
                    //dd($th->getMessage());
                    return [ 'errors' => [ 'domain' => 'Domain is not available for purchase' ] ];
                }
            } else {
                return [ 'errors' => [ 'domain' => 'Please choose the domain which price is less then US$10' ] ];
            }
        }

    }

    public function domainDetails($domain)
    {
        return $this->api->getWithHttpInfo($domain);
    }

    public function domainUpdate($domain, $body)
    {
        return $this->api->updateWithHttpInfo($domain, json_encode($body));
    }

    public function dnsDetails($domain)
    {
        return $this->api->getWithHttpInfo($domain);
    }

    public function dnsRecords(Site $site)
    {
        $response = Http::withHeaders(["Authorization" => "sso-key " . config('services.godaddy.key') . ":" . config('services.godaddy.secret')])
            ->get('https://api.godaddy.com/v1/domains/'.$site->domain.'/records/A/@');

        return collect($response->json())->where('data', $site->server_ip)->first();
    }

    public function dnsRecordsCount($domain)
    {
        $response = Http::withHeaders(["Authorization" => "sso-key " . config('services.godaddy.key') . ":" . config('services.godaddy.secret')])
            ->get('https://api.godaddy.com/v1/domains/'.$domain.'/records/A/@');
        return count($response->json());
    }

    public function deleteDnsRecords(Site $site)
    {
        if ($this->dnsRecords($site)){
            return Http::withHeaders(["Authorization" => "sso-key " . config('services.godaddy.key') . ":" . config('services.godaddy.secret')])
                ->delete('https://api.godaddy.com/v1/domains/'.$site->domain.'/records/A/@');
        }
    }

    public function cancelPurchaseDomain($domain)
    {
        return $this->api->cancelWithHttpInfo($domain);
    }

    public function attachHostingIp($domain, $ip)
    {
        $dns = new DNSRecord([
            'type' => 'A',
            'name' => $domain,
            'data' => $ip,
        ]);
        return $this->api->recordAddWithHttpInfo($domain, [$dns]);
    }
}
