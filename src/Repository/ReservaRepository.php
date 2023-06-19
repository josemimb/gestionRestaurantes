<?php

namespace App\Repository;

use App\Entity\Reserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reserva>
 *
 * @method Reserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserva[]    findAll()
 * @method Reserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    public function save(Reserva $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reserva $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function buscarPorFecha(string $fecha)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.fechaHora = :fecha')
            ->setParameter('fecha', $fecha)
            ->getQuery()
            ->getResult();
    }

    public function findIdRestauranteByUsuario($usuario)
    {
        return $this->createQueryBuilder('r')
            ->select('r.restaurante')
            ->where('r.usuario = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();
    }
    public function findExistingReserva($mesa, $fecha, $hora)
    {
        return $this->createQueryBuilder('r')
            ->where('r.mesa = :mesa')
            ->andWhere('r.fechaHora = :fecha')
            ->andWhere('r.hora = :hora')
            ->setParameter('mesa', $mesa)
            ->setParameter('fecha', $fecha)
            ->setParameter('hora', $hora)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findExistingReservaInRange($mesa, $fecha, $horaSeleccionada)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.mesa = :mesa')
        ->andWhere(':fecha BETWEEN r.fechaInicio AND r.fechaFin')
        ->setParameter('mesa', $mesa)
        ->setParameter('fecha', $fecha)
        ->andWhere(':horaSeleccionada BETWEEN r.horaInicio AND r.horaFin')
        ->setParameter('horaSeleccionada', $horaSeleccionada)
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Reserva[] Returns an array of Reserva objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reserva
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
