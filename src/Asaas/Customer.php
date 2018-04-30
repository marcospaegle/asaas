<?php

namespace Asaas;

use Asaas\Builders\AsaasCustomerBuilder;

class Customer extends Model
{
    private function __construct(array $data=[])
    {
        $this->attributes = [];
        $this->fill($data);
    }

    public static function asaas(Asaas $asaas)
    {
        return new AsaasCustomerBuilder($asaas, new Customer());
    }
}