<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Restaurante;
use App\Entity\User;
use App\Entity\Mesa;
use App\Form\ReservaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Controller\MailerController;
use App\Service\Correo;
use App\Repository\ReservaRepository;
use App\Repository\MesaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Component\Mailer\MailerInterface;


#[Route('/reserva')]
class ReservaController extends AbstractController
{
    
    #[Route('/', name: 'app_reserva_index', methods: ['GET'])]
    public function index(ReservaRepository $reservaRepository): Response
    {
        return $this->render('menuRestaurante/reservas.html.twig', [
            'reservas' => $reservaRepository->findAll(),
        ]);
    }


    #[Route('/nueva/{idRestaurante}/{idUser}', name: 'app_reserva_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        ReservaRepository $reservaRepository, 
        EntityManagerInterface $entityManager, 
        MesaRepository $mesaRepository,
        $idUser,
        $idRestaurante,
        MailerController $mailerController,
        MailerInterface $mailer,
        Correo $correo): Response
    {
        $restaurante = $entityManager->find(Restaurante::class, $idRestaurante);
        $nombreRestaurante = $restaurante->getNombre();

        $usuario = $entityManager->find(User::class, $idUser);

        $reserva = new Reserva();
        $reserva->setRestaurante($restaurante); // Asignar el objeto Restaurante a la reserva
        $reserva->setUsuario($usuario);

        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fecha = $reserva->getFechaHora();
            $hora = $reserva->getHora();

             // Verificar si la mesa seleccionada ya tiene una reserva en la fecha y hora seleccionadas
            $mesaSeleccionada = $reserva->getMesa();
            
            $horaSeleccionada = \DateTime::createFromFormat('H:i', $hora);

            // Obtener todas las reservas para la mesa y fecha seleccionadas
            $reservasExistentes = $reservaRepository->findBy(['mesa' => $mesaSeleccionada, 'fechaHora' => $fecha]);

            foreach ($reservasExistentes as $reservaExistente) {
                // Obtener la hora de la reserva existente
                $horaReservaExistente = \DateTime::createFromFormat('H:i', $reservaExistente->getHora());

                // Comparar la hora seleccionada con la hora de la reserva existente
                if ($horaSeleccionada >= $horaReservaExistente && $horaSeleccionada <= $horaReservaExistente->modify('+1 hour')) {
                    // La mesa ya está reservada dentro del rango horario
                    $this->addFlash('error', 'No hay mesas disponibles dentro del rango horario. Pruebe con otro dia u otro horario');
                    return $this->redirectToRoute('app_reserva_new', ['idRestaurante' => $idRestaurante, 'idUser' => $idUser]);
                }
            }
            $reservaRepository->save($reserva, true);

                // Obtener los datos del formulario
                $fecha = $reserva->getFechaHora(); // Ejemplo: obtener la fecha de la reserva
                $hora = $reserva->getHora(); // Ejemplo: obtener la fecha de la reserva
                $restaurante = $reserva->getRestaurante(); // Ejemplo: obtener la fecha de la reserva
                $usuario = $reserva->getUsuario(); // Ejemplo: obtener la fecha de la reserva
    

            // Llama al controlador de correo electrónico para enviar el correo
            $mailerController->sendEmail($mailer, $fecha, $hora, $restaurante, $usuario);

            return $this->redirectToRoute('misReservas', [], Response::HTTP_SEE_OTHER);
        }

        $mesas = $mesaRepository->findBy(['restaurante' => $restaurante]);
        $form->add('mesa', EntityType::class, [
            'class' => Mesa::class,
            'choices' => $mesas,
            'choice_label' => 'numeroPersonas',
            'placeholder' => 'Selecciona una mesa',
            'label' => 'Mesa',
            'required' => false,
        ]);
        return $this->renderForm('reserva/new.html.twig', [
            'reserva' => $reserva,
            'form' => $form,
            'nombreRestaurante' => $nombreRestaurante,
            
        ]);
    }
    

    #[Route('/{id}', name: 'app_reserva_show', methods: ['GET'])]
    public function show(Reserva $reserva): Response
    {
        return $this->render('reserva/show.html.twig', [
            'reserva' => $reserva,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reserva_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reserva $reservas, ReservaRepository $reservaRepository): Response
    {
        $form = $this->createForm(ReservaType::class, $reservas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservaRepository->save($reservas, true);

            return $this->redirectToRoute('misReservas', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reserva/edit.html.twig', [
            'reservas' => $reservas,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reserva_edit_cancelar', methods: ['GET', 'POST'])]
    public function editCancelar(Request $request, Reserva $reserva, ReservaRepository $reservaRepository): Response
    {
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservaRepository->save($reserva, true);

            return $this->redirectToRoute('app_reserva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reserva/edit.html.twig', [
            'reserva' => $reserva,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reserva_delete', methods: ['POST'])]
    public function delete(Request $request, Reserva $reserva, ReservaRepository $reservaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserva->getId(), $request->request->get('_token'))) {
            $reservaRepository->remove($reserva, true);
        }

        return $this->redirectToRoute('misReservas', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/restaurante', name: 'app_reserva_delete_restaurante', methods: ['POST'])]
    public function deleteRestaurante(Request $request, Reserva $reserva, ReservaRepository $reservaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserva->getId(), $request->request->get('_token'))) {
            $reservaRepository->remove($reserva, true);
        }

        return $this->redirectToRoute('mis-reservas', [], Response::HTTP_SEE_OTHER);
    }
}
