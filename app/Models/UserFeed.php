<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFeed extends AbstractModel
{
    /**
     * @var string $table
     */
    protected $table = 'user_feed';

    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    /**
     * @var string $icon
     */
    protected $icon;

    /**
     * @var string $color
     */
    protected $color;

    /**
     * @var bool $autoPin
     */
    protected $autoPin;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class, 'feed_id');
    }

    public function icon(): ?string
    {
        return $this->icon;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function autoPin(): bool
    {
        return $this->autoPin;
    }
}
