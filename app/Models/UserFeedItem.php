<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFeedItem extends AbstractModel
{
    /**
     * @var string $table
     */
    protected $table = 'user_feed_item';

    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    /**
     * @var bool $viewed
     */
    protected $viewed;

    /**
     * @var bool $pinned
     */
    protected $pinned;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class, 'feed_id');
    }

    public function userFeed(): BelongsTo
    {
        return $this->belongsTo(UserFeed::class, 'user_feed_id');
    }

    public function viewed(): bool
    {
        return $this->viewed;
    }

    public function pinned(): bool
    {
        return $this->pinned;
    }
}
