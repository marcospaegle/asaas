<?php

namespace Asaas;

class Curl
{
    public static function get($url, array $headers=[])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
        }
        curl_close($ch);

        return json_decode($result, true);
    }

    public static function post($url, array $data, array $headers=[])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
        }
        curl_close($ch);

        return json_decode($result, true);
    }

    public static function delete($url, array $headers=[])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Request Error: ' . curl_errno($ch) . ' ' . curl_error($ch));
        }
        curl_close($ch);

        return json_decode($result, true);
    }
}