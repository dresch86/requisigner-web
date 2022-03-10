<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    /**
     * The setting fields that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'setting',
        'value'
    ];
}
