<?php
require '../conexion.php';

$id = (int)$_GET['id'];

/* 1. Buscar la venta */
$stmt = $pdo->prepare("
    SELECT *
    FROM ventas
    WHERE id = ?
");
$stmt->execute([$id]);

$venta = $stmt->fetch();

if (!$venta) {
    die("La venta no existe.");
}

/* 2. Verificar si ya está anulada */
if ($venta['estado'] === 'ANULADA') {
    die("La venta ya fue anulada.");
}

/* 3. Obtener detalle de la venta (con precio) */
$stmt = $pdo->prepare("
    SELECT dv.producto_id, dv.cantidad, p.precio
    FROM venta_detalle dv
    JOIN productos p ON p.id = dv.producto_id
    WHERE dv.venta_id = ?
");
$stmt->execute([$id]);

$detalles = $stmt->fetchAll();

/* 4. Calcular total de la venta */
$totalVenta = 0;

foreach ($detalles as $d) {
    $totalVenta += $d['cantidad'] * $d['precio'];
}

/* 5. Devolver stock */
foreach ($detalles as $detalle) {

    $stmtStock = $pdo->prepare("
        UPDATE productos
        SET cantidad = cantidad + ?
        WHERE id = ?
    ");

    $stmtStock->execute([
        $detalle['cantidad'],
        $detalle['producto_id']
    ]);
}

/* 6. Restar ganancia / caja */
$stmt = $pdo->prepare("
    UPDATE caja
    SET total = total - ?
    WHERE id = 1
");

$stmt->execute([$totalVenta]);

/* 7. Marcar venta como anulada */
$stmt = $pdo->prepare("
    UPDATE ventas
    SET estado = 'ANULADA'
    WHERE id = ?
");

$stmt->execute([$id]);

/* 8. Redirigir */
header("Location: index.php");
exit;