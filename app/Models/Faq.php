<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use GuzzleHttp\Client;


class Faq extends Model implements Sortable
{
    //
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];


    public static function tutorials() {

        $client = new Client();

        /*$response = $client->request('GET', 'https://knowledge-base.codibu.com/wp-json/wp/v2/posts?categories=513&_embed');

        if ($response->getBody()) {
            $tutorials = $response->getBody();
            return json_decode($tutorials);
        }*/

        return false;

    }

}
