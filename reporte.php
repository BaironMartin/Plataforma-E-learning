<?php

include('includes/conectar.php');
include('includes/secionesUser.php');

if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}
require('fpdf\fpdf.php');
date_default_timezone_set("America/Bogota");

$clase = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM clase WHERE clave='" . $_SESSION['clave'] . "'"));
$resultado = mysqli_query($cont, "SELECT* FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave='" . $_SESSION['clave'] . "'");
$n = mysqli_num_rows($resultado);
$alumnos = mysqli_fetch_assoc($resultado);




$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Image('img/BannerPDF.jpg', 10, 10, 200);
$pdf->Ln(60);
$pdf->Cell(90, 20, 'Fecha: ' . date('d.m.Y.H.i.s') . '', 0);
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 20, utf8_decode('REPORTES DE CALIFICACIONES GRUPALES'));
$pdf->Ln(20);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(190, 20, utf8_decode($clase['nombre']), 1, 0, 'C', 1);
$pdf->Ln(30);
$pdf->SetFont('Arial', 'B', 10);

$pdf->SetFillColor(77, 77, 77);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(45, 10, 'NOMBRES', 0, 0, 'c', 1);
$pdf->Cell(27, 10, 'PERIODO1', 0, 0, 'c', 1);
$pdf->Cell(27, 10, 'PERIODO2', 0, 0, 'c', 1);
$pdf->Cell(27, 10, 'PERIODO4', 0, 0, 'c', 1);
$pdf->Cell(27, 10, 'PERIODO4', 0, 0, 'c', 1);
$pdf->Cell(27, 10, 'PROMEDIO', 0, 0, 'c', 1);

$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(10);

$pdf->SetFont('Arial', '', 8);



// Check connection
if (!$cont) {
    die("Connection failed: " . mysqli_connect_error());
}

//

if ($n > 0) {
    // output data of each row
    do {

        $promedio = mysqli_fetch_assoc(mysqli_query($cont, "SELECT AVG(evaluacion) as promedio FROM tareas WHERE clave='" . $_SESSION['clave'] . "' AND usuario ='" . $alumnos['Email'] . "'"));
        $periodo1 = mysqli_fetch_assoc(mysqli_query($cont, "SELECT AVG(evaluacion) as per1 FROM tareas WHERE clave='" . $_SESSION['clave'] . "' AND usuario ='" . $alumnos['Email'] . "' AND periodo = 'I'"));
        $periodo2 = mysqli_fetch_assoc(mysqli_query($cont, "SELECT AVG(evaluacion) as per2 FROM tareas WHERE clave='" . $_SESSION['clave'] . "' AND usuario ='" . $alumnos['Email'] . "' AND periodo = 'II'"));
        $periodo3 = mysqli_fetch_assoc(mysqli_query($cont, "SELECT AVG(evaluacion) as per3 FROM tareas WHERE clave='" . $_SESSION['clave'] . "' AND usuario ='" . $alumnos['Email'] . "' AND periodo = 'III'"));
        $periodo4 = mysqli_fetch_assoc(mysqli_query($cont, "SELECT AVG(evaluacion) as per4 FROM tareas WHERE clave='" . $_SESSION['clave'] . "' AND usuario ='" . $alumnos['Email'] . "' AND periodo = 'IV'"));

        $pdf->Cell( 45, 20, utf8_decode($alumnos['Nombre']), 0);

        if ($periodo1['per1'] == "") {
            $pdf->Cell(27, 20, '0', 0);
        } else {
            $numero_per1 = number_format($periodo1['per1'], 1);
            $pdf->Cell(27, 20, utf8_decode($numero_per1), 0);
        }

        if ($periodo2['per2'] == "") {
            $pdf->Cell(27, 20, '0', 0);
        } else {
            $numero_per2 = number_format($periodo2['per2'], 1);
            $pdf->Cell(27, 20, utf8_decode($numero_per2), 0);
        }

        if ($periodo3['per3'] == "") {
            $pdf->Cell(27, 20, '0', 0);
        } else {
            $numero_per3 = number_format($periodo3['per3'], 1);
            $pdf->Cell(27, 20, utf8_decode($numero_per3), 0);
        }

        if ($periodo4['per4'] == "") {
            $pdf->Cell(27, 20, '0', 0);
        } else {
            $numero_per4 = number_format($periodo4['per4'], 2);
            $pdf->Cell(27, 20, utf8_decode($numero_per4), 0);
        }

        if ($promedio['promedio'] == "") {
            $pdf->Cell(27, 20, 'Sin tares entregadas', 0);
        } else {
            $numero_formateado = number_format($promedio['promedio'], 0);
            $pdf->Cell(27, 20, utf8_decode($numero_formateado), 0);
        }




        $pdf->Ln(10);
    } while ($alumnos = mysqli_fetch_assoc($resultado));
}

$pdf->Output();
