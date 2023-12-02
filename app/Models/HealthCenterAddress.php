<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HealthCenterAddress extends Model
{
    use HasFactory;

    protected $table = 'health_center_addresses';
    protected $guarded = [];

    public function barangay(): HasOne
    {
        return $this->hasOne(Barangay::class, 'code', 'barangay_code');
    }
}
