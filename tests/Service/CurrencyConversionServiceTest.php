<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CurrencyConversionService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class CurrencyConversionServiceTest extends TestCase
{
    public function testConvertReturnsExpectedPayload(): void
    {
        $mock = new MockHttpClient([
            new MockResponse('{"amount":100,"base":"USD","date":"2026-04-06","rates":{"BRL":520.5}}', ['http_code' => 200]),
        ]);

        $service = new CurrencyConversionService($mock);
        $result = $service->convert('100.00', 'usd', 'brl');

        self::assertIsArray($result);
        self::assertSame('USD', $result['from']);
        self::assertSame('BRL', $result['to']);
        self::assertSame('100.00', $result['original_amount']);
        self::assertSame('520.50', $result['converted_amount']);
        self::assertSame('5.205000', $result['rate']);
        self::assertSame('2026-04-06', $result['date']);
    }

    public function testConvertReturnsNullForInvalidInput(): void
    {
        $service = new CurrencyConversionService(new MockHttpClient());

        self::assertNull($service->convert('0.00', 'USD', 'BRL'));
        self::assertNull($service->convert('10.00', 'US', 'BRL'));
        self::assertNull($service->convert('10.00', 'USD', 'USD'));
    }

    public function testConvertReturnsNullWhenResponseHasNoTargetRate(): void
    {
        $mock = new MockHttpClient([
            new MockResponse('{"amount":100,"base":"USD","date":"2026-04-06","rates":{}}', ['http_code' => 200]),
        ]);

        $service = new CurrencyConversionService($mock);

        self::assertNull($service->convert('100.00', 'USD', 'BRL'));
    }
}
