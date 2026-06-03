<?php require '../conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Ventas</title>
  <link rel="stylesheet" href="../style.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
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
    <div class="section-header">
      <h2>Ventas</h2>
      <a href="nueva.php" class="btn btn-teal"><i class="fa fa-plus"></i> Nueva Venta</a>
    </div>
    <?php $ventas = $pdo->query("SELECT v.*, CONCAT(c.nombre,' ',c.apellido) as cliente FROM ventas v JOIN clientes c ON c.id=v.cliente_id ORDER BY v.fecha DESC LIMIT 60")->fetchAll(); ?>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ventas as $v): ?>
          <tr>
            <td>#<?= $v['id'] ?></td>

            <td><?= htmlspecialchars($v['cliente']) ?></td>

            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>

            <td style="color:var(--teal);font-weight:600">
              $<?= number_format($v['total'], 0, ',', '.') ?>
            </td>

            <td><?= $v['estado'] ?></td>

            <td>
              <a href="resumen.php?id=<?= $v['id'] ?>" class="btn btn-ghost">
                Ver
              </a>

              <?php if ($v['estado'] == 'ACTIVA'): ?>
                <a href="anular.php?id=<?= $v['id'] ?>" class="btn btn-danger"
                  onclick="return confirm('¿Desea anular esta venta?');">
                  <i class="fa fa-trash"></i>
                  Anular
                </a>

              <?php endif; ?>
            </td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
  <script src="../script.js"></script>
</body>

</html>