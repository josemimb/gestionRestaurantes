<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\Correo;
use App\Service\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;



class MailerController extends AbstractController
{
    private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
      
    }
    #[Route('/email', name: 'email')]
    public function sendEmail(MailerInterface $mailer, String $fecha, String $hora, String $restaurante, String $nombre): Response
    {
        $miPdf = $this->pdf->pdf();
 
         // Crear el objeto Email con los datos del formulario
         $email = (new Email())
             ->from('josemimb98@gmail.com')
             ->to('josemimb98@gmail.com')
             ->subject('Tu reserva ha sido confirmada')
             ->text("hooa")
             ->attach($miPdf, 'reservaClick&Go.pdf')
             ->html("<p>Tienes una reserva en Click&Go, para el restaurante: <strong>$restaurante</strong></p>
                    <ul>
                        <li><strong>Fecha:</strong>  $fecha </li>
                        <li><strong>Hora:</strong>  $hora </li>
                        <li><strong>Nombre:</strong> $nombre </li>
                        <li><strong>Restaurante:</strong> $restaurante </li>
                        <!-- Agrega los demás campos del formulario que deseas mostrar -->
                    </ul>
                    <p>Acontinuacion le entregamos un pdf con las reglas de uso de nuesto establecimiento</p>
                    <p>Un cordial, Saludo.</p>");
 
         // Enviar el correo
         $mailer->send($email);
 
         $this->addFlash(
             'notice',
             'El correo ha sido enviado correcatamente!'
         );
 
         // Redirigir a una página de éxito o mostrar un mensaje de confirmación
         return $this->redirectToRoute('inicio');
    }

    #[Route('/enviar-correo', name: 'enviar_correo', methods: ['POST'])]
    public function enviarCorreo(Request $request, MailerInterface $mailer)
    {
        // Obtener los datos del formulario
        $nombre = $request->request->get('nombre');
        $email = $request->request->get('email');
        $nombreRestaurante = $request->request->get('nombre_restaurante');
        $contacto = $request->request->get('contacto');
        $mensaje = $request->request->get('mensaje');

        // Crear el objeto Email con los datos del formulario
        $email = (new Email())
            ->from($email)
            ->to('josemimb98@gmail.com')
            ->subject('Nuevo Restaurante')
            ->text("Nombre: $nombre \nRestaurante: $nombreRestaurante\nContacto: $contacto\nMensaje: $mensaje");

        // Enviar el correo
        $mailer->send($email);

        $this->addFlash(
            'notice',
            'El correo ha sido enviado correcatamente!'
        );

        // Redirigir a una página de éxito o mostrar un mensaje de confirmación
        return $this->redirectToRoute('contacto');
    }




}