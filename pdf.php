<?php
require_once('tcpdf/tcpdf.php');
include('conexion.php');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM datos_personales WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_all(MYSQLI_ASSOC);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$html = '<h1>Datos Personales</h1><table border="1"><tr><th>Nombre</th><th>Apellidos</th><th>bautizo</th><th>Padres</th></tr>';

foreach ($datos as $dato) {
    $html .= '<tr><td>' . $dato['nombre'] . '</td><td>' . $dato['apellidos'] . '</td><td>' . $dato['bautizo'] . '</td><td>' . $dato['padre'] . '</td></tr>';
}

$html .= '</table>';

$pdf->writeHTML($html);
$pdf->Output('datos_personales.pdf', 'I');
?>
