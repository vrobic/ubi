<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student.
 *
 * @ORM\Entity
 *
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post",
 *         "get_average_grade"={
 *             "method"="GET",
 *             "route_name"="get_collection_average_grade",
 *             "controller"=StudentController::class,
 *             "openapi_context"={
 *                 "summary"="Calculates the average grade for all students.",
 *                 "parameters"={
 *                 },
 *                 "responses"={
 *                     "200"={
 *                         "description"="A float value",
 *                     },
 *                 },
 *             },
 *             "pagination_enabled"=false,
 *         },
 *     },
 *     itemOperations={
 *         "get",
 *         "put",
 *         "patch",
 *         "delete",
 *         "get_average_grade"={
 *             "method"="GET",
 *             "route_name"="get_item_average_grade",
 *             "controller"=StudentController::class,
 *             "openapi_context"={
 *                 "summary"="Calculates the average grade for a student.",
 *                 "responses"={
 *                     "200"={
 *                         "description"="A float value",
 *                     },
 *                     "404"={
 *                         "description"="Resource not found",
 *                     }
 *                 },
 *             },
 *         },
 *     }
 * )
 */
class Student
{
    /**
     * Identifier.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * First name.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     *
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     */
    private $firstName;

    /**
     * Last name.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     *
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     */
    private $lastName;

    /**
     * Birth date.
     *
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank
     */
    private $birthDate;

    /**
     * Grades.
     *
     * @var Grade[]|Collection
     *
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="student", orphanRemoval=true)
     */
    private $grades;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return self
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTimeInterface $birthDate
     *
     * @return self
     */
    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Grade[]|Collection
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    /**
     * @param Grade $grade
     *
     * @return self
     */
    public function addGrade(Grade $grade): self
    {
        if (!$this->grades->contains($grade)) {
            $this->grades[] = $grade;
            $grade->setStudent($this);
        }

        return $this;
    }

    /**
     * @param Grade $grade
     *
     * @return self
     */
    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->contains($grade)) {
            $this->grades->removeElement($grade);
            // set the owning side to null (unless already changed)
            if ($grade->getStudent() === $this) {
                $grade->setStudent(null);
            }
        }

        return $this;
    }
}
