<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class HealthCenter extends Model
{
    use HasFactory;

    protected $table = 'health_centers';
    protected $guarded = [];

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function address(): HasOne
    {
        return $this->hasOne(
            HealthCenterAddress::class,
            'health_center_id',
            'id'
        );
    }
}
