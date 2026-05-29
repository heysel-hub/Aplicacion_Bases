<?php
require '../conexion.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO proveedores (nombre,telefono,ciudad) VALUES (?,?,?)");
  $stmt->execute([$_POST['nombre'],$_POST['telefono'],$_POST['ciudad']]);
  $prov_id = $pdo->lastInsertId();
  if (!empty($_POST['productos'])) {
    $ins = $pdo->prepare("INSERT IGNORE INTO producto_proveedor (producto_id, proveedor_id) VALUES (?,?)");
    foreach($_POST['productos'] as $pid) $ins->execute([$pid, $prov_id]);
  }
  $msg = '<div class="success-msg"><i class="fa fa-check-circle"></i> Proveedor registrado.</div>';
}
$productos = $pdo->query("SELECT id, nombre FROM productos ORDER BY nombre")->fetchAll();
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Nuevo Proveedor</title>
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
  <?= $msg ?>
  <div class="form-card">
    <h2><i class="fa fa-truck"></i> Nuevo Proveedor</h2>
    <form method="POST">
      <div class="form-row">
        <div class="form-group"><label>Nombre</label><input name="nombre" required></div>
        <div class="form-group"><label>Teléfono</label><input name="telefono"></div>
      </div>
      <div class="form-group"><label>Ciudad</label><input name="ciudad" required></div>
      <div class="form-group">
        <label>Productos que suministra (selección múltiple)</label>
        <select name="productos[]" multiple style="height:120px">
          <?php foreach($productos as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" class="btn btn-teal"><i class="fa fa-save"></i> Guardar</button>
        <a href="index.php" class="btn btn-ghost">Cancelar</a>
      </div>
    </form>
  </div>
</main>
<script src="../script.js"></script>
</body></html>