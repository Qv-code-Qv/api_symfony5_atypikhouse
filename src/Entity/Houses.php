<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\HousesImageController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Houses
 *
 * @ORM\Table(name="houses", indexes={@ORM\Index(name="house_id_user", columns={"ID_user"})})
 * @ORM\Entity
 * @ApiFilter(SearchFilter::class, properties={"city":"exact"}))
 * @ApiFilter(DateFilter::class, properties={"dateDebut: DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER"}))
 * @ApiFilter(DateFilter::class, properties={"dateFin: DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER"}))
 * @ApiFilter(RangeFilter::class, properties={"nbbeds"}))
 * @ApiFilter(BooleanFilter::class, properties={"status"}))
 * @Vich\Uploadable()
 */

#[ApiResource(
    itemOperations:[
        'put',
        'delete',
        'patch',
        'get' => [
            'normalization_context' => ['groups' => ['read:collection', 'read:item', 'read:Post']],
            'openapi_definition_name' => 'Detail',
        ],

        /*requete image*/
        'image' => [
            'method' => 'POST',
            'path' => '/houses/{id}/image',
            'controller' => HousesImageController::class,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    denormalizationContext:['groups' => ['write:Post']],
    normalizationContext:[
        'groups' => ['read:collection'],
        'openapi_definition_name' => 'Collection',
    ],

),

]
    class Houses
{

/**
 * @var int
 *
 * @ORM\Column(name="ID", type="integer", nullable=false)
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="IDENTITY")
 *
 */
        #[Groups(['read:collection'])]
    private $id;

/**
 * @var string
 *
 * @ORM\Column(name="title", type="string", length=50, nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $title;

/**
 * @var string
 *
 * @ORM\Column(name="description", type="text", length=65535, nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $description;

/**
 * @var string
 *
 * @ORM\Column(name="address", type="string", length=255, nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $address;

/**
 * @var int
 *
 * @ORM\Column(name="zipcode", type="integer", nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $zipcode;

/**
 * @var string
 *
 * @ORM\Column(name="city", type="string", length=255, nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $city;

/**
 * @var bool
 *
 * @ORM\Column(name="status", type="boolean", nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $status;

/**
 * @var int
 *
 * @ORM\Column(name="nbBeds", type="integer", nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $nbbeds;

/**
 * @var int
 *
 * @ORM\Column(name="price", type="integer", nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $price;

/**
 * @var int
 *
 * @ORM\Column(name="tax", type="integer", nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $tax;

/**
 * @var array
 *
 * @ORM\Column(name="listID_activities", type="array", nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $listidActivities;

/**
 * @var array
 *
 * @ORM\Column(name="listID_tags", type="array", nullable=false)
 */
    #[Groups(['read:collection', 'write:Post'])]

    private $listidTags;

/**
 * @var \User
 *
 * @ORM\ManyToOne(targetEntity="User")
 * @ORM\JoinColumns({
 * @ORM\JoinColumn(name="ID_user", referencedColumnName="ID")
 * })
 */

    #[Groups(['read:collection', 'write:Post'])]
    private $idUser;

/**
 * @ORM\Column(type="datetime", nullable=true)
 */
    #[Groups(['read:collection', 'write:Post'])]
    private $dateDebut;

/**
 * @ORM\Column(type="datetime", nullable=true)
 */
    #[Groups(['read:collection', 'write:Post'])]
    private $dateFin;

/**
 * @ORM\Column(type="array", nullable=true)
 */
    #[Groups(['read:collection', 'write:Post'])]
    private $listIdEquipements = [];

/**
 * @ORM\Column(type="string", length=255)
 */
    #[Groups(['read:collection', 'write:Post'])]
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filePath;

    /**
     * @var string|null
     */
    #[Groups(['read:collection'])]
    private $fileUrl;

/*fonction qui permet de changer la date de l'image*/
    function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="house_image", fileNameProperty="filePath")
     */

    #[Groups(['write:Post'])]
    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */

    /*date image*/
    #[Groups(['read:collection'])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */

    /*date image*/
    #[Groups(['read:collection'])]
    private $updatedAt;

    function getId(): ?int
    {
        return $this->id;
    }

    function getTitle(): ?string
    {
        return $this->title;
    }

    function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    function getDescription(): ?string
    {
        return $this->description;
    }

    function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    function getAddress(): ?string
    {
        return $this->address;
    }

    function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    function getCity(): ?string
    {
        return $this->city;
    }

    function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    function getStatus(): ?bool
    {
        return $this->status;
    }

    function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    function getNbbeds(): ?int
    {
        return $this->nbbeds;
    }

    function setNbbeds(int $nbbeds): self
    {
        $this->nbbeds = $nbbeds;

        return $this;
    }

    function getPrice(): ?int
    {
        return $this->price;
    }

    function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    function getTax(): ?int
    {
        return $this->tax;
    }

    function setTax(int $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    function getListidActivities(): ?array
    {
        return $this->listidActivities;
    }

    function setListidActivities(array $listidActivities): self
    {
        $this->listidActivities = $listidActivities;

        return $this;
    }

    function getListidTags(): ?array
    {
        return $this->listidTags;
    }

    function setListidTags(array $listidTags): self
    {
        $this->listidTags = $listidTags;

        return $this;
    }

    function getIdUser(): ?User
    {
        return $this->idUser;
    }

    function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    function setDateDebut(?\DateTimeInterface$dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    function setDateFin(?\DateTimeInterface$dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    function getListIdEquipements(): ?array
    {
        return $this->listIdEquipements;
    }

    function setListIdEquipements(?array $listIdEquipements): self
    {
        $this->listIdEquipements = $listIdEquipements;

        return $this;
    }

    function getCategorie(): ?string
    {
        return $this->categorie;
    }

    function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    function getFilePath(): ?string
    {
        return $this->filePath;
    }

    function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return File|null
     */
    function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return Houses
     */
    function setFile(?File $file): Houses
    {
        $this->file = $file;
        return $this;
    }

    function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    function setCreatedAt(\DateTimeInterface$createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    function setUpdatedAt(\DateTimeInterface$updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string|null
     */
    function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    /**
     * @param string|null $fileUrl
     * @return Houses
     */
    function setFileUrl(?string $fileUrl): Houses
    {
        $this->fileUrl = $fileUrl;
        return $this;
    }

}
