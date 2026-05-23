<?php
/**
 * Funciones de Seguridad para Subida de Archivos
 */

function validarArchivoSubido($file, $allowed_types = [], $max_size = 5242880) {
    // Verificar si el archivo fue subido correctamente
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'Error en la subida del archivo'];
    }
    
    // Tipos MIME permitidos por defecto (imágenes)
    if (empty($allowed_types)) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    }
    
    // Extensiones permitidas
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];
    
    // Validar tipo MIME
    $file_type = mime_content_type($file['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        return ['valid' => false, 'error' => 'Tipo de archivo no permitido'];
    }
    
    // Validar extensión
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_extensions)) {
        return ['valid' => false, 'error' => 'Extensión de archivo no permitida'];
    }
    
    // Validar tamaño
    if ($file['size'] > $max_size) {
        return ['valid' => false, 'error' => 'Archivo demasiado grande'];
    }
    
    // Validar que sea una imagen real (para imágenes)
    if (strpos($file_type, 'image/') === 0) {
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            return ['valid' => false, 'error' => 'El archivo no es una imagen válida'];
        }
    }
    
    return ['valid' => true, 'error' => null];
}

function generarNombreArchivoSeguro($original_name, $prefix = '') {
    // Obtener extensión
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    
    // Validar extensión
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];
    if (!in_array($extension, $allowed_extensions)) {
        $extension = 'bin';
    }
    
    // Generar nombre único y seguro
    $safe_name = $prefix . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    
    return $safe_name;
}

function subirArchivoSeguro($file, $destination_dir, $prefix = '', $allowed_types = [], $max_size = 5242880) {
    // Validar archivo
    $validation = validarArchivoSubido($file, $allowed_types, $max_size);
    
    if (!$validation['valid']) {
        return ['success' => false, 'error' => $validation['error'], 'filename' => null];
    }
    
    // Crear directorio si no existe
    if (!is_dir($destination_dir)) {
        mkdir($destination_dir, 0755, true);
    }
    
    // Generar nombre seguro
    $new_filename = generarNombreArchivoSeguro($file['name'], $prefix);
    $destination_path = $destination_dir . '/' . $new_filename;
    
    // Mover archivo
    if (move_uploaded_file($file['tmp_name'], $destination_path)) {
        return ['success' => true, 'error' => null, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'error' => 'Error al mover el archivo', 'filename' => null];
    }
}

?>
