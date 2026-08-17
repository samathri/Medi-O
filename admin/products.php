<?php
$pageTitle = 'Products';
require_once __DIR__ . '/../components/staff-header.php';
if (!is_admin()) { http_response_code(403); exit('Admin only.'); }

function save_product_image(?array $f): ?string
{
    if (!$f || $f['error'] !== UPLOAD_ERR_OK) return null;
    if ($f['size'] > 3 * 1024 * 1024) { flash('error', 'Image too large (max 3 MB).'); return null; }
    $mime = mime_content_type($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if (!$ext) { flash('error', 'Only JPG, PNG or WEBP images allowed.'); return null; }
    $name = 'p_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], ROOT_PATH . '/uploads/products/' . $name)) return null;
    return 'uploads/products/' . $name;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0) ?: null;
        $price = max(0, (float)($_POST['price'] ?? 0));
        $stock = max(0, (int)($_POST['stock'] ?? 0));
        $desc = trim($_POST['description'] ?? '');
        $rx = isset($_POST['requires_prescription']) ? 1 : 0;
        $active = isset($_POST['is_active']) ? 1 : 0;
        $img = save_product_image($_FILES['image'] ?? null);

        if ($name === '' || $price <= 0) {
            flash('error', 'Product needs a name and a price.');
        } elseif ($id) {
            q('UPDATE products SET name=?, category_id=?, price=?, stock=?, description=?,
               requires_prescription=?, is_active=?' . ($img ? ', image_path=?' : '') . ' WHERE id=?',
                array_merge([$name, $categoryId, $price, $stock, $desc ?: null, $rx, $active], $img ? [$img] : [], [$id]));
            flash('success', 'Product updated.');
        } else {
            q('INSERT INTO products (name, category_id, price, stock, description, requires_prescription, is_active, image_path)
               VALUES (?,?,?,?,?,?,?,?)', [$name, $categoryId, $price, $stock, $desc ?: null, $rx, $active, $img]);
            flash('success', 'Product added.');
        }
        redirect('admin/products.php');
    }

    if ($action === 'delete') {
        // Soft delete keeps order history intact
        q('UPDATE products SET is_active=0 WHERE id=?', [(int)$_POST['id']]);
        flash('info', 'Product hidden from the shop (kept for order history).');
        redirect('admin/products.php');
    }

    if ($action === 'category') {
        $catName = trim($_POST['cat_name'] ?? '');
        if ($catName !== '') {
            q('INSERT IGNORE INTO categories (name) VALUES (?)', [$catName]);
            flash('success', 'Category added.');
        }
        redirect('admin/products.php');
    }
}

$search = trim($_GET['q'] ?? '');
$where = 'WHERE 1';
$params = [];
if ($search !== '') {
    $where .= ' AND p.name LIKE ?';
    $params[] = "%$search%";
}
$products = q_all("SELECT p.*, c.name AS category FROM products p
                   LEFT JOIN categories c ON c.id=p.category_id $where
                   ORDER BY p.is_active DESC, p.id DESC LIMIT 200", $params);
$categories = q_all('SELECT * FROM categories ORDER BY name');
$editing = isset($_GET['edit']) ? q_one('SELECT * FROM products WHERE id=?', [(int)$_GET['edit']]) : null;
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <h1 class="h3 fw-bold mb-0">Products &amp; stock</h1>
  <form class="d-flex gap-2" method="get">
    <input type="search" name="q" class="form-control" placeholder="Search products…" value="<?= e($search) ?>">
    <button class="btn mo-btn"><i class="bi bi-search"></i></button>
  </form>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="mo-card p-4 position-sticky" style="top:16px">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3"><?= $editing ? 'Edit product' : 'Add product' ?></h2>
      <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>
        <div class="mb-2">
          <label class="form-label fw-semibold small">Name</label>
          <input name="name" class="form-control form-control-sm" required value="<?= e($editing['name'] ?? '') ?>">
        </div>
        <div class="row g-2 mb-2">
          <div class="col-7">
            <label class="form-label fw-semibold small">Category</label>
            <select name="category_id" class="form-select form-select-sm">
              <option value="">None</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($editing['category_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-5">
            <label class="form-label fw-semibold small">Price (Rs.)</label>
            <input type="number" name="price" step="0.01" min="0" class="form-control form-control-sm" required value="<?= e($editing['price'] ?? '') ?>">
          </div>
        </div>
        <div class="row g-2 mb-2">
          <div class="col-5">
            <label class="form-label fw-semibold small">Stock</label>
            <input type="number" name="stock" min="0" class="form-control form-control-sm" value="<?= e($editing['stock'] ?? '0') ?>">
          </div>
          <div class="col-7">
            <label class="form-label fw-semibold small">Image (JPG/PNG/WEBP)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-sm">
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label fw-semibold small">Description</label>
          <textarea name="description" class="form-control form-control-sm" rows="3"><?= e($editing['description'] ?? '') ?></textarea>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" name="requires_prescription" id="rxSwitch" <?= !empty($editing['requires_prescription']) ? 'checked' : '' ?>>
          <label class="form-check-label small" for="rxSwitch">Requires prescription</label>
        </div>
        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" <?= ($editing['is_active'] ?? 1) ? 'checked' : '' ?>>
          <label class="form-check-label small" for="activeSwitch">Visible in shop</label>
        </div>
        <div class="d-flex gap-2">
          <button class="btn mo-btn flex-fill"><?= $editing ? 'Save changes' : 'Add product' ?></button>
          <?php if ($editing): ?><a class="btn btn-outline-secondary" href="<?= url('admin/products.php') ?>">Cancel</a><?php endif; ?>
        </div>
      </form>
      <hr>
      <form method="post" class="d-flex gap-2">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="category">
        <input name="cat_name" class="form-control form-control-sm" placeholder="New category name">
        <button class="btn btn-sm mo-btn-soft">Add</button>
      </form>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="mo-card p-0 overflow-hidden">
      <div class="table-responsive">
        <table class="table mo-table table-hover align-middle mb-0">
          <thead><tr><th></th><th>Product</th><th>Price</th><th>Stock</th><th>Rx</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($products as $p): ?>
              <tr class="<?= $p['is_active'] ? '' : 'opacity-50' ?>">
                <td style="width:56px"><img src="<?= product_image($p['image_path']) ?>" width="46" height="46" style="object-fit:contain" class="rounded border bg-white" alt=""></td>
                <td>
                  <div class="fw-semibold small"><?= e(mb_strimwidth($p['name'], 0, 60, '…')) ?></div>
                  <small class="text-muted"><?= e($p['category'] ?? 'Uncategorised') ?><?= $p['is_active'] ? '' : ' · hidden' ?></small>
                </td>
                <td class="text-nowrap"><?= money((float)$p['price']) ?></td>
                <td>
                  <span class="mo-status <?= $p['stock'] == 0 ? 'mo-status-cancelled' : ($p['stock'] <= 5 ? 'mo-status-pending' : 'mo-status-ready') ?>"><?= (int)$p['stock'] ?></span>
                </td>
                <td><?= $p['requires_prescription'] ? '<span class="mo-chip-rx">Rx</span>' : '' ?></td>
                <td class="text-nowrap">
                  <a class="btn btn-sm mo-btn-soft" href="<?= url('admin/products.php?edit=' . $p['id']) ?>"><i class="bi bi-pencil"></i></a>
                  <?php if ($p['is_active']): ?>
                    <form method="post" class="d-inline" onsubmit="return confirm('Hide this product from the shop?')">
                      <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $p['id'] ?>">
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-eye-slash"></i></button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>
