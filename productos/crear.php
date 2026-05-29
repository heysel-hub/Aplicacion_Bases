<?php
require '../conexion.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO productos (codigo,nombre,peso,cantidad,empaque_id,categoria_id,precio_unitario) VALUES (?,?,?,?,?,?,?)");
  $stmt->execute([$_POST['codigo'],$_POST['nombre'],$_POST['peso'],$_POST['cantidad'],$_POST['empaque_id'],$_POST['categoria_id'],$_POST['precio_unitario']]);
  $msg = '<div class="success-msg"><i class="fa fa-check-circle"></i> Producto registrado correctamente.</div>';
}
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();
$empaques   = $pdo->query("SELECT * FROM empaques")->fetchAll();
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Nuevo Producto</title>
<link rel="stylesheet" href="../style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head><body>
<div class="sidebar">
  <div class="logo"><span class="logo-icon">🛍</span><span>TiendaPlus</span></div>
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
  <?= $msg ?>
  <div class="form-card">
    <h2><i class="fa fa-box"></i> Nuevo Producto</h2>
    <form method="POST">
      <div class="form-row">
        <div class="form-group"><label>Código</label><input name="codigo" required></div>
        <div class="form-group"><label>Nombre</label><input name="nombre" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Peso (kg)</label><input name="peso" type="number" step="0.01" required></div>
        <div class="form-group"><label>Cantidad inicial</label><input name="cantidad" type="number" required></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Precio Unitario</label><input name="precio_unitario" type="number" step="0.01" required></div>
        <div class="form-group"><label>Empaque</label>
          <select name="empaque_id">
            <?php foreach($empaques as $e): ?><option value="<?= $e['id'] ?>"><?= $e['tipo'] ?></option><?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group"><label>Categoría</label>
        <select name="categoria_id">
          <?php foreach($categorias as $c): ?><option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option><?php endforeach; ?>
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