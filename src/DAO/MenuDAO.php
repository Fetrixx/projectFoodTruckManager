<?php
namespace Src\DAO;

use Src\Config\Database;

class MenuDAO
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getMenuByFoodTruckId($foodtruck_id)
    {
        $conn = $this->db->getConnection();
        $foodtruck_id = mysqli_real_escape_string($conn, $foodtruck_id);

        $sql = "SELECT * FROM menus WHERE foodtruck_id = '$foodtruck_id'";
        $result = mysqli_query($conn, $sql);

        $menus = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $menus[] = $row;
        }
        return $menus;
    }

    public function createMenuItem($foodtruck_id, $nombre, $descripcion, $precio, $imagen)
    {
        $conn = $this->db->getConnection();
        $foodtruck_id = mysqli_real_escape_string($conn, $foodtruck_id);
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $descripcion = mysqli_real_escape_string($conn, $descripcion);
        $precio = mysqli_real_escape_string($conn, $precio);
        $imagen = mysqli_real_escape_string($conn, $imagen);

        $sql = "INSERT INTO menus (foodtruck_id, nombre, descripcion, precio, imagen) 
                VALUES ('$foodtruck_id', '$nombre', '$descripcion', '$precio', '$imagen')";

        if (mysqli_query($conn, $sql)) {
            return mysqli_insert_id($conn); // Retorna el ID del nuevo menú
        } else {
            return false;
        }
    }

    public function updateMenuItem($id, $nombre, $descripcion, $precio, $imagen)
    {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $id);
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $descripcion = mysqli_real_escape_string($conn, $descripcion);
        $precio = mysqli_real_escape_string($conn, $precio);
        $imagen = mysqli_real_escape_string($conn, $imagen);

        $sql = "UPDATE menus SET 
                nombre = '$nombre', 
                descripcion = '$descripcion', 
                precio = '$precio',
                imagen = '$imagen' 
                WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteMenuItem($id)
    {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $id);

        $sql = "DELETE FROM menus WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function getMenuItemById($id)
    {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $id);

        $sql = "SELECT * FROM menus WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    }

}
?>