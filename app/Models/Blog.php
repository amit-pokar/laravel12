<?php

namespace App\Models;

use App\Events\BlogCreated;
use App\Events\BlogDeleted;
use App\Events\BlogEdited;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'status',
        'image',
        'thumbnail',
    ];

    protected $dispatchesEvents = [
        'created' => BlogCreated::class,
        'updated' => BlogEdited::class,
        'deleted' => BlogDeleted::class,
    ];
}
