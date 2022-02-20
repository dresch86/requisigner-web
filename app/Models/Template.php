<?php

namespace App\Models;

use App\Models\User;
use App\Models\Version;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'filename',
        'head_version',
        'owner',
        'group_read',
        'group_edit',
        'child_read',
        'child_edit',
        'description',
        'metatags'
    ];
}
