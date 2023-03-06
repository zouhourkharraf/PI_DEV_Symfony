<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;


/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function save(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

    }

    public  function sms(){
// Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC6c526778abadd654ee726d7cafb49951';
        $auth_token = '6f59d6aeed838a1f3ef292f59f28041a';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
// A Twilio number you own with SMS capabilities
        $twilio_number = "+12762849300";

        $client = new Client($sid, $auth_token);
        $client->messages->create(
        // the number you'd like to send the message to
            '+21695173280',
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+12764092348',
                // the body of the text message you'd like to send
                'body' => 'Une participation est ajouté !'
            ]
        );
    }

//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByLocation($lieu)
    {
        return $this->createQueryBuilder('e')
            ->where('e.lieu_ev LIKE :lieu')
            ->setParameter('lieu', '%'.$lieu.'%')
            ->getQuery()
            ->getResult();
    }

    public function searchEvent($nom) :array {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT s FROM App\Entity\Evenement s WHERE s.nom_ev=:nom')
            ->setParameter('nom',$nom);

        return $query->getResult();
    }

    public function search(string $query)
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('a')
            ->from(Evenement::class, 'a')
            ->where('a.nom_ev LIKE :query OR a.content LIKE :query')
            ->setParameter('query', '%' . $query . '%');

        return $qb->getQuery()->getResult();
    }

    public function findStudentByLieu($lieu){
        return $this->createQueryBuilder("s")
            ->where('s.lieu_ev LIKE :lieu')
            ->setParameter('lieu', '%'.$lieu.'%')
            ->getQuery()
            ->getResult();
    }
}
