<?php
namespace com\leartik\daw24asdo\eskariak;

class Eskaria {
    private $id;
    private $izena;
    private $abizenak;
    private $helbidea;
    private $postapostala;
    private $hiria;
    private $telefono;
    private $email;
    private $notak;
    private $produktua_id;
    private $kantitatea;
    private $data;
    private $estatua; // Zain, Bidalita, Jasota
    private $bezeroa; // Mantenido por compatibilidad

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIzena() { return $this->izena; }
    public function setIzena($izena) { $this->izena = $izena; }

    public function getAbizenak() { return $this->abizenak; }
    public function setAbizenak($abizenak) { $this->abizenak = $abizenak; }

    public function getHelbidea() { return $this->helbidea; }
    public function setHelbidea($helbidea) { $this->helbidea = $helbidea; }

    public function getPostapostala() { return $this->postapostala; }
    public function setPostapostala($postapostala) { $this->postapostala = $postapostala; }

    public function getHiria() { return $this->hiria; }
    public function setHiria($hiria) { $this->hiria = $hiria; }

    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getNotak() { return $this->notak; }
    public function setNotak($notak) { $this->notak = $notak; }

    public function getProduktuaId() { return $this->produktua_id; }
    public function setProduktuaId($produktua_id) { $this->produktua_id = $produktua_id; }

    public function getKantitatea() { return $this->kantitatea; }
    public function setKantitatea($kantitatea) { $this->kantitatea = $kantitatea; }

    public function getData() { return $this->data; }
    public function setData($data) { $this->data = $data; }

    public function getEstatua() { return $this->estatua ?? 'Zain'; }
    public function setEstatua($estatua) { $this->estatua = $estatua; }

    public function getBezeroa() { return $this->bezeroa ?? $this->izena; }
    public function setBezeroa($bezeroa) { $this->bezeroa = $bezeroa; }
}
?>
