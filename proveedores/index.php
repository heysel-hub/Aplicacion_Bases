<?php require '../conexion.php'; ?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Proveedores</title>
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
    <a href="index.php" class="active"><i class="fa fa-truck"></i> Proveedores</a>
    <a href="../compras/index.php"><i class="fa fa-shopping-cart"></i> Ventas</a>
    <a href="../reportes/index.php"><i class="fa fa-file-alt"></i> Reportes</a>
  </nav>
</div>
<main class="main-content">
  <div class="section-header">
    <h2>Proveedores</h2>
    <a href="crear.php" class="btn btn-teal"><i class="fa fa-plus"></i> Nuevo Proveedor</a>
  </div>
  <?php $provs = $pdo->query("SELECT pv.*, GROUP_CONCAT(p.nombre SEPARATOR ', ') as productos FROM proveedores pv LEFT JOIN producto_proveedor pp ON pp.proveedor_id=pv.id LEFT JOIN productos p ON p.id=pp.producto_id GROUP BY pv.id")->fetchAll(); ?>
  <table class="table">
    <thead><tr><th>Nombre</th><th>Teléfono</th><th>Ciudad</th><th>Productos que suministra</th></tr></thead>
    <tbody>
    <?php foreach($provs as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= htmlspecialchars($p['telefono']) ?></td>
        <td><?= htmlspecialchars($p['ciudad']) ?></td>
        <td style="color:var(--muted);font-size:.85rem"><?= htmlspecialchars($p['productos'] ?? '—') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</main>
<script src="../script.js"></script>
</body></html>