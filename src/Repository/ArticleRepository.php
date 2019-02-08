<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Récupère les 5 derniers articles
     * @return mixed
     */
    public function findLastArticle()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults('5')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupère les articles en avant slider
     * @return mixed
     */
    public function findBySpotlight()
    {
        return $this->createQueryBuilder('a')
            ->where('a.spotlight = 1')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults('5')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère les articles de la sidebar a la une
     * @return mixed
     */
    public function findBySpecial()
    {
        return $this->createQueryBuilder('a')
            ->where('a.special = 1')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults('5')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère les articles de la sidebar
     * @return mixed
     */
    public function findArticleSuggestion($idAarticle, $idCategorie)
    {
        return $this->createQueryBuilder('a')
            ->where("a.categorie = :categorie_id")
            ->setParameter('categorie_id', $idCategorie)
            ->andWhere("a.id <> :article_id")
            ->setParameter('article_id', $idAarticle)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults('3')

            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
