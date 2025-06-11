<?php
namespace Src\DAO;

use Src\Config\Database;

class FavoritoDAO
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function addFavorito($usuario_id, $foodtruck_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("INSERT INTO favoritos (usuario_id, foodtruck_id) VALUES (?, ?)");
        if (!$stmt) {
            error_log("FavoritoDAO addFavorito prepare error: " . $conn->error);
            return false;
        }

        $stmt->bind_param("ii", $usuario_id, $foodtruck_id);

        if (!$stmt->execute()) {
            error_log("FavoritoDAO addFavorito execute error: " . $stmt->error);
            return false;
        }

        return true;
    }


    public function getFavoritosByUsuarioId_log($usuario_id)
    {
        echo "<pre>"; // Para una mejor visualización de los mensajes en pantalla
        echo "FavoritoDAO getFavoritosByUsuarioId - Inicio. Usuario ID recibido: " . var_export($usuario_id, true) . "\n";

        $conn = $this->db->getConnection();
        if (!$conn) {
            echo "FavoritoDAO getFavoritosByUsuarioId - Error al obtener conexión a la base de datos.\n";
            error_log("FavoritoDAO getFavoritosByUsuarioId - Error al obtener conexión a la base de datos.");
            return [];
        }
        echo "FavoritoDAO getFavoritosByUsuarioId - Conexión a DB obtenida correctamente.\n";

        $stmt = $conn->prepare("SELECT foodtruck_id FROM favoritos WHERE usuario_id = ?");
        if (!$stmt) {
            echo "FavoritoDAO getFavoritosByUsuarioId - Error en prepare: " . $conn->error . "\n";
            error_log("FavoritoDAO getFavoritosByUsuarioId - Error en prepare: " . $conn->error);
            return [];
        }
        echo "FavoritoDAO getFavoritosByUsuarioId - Sentencia preparada correctamente.\n";

        $bind = $stmt->bind_param("i", $usuario_id);
        if (!$bind) {
            echo "FavoritoDAO getFavoritosByUsuarioId - Error en bind_param: " . $stmt->error . "\n";
            error_log("FavoritoDAO getFavoritosByUsuarioId - Error en bind_param: " . $stmt->error);
            return [];
        }
        echo "FavoritoDAO getFavoritosByUsuarioId - Parámetro ligado correctamente: usuario_id = $usuario_id\n";

        $exec = $stmt->execute();
        if (!$exec) {
            echo "FavoritoDAO getFavoritosByUsuarioId - Error en execute: " . $stmt->error . "\n";
            error_log("FavoritoDAO getFavoritosByUsuarioId - Error en execute: " . $stmt->error);
            return [];
        }
        echo "FavoritoDAO getFavoritosByUsuarioId - Sentencia ejecutada correctamente.\n";

        $result = $stmt->get_result();
        if (!$result) {
            echo "FavoritoDAO getFavoritosByUsuarioId - Error al obtener resultado: " . $stmt->error . "\n";
            error_log("FavoritoDAO getFavoritosByUsuarioId - Error al obtener resultado: " . $stmt->error);
            return [];
        }
        echo "FavoritoDAO getFavoritosByUsuarioId - Resultado obtenido correctamente.\n";

        $favoritos = [];
        while ($row = $result->fetch_assoc()) {
            echo "FavoritoDAO getFavoritosByUsuarioId - Registro obtenido: " . json_encode($row) . "\n";
            $favoritos[] = $row['foodtruck_id'];
        }

        echo "FavoritoDAO getFavoritosByUsuarioId - Total favoritos encontrados: " . count($favoritos) . "\n";
        echo "FavoritoDAO getFavoritosByUsuarioId - Fin.\n";
        echo "</pre>"; // Cerrar el bloque de preformato

        return $favoritos; // Devuelve un array de foodtruck_ids
    }

    public function getFavoritosByUsuarioId($usuario_id, $getFoodtruck_id = false)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("SELECT foodtruck_id FROM favoritos WHERE usuario_id = ?");
        if (!$stmt) {
            error_log("FavoritoDAO getFavoritosByUsuarioId prepare error: " . $conn->error);
            return [];
        }

        $stmt->bind_param("i", $usuario_id);

        if (!$stmt->execute()) {
            error_log("FavoritoDAO getFavoritosByUsuarioId execute error: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        $favoritos = [];
        while ($row = $result->fetch_assoc()) {
            if ($getFoodtruck_id) {
                $favoritos[] = ['foodtruck_id' => $row['foodtruck_id']];

            } else {
                $favoritos[] = $row['foodtruck_id'];
            }
        }

        return $favoritos; // Devuelve un array de foodtruck_ids
    }
    public function getFavoritosByUsuarioId_($usuario_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("SELECT foodtruck_id FROM favoritos WHERE usuario_id = ?");
        if (!$stmt) {
            error_log("FavoritoDAO getFavoritosByUsuarioId prepare error: " . $conn->error);
            return [];
        }

        $stmt->bind_param("i", $usuario_id);

        if (!$stmt->execute()) {
            error_log("FavoritoDAO getFavoritosByUsuarioId execute error: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        $favoritos = [];
        while ($row = $result->fetch_assoc()) {
            $favoritos[] = $row['foodtruck_id'];
        }

        return $favoritos;
    }

    public function deleteFavorito($usuario_id, $foodtruck_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND foodtruck_id = ?");
        if (!$stmt) {
            error_log("FavoritoDAO deleteFavorito prepare error: " . $conn->error);
            return false;
        }

        $stmt->bind_param("ii", $usuario_id, $foodtruck_id);

        if (!$stmt->execute()) {
            error_log("FavoritoDAO deleteFavorito execute error: " . $stmt->error);
            return false;
        }

        return true;
    }

    /**
     * Verifica si un food truck está marcado como favorito por un usuario.
     * Retorna true si existe, false si no.
     */
    public function isFavorito($usuario_id, $foodtruck_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("SELECT 1 FROM favoritos WHERE usuario_id = ? AND foodtruck_id = ? LIMIT 1");
        if (!$stmt) {
            error_log("FavoritoDAO isFavorito prepare error: " . $conn->error);
            return false;
        }

        $stmt->bind_param("ii", $usuario_id, $foodtruck_id);

        if (!$stmt->execute()) {
            error_log("FavoritoDAO isFavorito execute error: " . $stmt->error);
            return false;
        }

        $result = $stmt->get_result();

        return $result->num_rows === 1;
    }
}
?>