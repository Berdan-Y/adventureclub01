<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    /**
     * @var Collection<int, UserLesson>
     */
    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'lesson')]
    private Collection $userLessons;

    public function __construct()
    {
        $this->userLessons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, UserLesson>
     */
    public function getUserLessons(): Collection
    {
        return $this->userLessons;
    }

    public function addUserLesson(UserLesson $userLesson): static
    {
        if (!$this->userLessons->contains($userLesson)) {
            $this->userLessons->add($userLesson);
            $userLesson->setLesson($this);
        }

        return $this;
    }

    public function removeUserLesson(UserLesson $userLesson): static
    {
        if ($this->userLessons->removeElement($userLesson)) {
            // set the owning side to null (unless already changed)
            if ($userLesson->getLesson() === $this) {
                $userLesson->setLesson(null);
            }
        }

        return $this;
    }
}
