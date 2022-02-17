<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExchangeResource;
use App\Services\ExchangeService;
use Illuminate\Http\Request;

/**
 *
 */
class QuizController extends Controller
{
    private $exchangeService;

    public function __construct(
        ExchangeService $exchangeService
    ) {
        $this->exchangeService = $exchangeService;
    }

    public function getExchangeRate(Request $request)
    {
        try{
            $response = collect([]);
            // TODO: 實作取得匯率
            $from = $request->from;
            $to  = $request->to;
            if(empty($from) || empty($to)){
                throw new \Exception('Not search curreny');
            }
            $rateData = $this->exchangeService->getExchangeRate($from,$to);
            if($rateData->isEmpty()){
                throw new \Exception('Not search curreny');
            }
            $response['exchange_rate'] = $rateData['Exrate'];
            $response['updated_at'] = $rateData['UTC'];
        }catch(\Exception $e){
            $response['message'] = $e->getMessage();
        }
        // API回傳結果
        return new ExchangeResource($response);
    }
}
