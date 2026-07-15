<?php
$host = "localhost";
$dbname = "ecommerce"; 
$user = "root";
$password = "";

// Intentar la conexión usando mysqli
$con = @mysqli_connect($host, $user, $password, $dbname);

// Verificar la conexión
if ($con) {
    // Configurar caracteres a utf8mb4 para evitar problemas con acentos o eñes
    mysqli_set_charset($con, "utf8mb4");
    
    // NOTA: Es recomendable quitar este echo una vez que veas que funciona, 
    // porque los echos dentro de archivos de configuración pueden romper los redireccionamientos (header("Location:..."))
   // echo "<h2 style='color:green;'>✅ Conexión exitosa a la base de datos (MySQLi).</h2>";
} else {
    // Mensaje de error
    echo "<h2 style='color:red;'>❌ Error al conectar con la base de datos.</h2>";
    echo "<p>" . mysqli_connect_error() . "</p>";
    die();
}
?>