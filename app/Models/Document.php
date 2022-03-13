<?php

namespace App\Models;

use App\Models\User;
use App\Models\Signee;
use App\Models\Version;
use App\Models\Template;
use App\Models\Placeholder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version_id',
        'requestor',
        'title',
        'checksum',
        'metatags',
        'complete_by'
    ];

    /**
     * Get the version for this document.
     */
    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id');
    }

    /**
     * Get the signees for this document.
     */
    public function signees()
    {
        return $this->hasMany(Signee::class, 'document_id');
    }

    /**
     * Get the placeholders for this document.
     */
    public function placeholders()
    {
        return $this->hasManyThrough(Placeholder::class, Version::class, 'version_id', 'placeholder_id')
        ->orderBy('placeholders.order');
    }

    /**
     * Get the person requesting this document be signed.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'requestor');
    }
}
