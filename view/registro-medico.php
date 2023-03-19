<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registro de médico</title>
</head>

<body>
    <div class="container">
        <h1>Registro de médico</h1>
        <form class="formulario" id="formulario-medico">
            <label for="identificacion">Identificación:</label>
            <input type="number" id="identificacion" name="identificacion" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="especialidad">Especialidad:</label>
            <select id="especialidad" name="especialidad" required>
                <option value="">Seleccione una especialidad</option>
                <option value="Medicina general">Medicina general</option>
                <option value="Pediatría">Pediatría</option>
                <option value="Cardiología">Cardiología</option>
                <option value="Dermatología">Dermatología</option>
                <option value="Ginecología">Ginecología</option>
                <option value="Neurología">Neurología</option>
                <option value="Oftalmología">Oftalmología</option>
                <option value="Oncología">Oncología</option>
                <option value="Ortopedia">Ortopedia</option>
                <option value="Psiquiatría">Psiquiatría</option>
            </select>


            <input type="submit" value="Registrar" onclick="agregarMedico(event)">
        </form>
        <div id="mensaje"></div>
    </div>
    <script src="../controller/medico.js"></script>
</body>

</html>