<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking", indexes={@ORM\Index(name="booking_id_user", columns={"ID_user"}), @ORM\Index(name="booking_id_house", columns={"ID_house"})})
 * @ORM\Entity
 */
#[ApiResource]
class Booking
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPersons", type="integer", nullable=false)
     */
    private $nbpersons;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="date", nullable=false)
     */
    private $datestart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnd", type="date", nullable=false)
     */
    private $dateend;

    /**
     * @var \Houses
     *
     * @ORM\ManyToOne(targetEntity="Houses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_house", referencedColumnName="ID")
     * })
     */
    private $idHouse;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_user", referencedColumnName="ID")
     * })
     */
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNbpersons(): ?int
    {
        return $this->nbpersons;
    }

    public function setNbpersons(int $nbpersons): self
    {
        $this->nbpersons = $nbpersons;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDatestart(): ?\DateTimeInterface
    {
        return $this->datestart;
    }

    public function setDatestart(\DateTimeInterface $datestart): self
    {
        $this->datestart = $datestart;

        return $this;
    }

    public function getDateend(): ?\DateTimeInterface
    {
        return $this->dateend;
    }

    public function setDateend(\DateTimeInterface $dateend): self
    {
        $this->dateend = $dateend;

        return $this;
    }

    public function getIdHouse(): ?Houses
    {
        return $this->idHouse;
    }

    public function setIdHouse(?Houses $idHouse): self
    {
        $this->idHouse = $idHouse;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
