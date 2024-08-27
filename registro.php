<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    $sql = "INSERT INTO usuarios (nombre_usuario, contrasena, email) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sss', $nombre_usuario, $contrasena, $email);

    if ($stmt->execute()) {
        echo "Usuario registrado exitosamente.";
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="submit" value="Registrar">
        <th><a href="dashboard.php">REGRESAR</th>
        
    </form>
    
</body>
</html>
