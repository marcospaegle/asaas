<?php

namespace Asaas\Builders;

use Asaas\Asaas;
use Asaas\Customer;
use Asaas\Exception\AsaasException;

class AsaasCustomerBuilder
{
    private $asaas;
    private $customer;

    /**
     * AsaasBuilder constructor.
     * @param $asaas
     */
    public function __construct(Asaas $asaas, Customer $customer)
    {
        $this->asaas = $asaas;
        $this->customer = $customer;
    }

    public function find($customerId)
    {
        if (is_null($customerId)) {
            throw new \Exception('Informe o Id do cliente no Asaas.');
        }

        try {
            $url = $this->asaas->getUrl() . "/customers/$customerId";
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

            return $this->customer->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function all(array $filter=[])
    {
        try {
            $url = $this->asaas->getUrl() . "/customers";
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

            $customers = [];
            foreach ($data['data'] as $item) {
                $customers[] = (clone $this->customer)->fill($item);
            }

            return [
                'data' => $customers,
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
        if (!isset($data['name']) || empty($data['name'])) {
            throw new \Exception('Campo \'name\' obrigatório');
        }

        // NA API DIZ QUE CPF/CNPJ É OBRIGATÓRIO MAIS SE VC CONSEGUE CRIAR UM CLIENTE SEM CPF/CNPJ.
        // OBS: SOMENTE PARA BOLETOS COM VALOR IGUAL OU MENOR QUE R$ 400,00
        /*if (!isset($data['cpfCnpj']) || empty($data['cpfCnpj'])) {
            throw new \Exception('Campo \'cpfCnpj\' obrigatório');
        }*/

        try {
            $url = $this->asaas->getUrl() . '/customers';
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

            return $this->customer->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function update(Customer $customer)
    {
        try {
            $url = $this->asaas->getUrl() . '/customers/' . $customer->id;
            $headers = $this->asaas->getHeaders();

            $data = $customer->toArray();

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

            return $customer->fill($data);
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function delete(Customer $customer)
    {
        try {
            $url = $this->asaas->getUrl() . '/customers/' . $customer->id;
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
}