<?php
namespace Src\Config;

class Database {
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpass = "";
    private $dbname = "practica1";
    public $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        if (!$this->conn) {
            die("No hay conexion: " . mysqli_connect_error());
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        mysqli_close($this->conn);
    }
}
?>


<?php
/*
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "practica1";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("No hay conexion". mysqli_connect_error());
}
*/
?>
