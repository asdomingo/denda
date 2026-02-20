<?php
namespace com\leartik\daw24asdo\eskariak;
use PDO;

class EskariaDB {
    private static function getDbPath() {
        // Detectar si estamos en local o en AWS
        $isLocal = strpos(__FILE__, 'C:\\xampp') !== false || strpos(__FILE__, 'xampp') !== false;
        
        if ($isLocal) {
            return "sqlite:C:\\xampp\\htdocs\\denda_sqlite\\produktuak.db";
        } else {
            // Para AWS/Linux
            return "sqlite:" . dirname(__FILE__, 6) . DIRECTORY_SEPARATOR . 'produktuak.db';
        }
    }

    private static function localLog($msg) {
        $logFile = dirname(__FILE__, 6) . DIRECTORY_SEPARATOR . 'debug.log';
        file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
    }

    /**
     * Obtiene todas las eskariak (pedidos)
     */
    public static function selectEskariak() {
        try {
            $db = new PDO(self::getDbPath());
            $erregistroak = $db->query("SELECT * FROM eskariak ORDER BY data DESC, id DESC");
            $eskariak = array();
            while ($erregistroa = $erregistroak->fetch(PDO::FETCH_ASSOC)) {
                $eskariak[] = self::mapearEskaria($erregistroa);
            }
            return $eskariak;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtiene una eskaria (pedido) específica por ID
     */
    public static function selectEskaria($id) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("SELECT * FROM eskariak WHERE id = ?");
            $stmt->execute([$id]);
            if ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return self::mapearEskaria($erregistroa);
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtiene todos los pedidos de un cliente (por email o nombre)
     */
    public static function selectEskariasByEmail($email) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("SELECT DISTINCT * FROM eskariak WHERE email = ? ORDER BY data DESC");
            $stmt->execute([$email]);
            $eskariak = array();
            while ($erregistroa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eskariak[] = self::mapearEskaria($erregistroa);
            }
            return $eskariak;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtiene los detalles completos de un pedido con todos sus productos
     */
    public static function selectEskariaDetalles($id) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("SELECT * FROM eskariak WHERE id = ?");
            $stmt->execute([$id]);
            
            $detalles = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $detalles[] = self::mapearEskaria($row);
            }
            return $detalles;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Inserta una nueva eskaria (pedido)
     */
    public static function insertEskaria($eskaria) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("
                INSERT INTO eskariak 
                (izena, abizenak, helbidea, postapostala, hiria, telefono, email, notak, produktua_id, kantitatea, data) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $eskaria->getIzena(),
                $eskaria->getAbizenak(),
                $eskaria->getHelbidea(),
                $eskaria->getPostapostala(),
                $eskaria->getHiria(),
                $eskaria->getTelefono(),
                $eskaria->getEmail(),
                $eskaria->getNotak(),
                $eskaria->getProduktuaId(),
                $eskaria->getKantitatea(),
                date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Actualiza una eskaria existente
     */
    public static function updateEskaria($eskaria) {
        try {
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("
                UPDATE eskariak 
                SET izena = ?, abizenak = ?, helbidea = ?, postapostala = ?, hiria = ?, 
                    telefono = ?, email = ?, notak = ?, produktua_id = ?, kantitatea = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $eskaria->getIzena(),
                $eskaria->getAbizenak(),
                $eskaria->getHelbidea(),
                $eskaria->getPostapostala(),
                $eskaria->getHiria(),
                $eskaria->getTelefono(),
                $eskaria->getEmail(),
                $eskaria->getNotak(),
                $eskaria->getProduktuaId(),
                $eskaria->getKantitatea(),
                $eskaria->getId()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Elimina una eskaria por ID
     */
    public static function deleteEskaria($id) {
        try {
            self::localLog("Trying to delete eskaria ID: $id");
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("DELETE FROM eskariak WHERE id = ?");
            $success = $stmt->execute([$id]);
            self::localLog("Delete result: " . ($success ? "Success" : "Failed"));
            return $success;
        } catch (\Exception $e) {
            self::localLog("deleteEskaria Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza el estado de una eskaria
     */
    public static function updateEstatua($id, $estatua) {
        try {
            self::localLog("Trying to update status for ID: $id to $estatua");
            $db = new PDO(self::getDbPath());
            $stmt = $db->prepare("UPDATE eskariak SET estatua = ? WHERE id = ?");
            $success = $stmt->execute([$estatua, $id]);
            self::localLog("Update result: " . ($success ? "Success" : "Failed"));
            return $success;
        } catch (\Exception $e) {
            self::localLog("updateEstatua Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mapea un registro SQL a un objeto Eskaria
     */
    private static function mapearEskaria($erregistroa) {
        $eskaria = new Eskaria();
        $eskaria->setId($erregistroa['id'] ?? null);
        $eskaria->setIzena($erregistroa['izena'] ?? '');
        $eskaria->setAbizenak($erregistroa['abizenak'] ?? '');
        $eskaria->setHelbidea($erregistroa['helbidea'] ?? '');
        $eskaria->setPostapostala($erregistroa['postapostala'] ?? '');
        $eskaria->setHiria($erregistroa['hiria'] ?? '');
        $eskaria->setTelefono($erregistroa['telefono'] ?? '');
        $eskaria->setEmail($erregistroa['email'] ?? '');
        $eskaria->setNotak($erregistroa['notak'] ?? '');
        $eskaria->setProduktuaId($erregistroa['produktua_id'] ?? null);
        $eskaria->setKantitatea($erregistroa['kantitatea'] ?? 1);
        $eskaria->setData($erregistroa['data'] ?? null);
        $eskaria->setEstatua($erregistroa['estatua'] ?? 'Zain');
        // Mantenemos compatibilidad con bezeroa
        $eskaria->setBezeroa($erregistroa['bezeroa'] ?? '');
        return $eskaria;
    }
}
?>
