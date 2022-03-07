<?php


namespace App\Services;


use App\Models\Demo;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeFilter
{
    /**
     * filtering themes in create website page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filteringThemes(Request $request)
    {

        $theme_type = $request->input('theme_type');
        $keyword    = $request->input('keyword');

        if ($theme_type == 'plain') {
            //TODO:: when theme type plan then plan section optimizing
            $themes = $this->filteringThemePlain($request, $keyword);
        } else {
            $themes = $this->filteringThemeDemo($request, $keyword);
        }
        return $themes->paginate(15);
    }

    /**
     *  filtering themes plain in create website page
     *
     * @param $request
     * @param $keyword
     * @return mixed
     */
    public function filteringThemePlain($request, $keyword)
    {
        $site_type   = $request->input('site_type');
        $demo_name   = $request->input('demo_name');
        $editor_type = $request->input('etype');

        if ($site_type) {
            if (is_array($site_type)) {
                $themes = Theme::whereIn('site_types_id', $site_type);
            } else {
                $themes = Theme::where('site_types_id', (int)$site_type);
            }
            $themes = $themes->where('folder_uri', '!=', '');
        } else {
            $themes = Theme::where('folder_uri', '!=', '');
        }
        if ($demo_name) {
            if (is_array($demo_name)) {
                $themes = Demo::orWhereIn('id', $demo_name);
            } else {
                $themes = Demo::orWhere('id', (int)$demo_name);
            }
        }
        if ($editor_type) {
            if (is_array($editor_type)) {
                $themes->orWhere(function ($query) use ($editor_type) {
                    $query->WhereHas('pageEditor', function ($query) use ($editor_type) {
                        $query->WhereIn('page_editors.id', $editor_type);
                    });
                });
            } else {
                $themes->orWhere(function ($query) use ($editor_type) {
                    $query->orWhereHas('pageEditor', function ($query) use ($editor_type) {
                        $query->orWhere('page_editors.id', (int)$editor_type);
                    });
                });
            }
        }
        $themes->with('versions', 'pageEditor')->whereHas('versions', function ($query) {
            $query->limit(1);
        });
        if ($keyword && !empty($keyword)) {
            $themes->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
                $query->orWhere('developer', 'like', '%' . $keyword . '%');
                $query->orWhere('description', 'REGEXP', $keyword);
                $this->getWhereLikeSearching($query, $keyword);
                $query->orWhere('tags', 'like', '%' . $keyword . '%');
            });
        }
        return $themes;
    }

    /**
     * get where like searching
     *
     * @param $query
     * @param $keyword
     */
    private function getWhereLikeSearching($query, $keyword)
    {
        return $query->orWhereHas('pageEditor', function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%');
        });
    }

    /**
     *  filtering themes demo in create website page
     *
     * @param $request
     * @param $keyword
     * @return mixed
     */
    public function filteringThemeDemo($request, $keyword)
    {
        $themes = Demo::with('network', 'network.theme', 'network.theme.pageEditor', 'site_types');
        if ($request->filled('site_type')) {
            $themes->whereHas('site_types', function ($q) use ($request) {
                $q->whereIn('site_type_id', json_decode($request->site_type));
            });
        }
        if ($request->filled('keyword')) {
            $themes->where(function ($query) use ($keyword) {
                $query->orWhereHas('network', function ($query) use ($keyword) {
                    $query->whereHas('theme', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('developer', 'like', '%' . $keyword . '%');
                        $this->getWhereLikeSearching($query, $keyword);
                    });
                })->orWhere('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('tags', 'like', '%' . $keyword . '%')
                    ->orWhereRaw('find_in_set("' . $keyword . '", tags)');
            });
        }
        if ($request->filled('demo_name')) {
            $themes->WhereIn('id', json_decode($request->demo_name));
        }
        if ($request->filled('etype')) {
            $themes->whereHas('network.theme.pageEditor', function ($query) use ($request) {
                $query->whereIn('page_editors.id', json_decode($request->etype));
            });
        }
        return $themes;
    }
}
