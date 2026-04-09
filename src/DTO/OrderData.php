<?php

namespace Codeglen\BinancePay\DTO;

class OrderData
{
    public function __construct(
        public string $merchantTradeNo,
        public string $currency,
        public string $goodsName,
        public string $goodsCategory,
        public string $referenceGoodsId,
        public int $totalFee,
        public array $extra = []
    ) {
    }

    public function toArray(): array
    {
        return array_merge([
            'merchantTradeNo' => $this->merchantTradeNo,
            'currency' => $this->currency,
            'totalFee' => $this->totalFee,
            'goods' => [
                'goodsType' => '01',
                'goodsCategory' => $this->goodsCategory,
                'referenceGoodsId' => $this->referenceGoodsId,
                'goodsName' => $this->goodsName,
            ],
        ], $this->extra);
    }
}
