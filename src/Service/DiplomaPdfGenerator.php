<?php

namespace App\Service;

use App\Entity\Diploma;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class DiplomaPdfGenerator
{
    public function __construct(
        private Environment $twig
    ) {
    }


    public function generate(Diploma $diploma): string
    {
        $html = $this->twig->render(
            'admin/diplomas/pdf.html.twig',
            [
                'diploma'=>$diploma
            ]
        );


        $options = new Options();
        $options->setDefaultFont('Arial');


        $pdf = new Dompdf($options);

        $pdf->loadHtml($html);

        $pdf->setPaper(
            'A4',
            'landscape'
        );

        $pdf->render();


        return $pdf->output();
    }
}