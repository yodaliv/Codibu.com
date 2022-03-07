<?php

namespace App\Admin\Actions\Site;

use App\Models\Site;
use App\Services\CloudFormationService;
use Encore\Admin\Actions\Response;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Terminate extends RowAction
{
    public $name = 'Terminate';

    public function handle(Model $model): Response
    {
        (new CloudFormationService())->terminate($model->id);
        return $this->response()->success('Success! This site is Terminated')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you want to Terminated this site?');
    }
}
