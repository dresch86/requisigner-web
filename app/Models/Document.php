<?php

namespace App\Models;

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
        'template_id',
        'version_id',
        'requestor',
        'require_signature_order',
        'metatags',
        'complete_by'
    ];
}
