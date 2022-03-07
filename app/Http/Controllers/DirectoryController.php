<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;
use App\Models\Plugin;
use Illuminate\Support\Facades\Auth;

class DirectoryController extends Controller
{
    /**
     * common where like searching for themes and plugins
     *
     * @param $query
     * @param $keyword
     * @return mixed
     */
    private function whereLikeSearching($query, $keyword)
    {
        return $query->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('developer', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'REGEXP', $keyword);
    }

    /**
     * Show the Themes list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function themes(Request $request)
    {
        $keyword = $request->input('keyword');
        $themes  = Theme::with('versions', 'pageEditor');
        $themes->whereHas('versions', function ($query) {
            $query->limit(1);
        });

        if ($request->filled('keyword')) {
            $themes->where(function ($query) use ($keyword) {
                $this->whereLikeSearching($query, $keyword);
                $query->orWhereHas('pageEditor', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            });
        }
        $lists = $themes->paginate(15);
        return view('directory.themes', compact('lists'));
    }

    /**
     * Show the Plugins list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function plugins(Request $request)
    {
        $keyword = $request->input('keyword');
        $query   = Plugin::with('versions')->whereHas('versions', function ($q) {
            $q->limit(1);
        });
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $this->whereLikeSearching($q, $keyword);
            });
        }
        $lists = $query->paginate(15);
        return view('directory.plugins', compact('lists'));
    }

    /**
     * download the themes and plugins
     *
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function download(Request $request)
    {
        $inputs = $request->all();
        $user   = Auth::user();
        $sites = auth()->user()->sites()->get();
        $stop_cnt = 0;
        $start_cnt = 0;
        foreach ($sites as $site) {
            if($site->status == 'stopped' || $site->status == 'deleted'){
                $stop_cnt++;
            }else{
                $start_cnt++;
            }
        }
        if(count($sites) > 0 && $stop_cnt == 0){
            if (isset($inputs['item_type'], $inputs['item_id'], $inputs['item_version']) && $user->downloads < auth()->user()->plans->sum('download_limit')) {
                $item_type    = $inputs['item_type'];
                $item_id      = (int)$inputs['item_id'];
                $item_version = (float)$inputs['item_version'];

                $itemClass = "App\Models\\" . ucfirst($item_type) . "Version";
                $item      = false;
                try {
                    $item = $itemClass::find($item_version);
                } catch (\Throwable $th) {
                    return ['error' => $th->getMessage()];
                }

                if (!$item) {
                    return redirect()->back()->with('error', 'Item couldn\'t be found.');
                }

                $user->downloads++;
                $user->save();
                return redirect()->to($item->s3_url());
            } else {
                return redirect()->back()->with('error', 'You have exceded your daily download limit.');
            }
        } else {
            return redirect()->back()->with('error', 'You did not create any website.');
        } 
    }
}
