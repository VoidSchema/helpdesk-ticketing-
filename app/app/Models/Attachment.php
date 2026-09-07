<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'original_name',
        'filename',
        'mime_type',
        'size',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url('attachments/' . $this->filename);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIconAttribute(): string
    {
        if ($this->isImage()) {
            return 'image';
        }
        if (str_contains($this->mime_type, 'pdf')) {
            return 'document';
        }
        if (str_contains($this->mime_type, 'text') || str_contains($this->mime_type, 'log') || str_contains($this->mime_type, 'csv')) {
            return 'text';
        }
        return 'file';
    }

    public function deleteFile(): void
    {
        Storage::disk('public')->delete('attachments/' . $this->filename);
        $this->delete();
    }
}
