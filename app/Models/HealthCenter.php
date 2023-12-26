<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

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

    public function members(): HasMany
    {
        return $this->hasMany(
            HealthCenterMember::class,
            'health_center_id',
            'id'
        );
    }

    public function operation_hour(): HasOne
    {
        return $this->hasOne(OperationHour::class, 'health_center_id', 'id');
    }
}
