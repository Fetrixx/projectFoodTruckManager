<?php
namespace Src\DAO;

use Src\Config\Database;

class AlumnoDAO {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAlumnoByCedula($cedula) {
        $conn = $this->db->getConnection();
        $cedula = mysqli_real_escape_string($conn, $cedula);

        $sql = "SELECT * FROM alumno_emedina WHERE cedula = '$cedula'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function createAlumno($nombres, $cedula, $correo) {
        $conn = $this->db->getConnection();
        $nombres = mysqli_real_escape_string($conn, $nombres);
        $cedula = mysqli_real_escape_string($conn, $cedula);
        $correo = mysqli_real_escape_string($conn, $correo);

        $sql = "INSERT INTO alumno_emedina (nombres, cedula, correo) VALUES ('$nombres', '$cedula', '$correo')";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAlumno($id, $nombres, $correo) {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $id);
        $nombres = mysqli_real_escape_string($conn, $nombres);
        $correo = mysqli_real_escape_string($conn, $correo);

        $sql = "UPDATE alumno_emedina SET nombres = '$nombres', correo = '$correo' WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteAlumno($cedula) {
        $conn = $this->db->getConnection();
        $cedula = mysqli_real_escape_string($conn, $cedula);
        
        $sql = "DELETE FROM alumno_emedina WHERE cedula = '$cedula'";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
