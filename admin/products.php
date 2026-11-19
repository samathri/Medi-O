<?php
$pageTitle = 'Products & stock';
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

/** Return to the same filtered list after an action. */
function back_to_list(): never
{
    $qs = (string)($_POST['back'] ?? '');
    redirect('admin/products.php' . ($qs !== '' && $qs[0] === '?' ? $qs : ''));
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
            flash('success', 'Saved: ' . $name);
        } else {
            q('INSERT INTO products (name, category_id, price, stock, description, requires_prescription, is_active, image_path)
               VALUES (?,?,?,?,?,?,?,?)', [$name, $categoryId, $price, $stock, $desc ?: null, $rx, $active, $img]);
            flash('success', 'Added: ' . $name);
        }
        back_to_list();
    }

    if ($action === 'stock') {
        $stock = max(0, (int)($_POST['stock'] ?? 0));
        q('UPDATE products SET stock=? WHERE id=?', [$stock, (int)$_POST['id']]);
        flash('success', 'Stock updated to ' . $stock . '.');
        back_to_list();
    }

    if ($action === 'toggle') {
        // Hiding keeps order history intact
        q('UPDATE products SET is_active = 1 - is_active WHERE id=?', [(int)$_POST['id']]);
        $on = (int)q_val('SELECT is_active FROM products WHERE id=?', [(int)$_POST['id']]);
        flash('info', $on ? 'Product is visible in the shop again.' : 'Product hidden from the shop (kept for order history).');
        back_to_list();
    }

    if ($action === 'category') {
        $catName = trim($_POST['cat_name'] ?? '');
        if ($catName !== '') {
            q('INSERT IGNORE INTO categories (name) VALUES (?)', [$catName]);
            flash('success', 'Category added.');
        }
        back_to_list();
    }
}

$search = trim($_GET['q'] ?? '');
$catFilter = (int)($_GET['cat'] ?? 0);
$view = $_GET['view'] ?? 'all';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 50;

$where = ['1'];
$params = [];
if ($search !== '') { $where[] = 'p.name LIKE ?'; $params[] = "%$search%"; }
if ($catFilter) { $where[] = 'p.category_id = ?'; $params[] = $catFilter; }
if ($view === 'low') $where[] = 'p.is_active = 1 AND p.stock <= 10';
if ($view === 'hidden') $where[] = 'p.is_active = 0';
$whereSql = implode(' AND ', $where);

