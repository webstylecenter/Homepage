<?php

namespace Entity;

/**
 * Class Feed
 * @package Entity
 */
class Feed
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @param $id
     * @param $name
     * @param $url
     * @param $color
     */
    public function __construct($id, $name, $url, $color)
    {
        $this->id           = $id;
        $this->name         = $name;
        $this->url          = $url;
        $this->color        = $color;
        $this->created      = new \DateTime;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle( $title ) {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl( $url ) {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor( $color ) {
        $this->color = $color;
    }

    /**
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated( $created ) {
        $this->created = $created;
    }
}
