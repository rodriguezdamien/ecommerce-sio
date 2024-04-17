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

    function __construct(string $id, string $nom, string $uriImage, float $prix, ?string $Label, ?string $Artiste, ?string $description, ?string $lienXFD, ?int $qte)
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

    public function GetPrix(): float
    {
        return $this->prix;
    }

    public function GetQte(): ?int
    {
        return $this->qte;
    }

    public function GetUriImage(): string
    {
        return $this->uriImage;
    }

    public function SetNom(string $nom)
    {
        $this->nom = $nom;
    }

    public function SetDescription(?string $description)
    {
        $this->description = $description;
    }

    public function SetLienXFD(?string $lienXFD)
    {
        $this->lienXFD = $lienXFD;
    }

    public function SetLabel(?string $Label)
    {
        $this->Label = $Label;
    }

    public function SetArtiste(?string $Artiste)
    {
        $this->Artiste = $Artiste;
    }

    public function SetPrix(float $prix)
    {
        $this->prix = $prix;
    }

    public function SetQte(?int $qte)
    {
        $this->qte = $qte;
    }

    public function SetUriImage(string $uriImage)
    {
        $this->uriImage = $uriImage;
    }

    // endregion
}
