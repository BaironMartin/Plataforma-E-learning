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

if (isset($_REQUEST['userEm']) && !empty($_REQUEST['userEm'])) {
    $_SESSION['userEm'] = $_REQUEST['userEm'];
}

require('fpdf\fpdf.php');
date_default_timezone_set("America/Bogota");
$clase=mysqli_fetch_assoc(mysqli_query($cont,"SELECT * FROM clase WHERE clave='".$_SESSION['clave']."'"));
$cal = mysqli_query($cont, "SELECT* FROM tareas,plan WHERE tareas.idplan=plan.idplan and tareas.usuario='" . $_SESSION['userEm'] . "'  AND tareas.clave='" . $_SESSION['clave'] . "'");
            $ncal = mysqli_num_rows($cal);
            $acal = mysqli_fetch_assoc($cal);

$atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['userEm'] . "'"));





$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Image('img/BannerPDF.jpg',10,10,200);
$pdf->Ln(60);
$pdf->Cell(90,20,'Fecha: '.date('d.m.Y.H.i.s').'',0);
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,20,utf8_decode('REPORTES DE CALIFICACIONES GRUPALES'));
$pdf->Ln(20);
$pdf->SetFont('Arial','B',16);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(190,20,utf8_decode($clase['nombre']), 1, 0, 'C', 1);
$pdf->Ln(20);
$pdf->Cell(190,20,utf8_decode($atipo['Nombre']), 1, 0, 'C', 1);
$pdf->Ln(30);
$pdf->SetFont('Arial','B',10);

$pdf->SetFillColor(77,77,77);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(100,10,'NOMBRES',0,0,'c',1);
$pdf->Cell(45,10,'PERIODO',0,0,'c',1);
$pdf->Cell(45,10,'CALIFICACION',0,0,'c',1);



$pdf->SetTextColor(0,0,0);

$pdf->Ln(10);

$pdf->SetFont('Arial','',8);



// Check connection
if (!$cont) {
    die("Connection failed: " . mysqli_connect_error());
}

//

if ($ncal > 0) {
    // output data of each row
    do{
$pdf->Cell(100,20, utf8_decode($acal['titulo']), 0);
$pdf->Cell(45,20, utf8_decode($acal['periodo']), 0);
$pdf->Cell(45,20, utf8_decode($acal['evaluacion']), 0);





$pdf->Ln(10);

}while($acal = mysqli_fetch_assoc($cal)) ;
}

$pdf->Output();

