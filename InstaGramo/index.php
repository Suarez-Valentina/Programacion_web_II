<?php

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["user"]);
    $titulo = trim($_POST["title"]);

    if (empty($usuario) || empty($titulo)) {

        $mensaje = "Usuario y título son obligatorios.";

    } else {

        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

            $nombreArchivo = $_FILES["image"]["name"];
            $tamanio = $_FILES["image"]["size"];

            $extension = strtolower(
                pathinfo($nombreArchivo, PATHINFO_EXTENSION)
            );

            $extensionesPermitidas = ["jpg", "jpeg", "png"];

            if (!in_array($extension, $extensionesPermitidas)) {

                $mensaje = "Solo se permiten imágenes JPG, JPEG y PNG.";

            } elseif ($tamanio > 5 * 1024 * 1024) {

                $mensaje = "La imagen supera los 5 MB.";

            } elseif (!getimagesize($_FILES["image"]["tmp_name"])) {

                $mensaje = "El archivo seleccionado no es una imagen válida.";

            } else {

                $nombreUnico = uniqid() . "." . $extension;

                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    "uploads/" . $nombreUnico
                );
                // Crear publicación
                    $publicacion = [
                        "usuario" => $usuario,
                        "titulo" => $titulo,
                        "imagen" => $nombreUnico,
                        "filtro" => $_POST["filter"],
                        "likes" => $_POST["level"],
                        "sponsor" => isset($_POST["sponsor"]),
                        "color" => $_POST["color"],
                        "fecha" => $_POST["date"]
                    ];
                 // Leer publicaciones existentes
                if (file_exists("publicaciones.json")) {

                    $publicaciones = json_decode(
                        file_get_contents("publicaciones.json"),
                        true
                    );

                    if (!$publicaciones) {
                        $publicaciones = [];
                    }
                } 
                
                else {

                     $publicaciones = [];
                }

                // Agregar nueva publicación
                $publicaciones[] = $publicacion;

                // Guardar JSON
                file_put_contents(
                    "publicaciones.json",
                    json_encode($publicaciones, JSON_PRETTY_PRINT)
                );

                $mensaje = "Publicación guardada correctamente.";
            }
         } 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaGramo</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="user">Usuario / Creador</label>
        <input type="text" placeholder="@juan" id="user" name="user"> 
        <br><br>
        <label for="title">Pie de foto / Título</label>
        <textarea id="title" name="title"></textarea>
        <p><?php echo $mensaje?></p>
        <label for="image">Imagen de la publicación</label>
        <input type="file" id="image" name="image"> 
        <br><br>
        <label for="filter">Filtro de estilo</label>
        <select id="filter" name="filter">
            <option value="normal">Normal</option>
            <option value="sepia">Sepia</option>
            <option value="grayscale">Grayscale</option>
            <option value="vintage">Vintage</option>
        </select>
        <br><br>
        <label for="level">Nivel de satisfacción / Likes iniciales</label>
        <input type="range" id="level" name="level" min="1" max="10" value="5">
        <br><br>
        <label for="sponsor">Contenido exclusivo / Sponsor</label>
        <input type="checkbox" id="sponsor" name="sponsor">
        <br><br>
        <label for="color">Color del borde de la tarjeta</label>
        <input type="color" id="color" name="color">
        <br><br>
        <label for="date">Fecha de captura</label>
        <input type="date" id="date" name="date">
        <br><br>
        <button type="submit">Publicar</button>
    </form>

</body>
</html>