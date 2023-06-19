<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;



class Pdf extends AbstractController {

    public function pdf() : String{
        $dompdf = new Dompdf();
        //$html = 'hola';
        $html = $this->renderView('pdf/myPdf.html.twig', [
                'title' => "Bienvenido a Click&Go"
            ]);
        
        $dompdf -> loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf -> render();
        $output = $dompdf->output();
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

        //aqui la guardo en una variable
        $pdf = $dompdf->output();

        return $pdf;
    }
    
    public function generarPdf(Request $request)
        {
            // Obtener los datos del formulario
            $fecha = $request->request->get('fecha');
            $hora = $request->request->get('hora');
            // Obtén otros campos del formulario según sea necesario

            // Renderizar la plantilla Twig con los datos del formulario
            $html = $this->renderView('pdf/myPdf.html.twig', [
                'fecha' => $fecha,
                'hora' => $hora,
                // Pasar otros datos del formulario según sea necesario
            ]);

            // Generar el PDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Obtener el contenido del PDF generado
            $pdfContent = $dompdf->output();

            // Enviar el PDF como respuesta al navegador
            $response = new Response($pdfContent);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment;filename="formulario.pdf"');

            return $response;
        }

}