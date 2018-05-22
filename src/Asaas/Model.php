<?php

namespace Asaas;

abstract class Model
{
    protected $attributes;

    public function __get($name)
    {
        if (!array_key_exists($name, $this->attributes)) {
            return null;
        }

        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function toArray()
    {
        return $this->attributes;
    }
}