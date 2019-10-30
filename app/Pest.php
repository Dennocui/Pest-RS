<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Pest extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'pests';

    protected $appends = [
        'pest_photo',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pest_name',
        'pest_desc',
        'created_at',
        'updated_at',
        'deleted_at',
        'category_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getPestPhotoAttribute()
    {
        $files = $this->getMedia('pest_photo');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
        });

        return $files;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
