<?php

namespace Asaas;

/*
 * 200 OK - Sua requisição foi bem sucedida.
 * 400 Bad Request - Algum parâmetro obrigatório não foi enviado ou é inválido. Neste caso a própria resposta indicará qual é o problema.
 * 401 Unauthorized - Não foi enviada API Key ou ela é inválida.
 * 404 Not Found - O endpoint ou o objeto solicitado não existe.
 * Exemplo de resposta para HTTP 400
 * {
 *  "errors":[
 *    {
 *      "code":"invalid_value",
 *      "description":"O campo value deve ser informado"
 *    },
 *    {
 *      "code":"invalid_dueDate",
 *      "description":"A data de vencimento não pode ser inferior à hoje"
 *    }
 *  ]
 * }
 * 500 Internal Server Error - Algo deu errado no servidor do ASAAS
 *
 * Todos os endpoints da API do ASAAS recebem e responde em JSON.
 *
 * Listas e paginação
 * Todos os endpoints da API que retornam uma lista de itens são paginados.
 * Para navegar entre as páginas há 3 parâmetros:
 * limit: quantidade de objetos por página
 * offset: posição do objeto a partir do qual a página deve ser carregada. O objeto inicial possui a posição 0.
 * totalCount: quantia total de itens para os filtros informados
 *
 * https://www.asaas.com/api/v3
 * https://sandbox.asaas.com/api/v3
 *
 */

class Asaas
{
    const URL_PROD = 'https://www.asaas.com/api/v3';
    const URL_SANDBOX = 'https://sandbox.asaas.com/api/v3';

    const STATUS_ENDING = 'ENDING'; // Aguardando pagamento
    const STATUS_RECEIVED = 'RECEIVED'; // Recebida (saldo já creditado na conta)
    const STATUS_CONFIRMED = 'CONFIRMED'; // Pagamento confirmado (saldo ainda não creditado)
    const STATUS_OVERDUE = 'OVERDUE'; // Vencida
    const STATUS_REFUNDED = 'REFUNDED'; // Estornada
    const STATUS_RECEIVED_IN_CASH = 'RECEIVED_IN_CASH'; //Recebida em dinheiro (não gera saldo na conta)
    const STATUS_REFUND_REQUESTED = 'REFUND_REQUESTED'; // Estorno Solicitado

    const BILLING_TYPE_BOLETO = 'BOLETO'; // Boleto Bancário
    const BILLING_TYPE_CREDIT_CARD = 'CREDIT_CARD'; // Cartão de Crédito
    const BILLING_TYPE_UNDEFINED = 'UNDEFINED'; // Perguntar ao Cliente

    const SUBSCRIPTION_CYCLE_WEEKLY = 'WEEKLY'; // Semanal
    const SUBSCRIPTION_CYCLE_BIWEEKLY = 'BIWEEKLY'; // Quinzenal (2 semanas)
    const SUBSCRIPTION_CYCLE_MONTHLY = 'MONTHLY'; // Mensal
    const SUBSCRIPTION_CYCLE_QUARTERLY = 'QUARTERLY'; // Trimestral
    const SUBSCRIPTION_CYCLE_SEMIANNUALLY = 'SEMIANNUALLY'; // Semestral
    const SUBSCRIPTION_CYCLE_YEARLY = 'YEARLY'; // Anual

    private $access_token;
    private $url;
    private $env;

    public function __construct($access_token=null, $env=null)
    {
        $this->access_token = $access_token;
        if (is_null($access_token)) {
            if (getenv('ASASS_TOKEN')) {
                $this->access_token = getenv('ASASS_TOKEN');
            }

            if (is_null($this->access_token)) {
                throw new \Exception('Token não informado.');
            }
        }

        $this->env = $env;
        if (is_null($env)) {
            if (getenv('ENV')) {
                $this->env = strtolower(getenv('ENV'));
            }

            if (getenv('ENVIRONMENT')) {
                $this->env = strtolower(getenv('ENVIRONMENT'));
            }

            if (is_null($this->env)) {
                $this->env = 'prod';
            }
        }
    }

    public function getHeaders() {
        return [
            'Content-Type:application/json',
            "access_token:$this->access_token",
        ];
    }

    public function getEnv() {
        return $this->env;
    }

    public function getUrl() {
        if ($this->url === null) {
            $this->url = self::URL_PROD;
            if ($this->env === 'sandbox') {
                $this->url = self::URL_SANDBOX;
            }
        }

        return $this->url;
    }
}