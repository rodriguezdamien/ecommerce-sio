<?php
class Album
{
    private string $id;
    private string $nom;
    private ?string $description;
    private ?string $lienXFD;
    private ?string $Label;
    private ?string $Artiste;
    private float $prix;
    private ?int $qte;
    private string $uriImage;
    private DateTime $dateSortie;

    function __construct(string $id, string $nom, string $uriImage, float $prix, ?string $Label, ?string $Artiste, ?string $description, ?string $lienXFD, ?int $qte, ?DateTime $dateSortie)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->lienXFD = $lienXFD;
        $this->Label = $Label;
        $this->Artiste = $Artiste;
        $this->prix = $prix;
        $this->qte = $qte;
        $this->uriImage = $uriImage;
        $this->dateSortie = $dateSortie;
    }

    // region Accesseurs
    public function GetId(): string
    {
        return $this->id;
    }

    public function GetNom(): string
    {
        return $this->nom;
    }

    public function GetDescription(): ?string
    {
        return $this->description;
    }

    public function GetLienXFD(): ?string
    {
        return $this->lienXFD;
    }

    public function GetLabel(): ?string
    {
        return $this->Label;
    }

    public function GetArtiste(): ?string
    {
        return $this->Artiste;
    }

    /*
     * Retourne le prix de l'album en float
     */
    public function GetPrixValue(): float
    {
        return $this->prix;
    }

    /*
     * Retourne le prix de l'album en string formaté
     * @return string Prix de l'album formaté
     */
    public function GetPrix()
    {
        return sprintf('%.2f', $this->prix);
    }

    public function GetQte(): ?int
    {
        return $this->qte;
    }

    public function SetQte(int $qte): void
    {
        $this->qte = $qte;
    }

    public function GetUriImage(): string
    {
        return $this->uriImage;
    }

    public function GetDateSortie(): DateTime
    {
        return $this->dateSortie;
    }

    // endregion
}
