<?php require '../conexion.php'; ?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Clientes</title>
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
    <h2>Clientes</h2>
    <a href="crear.php" class="btn btn-teal"><i class="fa fa-plus"></i> Nuevo Cliente</a>
  </div>
  <?php $clientes = $pdo->query("SELECT c.*, COUNT(v.id) as total_compras FROM clientes c LEFT JOIN ventas v ON v.cliente_id=c.id GROUP BY c.id ORDER BY total_compras DESC")->fetchAll(); ?>
  <table class="table">
    <thead><tr><th>Cédula</th><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Compras</th><th></th></tr></thead>
    <tbody>
    <?php foreach($clientes as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['cedula']) ?></td>
        <td><?= htmlspecialchars($c['nombre'].' '.$c['apellido']) ?></td>
        <td><?= htmlspecialchars($c['telefono']) ?></td>
        <td><?= htmlspecialchars($c['correo']) ?></td>
        <td><?= $c['total_compras'] ?></td>
        <td><a href="detalle.php?id=<?= $c['id'] ?>" class="btn btn-ghost" style="padding:5px 12px;font-size:.8rem">Ver</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</main>
<script src="../script.js"></script>
</body></html>