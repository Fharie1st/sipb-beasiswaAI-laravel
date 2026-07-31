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
    'nama',
    'prodi',
    'ipk',
    'sks',
    'tanggungan',
    'kehadiran',
    'prestasi',
    'organisasi',
    'penghasilan',
    'semester',
    'hasil',
    'confidence',
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
