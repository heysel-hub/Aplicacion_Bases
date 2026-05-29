<?php require 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TiendaPlus — Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php
  $totalClientes   = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
  $totalProductos  = $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();
  $totalVentas     = $pdo->query("SELECT COALESCE(SUM(total),0) FROM ventas")->fetchColumn();
  $totalGastos     = $pdo->query("SELECT COALESCE(SUM(costo_total),0) FROM pagos_proveedores")->fetchColumn();
  $stockBajo       = $pdo->query("SELECT p.nombre, p.cantidad, c.nombre as categoria FROM productos p JOIN categorias c ON p.categoria_id=c.id WHERE p.cantidad < 5")->fetchAll();
?>

<div class="sidebar">
  <div class="logo"><span class="logo-icon">🛍</span><span>TiendaPlus</span></div>
  <nav>
    <a href="index.php" class="active"><i class="fa fa-chart-pie"></i> Dashboard</a>
    <a href="productos/index.php"><i class="fa fa-box"></i> Productos</a>
    <a href="clientes/index.php"><i class="fa fa-users"></i> Clientes</a>
    <a href="proveedores/index.php"><i class="fa fa-truck"></i> Proveedores</a>
    <a href="compras/index.php"><i class="fa fa-shopping-cart"></i> Ventas</a>
    <a href="reportes/index.php"><i class="fa fa-file-alt"></i> Reportes</a>
  </nav>
</div>

<main class="main-content">
  <header class="topbar">
    <h1>Dashboard</h1>
    <span class="date"><?= date('d M Y') ?></span>
  </header>

  <div class="cards-grid">
    <div class="card card-teal">
      <i class="fa fa-users card-icon"></i>
      <div class="card-info"><span class="card-num"><?= $totalClientes ?></span><span class="card-label">Clientes</span></div>
    </div>
    <div class="card card-gold">
      <i class="fa fa-box card-icon"></i>
      <div class="card-info"><span class="card-num"><?= $totalProductos ?></span><span class="card-label">Productos</span></div>
    </div>
    <div class="card card-green">
      <i class="fa fa-dollar-sign card-icon"></i>
      <div class="card-info"><span class="card-num">$<?= number_format($totalVentas,0,',','.') ?></span><span class="card-label">Ingresos</span></div>
    </div>
    <div class="card card-red">
      <i class="fa fa-hand-holding-usd card-icon"></i>
      <div class="card-info"><span class="card-num">$<?= number_format($totalGastos,0,',','.') ?></span><span class="card-label">Gastos Proveedores</span></div>
    </div>
  </div>

  <?php if (!empty($stockBajo)): ?>
  <div class="alert-box">
    <h3><i class="fa fa-exclamation-triangle"></i> Productos con stock bajo (menos de 5 unidades)</h3>
    <table class="table">
      <thead><tr><th>Producto</th><th>Categoría</th><th>Cantidad</th></tr></thead>
      <tbody>
        <?php foreach($stockBajo as $p): ?>
        <tr><td><?= htmlspecialchars($p['nombre']) ?></td><td><?= htmlspecialchars($p['categoria']) ?></td><td class="qty-low"><?= $p['cantidad'] ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</main>
<script src="../script.js"></script>
</body>
</html>