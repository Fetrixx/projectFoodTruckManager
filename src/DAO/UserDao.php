<?php
namespace Src\DAO;

use Src\Config\Database;

class UserDAO {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getUserByUsernameAndEmail($username, $email) {
        $conn = $this->db->getConnection();
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);

        $sql = "SELECT * FROM usuarios WHERE nombre = '$username' AND email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function login($email, $password) {
        // Mostrar email y password ingresados (ojo: no recomendable mostrar password en producción)
        echo "<script>console.log('Email ingresado: " . addslashes($email) . "');</script>";
        echo "<script>console.log('Password ingresado: " . addslashes($password) . "');</script>";

        $conn = $this->db->getConnection();

        if (!$conn) {
            echo "<script>console.log('Error: No se pudo obtener la conexión a la base de datos');</script>";
            error_log("Error: No se pudo obtener la conexión a la base de datos");
            return null;
        }
        echo "<script>console.log('Conexión a BD exitosa');</script>";

        $email_esc = mysqli_real_escape_string($conn, $email);
        echo "<script>console.log('Email escapado: " . addslashes($email_esc) . "');</script>";

        $query = "SELECT * FROM usuarios WHERE email = '$email_esc'";
        echo "<script>console.log('Query SQL: " . addslashes($query) . "');</script>";

        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "<script>console.log('Error en la consulta SQL: " . addslashes(mysqli_error($conn)) . "');</script>";
            error_log("Error en la consulta SQL: " . mysqli_error($conn));
            return null;
        }

        echo "<script>console.log('Consulta ejecutada correctamente');</script>";
        echo "<script>console.log('Número de filas encontradas: " . mysqli_num_rows($result) . "');</script>";

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            echo "<script>console.log('Usuario encontrado: " . addslashes(json_encode($user)) . "');</script>";

            // para password sin hash: 
            if ($password === $user['password']) {
                // Contraseña correcta
                return $user;
            } else {
                // Contraseña incorrecta
                return null;
            }

            /* // en caso de tener una password con hash
            if (password_verify($password, $user['password'])) {
                echo "<script>console.log('Contraseña verificada correctamente');</script>";
                return $user; // Retorna la información del usuario
            } else {
                echo "<script>console.log('La contraseña no coincide');</script>";
                error_log("Login fallido: contraseña incorrecta para email $email_esc");
            }
            */
        } else {
            echo "<script>console.log('No se encontró usuario con ese email');</script>";
            error_log("Login fallido: usuario no encontrado con email $email_esc");
        }

        return null;
    }


    public function registerUser($nombre, $email, $password) {
        $conn = $this->db->getConnection();
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $email = mysqli_real_escape_string($conn, $email);
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash seguro

        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    // Para la página "Acerca de mí" (individual)
    //public function getAlumnoEmedina($cedula) {
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
}
?>
