<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$buscar = $_GET['buscar'] ?? '';

$sql = "SELECT * FROM datos_personales WHERE usuario_id = ? AND (nombre LIKE ? OR apellidos LIKE ?)";
$buscar = '%' . $buscar . '%';
$stmt = $conexion->prepare($sql);
$stmt->bind_param('iss', $usuario_id, $buscar, $buscar);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Datos</title>
</head>
<body>
    <h3>Buscar Datos Personales</h3>
    <form method="GET">
        <input type="text" name="buscar" placeholder="Buscar..." value="<?php echo htmlspecialchars($buscar); ?>">
        <input type="submit" value="Buscar">
    </form>

    <h3>Resultados de Búsqueda</h3>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>bautizo</th>
            <th>Padres</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($datos as $dato): ?>
        <tr>
            <td><?php echo $dato['nombre']; ?></td>
            <td><?php echo $dato['apellidos']; ?></td>
            <td><?php echo $dato['fecha']; ?></td>
            <td><?php echo $dato['bautizo']; ?></td>
            <td><?php echo $dato['padre']; ?></td>
            <td>
                <a href="editar.php?id=<?php echo $dato['id']; ?>">Editar</a> |
                <a href="eliminar.php?id=<?php echo $dato['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
