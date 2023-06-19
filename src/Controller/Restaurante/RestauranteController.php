<?php
namespace App\Controller\Restaurante;


use App\Entity\Reserva;
use App\Entity\Restaurante;
use App\Entity\User;
use App\Repository\RestauranteRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class RestauranteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mis-reservas", name="mis_reservas")
     */
    public function misReservas(Security $security, Request $request)
    {
        $fecha = $request->query->get('fecha');
        $fecha2 = new \DateTime($fecha);
        $fecha = $fecha ?? '';
        $reservas = $this->entityManager->getRepository(Reserva::class)->buscarPorFecha($fecha);

        // renderizar la vista o devolver los resultados en formato JSON, etc.
        return $this->render('menuRestaurante/reservas.html.twig', [
            'reservas' => $reservas,
            'fecha' => $fecha,
        ]);
    }


   
    #[Route('/miRestaurante', name: 'miRestaurante')]
    public function miRestaurante(Security $security)
    {
        // Obtén el usuario autenticado actual
        $usuario = $security->getUser();
        
        // Obtén el restaurante asociado al usuario
        $restaurante = $usuario->getRestaurantes();
        
        // Renderiza la plantilla del restaurante del usuario
        return $this->render('menuRestaurante/editRestaurante.html.twig', [
            'restaurantes' => $restaurante,
        ]);
    }

    
}
