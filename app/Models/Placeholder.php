<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Placeholder extends Model
{
    use HasFactory;

    protected $table = 'placeholders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'version_id',
        'pdf_name',
        'friendly_name',
        'order'
    ];

    /**
     * Get the template version this placeholder is registered to.
     */
    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id');
    }
}
