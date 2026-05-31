<?php require '../conexion.php'; ?>

<!DOCTYPE html>

<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reportes</title>

<link rel="stylesheet" href="../style.css">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

<div class="sidebar">
  <div class="logo">
    <span class="logo-icon">🛍</span>
    <span>TiendaPlus</span>
  </div>

  <nav>
    <a href="../index.php"><i class="fa fa-chart-pie"></i> Dashboard</a>
    <a href="../productos/index.php"><i class="fa fa-box"></i> Productos</a>
    <a href="../clientes/index.php"><i class="fa fa-users"></i> Clientes</a>
    <a href="../proveedores/index.php"><i class="fa fa-truck"></i> Proveedores</a>
    <a href="../compras/index.php"><i class="fa fa-shopping-cart"></i> Ventas</a>
    <a href="index.php" class="active"><i class="fa fa-file-alt"></i> Reportes</a>
  </nav>
</div>

<main class="main-content">

  <div class="section-header">
    <h2>Reportes</h2>
  </div>

  <?php

  $topCompras = $pdo->query("
      SELECT
          CONCAT(c.nombre,' ',c.apellido) AS cliente,
          COUNT(v.id) AS num_compras,
          COALESCE(SUM(v.total),0) AS total_gastado
      FROM clientes c
      JOIN ventas v ON v.cliente_id = c.id
      GROUP BY c.id
      ORDER BY num_compras DESC
      LIMIT 10
  ")->fetchAll();

  $unaCompra = $pdo->query("
      SELECT
          CONCAT(c.nombre,' ',c.apellido) AS cliente,
          c.cedula
      FROM clientes c
      JOIN ventas v ON v.cliente_id = c.id
      GROUP BY c.id
      HAVING COUNT(v.id)=1
  ")->fetchAll();

  $masFrecuente = $pdo->query("
      SELECT
          CONCAT(c.nombre,' ',c.apellido) AS cliente,
          COUNT(v.id) AS num,
          COALESCE(SUM(v.total),0) AS total
      FROM clientes c
      JOIN ventas v ON v.cliente_id = c.id
      GROUP BY c.id
      ORDER BY num DESC
      LIMIT 1
  ")->fetch();

  $mayorCompra = $pdo->query("
      SELECT
          CONCAT(c.nombre,' ',c.apellido) AS cliente,
          MAX(v.total) AS compra_maxima
      FROM clientes c
      JOIN ventas v ON v.cliente_id = c.id
      GROUP BY c.id
      ORDER BY compra_maxima DESC
      LIMIT 1
  ")->fetch();

  $ingresos = $pdo->query("
      SELECT COALESCE(SUM(total),0)
      FROM ventas
  ")->fetchColumn();

  $gastos = $pdo->query("
      SELECT COALESCE(SUM(costo_total),0)
      FROM pagos_proveedores
  ")->fetchColumn();

  $ganancia = $ingresos - $gastos;

  $stockBajo = $pdo->query("
      SELECT nombre, cantidad
      FROM productos
      WHERE cantidad <= 5
      ORDER BY cantidad ASC
  ")->fetchAll();

  $productosMasVendidos = $pdo->query("
      SELECT
          p.nombre,
          SUM(vd.cantidad) AS total_vendido
      FROM venta_detalle vd
      JOIN productos p
          ON p.id = vd.producto_id
      GROUP BY p.id
      ORDER BY total_vendido DESC
      LIMIT 5
  ")->fetchAll();

  ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:24px">

```
<div class="card card-green" style="flex-direction:column;align-items:flex-start">
  <span style="color:var(--muted);font-size:.82rem;text-transform:uppercase;letter-spacing:.05em">
    Ingresos Totales
  </span>

  <span style="font-size:1.8rem;font-weight:700;color:var(--green)">
    $<?= number_format($ingresos,0,',','.') ?>
  </span>
</div>

<div class="card card-red" style="flex-direction:column;align-items:flex-start">
  <span style="color:var(--muted);font-size:.82rem;text-transform:uppercase;letter-spacing:.05em">
    Gastos Proveedores
  </span>

  <span style="font-size:1.8rem;font-weight:700;color:var(--rose)">
    $<?= number_format($gastos,0,',','.') ?>
  </span>
</div>
```

  </div>

  <div class="card card-green" style="margin-bottom:24px;flex-direction:column;align-items:flex-start">
    <span style="color:var(--muted);font-size:.82rem;text-transform:uppercase;letter-spacing:.05em">
      Ganancia Total
    </span>

```
<span style="font-size:1.8rem;font-weight:700;color:var(--green)">
  $<?= number_format($ganancia,0,',','.') ?>
</span>
```

  </div>

  <?php if($masFrecuente): ?>

  <div style="background:linear-gradient(135deg,#0d2a23,#0d1f2a);border:1px solid #1a4a38;border-radius:var(--radius);padding:20px;margin-bottom:22px">

```
<p style="color:var(--muted);font-size:.82rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">
  ⭐ Cliente Más Frecuente
</p>

<p style="font-size:1.25rem;font-weight:600;color:var(--teal)">
  <?= htmlspecialchars($masFrecuente['cliente']) ?>
</p>

<p style="color:var(--muted)">
  <?= $masFrecuente['num'] ?> compras — Total:
  <b style="color:var(--text)">
    $<?= number_format($masFrecuente['total'],0,',','.') ?>
  </b>
</p>
```

  </div>
  <?php endif; ?>

  <?php if($mayorCompra): ?>

  <div style="background:#141d29;border:1px solid #27324a;border-radius:var(--radius);padding:20px;margin-bottom:22px">

```
<p style="color:var(--muted);font-size:.82rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">
  💰 Cliente con la Compra Más Alta
</p>

<p style="font-size:1.25rem;font-weight:600;color:var(--teal)">
  <?= htmlspecialchars($mayorCompra['cliente']) ?>
</p>

<p>
  $<?= number_format($mayorCompra['compra_maxima'],0,',','.') ?>
</p>
```

  </div>
  <?php endif; ?>

  <div class="report-grid">

```
<div class="report-card">
  <h3><i class="fa fa-trophy"></i> Top Clientes por Compras</h3>

  <table class="table">
    <thead>
      <tr>
        <th>Cliente</th>
        <th>Compras</th>
        <th>Total</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($topCompras as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['cliente']) ?></td>
        <td><?= $r['num_compras'] ?></td>
        <td style="color:var(--teal)">
          $<?= number_format($r['total_gastado'],0,',','.') ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>

<div class="report-card">

  <h3>
    <i class="fa fa-user-clock"></i>
    Clientes con una sola compra
  </h3>

  <?php if(empty($unaCompra)): ?>

    <p style="color:var(--muted)">
      Ninguno por ahora.
    </p>

  <?php else: ?>

  <table class="table">
    <thead>
      <tr>
        <th>Cliente</th>
        <th>Cédula</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($unaCompra as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['cliente']) ?></td>
        <td><?= htmlspecialchars($r['cedula']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php endif; ?>

</div>
```

  </div>

  <div class="report-grid" style="margin-top:20px">

```
<div class="report-card">

  <h3>
    <i class="fa fa-box-open"></i>
    Productos con Stock Bajo
  </h3>

  <?php if(empty($stockBajo)): ?>

    <p style="color:var(--muted)">
      No hay productos con stock bajo.
    </p>

  <?php else: ?>

  <table class="table">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($stockBajo as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= $p['cantidad'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php endif; ?>

</div>

<div class="report-card">

  <h3>
    <i class="fa fa-chart-line"></i>
    Productos Más Vendidos
  </h3>

  <table class="table">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Vendidos</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($productosMasVendidos as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= $p['total_vendido'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>
```

  </div>

</main>

<script src="../script.js"></script>

</body>
</html>
