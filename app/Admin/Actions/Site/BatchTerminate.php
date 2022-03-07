<?php

namespace App\Admin\Actions\Site;

use App\Models\Site;
use App\Services\CloudFormationService;
use Encore\Admin\Actions\BatchAction;
use Encore\Admin\Actions\Response;
use Illuminate\Database\Eloquent\Collection;

class BatchTerminate extends BatchAction
{
    public $name = 'Batch Terminate';

    /**
     * @param Collection $collection
     * @return Response
     */
    public function handle(Collection $collection): Response
    {
        foreach ($collection as $model) {
            (new CloudFormationService())->terminate($model->id);
        }

        return $this->response()->success('Success! All site are Terminated')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you want to Terminated all selected  site?');
    }
}
