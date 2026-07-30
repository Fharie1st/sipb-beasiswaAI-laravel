<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prodi',
        'ipk',
        'sks',
        'penghasilan',
        'tanggungan',
        'organisasi',
        'prediction',
        'confidence',
        'accuracy',
    ];

    protected $casts = [
        'prediction' => 'boolean',
        'ipk'        => 'float',
        'confidence' => 'float',
        'accuracy'   => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}