<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use StorageSupport;

class StorageAttachment extends Model
{
    use HasFactory;

    protected $table = 'storage_attachments';

    protected $fillable = [
        'name',
        'original',
        'filename',
        'type',
        'metadata',
        'size',
        'checksum',
        'service',
    ];

    protected $hidden = ['storable_type', 'storable_id', 'filename', 'checksum', 'service', 'deleted'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return StorageSupport::get($this->filename);
    }
}
