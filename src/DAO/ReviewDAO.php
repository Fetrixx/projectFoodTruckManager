<?php
namespace Src\DAO;

use Src\Config\Database;

class ReviewDAO
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Crea una reseña nueva.
     */
    public function createReview($usuario_id, $foodtruck_id, $rating, $comentario)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("INSERT INTO reviews (usuario_id, foodtruck_id, rating, comentario) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Error prepare createReview: " . $conn->error);
            return false;
        }

        $stmt->bind_param("iiis", $usuario_id, $foodtruck_id, $rating, $comentario);

        if (!$stmt->execute()) {
            error_log("Error execute createReview: " . $stmt->error);
            return false;
        }

        return true;
    }

    /**
     * Obtiene todas las reseñas para un food truck específico,
     * incluyendo el nombre del usuario y nombre del food truck.
     */
    public function getReviewsByFoodTruckId($foodtruck_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("
            SELECT r.*, u.nombre AS user_name, f.nombre AS foodtruck_nombre
            FROM reviews r
            JOIN usuarios u ON r.usuario_id = u.id
            JOIN foodtrucks f ON r.foodtruck_id = f.id
            WHERE r.foodtruck_id = ?
            ORDER BY r.fecha DESC
        ");
        if (!$stmt) {
            error_log("Error prepare getReviewsByFoodTruckId: " . $conn->error);
            return [];
        }

        $stmt->bind_param("i", $foodtruck_id);

        if (!$stmt->execute()) {
            error_log("Error execute getReviewsByFoodTruckId: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        return $reviews;
    }

    /**
     * Obtiene reseñas para varios food trucks (array de IDs),
     * incluyendo nombre usuario y nombre food truck.
     */
    public function getReviewsByFoodtruckIds_old(array $foodtruckIds)
    {
        if (empty($foodtruckIds)) {
            return [];
        }

        $conn = $this->db->getConnection();

        // Preparar placeholders para la consulta IN
        $placeholders = implode(',', array_fill(0, count($foodtruckIds), '?'));
        $types = str_repeat('i', count($foodtruckIds));

        $sql = "
            SELECT r.*, u.nombre AS user_name, f.nombre AS foodtruck_nombre
            FROM reviews r
            JOIN usuarios u ON r.usuario_id = u.id
            JOIN foodtrucks f ON r.foodtruck_id = f.id
            WHERE r.foodtruck_id IN ($placeholders)
            ORDER BY r.fecha DESC
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Error prepare getReviewsByFoodtruckIds: " . $conn->error);
            return [];
        }

        // Vincular parámetros dinámicamente
        $stmt->bind_param($types, ...$foodtruckIds);

        if (!$stmt->execute()) {
            error_log("Error execute getReviewsByFoodtruckIds: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        return $reviews;
    }

    public function getReviewsByFoodtruckIds(array $foodtruckIds)
    {
        if (empty($foodtruckIds)) {
            return [];
        }

        $conn = $this->db->getConnection();

        // Preparar placeholders para la consulta IN
        $placeholders = implode(',', array_fill(0, count($foodtruckIds), '?'));
        $types = str_repeat('i', count($foodtruckIds));

        $sql = "
        SELECT r.*, u.nombre AS user_name, f.nombre AS foodtruck_nombre
        FROM reviews r
        JOIN usuarios u ON r.usuario_id = u.id
        JOIN foodtrucks f ON r.foodtruck_id = f.id
        WHERE r.foodtruck_id IN ($placeholders)
        ORDER BY r.fecha DESC
    ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Error prepare getReviewsByFoodtruckIds: " . $conn->error);
            return [];
        }

        // Vincular parámetros dinámicamente
        $stmt->bind_param($types, ...$foodtruckIds);

        if (!$stmt->execute()) {
            error_log("Error execute getReviewsByFoodtruckIds: " . $stmt->error);
            return [];
        }

        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        return $reviews;
    }

    /**
     * Actualiza una reseña existente (solo si pertenece al usuario).
     */
    public function updateReview($review_id, $usuario_id, $rating, $comentario)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("UPDATE reviews SET rating = ?, comentario = ? WHERE id = ? AND usuario_id = ?");
        if (!$stmt) {
            error_log("Error prepare updateReview: " . $conn->error);
            return false;
        }

        $stmt->bind_param("isii", $rating, $comentario, $review_id, $usuario_id);

        if (!$stmt->execute()) {
            error_log("Error execute updateReview: " . $stmt->error);
            return false;
        }

        return $stmt->affected_rows > 0;
    }

    /**
     * Elimina una reseña (solo si pertenece al usuario).
     */
    public function deleteReview($review_id, $usuario_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND usuario_id = ?");
        if (!$stmt) {
            error_log("Error prepare deleteReview: " . $conn->error);
            return false;
        }

        $stmt->bind_param("ii", $review_id, $usuario_id);

        if (!$stmt->execute()) {
            error_log("Error execute deleteReview: " . $stmt->error);
            return false;
        }

        return $stmt->affected_rows > 0;
    }

    /**
     * Obtiene una reseña por su ID (para edición).
     */
    public function getReviewById($review_id, $usuario_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ? AND usuario_id = ?");
        if (!$stmt) {
            error_log("Error prepare getReviewById: " . $conn->error);
            return null;
        }

        $stmt->bind_param("ii", $review_id, $usuario_id);

        if (!$stmt->execute()) {
            error_log("Error execute getReviewById: " . $stmt->error);
            return null;
        }

        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function getReviewsByUserId($usuario_id)
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("SELECT * FROM reviews WHERE usuario_id = ?");
        if (!$stmt) {
            error_log("Error prepare getReviewsByUserId: " . $conn->error);
            return null;
        }

        // Solo un parámetro, tipo entero
        $stmt->bind_param("i", $usuario_id);

        if (!$stmt->execute()) {
            error_log("Error execute getReviewsByUserId: " . $stmt->error);
            return null;
        }

        $result = $stmt->get_result();

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        return $reviews;
    }


}
?>