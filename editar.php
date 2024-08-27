<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha = $_POST['fecha'];
    $bautizo = $_POST['bautizo'];
    $padre = $_POST['padre'];

    $sql = "UPDATE datos_personales SET nombre = ?, apellidos = ?, fecha = ?, bautizo = ?, padre = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssssii', $nombre, $apellidos, $fecha, $bautizo, $padre, $id, $usuario_id);

    if ($stmt->execute()) {
        echo "Datos actualizados exitosamente.";
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $sql = "SELECT * FROM datos_personales WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $dato = $resultado->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Datos</title>
</head>
<body>
    <h3>Editar Datos Personales</h3>
    <form method="POST">
        <input type="text" name="nombre" value="<?php echo $dato['nombre']; ?>" required>
        <input type="text" name="apellidos" value="<?php echo $dato['apellidos']; ?>" required>
        <input type="text" name="fecha" value="<?php echo $dato['fecha']; ?>" required>
        <input type="text" name="bautizo" value="<?php echo $dato['bautizo']; ?>" required>
        <input type="text" name="padre" value="<?php echo $dato['padre']; ?>" required>
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
