<?php

namespace App\Models;

use App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signee extends Model
{
    use HasFactory;

    protected $table = 'signees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_id',
        'user_id',
        'placeholder_id',
        'signed_on'
    ];

    /**
     * Get the document that requires a signature from this signee.
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}