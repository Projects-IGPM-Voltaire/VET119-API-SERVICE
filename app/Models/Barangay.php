<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barangay extends Model
{
    use HasFactory;

    protected $table = 'barangays';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $guarded = [];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }
}
