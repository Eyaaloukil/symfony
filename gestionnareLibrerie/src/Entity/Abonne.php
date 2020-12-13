<?php

namespace App\Entity;

use App\Repository\AbonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=AbonneRepository::class)
 * @UniqueEntity(
 * fields={"email"},
 * message="L'email que vous avez indiqué est déja utilisé ! "
 * )
 */
class Abonne implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;
    /**
     * @ORM\Column(type="json")
     */
    private $roles=[];
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8",minMessage="Votre Mot de passe doit faire minimum 8 caractéres")
     * @Assert\EqualTo(propertyPath="confirm_password")
     */
    private $password;
/**
 *@Assert\EqualTo(propertyPath="password")
 */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="Abonne")
     */
    private $emprunts;

    public function __construct()
    {
        $this->emprunts = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->prenom;
    }

    public function setUsername(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
    public function eraseCredentials(){

    }
    public function getSalt(){}
    public function getRoles(){
$roles=$this->roles;
$roles[]="ROLE_USER";
return array_unique($roles);    }

    /**
     * @return Collection|Emprunt[]
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): self
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts[] = $emprunt;
            $emprunt->setAbonne($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getAbonne() === $this) {
                $emprunt->setAbonne(null);
            }
        }

        return $this;
    }
}
