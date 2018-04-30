<?php

namespace Asaas\Builders;

use Asaas\Asaas;
use Asaas\Charge;
use Asaas\Exception\AsaasException;

class AsaasChargeBuilder
{
    private $asaas;
    private $charge;

    /**
     * AsaasBuilder constructor.
     * @param $asaas
     */
    public function __construct(Asaas $asaas, Charge $charge)
    {
        $this->asaas = $asaas;
        $this->charge = $charge;
    }

    public function find($chargeId)
    {
        if (is_null($chargeId)) {
            throw new \Exception('Informe o Id da cobrança no Asaas.');
        }

        try {
            $url = $this->asaas->getUrl() . "/payments/$chargeId";
            $headers = $this->asaas->getHeaders();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($result, true);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            return $this->charge->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function all(array $filter=[])
    {
        try {
            $url = $this->asaas->getUrl() . "/payments";
            if (!empty($filter)) {
                $url .= '?' . http_build_query($filter,'','&');
            }
            $headers = $this->asaas->getHeaders();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($result, true);
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

            $charges = [];
            foreach ($data['data'] as $item) {
                $charges[] = (clone $this->charge)->fill($item);
            }

            return [
                'data' => $charges,
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

        if (!isset($data['dueDate']) || empty($data['dueDate'])) {
            throw new \Exception('Campo \'dueDate\' obrigatório');
        }

        if (!isset($data['remoteIp']) || empty($data['remoteIp'])) {
            throw new \Exception('Campo \'remoteIp\' obrigatório');
        }

        try {
            $url = $this->asaas->getUrl() . '/payments';
            $headers = $this->asaas->getHeaders();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($result, true);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            var_dump($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function update(Charge $charge)
    {
        try {
            $url = $this->asaas->getUrl() . '/payments/' . $charge->id;
            $headers = $this->asaas->getHeaders();

            $data = $charge->toArray();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($result, true);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            return $charge->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function delete(Charge $charge)
    {
        try {
            $url = $this->asaas->getUrl() . '/payments/' . $charge->id;
            $headers = $this->asaas->getHeaders();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($result, true);
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

    public function refund(Charge $charge)
    {
        // É possível estornar cobranças via cartão de crédito recebidas ou confirmadas.
        // O cancelamento pode levar até 10 dias úteis para aparecer na fatura de seu cliente.
        if ($charge->billingType != Asaas::BILLING_TYPE_CREDIT_CARD) {
            throw new \Exception('Só é possível estornar cobranças pagas com cartão de crédito.');
        }

        try {
            $url = $this->asaas->getUrl() . '/payments/' . $charge->id . '/refund';
            $headers = $this->asaas->getHeaders();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($result, true);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            if ($data['status'] != Asaas::STATUS_REFUNDED || $data['status'] != Asaas::STATUS_REFUND_REQUESTED) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function receiveInCash(array $data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            throw new \Exception('Campo \'id\' obrigatório');
        }

        if (!isset($data['paymentDate']) || empty($data['paymentDate'])) {
            throw new \Exception('Campo \'paymentDate\' obrigatório');
        }

        if (!isset($data['value']) || empty($data['value'])) {
            throw new \Exception('Campo \'value\' obrigatório');
        }

        if (!isset($data['notifyCustomer']) || empty($data['notifyCustomer'])) {
            throw new \Exception('Campo \'notifyCustomer\' obrigatório');
        }

        try {
            $url = $this->asaas->getUrl() . '/payments/' . $data['id'] . '/receiveInCash';
            $headers = $this->asaas->getHeaders();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($result, true);
            if (isset($data['errors'])) {
                $e = new AsaasException();
                $e->setErrors($data['errors']);

                throw $e;
            }

            if ($data['status'] != Asaas::STATUS_RECEIVED_IN_CASH) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}