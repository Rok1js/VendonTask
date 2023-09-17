<?php

namespace Vendon\core;

abstract class Model
{
    /**
     * @param $data
     * @return void
     */
    //vvv Loading model data inside Model variables
    public function loadData($data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}