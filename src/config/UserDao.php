<?php
namespace Src\DAO;

use Src\Config\Database;

class UserDAO {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function login($nombre, $pass) {
        $conn = $this->db->getConnection();
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $pass = mysqli_real_escape_string($conn, $pass);

        $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario='" . $nombre . "' and password = '" . $pass . "'");
        $nr = mysqli_num_rows($query);

        return $nr;
    }
}
?>