$total = (int)q_val("SELECT COUNT(*) FROM products p WHERE $whereSql", $params);
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);
$products = q_all("SELECT p.*, c.name AS category FROM products p
                   LEFT JOIN categories c ON c.id=p.category_id WHERE $whereSql
                   ORDER BY " . ($view === 'low' ? 'p.stock ASC' : 'p.is_active DESC, p.name ASC') . "
                   LIMIT $perPage OFFSET " . (($page - 1) * $perPage), $params);
$categories = q_all('SELECT * FROM categories ORDER BY name');
$lowCount = (int)q_val('SELECT COUNT(*) FROM products WHERE is_active=1 AND stock <= 10');
$hiddenCount = (int)q_val('SELECT COUNT(*) FROM products WHERE is_active=0');
$editing = isset($_GET['edit']) ? q_one('SELECT * FROM products WHERE id=?', [(int)$_GET['edit']]) : null;
$showForm = $editing || isset($_GET['add']);

$listQs = '?' . http_build_query(array_filter(['q' => $search, 'cat' => $catFilter ?: null, 'view' => $view !== 'all' ? $view : null, 'page' => $page > 1 ? $page : null]));
$link = fn(array $over) => url('admin/products.php') . '?' . http_build_query(array_filter(array_merge(
    ['q' => $search, 'cat' => $catFilter ?: null, 'view' => $view !== 'all' ? $view : null], $over), fn($v) => $v !== null && $v !== ''));
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h1 class="h3 fw-bold mb-0">Products &amp; stock</h1>
  <a class="btn mo-btn" href="<?= $link(['add' => 1, 'page' => null]) ?>"><i class="bi bi-plus-lg me-1"></i>Add product</a>
</div>

<?php if ($showForm): ?>
<div class="mo-card p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 fw-bold mb-0"><?= $editing ? 'Edit: ' . e($editing['name']) : 'Add a new product' ?></h2>
    <a class="btn btn-sm btn-light border" href="<?= url('admin/products.php' . $listQs) ?>">Close</a>
  </div>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="back" value="<?= e($listQs) ?>">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Product name</label>
        <input name="name" class="form-control" required value="<?= e($editing['name'] ?? '') ?>" placeholder="e.g. Panadol 500mg — 12 tablets">
      </div>
      <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
          <option value="">Choose a category…</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($editing['category_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label">Price (Rs.)</label>
        <input type="number" name="price" step="0.01" min="0" class="form-control" required value="<?= e($editing['price'] ?? '') ?>">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label">In stock</label>
        <input type="number" name="stock" min="0" class="form-control" value="<?= e($editing['stock'] ?? '0') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Photo <span class="text-muted fw-normal">(JPG, PNG or WEBP)</span></label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control">
      </div>
      <div class="col-12">
        <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
        <textarea name="description" class="form-control" rows="2"><?= e($editing['description'] ?? '') ?></textarea>
      </div>
      <div class="col-12 d-flex flex-wrap gap-4">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" name="requires_prescription" id="rxSwitch" <?= !empty($editing['requires_prescription']) ? 'checked' : '' ?>>
          <label class="form-check-label" for="rxSwitch">Needs a prescription</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" <?= ($editing['is_active'] ?? 1) ? 'checked' : '' ?>>
          <label class="form-check-label" for="activeSwitch">Show in the shop</label>
        </div>
      </div>
    </div>
    <button class="btn mo-btn mt-3"><?= $editing ? 'Save changes' : 'Add product' ?></button>
  </form>
  <hr>
  <form method="post" class="d-flex flex-wrap gap-2 align-items-center">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="category">
    <input type="hidden" name="back" value="<?= e($listQs) ?>">
    <span class="small text-muted">Need a new category?</span>
    <input name="cat_name" class="form-control form-control-sm w-auto" placeholder="Category name">
    <button class="btn btn-sm mo-btn-soft">Add category</button>
  </form>
</div>
<?php endif; ?>

<!-- Filters -->
<form method="get" class="mo-card p-3 mb-3 d-flex flex-wrap gap-2 align-items-center">
  <input type="search" name="q" class="form-control" style="max-width:260px" placeholder="Search by name…" value="<?= e($search) ?>">
  <select name="cat" class="form-select w-auto" onchange="this.form.submit()">
    <option value="">All categories</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= $c['id'] ?>" <?= $catFilter == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
    <?php endforeach; ?>
  </select>
  <?php if ($view !== 'all'): ?><input type="hidden" name="view" value="<?= e($view) ?>"><?php endif; ?>
  <button class="btn mo-btn-soft"><i class="bi bi-search"></i></button>
  <div class="ms-auto d-flex gap-1 flex-wrap">
    <a class="btn btn-sm <?= $view === 'all' ? 'mo-btn' : 'btn-light border' ?>" href="<?= $link(['view' => null]) ?>">All</a>
    <a class="btn btn-sm <?= $view === 'low' ? 'mo-btn' : 'btn-light border' ?>" href="<?= $link(['view' => 'low']) ?>">Running low <span class="badge text-bg-warning ms-1"><?= $lowCount ?></span></a>
    <a class="btn btn-sm <?= $view === 'hidden' ? 'mo-btn' : 'btn-light border' ?>" href="<?= $link(['view' => 'hidden']) ?>">Hidden <span class="badge text-bg-secondary ms-1"><?= $hiddenCount ?></span></a>
  </div>
</form>

<div class="small text-muted mb-2"><?= $total ?> product<?= $total === 1 ? '' : 's' ?> · change a stock number and press Enter (or click away) to save it.</div>

<div class="mo-card p-0 overflow-hidden">
  <div class="table-responsive">
    <table class="table mo-table table-hover align-middle mb-0">
      <thead><tr><th></th><th>Product</th><th>Price</th><th style="width:130px">In stock</th><th></th></tr></thead>
      <tbody>
        <?php if (!$products): ?>
          <tr><td colspan="5" class="mo-empty"><?= $view === 'low' ? 'Nothing is running low 👍' : ($view === 'hidden' ? 'No hidden products.' : 'No products match.') ?></td></tr>
        <?php endif; ?>
        <?php foreach ($products as $p): ?>
          <tr class="<?= $p['is_active'] ? '' : 'opacity-50' ?>">
            <td style="width:56px"><img src="<?= product_image($p['image_path']) ?>" width="46" height="46" style="object-fit:contain" class="rounded border bg-white" alt=""></td>
            <td>
              <div class="fw-semibold small"><?= e(mb_strimwidth($p['name'], 0, 70, '…')) ?>
                <?= $p['requires_prescription'] ? '<span class="mo-chip-rx ms-1">Rx</span>' : '' ?></div>
              <small class="text-muted"><?= e($p['category'] ?? 'No category') ?><?= $p['is_active'] ? '' : ' · hidden from shop' ?></small>
            </td>
            <td class="text-nowrap"><?= money((float)$p['price']) ?></td>
            <td>
              <form method="post" class="d-flex align-items-center gap-1">
                <?= csrf_field() ?><input type="hidden" name="action" value="stock"><input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="hidden" name="back" value="<?= e($listQs) ?>">
                <input type="number" name="stock" min="0" value="<?= (int)$p['stock'] ?>" data-orig="<?= (int)$p['stock'] ?>"
                       class="form-control form-control-sm stock-input <?= $p['stock'] == 0 ? 'border-danger text-danger' : ($p['stock'] <= 10 ? 'border-warning' : '') ?>"
                       style="width:80px" onchange="if (this.value !== this.dataset.orig) this.form.submit()" aria-label="Stock for <?= e($p['name']) ?>">
              </form>
            </td>
            <td class="text-nowrap text-end">
              <a class="btn btn-sm mo-btn-soft" href="<?= $link(['edit' => $p['id'], 'page' => $page > 1 ? $page : null]) ?>"><i class="bi bi-pencil me-1"></i>Edit</a>
              <form method="post" class="d-inline">
                <?= csrf_field() ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="hidden" name="back" value="<?= e($listQs) ?>">
                <button class="btn btn-sm btn-light border" title="<?= $p['is_active'] ? 'Hide from shop' : 'Show in shop' ?>">
                  <i class="bi <?= $p['is_active'] ? 'bi-eye-slash' : 'bi-eye' ?> me-1"></i><?= $p['is_active'] ? 'Hide' : 'Show' ?></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($pages > 1): ?>
  <nav class="mt-3 d-flex justify-content-center align-items-center gap-2">
    <a class="btn btn-sm btn-light border <?= $page <= 1 ? 'disabled' : '' ?>" href="<?= $link(['page' => $page - 1]) ?>"><i class="bi bi-chevron-left"></i> Previous</a>
    <span class="small text-muted">Page <?= $page ?> of <?= $pages ?></span>
    <a class="btn btn-sm btn-light border <?= $page >= $pages ? 'disabled' : '' ?>" href="<?= $link(['page' => $page + 1]) ?>">Next <i class="bi bi-chevron-right"></i></a>
  </nav>
<?php endif; ?>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>
