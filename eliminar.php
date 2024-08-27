<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$id = $_GET['id'];

$sql = "DELETE FROM datos_personales WHERE id = ? AND usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('ii', $id, $usuario_id);

if ($stmt->execute()) {
    echo "Datos eliminados exitosamente.";
    header("Location: dashboard.php");
} else {
    echo "Error: " . $stmt->error;
}
?>
