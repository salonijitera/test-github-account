<?php

namespace App\Supports\Facades;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StorageSupport
{
    private $type;

    public function __construct($type = null)
    {
        $this->type = empty($type) ? config('filesystems.default') : $type;
    }

    /**
     * Upload a file to services
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $type
     * @return array|null
     */
    public function store(UploadedFile $file, $type = null): array
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = $file->getClientOriginalName();

        $filename = md5($originalName.microtime()).'.'.$extension;

        Storage::disk($type)->put($filename, $file->getContent());

        return [
            'original' => $originalName,
            'type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'filename' => $filename,
            'service' => $type ?? $this->type,
        ];
    }

    /**
     * Upload mutiple file to services
     *
     * @param  \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]  $file
     * @param  string  $type
     * @return array|null
     */
    public function files($input, $type = null): array
    {
        $files = is_array($input) ? $input : [$input];
        $results = array_map(function ($file) use ($type) {
            return $this->store($file, $type ?? $this->type);
        }, $files);

        return is_array($input) ? $results : $results[0];
    }

    public function remove(string|array $paths, $type = null)
    {
        return Storage::disk($type ?? $this->type)->delete($paths);
    }

    public function get(string $path, $type = null)
    {
        return match ($type ?? $this->type) {
            's3' => Storage::temporaryUrl($path, now()->addMinutes(15)),
            'public' => Storage::url($path),
            default => null
        };
    }
}
