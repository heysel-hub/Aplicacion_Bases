<?php
require '../conexion.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if(!$producto){
    die("Producto no encontrado");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sql = "UPDATE productos
            SET codigo=?,
                nombre=?,
                peso=?,
                cantidad=?,
                empaque_id=?,
                categoria_id=?,
                precio_unitario=?
            WHERE id=?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $_POST['codigo'],
        $_POST['nombre'],
        $_POST['peso'],
        $_POST['cantidad'],
        $_POST['empaque_id'],
        $_POST['categoria_id'],
        $_POST['precio_unitario'],
        $id
    ]);

    header("Location: index.php");
    exit;
}

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();
$empaques = $pdo->query("SELECT * FROM empaques")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Producto</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<main class="main-content">

<h2>Editar Producto</h2>

<form method="POST">

<label>Código</label>
<input type="text" name="codigo"
value="<?= $producto['codigo'] ?>" required>

<label>Nombre</label>
<input type="text" name="nombre"
value="<?= $producto['nombre'] ?>" required>

<label>Peso</label>
<input type="number" step="0.01"
name="peso"
value="<?= $producto['peso'] ?>" required>

<label>Cantidad</label>
<input type="number"
name="cantidad"
value="<?= $producto['cantidad'] ?>" required>

<label>Precio</label>
<input type="number"
step="0.01"
name="precio_unitario"
value="<?= $producto['precio_unitario'] ?>" required>

<label>Empaque</label>

<select name="empaque_id">
<?php foreach($empaques as $e): ?>
<option value="<?= $e['id'] ?>"
<?= $e['id']==$producto['empaque_id'] ? 'selected':'' ?>>
<?= $e['tipo'] ?>
</option>
<?php endforeach; ?>
</select>

<label>Categoría</label>

<select name="categoria_id">
<?php foreach($categorias as $c): ?>
<option value="<?= $c['id'] ?>"
<?= $c['id']==$producto['categoria_id'] ? 'selected':'' ?>>
<?= $c['nombre'] ?>
</option>
<?php endforeach; ?>
</select>

<br><br>

<button type="submit" class="btn btn-teal">
Guardar Cambios
</button>

<a href="index.php" class="btn btn-ghost">
Cancelar
</a>

</form>

</main>

</body>
</html>