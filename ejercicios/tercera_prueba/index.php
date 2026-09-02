// 1. En index.php, diseñar un formulario HTML con método POST que solicite: 
    Nombre del plato, Categoría (Pizzas, Hamburguesas, Empanadas), 
    Precio Base y URL de la Imagen.
2. El script PHP debe recibir la petición, verificar con ?? y empty() que los campos no estén vacíos.
3. Si los datos son válidos, crear un array asociativo $nuevoPlato.
4. Dibujar dinámicamente en pantalla la tarjeta HTML del plato recién ingresado,
    aplicando un 15% de descuento si la categoría es "Pizzas" e imprimiendo un badge destacado.

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de Plato</title>
</head>
<body>

    <h1>Formulario de Plato</h1>

    <form action="" method="POST">

        <label for="nombre">Nombre del plato:</label><br>
        <input type="text" id="nombre" name="nombre" required>
        <br><br>

        <label for="categoria">Categoría:</label><br>
        <select id="categoria" name="categoria" required>
            <option value="">Seleccione una categoría</option>
            <option value="Pizzas">Pizzas</option>
            <option value="Hamburguesas">Hamburguesas</option>
            <option value="Empanadas">Empanadas</option>
        </select>
        <br><br>

        <label for="precio">Precio Base:</label><br>
        <input type="number" id="precio" name="precio" min="0" required>
        <br><br>

        <label for="imagen">URL de la Imagen:</label><br>
        <input type="url" id="imagen" name="imagen" required>
        <br><br>

        <button type="submit">Guardar Plato</button>

    </form>

</body>
</html>