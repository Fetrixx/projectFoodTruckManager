<?php
namespace Src\DAO;

use Src\Config\Database;

class FoodTruckDAO {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Obtiene todos los food trucks con promedio de rating y número de reseñas.
     * Retorna un array con los datos.
     */
    public function getAllFoodTrucks() {
        $conn = $this->db->getConnection();

        // Consulta con LEFT JOIN para obtener promedio y cantidad de reseñas
        $sql = "
            SELECT 
                ft.*,
                IFNULL(AVG(r.rating), 0) AS avg_rating,
                COUNT(r.id) AS review_count
            FROM foodtrucks ft
            LEFT JOIN reviews r ON r.foodtruck_id = ft.id
            GROUP BY ft.id
            ORDER BY ft.nombre ASC
        ";

        $result = mysqli_query($conn, $sql);

        $foodTrucks = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $foodTrucks[] = $row;
            }
        }
        return $foodTrucks;
    }

    /**
     * Obtiene un food truck por ID, incluyendo su menú.
     */
    public function getFoodTruckById($id) {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $id);

        // Obtener datos del food truck
        $sql = "SELECT * FROM foodtrucks WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (!$result || mysqli_num_rows($result) !== 1) {
            return null;
        }

        $foodTruck = mysqli_fetch_assoc($result);

        // Obtener menú asociado
        $sqlMenu = "SELECT * FROM menus WHERE foodtruck_id = '$id' ORDER BY nombre ASC";
        $resultMenu = mysqli_query($conn, $sqlMenu);

        $menu = [];
        if ($resultMenu) {
            while ($row = mysqli_fetch_assoc($resultMenu)) {
                $menu[] = $row;
            }
        }

        $foodTruck['menu'] = $menu;

        return $foodTruck;
    }

    public function createFoodTruck($nombre, $descripcion, $ubicacion, $lat, $lng, $horario_apertura, $horario_cierre, $imagen) {
        $conn = $this->db->getConnection();
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $descripcion = mysqli_real_escape_string($conn, $descripcion);
        $ubicacion = mysqli_real_escape_string($conn, $ubicacion);
        $lat = mysqli_real_escape_string($conn, $lat);
        $lng = mysqli_real_escape_string($conn, $lng);
        $horario_apertura = mysqli_real_escape_string($conn, $horario_apertura);
        $horario_cierre = mysqli_real_escape_string($conn, $horario_cierre);
        $imagen = mysqli_real_escape_string($conn, $imagen);

        $sql = "INSERT INTO foodtrucks (nombre, descripcion, ubicacion, lat, lng, horario_apertura, horario_cierre, imagen) 
                VALUES ('$nombre', '$descripcion', '$ubicacion', '$lat', '$lng', '$horario_apertura', '$horario_cierre', '$imagen')";

        if (mysqli_query($conn, $sql)) {
            return mysqli_insert_id($conn); // Retorna el ID del nuevo food truck
        } else {
            return false;
        }
    }

    public function updateFoodTruck($id, $nombre, $descripcion, $ubicacion, $lat, $lng, $horario_apertura, $horario_cierre, $imagen) {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $id);
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $descripcion = mysqli_real_escape_string($conn, $descripcion);
        $ubicacion = mysqli_real_escape_string($conn, $ubicacion);
        $lat = mysqli_real_escape_string($conn, $lat);
        $lng = mysqli_real_escape_string($conn, $lng);
        $horario_apertura = mysqli_real_escape_string($conn, $horario_apertura);
        $horario_cierre = mysqli_real_escape_string($conn, $horario_cierre);
        $imagen = mysqli_real_escape_string($conn, $imagen);

        $sql = "UPDATE foodtrucks SET 
                nombre = '$nombre', 
                descripcion = '$descripcion', 
                ubicacion = '$ubicacion', 
                lat = '$lat', 
                lng = '$lng', 
                horario_apertura = '$horario_apertura', 
                horario_cierre = '$horario_cierre', 
                imagen = '$imagen' 
                WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteFoodTruck($id) {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $id);

        $sql = "DELETE FROM foodtrucks WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
