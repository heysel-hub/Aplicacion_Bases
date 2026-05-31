<?php
require '../conexion.php';

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

$proveedores = $pdo->query("SELECT * FROM proveedores ORDER BY nombre")->fetchAll();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cantidad = (int)$_POST['cantidad'];
    $proveedor_id = (int)$_POST['proveedor_id'];
    $costo_total = (float)$_POST['costo_total'];

    if ($cantidad > 0) {

        // Aumentar stock
        $stmt = $pdo->prepare("
            UPDATE productos
            SET cantidad = cantidad + ?
            WHERE id = ?
        ");
        $stmt->execute([$cantidad, $id]);

        // Registrar compra al proveedor
        $stmt = $pdo->prepare("
            INSERT INTO pagos_proveedores
            (proveedor_id, producto_id, cantidad, costo_total)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $proveedor_id,
            $id,
            $cantidad,
            $costo_total
        ]);

        header("Location: index.php");
        exit;
    }

    $msg = "La cantidad debe ser mayor a cero.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Stock</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<main class="main-content">

    <h2>Agregar Inventario</h2>

    <?php if($msg): ?>
        <div class="error-msg"><?= $msg ?></div>
    <?php endif; ?>

    <div class="form-card">

        <p><strong>Producto:</strong> <?= htmlspecialchars($producto['nombre']) ?></p>

        <p><strong>Stock actual:</strong> <?= $producto['cantidad'] ?></p>

        <form method="POST">

            <div class="form-group">
                <label>Cantidad a agregar</label>
                <input type="number" name="cantidad" min="1" required>
            </div>

            <div class="form-group">
                <label>Proveedor</label>
                <select name="proveedor_id" required>

                    <?php foreach($proveedores as $prov): ?>
                        <option value="<?= $prov['id'] ?>">
                            <?= htmlspecialchars($prov['nombre']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label>Costo Total de la Compra</label>
                <input type="number" step="0.01" name="costo_total" required>
            </div>

            <button type="submit" class="btn btn-teal">
                Guardar
            </button>

            <a href="index.php" class="btn btn-danger">
                Cancelar
            </a>

        </form>

    </div>

</main>

</body>
</html>