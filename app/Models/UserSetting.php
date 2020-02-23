<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends AbstractModel
{
    /**
     * @var string $table
     */
    protected $table = 'user_settings';

    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    /**
     * @var string $setting
     */
    protected $setting;

    /**
     * @var string $value
     */
    protected $value;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setting(): string
    {
        return $this->setting;
    }

    public function vlaue(): string
    {
        return $this->value;
    }
}
