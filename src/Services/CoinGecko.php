<?php

declare(strict_types=1);

/*
 * This file is part of Topia.Money.
 *
 * (c) Konceiver <info@konceiver.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Konceiver\TopiaMoney\Services;

use Carbon\Carbon;
use Konceiver\TopiaMoney\Contracts\Service;
use Konceiver\TopiaMoney\DTO\Rate;
use Konceiver\TopiaMoney\DTO\Symbol;
use Konceiver\TopiaMoney\Helpers\Client;
use Konceiver\TopiaMoney\Helpers\ResolveScientificNotation;

/**
 * Undocumented class.
 */
final class CoinGecko implements Service
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $this->client = Client::new('https://api.coingecko.com/api/v3/');
    }

    /**
     * {@inheritdoc}
     */
    public function symbols(): array
    {
        return array_map(fn ($symbol) => new Symbol([
            'symbol'   => $symbol['id'],
            'source'   => $symbol['symbol'],
            'sourceId' => $symbol['symbol'],
            'target'   => null,
            'targetId' => null,
        ]), $this->client->get('coins/list')->json());
    }

    /**
     * {@inheritdoc}
     */
    public function historical(Symbol $symbol): array
    {
        return array_map(fn ($day) => new Rate([
            'date' => Carbon::createFromTimestampMs($day[0]),
            'rate' => ResolveScientificNotation::execute((float) $day[1]),
        ]), $this->client->get('coins/'.strtolower($symbol->source).'/market_chart', [
            'vs_currency' => strtolower($symbol->target),
            'days'        => Carbon::now()->startOfCentury()->diffInDays(),
        ])->json()['prices']);
    }

    /**
     * {@inheritdoc}
     */
    public function rate(Symbol $symbol): Rate
    {
        $source = strtolower($symbol->source);
        $target = strtolower($symbol->target);

        $response = $this->client->get('simple/price', [
            'ids'           => $source,
            'vs_currencies' => $target,
        ])->json()[$source][$target];

        return new Rate([
            'date' => Carbon::now(),
            'rate' => ResolveScientificNotation::execute((float) $response),
        ]);
    }
}
