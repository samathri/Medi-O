<?php
/** QR generation via endroid/qr-code (already in vendor/). */

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;

/** Returns a data: URI PNG for embedding directly in <img src>. */
function qr_data_uri(string $payload, int $size = 260): string
{
    $result = Builder::create()
        ->writer(new PngWriter())
        ->data($payload)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size($size)
        ->margin(12)
        ->build();
    return $result->getDataUri();
}
