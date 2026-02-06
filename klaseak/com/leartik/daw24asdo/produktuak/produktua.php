<?php

namespace com\leartik\daw24asdo\produktuak;

class Produktua
{
    private $id;
    private $izena;
    private $prezioa;    
    private $irudia; 
    private $kategoria_id;
    private $eskaintzak;
    private $nobedadeak;
    private $deskontua;
    

    public function getId() {
        return $this->id;
    }

    public function getIzena() {
        return $this->izena;
    }

    public function getPrezioa() {
        return $this->prezioa;
    }

    public function getIrudia() { 
        return $this->irudia;
    }

    public function getKategoriaId() {
        return $this->kategoria_id;
    }

    public function getEskaintzak() {
        return $this->eskaintzak;
    }

    public function getNobedadeak() {
        return $this->nobedadeak;
    }

    public function getDeskontua() {
        return $this->deskontua;
    }
    

    public function setId($id) {
        $this->id = $id;
    }

    public function setIzena($izena) {
        $this->izena = $izena;
    }

    public function setPrezioa($prezioa) {
        $this->prezioa = $prezioa;
    }

    public function setIrudia($irudia) { 
        $this->irudia = $irudia;
    }

    public function setKategoriaId($kategoria_id) {
        $this->kategoria_id = $kategoria_id;
    }

    public function setEskaintzak($eskaintzak) {
        $this->eskaintzak = $eskaintzak;
    }

    public function setNobedadeak($nobedadeak) {
        $this->nobedadeak = $nobedadeak;
    }

    public function setDeskontua($deskontua) {
        $this->deskontua = $deskontua;
    }
}

?>