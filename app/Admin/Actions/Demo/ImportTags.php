<?php

namespace App\Admin\Actions\Demo;

use Encore\Admin\Actions\Action;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Demo;
use Illuminate\Http\Request;

class ImportTags extends Action
{
    public    $name     = 'Import tags';
    protected $selector = '.import-post';

    public function handle(Request $request)
    {
        $file = $request->file('file');
        $csv  = array_map('str_getcsv', file($file));
        /* foreach ($csv as $row){
             $id = $row[0];
             if($id == 'Id') continue;

             $tags = $row[2];
             Demo::find($id)->update(['tags' => $tags]);
         }*/
        foreach ($csv as $row) {
            $id = (int)$row[0];
            if ($id > 0) {
                $find_demo_data = Demo::find($id);
                if ($find_demo_data){
                    $tags           = $row[2];
                    $find_demo_data->update(['tags' => $tags]);
                }

            }
        }

        return $this->response()->success('Imported successfully...')->refresh();
    }


    public function form()
    {
        $this->file('file', 'CSV File');
    }


    public function html()
    {
        return '<a class="btn btn-sm btn-default import-post"><i class="fa fa-upload"></i> &nbsp; Import tags</a>';
    }


}
