<?php
echo "<h1>Formulario para Crear Cliente</h1>";
echo "<form method='POST' action='/clientes'>"; // Asegúrate de que la ruta es correcta
echo csrf_field(); // Token CSRF para seguridad
echo "Nombre: <input type='text' name='nombre' required><br>";
echo "Email: <input type='email' name='email' required><br>";
echo "Telefono: <input type='text' name='telefono' required><br>";
echo "<button type='submit'>Guardar Cliente</button>";
echo "</form>";

