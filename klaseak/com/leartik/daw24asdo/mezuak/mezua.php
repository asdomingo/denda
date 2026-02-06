<?php
namespace com\leartik\daw24asdo\mezuak;

class Mezua {
    private $id;
    private $izena;
    private $emaila;
    private $mezua;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIzena() { return $this->izena; }
    public function setIzena($izena) { $this->izena = $izena; }

    public function getEmaila() { return $this->emaila; }
    public function setEmaila($emaila) { $this->emaila = $emaila; }

    public function getMezua() { return $this->mezua; }
    public function setMezua($mezua) { $this->mezua = $mezua; }
}
?>
