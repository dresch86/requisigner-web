<?php

namespace App\Models;

use App\Models\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Version extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'versions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'semver',
        'checksum',
        'contributor',
        'is_head'
    ];

    /**
     * Get the template details.
     */
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }
}
