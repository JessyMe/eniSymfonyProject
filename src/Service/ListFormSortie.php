<?php


namespace App\Service;


class ListFormSortie
{
private $campus;

private $nom;

private $datedebut;

private $datefin;

private $sortieOrganisateur;

private $sortieInscrit;

private $sortieNonInscrit;

private $sortiePassee;

    /**
     * @return mixed
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     */
    public function setCampus($campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * @param mixed $datedebut
     */
    public function setDatedebut($datedebut): void
    {
        $this->datedebut = $datedebut;
    }

    /**
     * @return mixed
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * @param mixed $datefin
     */
    public function setDatefin($datefin): void
    {
        $this->datefin = $datefin;
    }

    /**
     * @return mixed
     */
    public function getSortieOrganisateur()
    {
        return $this->sortieOrganisateur;
    }

    /**
     * @param mixed $sortieOrganisateur
     */
    public function setSortieOrganisateur($sortieOrganisateur): void
    {
        $this->sortieOrganisateur = $sortieOrganisateur;
    }

    /**
     * @return mixed
     */
    public function getSortieInscrit()
    {
        return $this->sortieInscrit;
    }

    /**
     * @param mixed $sortieInscrit
     */
    public function setSortieInscrit($sortieInscrit): void
    {
        $this->sortieInscrit = $sortieInscrit;
    }

    /**
     * @return mixed
     */
    public function getSortieNonInscrit()
    {
        return $this->sortieNonInscrit;
    }

    /**
     * @param mixed $sortieNonInscrit
     */
    public function setSortieNonInscrit($sortieNonInscrit): void
    {
        $this->sortieNonInscrit = $sortieNonInscrit;
    }

    /**
     * @return mixed
     */
    public function getSortiePassee()
    {
        return $this->sortiePassee;
    }

    /**
     * @param mixed $sortiePassee
     */
    public function setSortiePassee($sortiePassee): void
    {
        $this->sortiePassee = $sortiePassee;
    }





}