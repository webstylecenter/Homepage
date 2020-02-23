<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedItem extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'feed_item';

    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    /**
     * @var string $guid
     */
    protected $guid;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var bool $pinned
     */
    protected $pinned;

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class, 'feed_id');
    }

    public function guid(): string
    {
        return $this->guid;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function pinned(): bool
    {
        return $this->pinned;
    }
}
