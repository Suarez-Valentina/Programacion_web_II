<?php

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = htmlspecialchars(trim($_POST["user"] ?? ""));
    $titulo = htmlspecialchars(trim($_POST["title"] ?? ""));

    if (empty($usuario) || empty($titulo)) {

        $mensaje = "Usuario y título son obligatorios.";

    } elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] == UPLOAD_ERR_NO_FILE) {

        $mensaje = "Debés seleccionar una imagen.";

    } elseif ($_FILES["image"]["error"] != UPLOAD_ERR_OK) {

        $mensaje = "Ocurrió un error al subir el archivo (código " . $_FILES["image"]["error"] . ").";

    } else {

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

            // Asegurarse de que exista la carpeta de destino
            if (!is_dir("uploads")) {
                mkdir("uploads", 0755, true);
            }

            $nombreUnico = uniqid() . "." . $extension;

            $moveOk = move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                "uploads/" . $nombreUnico
            );

            if (!$moveOk) {

                $mensaje = "No se pudo guardar la imagen en el servidor.";

            } else {

                // Crear publicación
                $publicacion = [
                    "usuario" => $usuario,
                    "titulo" => $titulo,
                    "imagen" => $nombreUnico,
                    "filtro" => htmlspecialchars($_POST["filter"] ?? "normal"),
                    "likes" => (int)($_POST["level"] ?? 5),
                    "sponsor" => isset($_POST["sponsor"]),
                    "color" => htmlspecialchars($_POST["color"] ?? "#000000"),
                    "fecha" => htmlspecialchars($_POST["date"] ?? "")
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

                } else {

                    $publicaciones = [];
                }

                // Agregar nueva publicación
                $publicaciones[] = $publicacion;

                // Guardar JSON
                file_put_contents(
                    "publicaciones.json",
                    json_encode($publicaciones, JSON_PRETTY_PRINT)
                );

                header("Location: index.php");
                exit;
            }
        }
    }
}

$publicaciones = [];

if (file_exists("publicaciones.json")) {
    $publicaciones = json_decode(
        file_get_contents("publicaciones.json"),
        true
    );

    if (!$publicaciones) {
        $publicaciones = [];
    }
}

// Para mostrar el feed del más nuevo al más viejo
$publicaciones = array_reverse($publicaciones);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaGramo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="topbar">
        <div class="topbar-inner">
            <span class="logo">📸 InstaGramo</span>
        </div>
    </header>

    <div class="contenedor">

        <?php if (!empty($mensaje)) { ?>
            <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>

        <div class="form-wrap">
        <form action="" method="POST" enctype="multipart/form-data">

            <div class="campo">
                <label for="user">Usuario / Creador</label>
                <input type="text" placeholder="@juan" id="user" name="user">
            </div>

            <div class="campo">
                <label for="title">Pie de foto / Título</label>
                <textarea id="title" name="title"></textarea>
            </div>

            <div class="campo">
                <label for="image">Imagen de la publicación</label>
                <input type="file" id="image" name="image">
            </div>

            <div class="fila-doble">
                <div class="campo">
                    <label for="filter">Filtro de estilo</label>
                    <select id="filter" name="filter">
                        <option value="normal">Normal</option>
                        <option value="sepia">Sepia</option>
                        <option value="grayscale">Grayscale</option>
                        <option value="vintage">Vintage</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="date">Fecha de captura</label>
                    <input type="date" id="date" name="date">
                </div>
            </div>

            <div class="campo">
                <label for="level">Nivel de satisfacción / Likes iniciales</label>
                <input type="range" id="level" name="level" min="1" max="10" value="5">
            </div>

            <div class="campo-check">
                <input type="checkbox" id="sponsor" name="sponsor">
                <label for="sponsor">Contenido exclusivo / Sponsor</label>
            </div>

            <div class="campo-color">
                <label for="color">Color del borde de la tarjeta</label>
                <input type="color" id="color" name="color" value="#dbdbdb">
            </div>

            <button type="submit">Publicar</button>

        </form>
        </div>

        <div class="feed">

        <?php if (empty($publicaciones)) { ?>

            <p class="vacio">Todavía no hay publicaciones. ¡Subí la primera!</p>

        <?php } ?>

        <?php foreach ($publicaciones as $publicacion) { ?>

            <div class="publicacion" style="border-color: <?php echo htmlspecialchars($publicacion['color']); ?>;">

                <div class="publicacion-header">
                    <span class="publicacion-usuario"><?php echo htmlspecialchars($publicacion['usuario']); ?></span>

                    <?php if ($publicacion['sponsor']) { ?>
                        <span class="badge-sponsor">⭐ Sponsor</span>
                    <?php } ?>
                </div>

                <img src="uploads/<?php echo htmlspecialchars($publicacion['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($publicacion['usuario']); ?>">

                <div class="publicacion-body">
                    <p class="publicacion-likes">❤️ <?php echo (int)$publicacion['likes']; ?> likes</p>
                    <p class="publicacion-titulo"><strong><?php echo htmlspecialchars($publicacion['usuario']); ?></strong><?php echo htmlspecialchars($publicacion['titulo']); ?></p>
                    <p class="publicacion-meta">Filtro: <?php echo htmlspecialchars($publicacion['filtro']); ?> · <?php echo htmlspecialchars($publicacion['fecha']); ?></p>
                </div>

            </div>

        <?php } ?>

        </div>

    </div>
</body>
</html>