<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Theme;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ThemeVersion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_at',
    ];
    //
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function s3_url() {
        $path = "themes/{$this->theme->slug}__{$this->version}.zip";
        return Storage::disk('s3')->temporaryUrl($path, Carbon::now()->addMinutes(50));
    }
}
