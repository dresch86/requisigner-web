<?php

namespace App\Models;

use App\Models\Template;
use App\Models\Placeholder;

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
        'is_head',
        'enforce_sig_order'
    ];

    /**
     * Get the template details.
     */
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * Get the signature placeholders for this version.
     */
    public function placeholders()
    {
        return $this->hasMany(Placeholder::class, 'version_id')->orderBy('order');;
    }
}
