<?php
require '../conexion.php';
$id = (int)$_GET['id'];
$venta = $pdo->prepare("SELECT v.*, CONCAT(c.nombre,' ',c.apellido) as cliente, c.cedula FROM ventas v JOIN clientes c ON c.id=v.cliente_id WHERE v.id=?");
$venta->execute([$id]); $venta = $venta->fetch();
$items = $pdo->prepare("SELECT vd.*, p.nombre as producto, cat.nombre as categoria FROM venta_detalle vd JOIN productos p ON p.id=vd.producto_id JOIN categorias cat ON cat.id=p.categoria_id WHERE vd.venta_id=?");
$items->execute([$id]); $items = $items->fetchAll();
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Resumen Venta</title>
<link rel="stylesheet" href="../style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head><body>
<div class="sidebar">
  <div class="logo"><span class="logo-icon">🛍</span><span>TiendaPlus</span></div>
  <nav>
    <a href="../index.php"><i class="fa fa-chart-pie"></i> Dashboard</a>
    <a href="../productos/index.php"><i class="fa fa-box"></i> Productos</a>
    <a href="../clientes/index.php"><i class="fa fa-users"></i> Clientes</a>
    <a href="../proveedores/index.php"><i class="fa fa-truck"></i> Proveedores</a>
    <a href="index.php" class="active"><i class="fa fa-shopping-cart"></i> Ventas</a>
    <a href="../reportes/index.php"><i class="fa fa-file-alt"></i> Reportes</a>
  </nav>
</div>
<main class="main-content">
  <div class="resumen-box">
    <h2><i class="fa fa-receipt"></i> Resumen de Compra #<?= $id ?></h2>
    <p style="color:var(--muted);margin-bottom:18px">
      Cliente: <b style="color:var(--text)"><?= htmlspecialchars($venta['cliente']) ?></b> (<?= htmlspecialchars($venta['cedula']) ?>)<br>
      Fecha: <b style="color:var(--text)"><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></b>
    </p>
    <table class="table">
      <thead><tr><th>Producto</th><th>Categoría</th><th>Cant.</th><th>Precio Unit.</th><th>Impuesto</th><th>Subtotal</th></tr></thead>
      <tbody>
      <?php foreach($items as $it): ?>
        <tr>
          <td><?= htmlspecialchars($it['producto']) ?></td>
          <td><?= htmlspecialchars($it['categoria']) ?></td>
          <td><?= $it['cantidad'] ?></td>
          <td>$<?= number_format($it['precio_unitario'],0,',','.') ?></td>
          <td><?= $it['impuesto'] ?>%</td>
          <td style="color:var(--teal)">$<?= number_format($it['subtotal'],0,',','.') ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <div class="resumen-total">Total pagado: $<?= number_format($venta['total'],0,',','.') ?></div>
    <div style="margin-top:20px;display:flex;gap:10px">
      <a href="index.php" class="btn btn-ghost"><i class="fa fa-arrow-left"></i> Volver</a>
      <a href="nueva.php" class="btn btn-teal"><i class="fa fa-plus"></i> Nueva Venta</a>
    </div>
  </div>
</main>
<script src="../script.js"></script>
</body></html>