<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha = $_POST['fecha'];
    $bautizo = $_POST['bautizo'];
    $padre = $_POST['padre'];

    $sql = "INSERT INTO datos_personales (nombre, apellidos, fecha, bautizo, padre, usuario_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssssi', $nombre, $apellidos, $fecha, $bautizo, $padre, $usuario_id);
//DATOS REGISTRDOS EXITOSAMENTE
    if ($stmt->execute()) {
        echo "";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$sql = "SELECT * FROM datos_personales WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Control</title>
    <link rel="stylesheet" type="text/css" href="panel.css">

</head>
<!-- Barra lateral -->
<div class="sidebar">
        <h2>SANTUARIO DE COTUNDO</h2>
        <a href="">Inicio</a>
        <a href="registro.php">Configuración</a>
        
    </div>
<body>
    <div class="lg">
    <h2>Bienvenido:<?php echo $_SESSION['usuario_id']; ?><a href="logout.php">Cerrar sesión</a></h2>
    </div>
    <div class="bt">
    <h3>REGISTRAR DATOS</h3>
    <form method="POST">
        <th><input type="text" name="nombre" placeholder="Nombres" ></th>
        <th><input type="text" name="apellidos" placeholder="Apellidos" ></th>
        <th><input type="text" name="fecha" placeholder="Fecha De Nacimiento" ></th>
        <th><input type="text" name="bautizo" placeholder="Lugar De Bautizo" ></th>
        <th><input type="text" name="padre" placeholder="Padres" ></th>
        <th><input type="submit" value="Guardar"></th>
                
    </form>
    </div>
    <div class="b-d">
    <h3>BUSCAR DATOS</h3>
    <form action="dashboard.php" method="GET">
        <input type="text" name="buscar" placeholder="Buscar por nombre...">
        <input type="submit" value="Buscar">
        <a href="dashboard.php"><button>ACTUALIZAR</button></a>
        <?php
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
    </form>
    </div>
    
<div class="bdatos">
    <h3>LISTA DE PERSONAS</h3>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Fecha de Nacimiento</th>
            <th>bautizo</th>
            <th>Padres</th>
            <th>Acciones</th>
        </tr>
        </div>
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
