<?php
// Inicializa la agenda como un array en el archivo
$agendaFile = 'agenda.json';
$agenda = [];

// Cargar la agenda desde el archivo si existe
if (file_exists($agendaFile)) {
    $agenda = json_decode(file_get_contents($agendaFile), true);
}

// Manejo del formulario al enviar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);

    // Si se proporciona un nombre y un número de teléfono
    if ($nombre && $telefono !== '') {
        // R1: Si el nombre no existe, agregarlo
        if (!array_key_exists($nombre, $agenda)) {
            $agenda[$nombre] = $telefono;
        } else {
            // R2: Si el nombre ya existe, actualizar el teléfono
            $agenda[$nombre] = $telefono;
        }
    } elseif ($nombre && $telefono === '') {
        // R3: Si el nombre ya existe y no se indica número, eliminarlo
        if (array_key_exists($nombre, $agenda)) {
            unset($agenda[$nombre]);
        }
    }

    // Guardar la agenda actualizada en el archivo
    file_put_contents($agendaFile, json_encode($agenda));
}

// Manejo de la variable para vaciar la agenda
if (isset($_GET['vaciar']) && $_GET['vaciar'] === 'true') {
    $agenda = [];
    file_put_contents($agendaFile, json_encode($agenda));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Simple</title>
</head>
<body>
    <h1>Agenda de Contactos</h1>
    <ul>
        <?php foreach ($agenda as $nombre => $telefono): ?>
            <li><?php echo htmlspecialchars($nombre) . ': ' . htmlspecialchars($telefono); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Añadir/Modificar Contacto</h2>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono">
        <br>
        <input type="submit" value="Guardar">
    </form>

    <?php if (!empty($agenda)): ?>
        <h2>Vaciar Agenda</h2>
        <form method="GET">
            <input type="hidden" name="vaciar" value="true">
            <input type="submit" value="Vaciar todos los contactos">
        </form>
    <?php endif; ?>
</body>
</html>
