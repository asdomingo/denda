<?php
namespace com\leartik\daw24asdo\eskariak;

class Eskaria {
    private $id;
    private $bezeroa;
    private $helbidea;
    private $produktua_id;
    private $kantitatea;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getBezeroa() { return $this->bezeroa; }
    public function setBezeroa($bezeroa) { $this->bezeroa = $bezeroa; }

    public function getHelbidea() { return $this->helbidea; }
    public function setHelbidea($helbidea) { $this->helbidea = $helbidea; }

    public function getProduktuaId() { return $this->produktua_id; }
    public function setProduktuaId($produktua_id) { $this->produktua_id = $produktua_id; }

    public function getKantitatea() { return $this->kantitatea; }
    public function setKantitatea($kantitatea) { $this->kantitatea = $kantitatea; }
}
?>
