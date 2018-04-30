<?php

namespace Asaas;

use Asaas\Builders\AsaasChargeBuilder;

class Charge extends Model
{
    private function __construct(array $data=[])
    {
        $this->attributes = [];
        $this->fill($data);
    }

    public static function asaas(Asaas $asaas)
    {
        return new AsaasChargeBuilder($asaas, new Charge());
    }
}