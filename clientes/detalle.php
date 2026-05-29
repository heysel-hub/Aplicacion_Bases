<?php
require '../conexion.php';
$id = (int)$_GET['id'];
$cliente = $pdo->prepare("SELECT * FROM clientes WHERE id=?");
$cliente->execute([$id]); $cliente = $cliente->fetch();
$ventas = $pdo->prepare("SELECT v.id, v.fecha, v.total FROM ventas v WHERE v.cliente_id=? ORDER BY v.fecha DESC");
$ventas->execute([$id]); $ventas = $ventas->fetchAll();
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Detalle Cliente</title>
<link rel="stylesheet" href="../style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head><body>
<div class="sidebar">
  <div class="logo"><span class="logo-icon">🛍</span><span>TiendaPlus</span></div>
  <nav>
    <a href="../index.php"><i class="fa fa-chart-pie"></i> Dashboard</a>
    <a href="../productos/index.php"><i class="fa fa-box"></i> Productos</a>
    <a href="index.php" class="active"><i class="fa fa-users"></i> Clientes</a>
    <a href="../proveedores/index.php"><i class="fa fa-truck"></i> Proveedores</a>
    <a href="../compras/index.php"><i class="fa fa-shopping-cart"></i> Ventas</a>
    <a href="../reportes/index.php"><i class="fa fa-file-alt"></i> Reportes</a>
  </nav>
</div>
<main class="main-content">
  <div class="section-header">
    <h2><?= htmlspecialchars($cliente['nombre'].' '.$cliente['apellido']) ?></h2>
    <a href="index.php" class="btn btn-ghost"><i class="fa fa-arrow-left"></i> Volver</a>
  </div>
  <div style="color:var(--muted);margin-bottom:24px">
    Cédula: <b style="color:var(--text)"><?= htmlspecialchars($cliente['cedula']) ?></b> &nbsp;|&nbsp;
    Tel: <b style="color:var(--text)"><?= htmlspecialchars($cliente['telefono']) ?></b> &nbsp;|&nbsp;
    Correo: <b style="color:var(--text)"><?= htmlspecialchars($cliente['correo']) ?></b>
  </div>
  <h3 style="margin-bottom:14px;font-family:'Playfair Display',serif">Historial de Compras</h3>
  <table class="table">
    <thead><tr><th>ID Venta</th><th>Fecha</th><th>Total</th><th></th></tr></thead>
    <tbody>
    <?php foreach($ventas as $v): ?>
      <tr>
        <td>#<?= $v['id'] ?></td>
        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
        <td style="color:var(--teal);font-weight:600">$<?= number_format($v['total'],0,',','.') ?></td>
        <td><a href="../compras/resumen.php?id=<?= $v['id'] ?>" class="btn btn-ghost" style="padding:5px 12px;font-size:.8rem">Ver resumen</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</main>
<script src="../script.js"></script>
</body></html>