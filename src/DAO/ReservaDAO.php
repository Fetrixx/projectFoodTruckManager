<?php
namespace Src\DAO;

use Src\Config\Database;

class ReservaDAO
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createReserva($usuario_id, $foodtruck_id, $fecha, $hora, $total)
    {
        $conn = $this->db->getConnection();
        $usuario_id = mysqli_real_escape_string($conn, $usuario_id);
        $foodtruck_id = mysqli_real_escape_string($conn, $foodtruck_id);
        $fecha = mysqli_real_escape_string($conn, $fecha);
        $hora = mysqli_real_escape_string($conn, $hora);
        $total = mysqli_real_escape_string($conn, $total);

        $sql = "INSERT INTO reservas (usuario_id, foodtruck_id, fecha, hora, total) 
                VALUES ('$usuario_id', '$foodtruck_id', '$fecha', '$hora', '$total')";

        if (mysqli_query($conn, $sql)) {
            return mysqli_insert_id($conn); // Retorna el ID de la nueva reserva
        } else {
            return false;
        }
    }

    public function getReservasByUsuarioId($usuario_id, $showAll = false)
    {
        $conn = $this->db->getConnection();
        $usuario_id = mysqli_real_escape_string($conn, $usuario_id);


        if ($showAll) {
            $sql = "SELECT r.*, u.nombre AS usuario_nombre 
                FROM reservas r
                JOIN usuarios u ON r.usuario_id = u.id
                ORDER BY r.fecha_creacion DESC";
        } else {
            $sql = "SELECT r.*, u.nombre AS usuario_nombre 
                FROM reservas r
                JOIN usuarios u ON r.usuario_id = u.id
                WHERE r.usuario_id = '$usuario_id'
                ORDER BY r.fecha_creacion DESC";
        }


        $result = mysqli_query($conn, $sql);

        $reservas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reservas[] = $row;
        }
        return $reservas;
    }

    public function getItemsByReservaId($reserva_id)
    {
        $conn = $this->db->getConnection();
        $reserva_id = mysqli_real_escape_string($conn, $reserva_id);

        $sql = "SELECT ri.*, m.nombre AS menu_nombre FROM reserva_items ri
                JOIN menus m ON ri.menu_id = m.id
                WHERE ri.reserva_id = '$reserva_id'";

        $result = mysqli_query($conn, $sql);

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function createReservaItem($reserva_id, $menu_id, $cantidad, $precio)
    {
        $conn = $this->db->getConnection();

        // Usar sentencias preparadas para seguridad
        $stmt = $conn->prepare("INSERT INTO reserva_items (reserva_id, menu_id, cantidad, precio_unitario) 
                                VALUES (?, ?, ?, ?)");

        $stmt->bind_param("iiid", $reserva_id, $menu_id, $cantidad, $precio);

        return $stmt->execute();
    }

    public function getReservaById($reserva_id)
    {
        $conn = $this->db->getConnection();
        $reserva_id = mysqli_real_escape_string($conn, $reserva_id);

        $sql = "SELECT * FROM reservas WHERE id = '$reserva_id'";
        $result = mysqli_query($conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function deleteReserva($reserva_id)
    {
        $conn = $this->db->getConnection();
        $reserva_id = mysqli_real_escape_string($conn, $reserva_id);

        $sql = "DELETE FROM reservas WHERE id = '$reserva_id'";
        return mysqli_query($conn, $sql);
    }

    public function updateReservaEstado($reserva_id, $estado)
    {
        $conn = $this->db->getConnection();
        $reserva_id = mysqli_real_escape_string($conn, $reserva_id);
        $estado = mysqli_real_escape_string($conn, $estado);

        $sql = "UPDATE reservas SET estado = '$estado' WHERE id = '$reserva_id'";
        return mysqli_query($conn, $sql);
    }

    // Métodos adicionales para actualizar, cancelar, etc.
}
?>