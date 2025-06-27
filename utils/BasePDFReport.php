<?php
require_once __DIR__ . '/../fpdf/fpdf.php'; // Ajustar la ruta a FPDF

class BasePDFReport extends FPDF {
    protected $pageTitle = 'Reporte';
    protected $logoPath = 'img/BannerPDF.jpg'; // Ruta relativa a la raíz del proyecto donde se ejecuta index.php
    protected $claseNombre = '';

    public function setReportTitle($title) {
        $this->pageTitle = $title;
    }

    public function setClaseNombre($nombre) {
        $this->claseNombre = $nombre;
    }

    public function setLogoPath($path){
        // Permite cambiar el logo si es necesario desde el controlador.
        // Validar que el archivo exista antes de asignarlo podría ser una buena idea.
        if (file_exists(__DIR__ . '/../' . $path)) { // Asume que $path es relativo a la raíz del proyecto
            $this->logoPath = $path;
        } else {
            // Fallback o error si el logo no se encuentra.
            // Por ahora, simplemente no actualiza si no existe.
            // Considerar usar una imagen por defecto o lanzar un error.
            // error_log("Logo no encontrado en: " . __DIR__ . '/../' . $path);
        }
    }

    // Cabecera de página
    function Header() {
        // Logo
        // La ruta al logo debe ser accesible desde donde se instancia FPDF (generalmente index.php)
        // Si el script que genera el PDF está en controllers/, y la imagen en img/, la ruta es '../img/...'
        // Pero FPDF usualmente resuelve rutas relativas al script que lo *ejecuta* (index.php)
        // Por lo tanto, 'img/BannerPDF.jpg' debería funcionar si img está en la raíz.
        if (!empty($this->logoPath) && file_exists($this->logoPath)) {
             $this->Image($this->logoPath, 10, 10, 190); // Ajustado a 190 para que ocupe casi todo el ancho
        } else {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Logo no encontrado', 0, 1, 'C');
        }

        // Salto de línea grande después del banner
        $this->Ln(50); // Ajustar este valor según el tamaño real del banner

        // Título del reporte (ej. "REPORTE DE CALIFICACIONES GRUPALES")
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode($this->pageTitle), 0, 1, 'L'); // 0 para ancho completo, 1 para nueva línea, 'C' para centrar

        // Fecha de generación
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Fecha de generacion: ' . date('d/m/Y H:i:s'), 0, 1, 'L');

        // Nombre de la Clase (si se ha establecido)
        if(!empty($this->claseNombre)) {
            $this->SetFont('Arial', 'B', 14);
            $this->SetFillColor(230,230,230); // Un color de fondo suave para el nombre de la clase
            $this->Cell(0, 12, utf8_decode($this->claseNombre), 1, 1, 'C', true); // Borde y relleno
        }

        // Salto de línea antes del contenido principal de la tabla
        $this->Ln(10);
    }

    // Pie de página
    function Footer() {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Tabla simple para datos
    function BasicTable($header, $data) {
        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(77, 77, 77); // Gris oscuro para cabecera
        $this->SetTextColor(255, 255, 255); // Texto blanco
        $w = array_map(function($h) { return $this->GetStringWidth($h) + 6; }, $header); // Ancho automático básico
        // Ajustar anchos manualmente si es necesario, ejemplo:
        // $w = [40, 35, 40, 40]; // Ejemplo de anchos fijos

        // Para este caso, intentaremos distribuir el ancho.
        // Asumimos un ancho total de página de 190 (210 total - 10 margen izq - 10 margen der)
        $totalWidth = 190;
        if (count($header) > 0) {
            $calculatedWidth = $totalWidth / count($header);
             $w = array_fill(0, count($header), $calculatedWidth);
        } else {
            return; // No hay cabecera, no dibujar tabla.
        }


        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();

        // Datos de la tabla
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(0, 0, 0); // Texto negro
        $this->SetFillColor(245, 245, 245); // Gris claro para filas alternas
        $fill = false;
        foreach ($data as $row) {
            $cellCount = 0;
            foreach ($row as $col) {
                $this->Cell($w[$cellCount], 6, utf8_decode($col), 'LRBT', 0, 'L', $fill); // Añadido B y T para bordes completos
                $cellCount++;
                 if ($cellCount >= count($w)) break; // Evitar error si hay más datos que cabeceras
            }
            $this->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        // $this->Cell(array_sum($w),0,'','T'); // No necesaria si las celdas tienen borde completo
    }
}
?>
