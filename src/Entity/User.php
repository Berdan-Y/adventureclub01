<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $fname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $master = null;

    /**
     * @var Collection<int, Story>
     */
    #[ORM\OneToMany(targetEntity: Story::class, mappedBy: 'user')]
    private Collection $stories;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $iban = null;

    /**
     * @var Collection<int, UserLesson>
     */
    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'student')]
    private Collection $studentLessons;

    /**
     * @var Collection<int, UserLesson>
     */
    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'teacher')]
    private Collection $teacherLesson;

    public function __construct()
    {
        $this->stories = new ArrayCollection();
        $this->studentLessons = new ArrayCollection();
        $this->teacherLesson = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): static
    {
        $this->fname = $fname;

        return $this;
    }

    public function getMaster(): ?string
    {
        return $this->master;
    }

    public function setMaster(?string $master): static
    {
        $this->master = $master;

        return $this;
    }

    /**
     * @return Collection<int, Story>
     */
    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Story $story): static
    {
        if (!$this->stories->contains($story)) {
            $this->stories->add($story);
            $story->setUser($this);
        }

        return $this;
    }

    public function removeStory(Story $story): static
    {
        if ($this->stories->removeElement($story)) {
            // set the owning side to null (unless already changed)
            if ($story->getUser() === $this) {
                $story->setUser(null);
            }
        }

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return Collection<int, UserLesson>
     */
    public function getStudentLessons(): Collection
    {
        return $this->studentLessons;
    }

    public function addStudentLesson(UserLesson $studentLesson): static
    {
        if (!$this->studentLessons->contains($studentLesson)) {
            $this->studentLessons->add($studentLesson);
            $studentLesson->setStudent($this);
        }

        return $this;
    }

    public function removeStudentLesson(UserLesson $studentLesson): static
    {
        if ($this->studentLessons->removeElement($studentLesson)) {
            // set the owning side to null (unless already changed)
            if ($studentLesson->getStudent() === $this) {
                $studentLesson->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLesson>
     */
    public function getTeacherLesson(): Collection
    {
        return $this->teacherLesson;
    }

    public function addTeacherLesson(UserLesson $teacherLesson): static
    {
        if (!$this->teacherLesson->contains($teacherLesson)) {
            $this->teacherLesson->add($teacherLesson);
            $teacherLesson->setTeacher($this);
        }

        return $this;
    }

    public function removeTeacherLesson(UserLesson $teacherLesson): static
    {
        if ($this->teacherLesson->removeElement($teacherLesson)) {
            // set the owning side to null (unless already changed)
            if ($teacherLesson->getTeacher() === $this) {
                $teacherLesson->setTeacher(null);
            }
        }

        return $this;
    }
}
