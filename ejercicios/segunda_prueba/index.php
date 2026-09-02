<?php
echo "<h1> Prueba 1</h1>";
echo  "15" + 5;

echo "<h1> Prueba 2</h1>";
echo  "15" . 5;

?>
<?php
echo "<h1>Array</h1>";

$array = [
    "esclavo" => [
    "nombre" => "Facu",
    "profesion" =>"maestro pokemon",
    "edad" => 26,
    "le gusta morfar" => true,
    ],
    "jefe" => [
        "nombre" => "Ale",
        "profesion" =>"señor de los anillos",
        "edad" => 20,
        "le gusta morfar" => true,
    ]
];

foreach ($array as $persona) {
    foreach ($persona as $key => $value) {
        echo $key . ":  " . $value . "<br>";
    }
    echo "<br>";
}

echo $array["esclavo"]["edad"];

?>