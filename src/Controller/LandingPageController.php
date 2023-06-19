<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestauranteRepository;



#[Route('/')]
class LandingPageController extends AbstractController
{
    #[Route('/', name: 'inicio')]
    public function index2(RestauranteRepository $restauranteRepository): Response
    {

        $restaurants = $restauranteRepository->findAll();
        $restaurants = array_slice($restaurants, 0, 6); // selecciona los primeros 10 restaurantes

        $totalOpiniones = [];

        foreach ($restaurants as $restaurante) {
            $opiniones = $restaurante->getOpinions();
            $totalOpiniones[$restaurante->getId()] = count($opiniones);
        }

        $mediaValoraciones = [];

        foreach ($restaurants as $restaurante) {
            $opiniones = $restaurante->getOpinions();
            $totalOpinions = count($opiniones);
            $sumaValoraciones = 0;
        
            foreach ($opiniones as $opinion) {
                $sumaValoraciones += $opinion->getValoracion();
            }
        
            if ($totalOpinions > 0) {
                $mediaValoraciones[$restaurante->getId()] = number_format($sumaValoraciones / $totalOpinions, 1);
            } else {
                $mediaValoraciones[$restaurante->getId()] = 0;
            }
        }
        return $this->render('ladingPage/landingPage.html.twig', [
            'controller_name' => 'LandingPageController',
            'restaurants' => $restaurants,
            'mediaValoraciones' => $mediaValoraciones,
            'totalOpiniones' => $totalOpiniones

        ]);
    }
    #[Route('/servicios', name: 'servicios')]
    public function servicios(): Response
    {
        return $this->render('ladingPage/servicios.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }
   
    #[Route('/listaRestaurantes', name: 'listaRestaurantes')]
    public function index3(RestauranteRepository $restauranteRepository): Response
    {
        $restaurantes = $restauranteRepository->findAll();

        $restaurants = $restauranteRepository->findAll();
        $restaurants = array_slice($restaurants, 0, 6); // selecciona los primeros 10 restaurantes
        $totalOpiniones = [];

        foreach ($restaurants as $restaurante) {
            $opiniones = $restaurante->getOpinions();
            $totalOpiniones[$restaurante->getId()] = count($opiniones);
        }

        $mediaValoraciones = [];

        foreach ($restaurants as $restaurante) {
            $opiniones = $restaurante->getOpinions();
            $totalOpinions = count($opiniones);
            $sumaValoraciones = 0;
        
            foreach ($opiniones as $opinion) {
                $sumaValoraciones += $opinion->getValoracion();
            }
        
            if ($totalOpinions > 0) {
                $mediaValoraciones[$restaurante->getId()] = number_format($sumaValoraciones / $totalOpinions, 1);
            } else {
                $mediaValoraciones[$restaurante->getId()] = 0;
            }
        }

        $totalOpiniones = [];

        foreach ($restaurantes as $restaurante) {
            $opiniones = $restaurante->getOpinions();
            $totalOpiniones[$restaurante->getId()] = count($opiniones);
        }

        $mediaValoraciones = [];

        foreach ($restaurantes as $restaurante) {
            $opiniones = $restaurante->getOpinions();
            $totalOpinions = count($opiniones);
            $sumaValoraciones = 0;
        
            foreach ($opiniones as $opinion) {
                $sumaValoraciones += $opinion->getValoracion();
            }
        
            if ($totalOpinions > 0) {
                $mediaValoraciones[$restaurante->getId()] = number_format($sumaValoraciones / $totalOpinions, 1);
            } else {
                $mediaValoraciones[$restaurante->getId()] = 0;
            }
        }

        return $this->render('ladingPage/restaurantes.html.twig', [
            'controller_name' => 'LandingPageController',
            'restaurantes' => $restaurantes,
            'restaurants' => $restaurants,
            'mediaValoraciones' => $mediaValoraciones,
            'totalOpiniones' => $totalOpiniones
        ]);
    }

    
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('ladingPage/about.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }
    #[Route('/contacto', name: 'contacto')]
    public function contacto(): Response
    {
        return $this->render('ladingPage/contactanos.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }

    #[Route('/base', name: 'base')]
    public function base(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }
}
