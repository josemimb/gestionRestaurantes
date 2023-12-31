<?php

namespace App\Repository;

use App\Entity\Mesa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mesa>
 *
 * @method Mesa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mesa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mesa[]    findAll()
 * @method Mesa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MesaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mesa::class);
    }

    public function save(Mesa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Mesa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findMesasDisponiblesParaReserva(Reserva $reserva)
{
    $qb = $this->createQueryBuilder('m');
    $qb->leftJoin('m.reservas', 'r', Expr\Join::WITH, 'r.fechaHora = :fechaHora');
    $qb->andWhere($qb->expr()->orX(
        $qb->expr()->isNull('r.id'),
        $qb->expr()->neq('r.id', ':reservaId')
    ));
    $qb->andWhere('m.restaurante = :restaurante');
    $qb->setParameter('fechaHora', $reserva->getFechaHora());
    $qb->setParameter('restaurante', $reserva->getRestaurante());
    $qb->setParameter('reservaId', $reserva->getId() ?? -1); // Evita un error si no se proporciona ID de reserva
    $mesas = $qb->getQuery()->getResult();
    return $mesas;
}

//    /**
//     * @return Mesa[] Returns an array of Mesa objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mesa
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
