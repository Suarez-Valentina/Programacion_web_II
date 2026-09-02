<?php
// Array asociativo de un plato de PideYa
$plato = [
    "nombre" => "Milanesa Napolitana",
    "precio" => 5200,
    "categoria" => "Promociones",
    "esVegano" => false
];

echo $plato["nombre"] . "<br>"; // Milanesa Napolitana

if ($plato["categoria"] == "Promociones") {
    $plato["precio"] = $plato["precio"] - ($plato["precio"] * 0.15);
}

echo $plato["precio"]; // 15% de descuento

?>