<?php

namespace App\Controller\Usuario;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Restaurante;
use App\Entity\Opinion;
use App\Form\RestauranteType;
use App\Form\OpinionType;
use App\Repository\RestauranteRepository;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/perfil', name: 'perfil')]
    public function perfil(): Response
    {
        return $this->render('user/perfil.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/redireccionRoles', name: 'redireccionRoles')]
    public function roles(): Response
    {
        $roles = $this->getUser()->getRoles();

        if (in_array('ROLE_RESTAURANTE', $roles)) {
            return $this->redirectToRoute('miRestaurante');
        } elseif (in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('app_restaurante_index');
        } else {
            return $this->redirectToRoute('verRestaurantes');
        }
    }

    
    
    #[Route('/verRestaurantes', name: 'verRestaurantes', methods: ['GET'])]
    public function listadoRestaurante(Request $request, RestauranteRepository $restauranteRepository): Response
    {

        $busqueda = $request->query->get('busqueda');

            $restaurantes = $restauranteRepository->findAll();

            if ($busqueda) {
                $restaurantes = $restauranteRepository->buscarPorNombre($busqueda);
            }

            $nombresRestaurantes = [];
            foreach ($restaurantes as $restaurante) {
                $nombresRestaurantes[$restaurante->getId()] = $restaurante->getNombre();
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

        return $this->render('user/verRestaurantes.html.twig', [
            'restaurantes' => $restaurantes,
            'nombresRestaurantes' => $nombresRestaurantes,
            'mediaValoraciones' => $mediaValoraciones,
            'totalOpiniones' => $totalOpiniones
        ]);
    }


    #[Route('/misReservas', name: 'misReservas')]
    public function verReservasUsuario(PaginatorInterface $paginator, Request $request, RestauranteRepository $restauranteRepository): Response
    {
        $usuario = $this->getUser();
        $reservas = $usuario->getReservas();

        $nombreRestaurante = $request->query->get('restaurante');

        // Filtrar las reservas por el nombre del restaurante si se proporciona un valor de búsqueda
        if ($nombreRestaurante) {
            $reservas = array_filter($reservas, function ($reserva) use ($nombreRestaurante) {
                $restaurante = $reserva->getRestaurante();
                return $restaurante->getNombre() === $nombreRestaurante;
            });
        }

        $idRestaurantes = [];
        foreach ($reservas as $reserva) {
            $idRestaurantes[] = $reserva->getRestaurante()->getId();
        }

        $pagination = $paginator->paginate(
            $reservas, // Array con los registros a paginar
            $request->query->getInt('page', 1), // Número de página actual
            10 // Número de registros por página
        );

        

        return $this->render('user/listadoReservas.html.twig', [
            'reservas' => $reservas,
            'idRestaurantes' => $idRestaurantes,
            'pagination' => $pagination,
            'nombreRestaurante' => $nombreRestaurante,
        ]);
    }
    
    #[Route('/{id}/opinion/nueva', name:'opinion_nueva', methods: ['GET', 'POST'])]
     
    public function nueva(Request $request, Restaurante $restaurante): Response
    {
        $opinion = new Opinion();
        $opinion->setRestaurante($restaurante);

        $formulario = $this->createForm(OpinionType::class, $opinion);

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($opinion);
            // $entityManager->flush();

            return $this->redirectToRoute('misReservas', ['id' => $restaurante->getId()]);
        }
        return $this->render('opinion/edit.html.twig', [
            'restaurante' => $restaurante,
            'formulario' => $formulario->createView(),
        ]);
    }

    #[Route('/reseñas/{id}', name:'reseñas')]
     
    public function reseñasUser(Restaurante $restaurante): Response
    {
        return $this->render('user/reseñas.html.twig', [
            'restaurante' => $restaurante,
        ]);
    }
}
