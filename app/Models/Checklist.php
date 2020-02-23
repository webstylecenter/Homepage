<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checklist extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'checklist_item';

    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    /**
     * @var string $item
     */
    protected $item;

    /**
     * @var boolean $checked
     */
    protected $checked;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item(): string
    {
        return $this->item;
    }

    public function checked(): bool
    {
        return $this->checked;
    }
}
