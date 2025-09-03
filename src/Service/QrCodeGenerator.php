<?php

namespace App\Service;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;

class QrCodeGenerator
{
    public function __construct(private string $publicDir)
    {
    }

    public function generate(string $data, string $filename, string $label = null): string
    {
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0,0,0),
            backgroundColor: new Color(255,255,255)
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode, null, null, []);

        $dir = $this->publicDir . DIRECTORY_SEPARATOR . 'qr';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
    file_put_contents($path, $result->getString());
        return 'qr/' . $filename; // relative path
    }
}
