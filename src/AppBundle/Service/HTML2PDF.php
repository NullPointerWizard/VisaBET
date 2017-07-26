<?php

namespace AppBundle\Service;

class HTML2PDF{

    private $pdf;

    /**
    * Fonction permettant l'instanciation d'un objet html2pdf
    */
    public function create(
        $orientation = 'P',
        $format = 'A4',
        $lang = 'fr',
        $unicode = true,
        $encoding = 'UTF-8',
        $margins = array(5, 5, 5, 8),
        $pdfa = false
        )
    {
        $this->pdf = new \Spipu\Html2Pdf\Html2Pdf(
            $orientation,
            $format,
            $lang,
            $unicode,
            $encoding ,
            $margins,
            $pdfa
        );
    }

    /**
    * Genere le pdf
    * @param $template
    * @param string $filename le nom du fichier avec l'extension .pdf
    */
    public function generatePdf($template, $filename){
        //$this->pdf->ignore_invalid_utf8 = true;
        //$stylesheet = file_get_contents('C:\Users\pab\Desktop\STAGE_VISA\WORKSPACE\GIT\PROJET_VISA\web\css\pdf_generator_html2pdf.css');
        //$this->pdf->WriteHTML($stylesheet);
        $this->pdf->writeHTML($template);

        return $this->pdf->Output($filename, 'S');
    }
}
