<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\api\ApiController;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;




/**
 * User
 * @ApiFilter(SearchFilter::class, properties={"email":"exact"}))
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Adresse mail déjà utilisé")
 */
#[ApiResource()]
class User implements UserInterface, EncoderAwareInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[Groups(['read:User'])]
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="type", type="boolean", nullable=true)
     */
    private bool $isActive;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email(message="Le format de l'email '{{ value }}' est incorrect")
     */
    #[Groups(['read:User'])]
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private string $password;

    /**
     * A non-persisted field that's used to create the encoded password.
     *
     * @var string|null
     */
    private ?string $plainPassword = null;

    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le nom doit avoir au minimum 2 lettres ?"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-z\s+a-z+ÖØ-öø-ÿ]+$/i",
     *     message="Votre nom ne doit pas comporter de chiffre et ni de symbole"
     * )
     */
    private string $lastname;

    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le prénom doit avoir au minimum 2 lettres ?"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-z\s+a-z+ÖØ-öø-ÿ]+$/i",
     *     message="Votre prénom ne doit pas comporter de chiffre et ni de symbole"
     * )
     */
    private string $firstname;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "Avez vous bien saisi votre adresse ?"
     * )
     */
    private string $address;

    /**
     * @var int
     *
     * @ORM\Column(name="zipcode", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "Votre code postal doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre code postal ne doit pas faire plus de {{ limit }} caractères"
     * )
     */
    private int $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * 
     */
    private string $city;

    /**
     * @ORM\Column(name="phone", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/i",
     *     message="Veuillez saisir seulement des chiffres"
     * )
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      minMessage = "Votre numéro de téléphonne doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre numéro de téléphonne ne doit pas faire plus de {{ limit }} caractères"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['read:User'])]
    private array $roles = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private bool $isVerified = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $lastLogin;


    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate(DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return DateTime
     */
    public function getLastLogin(): DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     */
    public function setLastLogin(DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }



    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    function addRole($role) {
        $this->roles[] = $role;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getEncoderName()
    {
        // TODO: Implement getEncoderName() method.
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function serialize()
    {

        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));

    }

    public function unserialize($serialized) {

        list (
            $this->id,
            $this->email ,
            $this->password,
            ) = unserialize($serialized);
    }

    public function __draw(): array
    {
        return [
            "email" => $this->email,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "address" => $this->address,
            "zipcode" => $this->zipcode,
            "roles" => $this->roles,
        ];
    }
}
