<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Form\OpinionType;
use App\Repository\OpinionRepository;
use App\Entity\Restaurante;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/opinion')]
class OpinionController extends AbstractController
{
    #[Route('/restaurante/{id}', name:'restaurante_opinion')]
     
    public function opinionesRestaurante(Restaurante $restaurante): Response
    {
        return $this->render('opinion/indexRestaurante.html.twig', [
            'restaurante' => $restaurante,
        ]);
    }

    #[Route('/responderOpinion/{id}', name: 'responderOpinion', methods: ['POST'])]
    public function responderOpinion(OpinionRepository $opinionRepository, Request $request): Response
    {
         // Verificar si el usuario autenticado es el propietario del restaurante
    if (!$this->isGranted('ROLE_RESTAURANTE')) {
        throw new AccessDeniedException('No tienes permisos para acceder a esta página.');
    }

    // Obtener las opiniones asociadas al restaurante
    $opiniones = $restaurante->getOpinions();

    // Procesar el formulario de respuesta a opiniones
    $form = $this->createForm(OpinionType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Obtener los datos del formulario
        $datos = $form->getData();

        // Guardar la respuesta en la base de datos y actualizar la opinión correspondiente
        $opinionId = $datos['opinion_id'];
        $respuesta = $datos['respuesta'];

        $opinion = $em->getRepository(Opinion::class)->find($opinionId);

        if (!$opinion) {
            throw new NotFoundHttpException('No se encontró la opinión.');
        }

        // Verificar si la opinión pertenece al restaurante actual
        if ($opinion->getRestaurante() !== $restaurante) {
            throw new AccessDeniedException('No tienes permisos para responder a esta opinión.');
        }

        // Guardar la respuesta en la opinión y actualizar en la base de datos
        $opinion->setRespuesta($respuesta);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Redireccionar a la página anterior
        return $this->redirect($request->headers->get('referer'));
    }

    return $this->render('opinion/indexRestaurante.html.twig', [
        'restaurante' => $restaurante,
        'opiniones' => $opiniones,
        'form' => $form->createView(),
    ]);
    }
    

    #[Route('/', name: 'app_opinion_index', methods: ['GET'])]
    public function index(OpinionRepository $opinionRepository): Response
    {
        return $this->render('opinion/indexUser.html.twig', [
            'opinions' => $opinionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_opinion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OpinionRepository $opinionRepository): Response
    {
        $opinion = new Opinion();
        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinionRepository->save($opinion, true);

            return $this->redirectToRoute('app_opinion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('opinion/new.html.twig', [
            'opinion' => $opinion,
            'formulario' => $form,
        ]);
    }


    #[Route('/nueva/{idRestaurante}/{idUser}', name: 'app_opinion_neww', methods: ['GET', 'POST'])]
    public function nueva(
        Request $request, 
        OpinionRepository $opinionRepository, 
        EntityManagerInterface $entityManager, 
        $idRestaurante,
        $idUser
        ): Response
    {
        $restaurante = $entityManager->find(Restaurante::class, $idRestaurante);
        $nombreRestaurante = $restaurante->getNombre();

        $usuario = $entityManager->find(User::class, $idUser);

        $opinion = new Opinion();
        $opinion->setRestaurante($restaurante); // Asignar el objeto Restaurante a la opinion
        $opinion->setUser($usuario);

        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinionRepository->save($opinion, true);
            return $this->redirectToRoute('misReservas', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('opinion/new.html.twig', [
            'opinion' => $opinion,
            'formulario' => $form,
            'nombreRestaurante' => $nombreRestaurante,
        ]);
    }










    #[Route('/{id}', name: 'app_opinion_show', methods: ['GET'])]
    public function show(Opinion $opinion): Response
    {
        return $this->render('opinion/show.html.twig', [
            'opinion' => $opinion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_opinion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Opinion $opinion, OpinionRepository $opinionRepository): Response
    {
        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinionRepository->save($opinion, true);

            return $this->redirectToRoute('miRestaurante', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('opinion/edit.html.twig', [
            'opinion' => $opinion,
            'formulario' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opinion_delete', methods: ['POST'])]
    public function delete(Request $request, Opinion $opinion, OpinionRepository $opinionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$opinion->getId(), $request->request->get('_token'))) {
            $opinionRepository->remove($opinion, true);
        }

        return $this->redirectToRoute('app_opinion_index', [], Response::HTTP_SEE_OTHER);
    }
}
