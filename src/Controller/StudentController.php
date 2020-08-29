<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * A custom controller for the students.
 */
class StudentController extends AbstractController
{
    /**
     * Get the average grade for all students.
     *
     * @Route(
     *     name="get_collection_average_grade",
     *     path="/students/average-grade",
     *     methods={"GET"},
     * )
     *
     * @return JsonResponse
     */
    public function collectionAverageGrade(): JsonResponse
    {
        // @var float
        $averageGrade = $this
            ->getDoctrine()
            ->getRepository(Grade::class)
            ->getAverageGrade()
        ;

        return new JsonResponse($averageGrade);
    }

    /**
     * Get the average grade for a student.
     *
     * @Route(
     *     name="get_item_average_grade",
     *     path="/students/{id}/average-grade",
     *     methods={"GET"},
     * )
     *
     * @param string $id The student identifier
     *
     * @return JsonResponse
     */
    public function itemAverageGrade(string $id): JsonResponse
    {
        $response = new JsonResponse(null, Response::HTTP_NOT_FOUND);

        // @var Student|null
        $student = $this
            ->getDoctrine()
            ->getRepository(Student::class)
            ->find((int) $id)
        ;

        if (null !== $student) {
            // @var float
            $averageGrade = $this
                ->getDoctrine()
                ->getRepository(Grade::class)
                ->getAverageGradeForStudent($student)
            ;

            $response
                ->setContent($averageGrade)
                ->setStatusCode(Response::HTTP_NOT_FOUND)
            ;
        }

        return $response;
    }
}
