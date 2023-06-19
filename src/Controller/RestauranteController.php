<?php

namespace App\Controller;

use App\Entity\Restaurante;
use App\Form\RestauranteType;
use App\Repository\RestauranteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;


#[Route('/restaurante')]
class RestauranteController extends AbstractController
{
    #[Route('/', name: 'app_restaurante_index', methods: ['GET'])]
    public function index(RestauranteRepository $restauranteRepository, PaginatorInterface $paginator, Request $request): Response
    {

        
       $registros = $restauranteRepository->findAll();
      // $query = $restauranteRepository->createQueryBuilder('r')->getQuery();
        $pagination = $paginator->paginate(
            $registros, // Array con los registros a paginar
            $request->query->getInt('page', 1), // Número de página actual
            3 // Número de registros por página
        );
        return $this->render('restaurante/index.html.twig', [
            // 'restaurantes' => $restauranteRepository->findAll(),
            'pagination' => $pagination,
            //'restaurantes' => $registros,
        ]);
    }

    #[Route('/new', name: 'app_restaurante_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RestauranteRepository $restauranteRepository): Response
    {
        $restaurante = new Restaurante();
        $form = $this->createForm(RestauranteType::class, $restaurante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restauranteRepository->save($restaurante, true);

            return $this->redirectToRoute('app_restaurante_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurante/new.html.twig', [
            'restaurante' => $restaurante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurante_show', methods: ['GET'])]
    public function show(Restaurante $restaurante): Response
    {
        return $this->render('restaurante/show.html.twig', [
            'restaurante' => $restaurante,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_restaurante_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Restaurante $restaurante, RestauranteRepository $restauranteRepository): Response
    {
        $form = $this->createForm(RestauranteType::class, $restaurante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restauranteRepository->save($restaurante, true);

            return $this->redirectToRoute('app_restaurante_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('restaurante/edit.html.twig', [
            'restaurante' => $restaurante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editRestaurante', name: 'restaurante_edit', methods: ['GET', 'POST'])]
    public function editRestaurante(Request $request, Restaurante $restaurante, RestauranteRepository $restauranteRepository): Response
    {
        $form = $this->createForm(RestauranteType::class, $restaurante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restauranteRepository->save($restaurante, true);

            return $this->redirectToRoute('miRestaurante', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('menuRestaurante/edit.html.twig', [
            'restaurante' => $restaurante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurante_delete', methods: ['POST'])]
    public function delete(Request $request, Restaurante $restaurante, RestauranteRepository $restauranteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurante->getId(), $request->request->get('_token'))) {
            $restauranteRepository->remove($restaurante, true);
        }

        return $this->redirectToRoute('app_restaurante_index', [], Response::HTTP_SEE_OTHER);
    }
}
