<?php
require '../conexion.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cliente_id = (int)$_POST['cliente_id'];
  $productos  = $_POST['producto_id'];
  $cantidades = $_POST['cantidad'];

  $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id, total) VALUES (?,0)");
  $stmt->execute([$cliente_id]);
  $venta_id = $pdo->lastInsertId();
  $total = 0;

  for ($i=0; $i<count($productos); $i++) {
    $pid = (int)$productos[$i];
    $qty = (int)$cantidades[$i];
    if ($pid <= 0 || $qty <= 0) continue;
    $prod = $pdo->prepare("SELECT p.precio_unitario, c.impuesto, p.cantidad FROM productos p JOIN categorias c ON c.id=p.categoria_id WHERE p.id=?");
    $prod->execute([$pid]); $prod = $prod->fetch();
    $precio   = $prod['precio_unitario'];
    $imp      = $prod['impuesto'];
    $subtotal = $qty * $precio * (1 + $imp/100);
    $total   += $subtotal;
    $ins = $pdo->prepare("INSERT INTO venta_detalle (venta_id,producto_id,cantidad,precio_unitario,impuesto,subtotal) VALUES (?,?,?,?,?,?)");
    $ins->execute([$venta_id,$pid,$qty,$precio,$imp,round($subtotal,2)]);
    $pdo->prepare("UPDATE productos SET cantidad=cantidad-? WHERE id=?")->execute([$qty,$pid]);
  }
  $pdo->prepare("UPDATE ventas SET total=? WHERE id=?")->execute([round($total,2),$venta_id]);
  header("Location: resumen.php?id=$venta_id");
  exit;
}
$clientes  = $pdo->query("SELECT * FROM clientes ORDER BY nombre")->fetchAll();
$productos = $pdo->query("SELECT p.*, c.nombre as cat FROM productos p JOIN categorias c ON c.id=p.categoria_id ORDER BY c.nombre,p.nombre")->fetchAll();
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Nueva Venta</title>
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
  <div class="section-header"><h2>Nueva Venta</h2></div>
  <div class="form-card" style="max-width:860px">
    <form method="POST" id="ventaForm">
      <div class="form-group"><label>Cliente</label>
        <select name="cliente_id" required>
          <option value="">— Seleccionar cliente —</option>
          <?php foreach($clientes as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre'].' '.$c['apellido'].' — '.$c['cedula']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div id="items">
        <div class="item-row form-row" style="align-items:end;margin-bottom:10px">
          <div class="form-group"><label>Producto</label>
            <select name="producto_id[]">
              <option value="">— Producto —</option>
              <?php foreach($productos as $p): ?><option value="<?= $p['id'] ?>">[<?= htmlspecialchars($p['cat']) ?>] <?= htmlspecialchars($p['nombre']) ?> — $<?= number_format($p['precio_unitario'],0,',','.') ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="max-width:120px"><label>Cantidad</label><input name="cantidad[]" type="number" min="1" value="1"></div>
        </div>
      </div>
      <button type="button" class="btn btn-ghost" style="margin-bottom:18px" onclick="addItem()"><i class="fa fa-plus"></i> Agregar producto</button>
      <div style="display:flex;gap:10px">
        <button type="submit" class="btn btn-teal"><i class="fa fa-check"></i> Registrar Venta</button>
        <a href="index.php" class="btn btn-ghost">Cancelar</a>
      </div>
    </form>
  </div>
</main>
<script src="../script.js"></script>
<script>
const productosHTML = document.querySelector('#items .item-row').innerHTML;
function addItem() {
  const div = document.createElement('div');
  div.className = 'item-row form-row';
  div.style.cssText = 'align-items:end;margin-bottom:10px';
  div.innerHTML = productosHTML + `<div class="form-group" style="max-width:60px;padding-top:22px"><button type="button" onclick="this.closest('.item-row').remove()" class="btn btn-rose" style="padding:8px 12px"><i class="fa fa-trash"></i></button></div>`;
  document.getElementById('items').appendChild(div);
}
</script>
</body></html>