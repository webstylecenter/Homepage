<?php

namespace App\Services\Interfaces;

interface AbstractServiceInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function get(int $id);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function persist(int $id, array $data);
}
