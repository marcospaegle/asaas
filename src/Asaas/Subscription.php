<?php

namespace Asaas;

class Subscription extends Model
{
    private function __construct(array $data=[])
    {
        $this->attributes = [];
        $this->fill($data);
    }
}