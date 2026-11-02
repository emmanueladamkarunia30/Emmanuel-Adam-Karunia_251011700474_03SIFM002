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
}