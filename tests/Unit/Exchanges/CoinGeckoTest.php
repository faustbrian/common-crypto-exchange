<?php

use KodeKeep\TopiaMoney\DTO\Rate;
use KodeKeep\TopiaMoney\DTO\Symbol;
use KodeKeep\TopiaMoney\Exchanges\CoinGecko;

it('can fetch all symbols', function () {
    $subject = new CoinGecko();

    expect($response = $subject->symbols())->toBeArray();
    expect($response[0])->toBeInstanceOf(Symbol::class);
});

it('can fetch the historical rates for the given symbol', function () {
    $subject = new CoinGecko();

    expect($response = $subject->historical(new Symbol([
        'symbol' => 'ark',
        'source' => 'ark',
        'target' => 'btc',
    ])))->toBeArray();
    expect($response[0])->toBeInstanceOf(Rate::class);
});

it('can fetch the current rate for the given symbol', function () {
    $subject = new CoinGecko();

    expect($subject->rate(new Symbol([
        'symbol' => 'ark',
        'source' => 'ark',
        'target' => 'btc',
    ])))->toBeInstanceOf(Rate::class);
});
