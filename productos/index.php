<?php require '../conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Productos — TiendaPlus</title>

  <link rel="stylesheet" href="../style.css">

  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet">

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
      <a href="index.php" class="active"><i class="fa fa-box"></i> Productos</a>
      <a href="../clientes/index.php"><i class="fa fa-users"></i> Clientes</a>
      <a href="../proveedores/index.php"><i class="fa fa-truck"></i> Proveedores</a>
      <a href="../compras/index.php"><i class="fa fa-shopping-cart"></i> Ventas</a>
      <a href="../reportes/index.php"><i class="fa fa-file-alt"></i> Reportes</a>
    </nav>
  </div>

  <main class="main-content">

    <div class="section-header">
      <h2>Productos</h2>

      <a href="crear.php" class="btn btn-teal">
        <i class="fa fa-plus"></i> Nuevo Producto
      </a>
    </div>

    <?php

    $productos = $pdo->query("
        SELECT
            p.*,
            c.nombre AS categoria,
            e.tipo AS empaque
        FROM productos p
        JOIN categorias c
            ON p.categoria_id = c.id
        JOIN empaques e
            ON p.empaque_id = e.id
        ORDER BY c.nombre, p.nombre
    ")->fetchAll();

    $categoriaMap = [
      'Papelería' => 'papeleria',
      'Droguería' => 'drogueria',
      'Supermercado' => 'supermercado',
      'Aseo' => 'aseo'
    ];

    $stockBajo = array_filter($productos, function ($p) {
      return $p['cantidad'] < 5;
    });

    ?>

    <?php if (count($stockBajo) > 0): ?>
      <div class="alert-stock">
        ⚠ Hay <?= count($stockBajo) ?> producto(s) con stock por debajo del mínimo permitido (5 unidades).
      </div>
    <?php endif; ?>

    <table class="table">

      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Precio</th>
          <th>Peso</th>
          <th>Empaque</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>

        <?php foreach ($productos as $p):

          $slug = $categoriaMap[$p['categoria']] ?? 'papeleria';

          ?>

          <tr>

            <td><?= htmlspecialchars($p['codigo']) ?></td>

            <td><?= htmlspecialchars($p['nombre']) ?></td>

            <td>
              <span class="badge badge-<?= $slug ?>">
                <?= htmlspecialchars($p['categoria']) ?>
              </span>
            </td>

            <td>
              $<?= number_format($p['precio_unitario'], 0, ',', '.') ?>
            </td>

            <td>
              <?= $p['peso'] ?> kg
            </td>

            <td>
              <?= htmlspecialchars($p['empaque']) ?>
            </td>

            <td class="<?= $p['cantidad'] < 5 ? 'qty-low' : '' ?>">

              <?= $p['cantidad'] ?>

              <?php if ($p['cantidad'] < 5): ?>
                <br>
                <span class="stock-alert">
                  ⚠ Reponer
                </span>
              <?php endif; ?>

            </td>

            <td>

              <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-teal">
                <i class="fa fa-edit"></i>
                Editar
              </a>
              <a href="stock.php?id=<?= $p['id'] ?>" class="btn btn-success">
                <i class="fa fa-plus"></i> Stock
              </a>

              <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn btn-danger">
                <i class="fa fa-trash"></i>
                Eliminar
              </a>

            </td>

          </tr>

        <?php endforeach; ?>

      </tbody>

    </table>

  </main>

  <script src="../script.js"></script>

</body>

</html>