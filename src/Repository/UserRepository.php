<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\RegionHelper;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


 // Afficher la liste des régions contenant des artistes
        public function findRegionsWithArtists(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.departement, COUNT(u.id) as total_artistes')
            ->where('u.role = :role')
            ->setParameter('role', 'artiste')
            ->groupBy('u.departement')
            ->orderBy('u.departement', 'ASC');

        $results = $qb->getQuery()->getResult();

        // Tableau associatif pour stocker les régions uniques
        $regionsWithArtists = [];

        // Ajouter la région associée et éviter les doublons
        foreach ($results as $result) {
            $region = \App\Service\RegionHelper::getRegionByDepartement($result['departement']);

            if (!isset($regionsWithArtists[$region])) {
                $regionsWithArtists[$region] = [
                    'region' => $region,
                    'total_artistes' => 0
                ];
            }

            // Additionner le nombre total d'artistes pour chaque région
            $regionsWithArtists[$region]['total_artistes'] += $result['total_artistes'];
        }

        return array_values($regionsWithArtists);
    }



// Trouve tous les départements appartenant à la région donné
// Récupère les artistes ayant un département correspondant
    public function findArtistsByRegion(string $region): array
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', 'artiste');

        $departements = [];

        // Accéder aux départements via la méthode statique
        foreach (RegionHelper::getDepartementsRegions() as $departement => $regionName) {
            if ($regionName === $region) {
                $departements[] = $departement;
            }
        }

        if (!empty($departements)) {
            $qb->andWhere('u.departement IN (:departements)')
                ->setParameter('departements', $departements);
        }

        return $qb->orderBy('u.pseudo', 'ASC')->getQuery()->getResult();
    }

    


}
    
    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

