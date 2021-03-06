<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Signature extends Model
{
    use HasFactory;

    protected $table = 'signatures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'common_name',
        'organizational_unit',
        'country',
        'visual_signature_cpts',
        'public_key'
    ];

    /**
     * Get the user this signature belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
