<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 *  @UniqueEntity("username")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *  min = 6,
     *  minMessage = "Le mot de passe doit comprendre minimum {{ limit }} caractères",
     *  allowEmptyString = false
     * )
     */
    private $password;


    /**
     * @Assert\EqualTo(propertyPath = "password", message = "Les mots de passe doivent être identiques")
    */
    private $verifPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getVerifPassword(): ?string
    {
        return $this->verifPassword;
    }

    public function setVerifPassword(string $verifPassword): self
    {
        $this->verifPassword = $verifPassword;

        return $this;
    }

    public function getRoles()
    {
        return [$this->roles];
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
    }
}
