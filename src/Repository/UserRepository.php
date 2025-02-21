<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Récupère la liste des départements avec le nombre d'artistes par département
     * et associe chaque département à sa région correspondante.
     *
     * @return array Un tableau contenant les départements, le nombre d'artistes, et la région associée.
     */
    public function findArtistsGroupedByRegion(): array
    {
        // Création du QueryBuilder pour sélectionner les artistes groupés par département
        $qb = $this->createQueryBuilder('u')
            ->select('u.departement, COUNT(u.id) as total_artistes') // Sélectionne le département et le nombre d'artistes
            ->where('u.role = :role') // Filtre uniquement les utilisateurs ayant le rôle "artiste"
            ->setParameter('role', 'artiste') // Définit la valeur du paramètre ":role" à "artiste"
            ->groupBy('u.departement') // Groupe les résultats par département
            ->orderBy('u.departement', 'ASC'); // Trie les départements dans l'ordre croissant

        // Exécution de la requête et récupération des résultats
        $results = $qb->getQuery()->getResult();

        // Parcourt chaque département récupéré pour y ajouter la région correspondante
        foreach ($results as &$result) {
            $result['region'] = \App\Service\RegionHelper::getRegionByDepartement($result['departement']);
            // Utilise la méthode statique de RegionHelper pour récupérer la région correspondant au département
        }

        return $results; // Retourne les résultats enrichis avec la région associée
    }

    public function findArtistsByRegion(string $departement): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->andWhere('u.departement = :departement')
            ->setParameter('role', 'artiste')
            ->setParameter('departement', $departement)
            ->orderBy('u.pseudo', 'ASC')
            ->getQuery()
            ->getResult();
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

