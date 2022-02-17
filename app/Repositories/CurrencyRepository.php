<?php

namespace App\Repositories;

use App\Constants\CurrencyConstant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class CurrencyRepository
{
    /**
     * 取得幣別列表 中英轉換
     * @return string[]
     */
    public function getCurrencyList()
    {
        return CurrencyConstant::CURRENCY;
    }

    /**
     * 取得美元兌換該幣別匯率
     *
     * @param String $currency 幣別 ex: TWD, JPY
     *
     * @return array|mixed [
     *                      "Exrate": 匯率
     *                      "UTC": 更新時間
     *                      ]
     * @throws GuzzleException
     */
    public function getExchangeRate(string $currency)
    {
        $client   = new Client();
        $response = $client->get('https://tw.rter.info/capi.php');
        $rateData = json_decode($response->getBody(), true);
        $key      = "USD" . $currency;
        return collect($rateData[$key]) ?? collect([]);
    }

    /**
     * 取得美元兌換該幣別匯率
     *
     * @param String $currency 幣別 ex: TWD, JPY
     *
     * @return array|mixed [
     *                      "Exrate": 匯率
     *                      "UTC": 更新時間
     *                      ]
     * @throws GuzzleException
     */
    public function getAllCurrencyRate(string $from,string $to)
    {
        if($from == 'USD'){
            $rateData = $this->getExchangeRate($to);
        } else {
            $fromRate =  $this->getExchangeRate($from);
            $toRate =  $this->getExchangeRate($to);
            if(
                $fromRate->isEmpty() ||
                $toRate->isEmpty()
            ){
                throw new \Exception('Not search curreny');
            }
            $rate = (double)$toRate['Exrate'] / (double)$fromRate['Exrate'];
            $rate = round($rate,3);
            $rateData = [
                "Exrate" => $rate,
                'UTC' => $fromRate['UTC']
            ];
        }
        return collect($rateData) ?? collect([]);
    }
}
