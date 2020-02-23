<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends AbstractModel
{
    /**
     * @var string $table
     */
    protected $table = 'notes';

    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $content
     */
    protected $content;

    /**
     * @var int $position
     */
    protected $position;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function name(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function position(): int
    {
        return $this->getPosition();
    }
}
