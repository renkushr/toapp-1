<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public static function aboutUs(): self
    {
        return static::query()->firstOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'About Us',
                'body' => '<p>Welcome to To-App.</p>',
                'is_published' => true,
            ]
        );
    }
}
