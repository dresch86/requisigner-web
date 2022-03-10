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
        'owner_user',
        'owner_group',
        'group_read',
        'group_edit',
        'world_read',
        'world_edit',
        'description',
        'metatags'
    ];

    /**
     * Get the group that owns this template.
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'owner_group');
    }
}
