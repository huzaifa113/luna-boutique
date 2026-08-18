<?php

namespace App\Services;

class InvoiceFormatterService
{
    /**
     * Format a monetary amount with the currency symbol.
     *
     * @return string e.g. "Rs 1,234.56"
     */
    public static function money(float $amount): string
    {
        $symbol = config('pos.currency.symbol', 'Rs');

        return $symbol.' '.number_format($amount, 2);
    }

    /**
     * Convert a numeric amount to words (English).
     *
     * Supports up to 999,999,999.99. Subunit (paisa) included when present.
     *
     * @return string e.g. "Rupees Thirty Eight Thousand Eight Hundred Only"
     */
    public static function amountInWords(float $amount): string
    {
        $currencyWords = config('pos.currency.words', 'Rupees');
        $subunitWords = config('pos.currency.subunit_words', 'Paisa');

        $whole = floor($amount);
        $fraction = round(($amount - $whole) * 100);

        $words = self::numberToWords((int) $whole);

        $result = $currencyWords.' '.$words;

        if ($fraction > 0) {
            $result .= ' And '.self::numberToWords((int) $fraction).' '.$subunitWords;
        }

        $result .= ' Only';

        return $result;
    }

    /**
     * Convert an integer (0–999,999,999) to English words.
     */
    private static function numberToWords(int $number): string
    {
        if ($number === 0) {
            return 'Zero';
        }

        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen',
        ];

        $tens = [
            '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety',
        ];

        if ($number < 20) {
            return $ones[$number];
        }

        if ($number < 100) {
            return $tens[(int) ($number / 10)].($number % 10 > 0 ? ' '.$ones[$number % 10] : '');
        }

        if ($number < 1000) {
            return $ones[(int) ($number / 100)].' Hundred'.($number % 100 > 0 ? ' '.self::numberToWords($number % 100) : '');
        }

        if ($number < 100000) {
            return self::numberToWords((int) ($number / 1000)).' Thousand'.($number % 1000 > 0 ? ' '.self::numberToWords($number % 1000) : '');
        }

        if ($number < 10000000) {
            return self::numberToWords((int) ($number / 100000)).' Lakh'.($number % 100000 > 0 ? ' '.self::numberToWords($number % 100000) : '');
        }

        return self::numberToWords((int) ($number / 10000000)).' Crore'.($number % 10000000 > 0 ? ' '.self::numberToWords($number % 10000000) : '');
    }
}
