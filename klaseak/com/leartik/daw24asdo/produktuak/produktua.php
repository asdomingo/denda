<?php

namespace com\leartik\daw24asdo\produktuak;

class Produktua
{
    private $id;
    private $izena;
    private $prezioa;    
    private $irudia; 
    private $kategoria_id;
    // Inicializamos a 0 para evitar valores null en la BD
    private $eskaintzak = 0;
    private $nobedadeak = 0;
    private $deskontua = 0;

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
        // Forzamos a entero para que el catálogo (if == 1) funcione siempre
        return (int)$this->nobedadeak;
    }

    public function getDeskontua() {
        return $this->deskontua;
    }

    /**
     * Calcula el precio final con descuento
     * deskontua es un porcentaje (0-100)
     * Retorna el precio final: prezioa * (1 - deskontua/100)
     */
    public function getPrezioaDeskontuarekin() {
        if ($this->deskontua > 0) {
            return $this->prezioa * (1 - $this->deskontua / 100);
        }
        return $this->prezioa;
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
        // Aseguramos que guardamos 1 o 0
        $this->nobedadeak = $nobedadeak ? 1 : 0;
    }

    public function setDeskontua($deskontua) {
        $this->deskontua = $deskontua;
    }
}