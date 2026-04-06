<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CurrencyConversionService
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    /**
     * @return array{
     *     from: string,
     *     to: string,
     *     original_amount: string,
     *     converted_amount: string,
     *     rate: string,
     *     date: string|null,
     *     provider: string
     * }|null
     */
    public function convert(string $amount, string $fromCurrency, string $toCurrency): ?array
    {
        $from = mb_strtoupper(trim($fromCurrency));
        $to = mb_strtoupper(trim($toCurrency));

        if (!preg_match('/^[A-Z]{3}$/', $from) || !preg_match('/^[A-Z]{3}$/', $to)) {
            return null;
        }

        if ($from === $to) {
            return null;
        }

        $originalAmount = (float) $amount;
        if ($originalAmount <= 0) {
            return null;
        }

        try {
            $response = $this->httpClient->request('GET', 'https://api.frankfurter.app/latest', [
                'query' => [
                    'amount' => number_format($originalAmount, 2, '.', ''),
                    'from' => $from,
                    'to' => $to,
                ],
                'timeout' => 8,
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $data = $response->toArray(false);
            if (!isset($data['rates'][$to])) {
                return null;
            }

            $converted = (float) $data['rates'][$to];
            $rate = $converted / $originalAmount;

            return [
                'from' => $from,
                'to' => $to,
                'original_amount' => number_format($originalAmount, 2, '.', ''),
                'converted_amount' => number_format($converted, 2, '.', ''),
                'rate' => number_format($rate, 6, '.', ''),
                'date' => isset($data['date']) ? (string) $data['date'] : null,
                'provider' => 'Frankfurter',
            ];
        } catch (\Throwable) {
            return null;
        }
    }
}
