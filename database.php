<?php
class Database {
    private $host = "localhost";
    private $dbname = "nim1";
    private $username = "root";
    private $password = "";
    public $db;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
}
?>
