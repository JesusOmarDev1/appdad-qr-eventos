<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Eventos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .main-content {
            margin-left: 210px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .action-btns button {
            margin-right: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
include('conn/conn.php'); // Incluye la conexión con PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'add') {
        $nombre = $_POST['nombre'];
        $tipo_evento = $_POST['tipo_evento'];
        $lugar = $_POST['lugar'];
        $instructor = $_POST['instructor'];
        $cupo = $_POST['cupo'];
        $horario = $_POST['horario'];
        $fecha = $_POST['fecha'];
        $sql = "INSERT INTO event (nombre, tipo_evento, lugar, instructor, cupo, horario, fecha) VALUES (:nombre, :tipo_evento, :lugar, :instructor, :cupo, :horario, :fecha)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nombre' => $nombre, 'tipo_evento' => $tipo_evento, 'lugar' => $lugar, 'instructor' => $instructor, 'cupo' => $cupo, 'horario' => $horario, 'fecha' => $fecha]);
    } elseif ($action == 'edit') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $tipo_evento = $_POST['tipo_evento'];
        $lugar = $_POST['lugar'];
        $instructor = $_POST['instructor'];
        $cupo = $_POST['cupo'];
        $horario = $_POST['horario'];
        $fecha = $_POST['fecha'];
        $sql = "UPDATE event SET nombre = :nombre, tipo_evento = :tipo_evento, lugar = :lugar, instructor = :instructor, cupo = :cupo, horario = :horario, fecha = :fecha WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nombre' => $nombre, 'tipo_evento' => $tipo_evento, 'lugar' => $lugar, 'instructor' => $instructor, 'cupo' => $cupo, 'horario' => $horario, 'fecha' => $fecha, 'id' => $id]);
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM event WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}

// Obtener los eventos de la base de datos
$stmt = $conn->query("SELECT * FROM event");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="sidebar">
    <a href="index.php">Inicio</a>
    <a href="eventos.php">Registro de Eventos</a>
</div>

<div class="main-content">
    <h2>Registro de Eventos</h2>
    <button onclick="agregarEvento()">Agregar Evento</button>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo de Evento</th>
                <th>Lugar</th>
                <th>Instructor</th>
                <th>Cupo</th>
                <th>Horario</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="eventosTableBody">
            <?php foreach ($eventos as $evento) { ?>
            <tr>
                <td><?php echo $evento['id']; ?></td>
                <td><?php echo $evento['nombre']; ?></td>
                <td><?php echo $evento['tipo_evento']; ?></td>
                <td><?php echo $evento['lugar']; ?></td>
                <td><?php echo $evento['instructor']; ?></td>
                <td><?php echo $evento['cupo']; ?></td>
                <td><?php echo $evento['horario']; ?></td>
                <td><?php echo $evento['fecha']; ?></td>
                <td>
                    <button onclick="editarEvento(<?php echo $evento['id']; ?>)">Editar</button>
                    <button onclick="eliminarEvento(<?php echo $evento['id']; ?>)">Eliminar</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    function agregarEvento() {
        const nombre = prompt('Nombre del evento:');
        const tipo_evento = prompt('Tipo de evento:');
        const lugar = prompt('Lugar del evento:');
        const instructor = prompt('Instructor del evento:');
        const cupo = prompt('Cupo del evento:');
        const horario = prompt('Horario del evento:');
        const fecha = prompt('Fecha del evento (YYYY-MM-DD):');
        if (nombre && tipo_evento && lugar && instructor && cupo && horario && fecha) {
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('nombre', nombre);
            formData.append('tipo_evento', tipo_evento);
            formData.append('lugar', lugar);
            formData.append('instructor', instructor);
            formData.append('cupo', cupo);
            formData.append('horario', horario);
            formData.append('fecha', fecha);
            fetch('eventos.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();
            });
        }
    }

    function editarEvento(id) {
        const nombre = prompt('Nuevo nombre del evento:');
        const tipo_evento = prompt('Nuevo tipo de evento:');
        const lugar = prompt('Nuevo lugar del evento:');
        const instructor = prompt('Nuevo instructor del evento:');
        const cupo = prompt('Nuevo cupo del evento:');
        const horario = prompt('Nuevo horario del evento:');
        const fecha = prompt('Nueva fecha del evento (YYYY-MM-DD):');
        if (nombre && tipo_evento && lugar && instructor && cupo && horario && fecha) {
            const formData = new FormData();
            formData.append('action', 'edit');
            formData.append('id', id);
            formData.append('nombre', nombre);
            formData.append('tipo_evento', tipo_evento);
            formData.append('lugar', lugar);
            formData.append('instructor', instructor);
            formData.append('cupo', cupo);
            formData.append('horario', horario);
            formData.append('fecha', fecha);
            fetch('eventos.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();
            });
        }
    }

    function eliminarEvento(id) {
        if (confirm('¿Estás seguro de eliminar este evento?')) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);
            fetch('eventos.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();
            });
        }
    }
</script>

</body>
</html>


