<?php
require '../conexion.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    die("Producto no encontrado");
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cantidad = (int)$_POST['cantidad'];

    if ($cantidad <= 0) {
        $mensaje = "Ingrese una cantidad válida.";
    } elseif ($cantidad > $producto['cantidad']) {
        $mensaje = "No puede eliminar más unidades de las disponibles.";
    } else {

        $stmt = $pdo->prepare("
            UPDATE productos
            SET cantidad = cantidad - ?
            WHERE id = ?
        ");

        $stmt->execute([$cantidad, $id]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Eliminar Stock</title>
<link rel="stylesheet" href="../style.css">
</head>

<body>

<main class="main-content">

<h2>Eliminar unidades del inventario</h2>

<?php if(!empty($msg)): ?>
<p><?= $msg ?></p>
<?php endif; ?>

<p>
Producto:
<strong><?= htmlspecialchars($producto['nombre']) ?></strong>
</p>

<p>
Stock actual:
<strong><?= $producto['cantidad'] ?></strong>
</p>

<form method="POST">

<label>Cantidad a eliminar</label>

<input
    type="number"
    name="cantidad"
    min="1"
    max="<?= $producto['cantidad'] ?>"
    required>

<br><br>

<button type="submit" class="btn btn-danger">
    Eliminar
</button>

<a href="index.php" class="btn btn-ghost">
    Cancelar
</a>

</form>

</main>

</body>
</html>