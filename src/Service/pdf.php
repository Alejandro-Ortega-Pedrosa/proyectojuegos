<?php
 
    namespace App\Service;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Dompdf\Dompdf;

class pdf extends AbstractController
{
    private $client;
    
    public function generarPdf()
    {

        $html= $this->renderView('base.html.twig');
        $dompdf=new Dompdf();

        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4","landscape");
        $dompdf->render();
        $output=$dompdf->output();
         
        return $output;

    }

}