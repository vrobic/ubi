<?php

namespace App\Repository;

use App\Entity\Grade;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Grades repository.
 */
class GradeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    /**
     * Get the average grade for all students.
     *
     * @return float
     */
    public function getAverageGrade(): float
    {
        return (float) $this->createQueryBuilder('g')
            ->select('AVG(g.value)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get the average grade for a student.
     *
     * @param Student $student
     *
     * @return float
     */
    public function getAverageGradeForStudent(Student $student): float
    {
        return (float) $this->createQueryBuilder('g')
            ->select('AVG(g.value)')
            ->join('g.student', 's')
            ->andWhere('s.id IN (:student_id)')
            ->setParameter('student_id', $student->getId())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
