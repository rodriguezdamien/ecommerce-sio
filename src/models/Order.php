<?php
class Order
{
    private int $id;
    private string $prenomDest;
    private string $nomDest;
    private DateTime $dateHeure;
    private string $adresse;
    private string|null $complementAdresse;
    private string $cp;
    private string $ville;
    private string $numTel;
    private string $mailContact;
    private int $idUser;
    private string $note;
    private float $total;
    private array $orderItems;

    public function __construct(int $id, string $prenomDest, string $nomDest, DateTime $dateHeure, string $adresse, string $complementAdresse = null, string $cp, string $ville, string $numTel, string $mailContact, int $idUser, float $total)
    {
        $this->id = $id;
        $this->prenomDest = $prenomDest;
        $this->nomDest = $nomDest;
        $this->dateHeure = $dateHeure;
        $this->adresse = $adresse;
        $this->complementAdresse = $complementAdresse;
        $this->cp = $cp;
        $this->ville = $ville;
        $this->numTel = $numTel;
        $this->mailContact = $mailContact;
        $this->idUser = $idUser;
        $this->total = $total;
    }

    public function GetId(): int
    {
        return $this->id;
    }

    public function GetPrenomDest(): string
    {
        return $this->prenomDest;
    }

    public function GetNomDest(): string
    {
        return $this->nomDest;
    }

    public function GetDateHeure(): DateTime
    {
        return $this->dateHeure;
    }

    public function GetAdresse(): string
    {
        return $this->adresse;
    }

    public function GetComplementAdresse(): string
    {
        return $this->complementAdresse ?? '';
    }

    public function GetCp(): string
    {
        return $this->cp;
    }

    public function GetVille(): string
    {
        return $this->ville;
    }

    public function GetNumTel(): string
    {
        return $this->numTel;
    }

    public function GetMailContact(): string
    {
        return $this->mailContact;
    }

    public function GetIdUser(): int
    {
        return $this->idUser;
    }

    public function GetNote(): string
    {
        return $this->note;
    }

    public function GetOrderItems(): array
    {
        return $this->orderItems;
    }

    public function SetOrderItems(array $orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    public function GetTotal(): float
    {
        return $this->total;
    }

    public function SetTotal(float $total): void
    {
        $this->total = $total;
    }
}
