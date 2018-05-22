<?php

namespace Asaas\Builders;

use Asaas\Asaas;
use Asaas\Curl;
use Asaas\Subscription;
use Asaas\Exception\AsaasException;

class AsaasSubscriptionBuilder
{
    private $asaas;
    private $subscription;

    /**
     * AsaasBuilder constructor.
     * @param $asaas
     */
    public function __construct(Asaas $asaas, Subscription $subscription)
    {
        $this->asaas = $asaas;
        $this->subscription = $subscription;
    }

    public function find($subscriptionId)
    {
        if (is_null($subscriptionId)) {
            throw new \Exception('Informe o Id da assinatura no Asaas.');
        }

        try {
            $url = $this->asaas->getUrl() . "/subscriptions/$subscriptionId";
            $headers = $this->asaas->getHeaders();

            $data = Curl::get($url, $headers);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            return $this->subscription->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function all(array $filter=[])
    {
        try {
            $url = $this->asaas->getUrl() . "/subscriptions";
            if (!empty($filter)) {
                $url .= '?' . http_build_query($filter,'','&');
            }
            $headers = $this->asaas->getHeaders();

            $data = Curl::get($url, $headers);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            $paginator = [
                'hasMore' => $data['hasMore'],
                'totalCount' => $data['totalCount'],
                'limit' => $data['limit'],
                'offset' => $data['offset']
            ];

            $subscriptions = [];
            foreach ($data['data'] as $item) {
                $subscriptions[] = (clone $this->subscription)->fill($item);
            }

            return [
                'data' => $subscriptions,
                'paginator' => $paginator
            ];
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function create(array $data)
    {
        if (!isset($data['customer']) || empty($data['customer'])) {
            throw new \Exception('Campo \'customer\' obrigatório');
        }

        if (!isset($data['billingType']) || empty($data['billingType'])) {
            throw new \Exception('Campo \'billingType\' obrigatório');
        }

        if (!isset($data['value']) || empty($data['value'])) {
            throw new \Exception('Campo \'value\' obrigatório');
        }

        if (!isset($data['nextDueDate']) || empty($data['nextDueDate'])) {
            throw new \Exception('Campo \'nextDueDate\' obrigatório');
        }

        if (!isset($data['cycle']) || empty($data['cycle'])) {
            throw new \Exception('Campo \'cycle\' obrigatório');
        }

        try {
            $url = $this->asaas->getUrl() . '/subscriptions';
            $headers = $this->asaas->getHeaders();

            $data = Curl::post($url, $data, $headers);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            return $this->subscription->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function update(Subscription $subscription)
    {
        try {
            $url = $this->asaas->getUrl() . '/subscriptions/' . $subscription->id;
            $headers = $this->asaas->getHeaders();

            $data = Curl::post($url, $subscription->toArray(), $headers);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            return $subscription->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function delete(Subscription $subscription)
    {
        try {
            $url = $this->asaas->getUrl() . '/subscriptions/' . $subscription->id;
            $headers = $this->asaas->getHeaders();

            $data = Curl::delete($url, $headers);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            return $data['deleted'];
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}