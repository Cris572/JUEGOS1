   <link rel="stylesheet" href="desing.css">
<?php
// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'juegos';

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar la eliminación si se pasa un ID
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $delete_sql = "DELETE FROM juegos WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Registro eliminado correctamente.<br>";
    } else {
        echo "Error al eliminar el registro: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

// Procesar la modificación si se pasa un ID y se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modificar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $año = $_POST['año'];
    $costo = $_POST['costo'];

    $update_sql = "UPDATE juegos SET nombre = ?, año = ?, costo = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sidi", $nombre, $año, $costo, $id);

    if ($stmt->execute()) {
        echo "Registro modificado correctamente.<br>";
    } else {
        echo "Error al modificar el registro: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

// Mostrar los registros de la tabla con opciones de modificación y eliminación
$sql = "SELECT * FROM juegos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Lista de Juegos</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Año</th>
                <th>Costo</th>
                <th>Acciones</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["nombre"] . "</td>
                <td>" . $row["año"] . "</td>
                <td>" . $row["costo"] . "</td>
                <td>
                    <a href='?modificar=" . $row["id"] . "'>Modificar</a> | 
                    <a href='?eliminar=" . $row["id"] . "' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este registro?\")'>Eliminar</a>
                </td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados<br>";
}

// Cerrar la conexión
$conn->close();
?>

<!-- Formulario para agregar un nuevo registro -->
 <body>
    <div class="colortrans" ></div>
<h2>Agregar Nuevo Registro</h2>
<form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br>
    <label>Año:</label>
    <input type="number" name="año" required><br>
    <label>Costo:</label>
    <input type="text" name="costo" required><br>
    <button type="submit" name="agregar">Agregar</button>
</form>
</body>
<?php
// Procesar la inserción de un nuevo registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $año = $_POST['año'];
    $costo = $_POST['costo'];

    $conn = new mysqli($host, $user, $password, $dbname);
    $insert_sql = "INSERT INTO juegos (nombre, año, costo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sdi", $nombre, $año, $costo);

    if ($stmt->execute()) {
        echo "Nuevo registro agregado correctamente.<br>";
    } else {
        echo "Error al agregar el registro: " . $stmt->error . "<br>";
    }

    $stmt->close();
    $conn->close();
}
?>
