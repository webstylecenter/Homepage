<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'feed';

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
     * @var string $url
     */
    protected $url;

    /**
     * @var string $color
     */
    protected $color;

    public function name(): string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function color(): string
    {
        return $this->color;
    }
}
