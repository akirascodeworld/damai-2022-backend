<?php

namespace App\Services;

use App\Constants\CurrencyConstant;
use App\Repositories\CurrencyRepository;

/**
 *
 */
class ExchangeService
{
    private $currencyRepo;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepo = $currencyRepository;
    }

    public function getExchangeRate(string $from, string $to, $amount = 1)
    {
        $rate = $this->currencyRepo->getExchangeRate($from, $to);
        return $rate;
    }
}
