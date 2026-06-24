<?php

namespace App\Service;

use App\Entity\Certified;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QrCodeService
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function generateForCertified(Certified $certified): string
    {
        $url = $this->urlGenerator->generate(
            'app_verify_certified',
            ['uuid' => $certified->getUuid()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $qrCode = new QrCode($url);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return base64_encode($result->getString());
    }
}