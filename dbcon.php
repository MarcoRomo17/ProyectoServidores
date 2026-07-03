<?php

$host = "localhost";
$dbname = "ecommerce";   // Cambia esto por el nombre de tu BD
$user = "root";
$password = "";

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    // Mostrar los errores de PDO
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mensaje de éxito
    echo "<h2 style='color:green;'>✅ Conexión exitosa a la base de datos.</h2>";

} catch (PDOException $e) {

    // Mensaje de error
    echo "<h2 style='color:red;'>❌ Error al conectar con la base de datos.</h2>";
    echo "<p>" . $e->getMessage() . "</p>";

}
?>
