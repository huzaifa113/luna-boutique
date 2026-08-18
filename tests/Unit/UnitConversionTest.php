<?php

use App\Services\InvoiceFormatterService;
use App\Services\UnitConversionService;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->service = new UnitConversionService;
});

it('calculates §5.1 vendor purchase packaged goods with shortage', function () {
    // Rice 5kg Pack: base unit = piece, vendor unit = bag, factor = 20
    // 10 bags @ 4,000/bag, shortage 6 pieces
    $quantity = 10;
    $factor = 20;
    $rate = 4000;
    $shortageBase = 6;

    $grossBaseQty = $this->service->toBaseQuantity($quantity, $factor);
    expect($grossBaseQty)->toBe(200.000);

    $grossAmount = round($quantity * $rate, 2);
    expect($grossAmount)->toBe(40000.00);

    $baseUnitRate = $this->service->baseUnitRate($grossAmount, $grossBaseQty);
    expect($baseUnitRate)->toBe(200.0000);

    $receivedBaseQty = $grossBaseQty - $shortageBase;
    expect($receivedBaseQty)->toBe(194.000);

    $shortageAmount = round($shortageBase * $baseUnitRate, 2);
    expect($shortageAmount)->toBe(1200.00);

    $netAmount = $grossAmount - $shortageAmount;
    expect($netAmount)->toBe(38800.00);

    // Weighted average cost_price: existing 100 pcs @ 190.00, incoming 194 pcs @ 200.00
    $existingValue = 100 * 190.00;
    $incomingValue = 194 * 200.00;
    $totalQty = 100 + 194;
    $weightedAvg = round(($existingValue + $incomingValue) / $totalQty, 2);
    expect($weightedAvg)->toBe(196.60);
});

it('calculates §5.2 vendor purchase loose goods fractional kg', function () {
    // Loose Basmati: base unit = kg, vendor unit = bag, factor = 50
    $quantity = 8;
    $factor = 50;
    $rate = 9500;
    $shortageBase = 3.5;

    $grossBaseQty = $this->service->toBaseQuantity($quantity, $factor);
    expect($grossBaseQty)->toBe(400.000);

    $grossAmount = round($quantity * $rate, 2);
    expect($grossAmount)->toBe(76000.00);

    $baseUnitRate = $this->service->baseUnitRate($grossAmount, $grossBaseQty);
    expect($baseUnitRate)->toBe(190.0000);

    $receivedBaseQty = $grossBaseQty - $shortageBase;
    expect($receivedBaseQty)->toBe(396.500);

    $shortageAmount = round($shortageBase * $baseUnitRate, 2);
    expect($shortageAmount)->toBe(665.00);

    $netAmount = $grossAmount - $shortageAmount;
    expect($netAmount)->toBe(75335.00);
});

it('calculates §5.3 customer sale bulk unit with shortage', function () {
    // Sell 2 bags of Rice (factor 20) @ 4600/bag, shortage 2 pieces
    $quantity = 2;
    $factor = 20;
    $rate = 4600;
    $shortage = 2;
    $costPrice = 196.60;

    $grossBaseQty = $this->service->toBaseQuantity($quantity, $factor);
    expect($grossBaseQty)->toBe(40.000);

    $grossAmount = round($quantity * $rate, 2);
    expect($grossAmount)->toBe(9200.00);

    $baseUnitRate = $this->service->baseUnitRate($grossAmount, $grossBaseQty);
    expect($baseUnitRate)->toBe(230.0000);

    $billedBaseQty = $grossBaseQty - $shortage;
    expect($billedBaseQty)->toBe(38.000);

    $shortageAmount = round($shortage * $baseUnitRate, 2);
    expect($shortageAmount)->toBe(460.00);

    $netAmount = $grossAmount - $shortageAmount;
    expect($netAmount)->toBe(8740.00);

    $shortageCost = round($shortage * $costPrice, 2);
    expect($shortageCost)->toBe(393.20);
});

it('calculates §5.4 customer sale single pieces', function () {
    // 7 pieces @ 245/piece, factor = 1
    $quantity = 7;
    $factor = 1;
    $rate = 245;

    $grossBaseQty = $this->service->toBaseQuantity($quantity, $factor);
    expect($grossBaseQty)->toBe(7.000);

    $grossAmount = round($quantity * $rate, 2);
    expect($grossAmount)->toBe(1715.00);

    $baseUnitRate = $this->service->baseUnitRate($grossAmount, $grossBaseQty);
    expect($baseUnitRate)->toBe(245.0000);

    $netAmount = $grossAmount; // no shortage
    expect($netAmount)->toBe(1715.00);
});

it('formatQuantity trims trailing zeros', function () {
    expect($this->service->formatQuantity(194.000))->toBe('194');
    expect($this->service->formatQuantity(396.500))->toBe('396.5');
    expect($this->service->formatQuantity(0.000))->toBe('0');
    expect($this->service->formatQuantity(7.000))->toBe('7');
    expect($this->service->formatQuantity(100.100))->toBe('100.1');
    expect($this->service->formatQuantity(100.0))->toBe('100');
});

it('baseUnitRate returns 0 when gross base quantity is 0 or negative', function () {
    expect($this->service->baseUnitRate(100, 0))->toBe(0.0);
    expect($this->service->baseUnitRate(100, -5))->toBe(0.0);
});

it('toUnitQuantity returns 0 when factor is 0 or negative', function () {
    expect($this->service->toUnitQuantity(100, 0))->toBe(0.0);
    expect($this->service->toUnitQuantity(100, -1))->toBe(0.0);
});

it('converts base quantity back to unit quantity', function () {
    $result = $this->service->toUnitQuantity(200, 20);
    expect($result)->toBe(10.000);
});

it('invoice formatter money adds currency symbol', function () {
    config(['pos.currency.symbol' => 'Rs']);
    expect(InvoiceFormatterService::money(38800.00))->toBe('Rs 38,800.00');
    expect(InvoiceFormatterService::money(75335.00))->toBe('Rs 75,335.00');
    expect(InvoiceFormatterService::money(8740.00))->toBe('Rs 8,740.00');
});

it('amountInWords produces correct output', function () {
    config(['pos.currency.words' => 'Rupees']);
    config(['pos.currency.subunit_words' => 'Paisa']);

    $result = InvoiceFormatterService::amountInWords(38800.00);
    expect($result)->toBe('Rupees Thirty Eight Thousand Eight Hundred Only');

    $result = InvoiceFormatterService::amountInWords(75335.00);
    expect($result)->toBe('Rupees Seventy Five Thousand Three Hundred Thirty Five Only');

    $result = InvoiceFormatterService::amountInWords(0.00);
    expect($result)->toBe('Rupees Zero Only');
});

it('amountInWords includes paisa when present', function () {
    config(['pos.currency.words' => 'Rupees']);
    config(['pos.currency.subunit_words' => 'Paisa']);

    $result = InvoiceFormatterService::amountInWords(100.50);
    expect($result)->toBe('Rupees One Hundred And Fifty Paisa Only');
});
