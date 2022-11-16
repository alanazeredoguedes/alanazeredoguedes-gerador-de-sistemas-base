<?php

namespace App\Application\Internit\EmpreendimentoBundle\Repository;

use App\Application\Internit\EmpreendimentoBundle\Entity\Empreendimento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Empreendimento>
 *
 * @method Empreendimento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empreendimento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empreendimento[]    findAll()
 * @method Empreendimento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpreendimentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Empreendimento::class);
    }


}