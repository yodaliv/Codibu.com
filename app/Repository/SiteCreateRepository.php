<?php

namespace App\Repository;

use App\Models\Site;

class SiteCreateRepository
{
    /**
     * create site form session data
     *
     * @return Site
     */
    public function createSite()
    {
        if (session()->exists('site_create_array')) {
            $site = new Site();
            $site->fill(session()->get('site_create_array'));
            $site->save();
            return $site;
        } else {
            return redirect()->back()->with('error', 'Site data not found! please try again');
        }
    }
}
