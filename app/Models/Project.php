<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'kategori',
        'description',
        'teknologi',
        'image',
        'status',
    ];

    /**
     * URL gambar:
     * - upload admin  -> storage/app/public/projects/xxx.jpg (URL: /storage/projects/xxx.jpg)
     * - data seeder   -> public/images/project1.jpg          (URL: /images/project1.jpg)
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return 'data:image/svg+xml;utf8,' . rawurlencode(
                '<svg xmlns="http://www.w3.org/2000/svg" width="96" height="76"><rect width="100%" height="100%" fill="#e9ecef"/>'
                . '<text x="50%" y="50%" font-size="10" fill="#6c757d" text-anchor="middle" dy=".3em">No Image</text></svg>'
            );
        }

        if (str_starts_with($this->image, 'projects/')) {
            return asset('storage/' . $this->image);
        }

        return asset('images/' . $this->image);
    }

    /**
     * Gambar untuk PDF (dompdf): diubah ke base64 dari file lokal,
     * karena dompdf tidak bisa memuat URL asset() dengan andal.
     */
    public function getPdfImageAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        $path = str_starts_with($this->image, 'projects/')
            ? storage_path('app/public/' . $this->image)
            : public_path('images/' . $this->image);

        if (! is_file($path)) {
            return null;
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}