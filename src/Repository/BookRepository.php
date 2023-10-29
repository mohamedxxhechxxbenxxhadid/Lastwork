<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use \Datetime;
use Doctrine\ORM\Query\Expr\GroupBy;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function searchBookByRef($ref){
        $qb = $this->createQueryBuilder('a')
        ->where('a.ref= :ref')
        ->setParameter('ref', (int)$ref);
        $query = $qb->getQuery();
        return $query->getResult();
    } 
    public function booksListByAuthors($author){
        $qb = $this->createQueryBuilder('b')
        ->Join('b.author','a')
        ->where('b.author = a.id AND a.username = :author ')
        ->setParameter('author',$author);
        $query = $qb->getQuery();
        return $query->getResult();
    }
    public function booksList2023(){
        $datetime = new DateTime();
        $qb = $this->createQueryBuilder('b')
        ->innerJoin('b.author','a')
        ->where("b.publicationDate < :currentDate AND a.nb_books > 10")
        ->setParameter('currentDate', $datetime->createFromFormat('Y/m/d', '2023/01/01'));
        $query = $qb->getQuery();
        return $query->getResult();
    }
    public function SciencetoRomance(){
        $qb = $this->createQueryBuilder('b')
        ->update()
        ->set('b.category', ':newCategory')
        ->where('b.category = :oldCategory')
        ->setParameter('newCategory', 'Romance') 
        ->setParameter('oldCategory', 'Science-Fiction'); 
        $qb->getQuery()->execute();
    }
    public function numberOfRomance(){
        $qb = $this->createQueryBuilder('b')
        ->select("COUNT('*') ")
        ->where('b.category = :Category')
        ->setParameter('Category', 'Romance'); 
        $qb=$qb->getQuery();
        $qb = $qb->getResult();
        return $qb;
    }
    public function livresPublies(){
        $datetime = new DateTime();
        $qb = $this->createQueryBuilder('b')
        ->where("b.publicationDate < :date1 AND b.publicationDate > :date2")
        ->setParameter('date1', $datetime->createFromFormat('Y/m/d', '2018/01/01'))
        ->setParameter('date2', $datetime->createFromFormat('Y/m/d', '2014/01/01'));
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
