<?php

namespace App\Controller;

use App\Entity\Mesa;
use App\Entity\Restaurante;
use App\Form\MesaType;
use App\Repository\MesaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/mesa')]
class MesaController extends AbstractController
{

    
    #[Route('/restaurante/{id}/mesas', name:'restaurante_mesas')]
     
    public function mesas(Restaurante $restaurante): Response
    {
        return $this->render('mesa/index.html.twig', [
            'restaurante' => $restaurante,
        ]);
    }

    #[Route('/new/{id}', name: 'app_mesa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MesaRepository $mesaRepository, Restaurante $restaurante): Response
    {
        $mesa = new Mesa();
        $form = $this->createForm(MesaType::class, $mesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mesaRepository->save($mesa, true);

            return $this->redirectToRoute('restaurante_mesas', ['id' => $restaurante->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mesa/new.html.twig', [
            'mesa' => $mesa,
            'form' => $form,
            'restaurante' => $restaurante,
        ]);
    }


    /**
     * @Route("/{id}/edit/{id1}", name="app_mesa_edit", methods={"GET","POST"})
     * 
     * @ParamConverter("restaurante", class="App\Entity\Restaurante", options={"id" = "id1"})
     * @ParamConverter("mesa", class="App\Entity\Mesa", options={"id" = "id"})
     */
    #[Route('/{id}/edit/{id1}', name: 'app_mesa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mesa $mesa, MesaRepository $mesaRepository, Restaurante $restaurante): Response
    {
        $form = $this->createForm(MesaType::class, $mesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mesaRepository->save($mesa, true);

            return $this->redirectToRoute('restaurante_mesas', ['id' => $restaurante->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mesa/edit.html.twig', [
            'mesa' => $mesa,
            'form' => $form,
            'restaurante' => $restaurante,

        ]);
    }

    /**
     * @Route("/restaurante/{id1}/mesa/{id}", name="app_mesa_delete", methods={"POST"})
     * 
     * @ParamConverter("restaurante", class="App\Entity\Restaurante", options={"id" = "id1"})
     * @ParamConverter("mesa", class="App\Entity\Mesa", options={"id" = "id"})
     */
    public function delete(Request $request, Mesa $mesa, MesaRepository $mesaRepository, Restaurante $restaurante): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mesa->getId(), $request->request->get('_token'))) {
            $mesaRepository->remove($mesa, true);
        }

        return $this->redirectToRoute('restaurante_mesas', ['id' => $restaurante->getId()], Response::HTTP_SEE_OTHER);
    }
}
