<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
    ];

    /**
     * The author that belongs to the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
