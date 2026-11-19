<?php
require_once __DIR__ . '/config.php';

$search = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');
$maxPrice = (float)($_GET['max_price'] ?? 0);
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;

$where = ['p.is_active = 1'];
$params = [];

if ($search !== '') {
    $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category !== '') {
    $where[] = 'c.name = ?';
    $params[] = $category;
}
if ($maxPrice > 0) {
    $where[] = 'p.price <= ?';
    $params[] = $maxPrice;
}

$orderBy = match ($sort) {
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'rating' => 'p.rating DESC',
    'name' => 'p.name ASC',
    default => 'p.id DESC',
};

$whereSql = implode(' AND ', $where);
$total = (int)q_val("SELECT COUNT(*) FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE $whereSql", $params);
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);
$offset = ($page - 1) * $perPage;

$products = q_all("SELECT p.*, c.name AS category_name FROM products p
                   LEFT JOIN categories c ON c.id = p.category_id
                   WHERE $whereSql ORDER BY $orderBy LIMIT $perPage OFFSET $offset", $params);

$categories = q_all('SELECT c.*, COUNT(p.id) AS cnt FROM categories c
                     JOIN products p ON p.category_id = c.id AND p.is_active = 1
                     GROUP BY c.id ORDER BY c.name');
$priceMax = (float)q_val('SELECT CEIL(MAX(price)) FROM products WHERE is_active = 1');

function shop_url(array $overrides = []): string
{
    $qs = array_merge($_GET, $overrides);
    unset($qs['page']);
    if (isset($overrides['page'])) $qs['page'] = $overrides['page'];
    return url('shop.php') . ($qs ? '?' . http_build_query(array_filter($qs, fn($v) => $v !== '' && $v !== null)) : '');
}

$pageTitle = 'Shop';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4">
  <div class="page-head d-flex flex-wrap justify-content-between align-items-end gap-2">
    <div>
      <h1 class="h2 mb-1"><?= $category !== '' ? e($category) : 'All products' ?></h1>
      <div class="text-muted"><?= $total ?> product<?= $total === 1 ? '' : 's' ?><?= $search !== '' ? ' for "' . e($search) . '"' : '' ?></div>
    </div>
    <form class="d-flex gap-2" method="get">
      <?php if ($category !== ''): ?><input type="hidden" name="category" value="<?= e($category) ?>"><?php endif; ?>
      <input type="search" name="q" class="form-control" placeholder="Search products…" value="<?= e($search) ?>" style="min-width:200px">
      <button class="btn mo-btn"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <div class="row g-4">
    <!-- Filters -->
    <aside class="col-lg-3">
      <div class="mo-card p-3 mb-3 d-lg-none">
        <button class="btn mo-btn-soft w-100" data-bs-toggle="collapse" data-bs-target="#filters">
          <i class="bi bi-funnel me-2"></i>Filters
        </button>
      </div>
      <div class="collapse d-lg-block" id="filters">
        <div class="mb-4">
          <div class="mo-filter-title">Aisles</div>
          <div class="mo-filter-list">
            <a href="<?= shop_url(['category' => '']) ?>" class="<?= $category === '' ? 'on' : '' ?>"><span>All products</span></a>
            <?php foreach ($categories as $c): ?>
              <a href="<?= shop_url(['category' => $c['name']]) ?>" class="<?= $category === $c['name'] ? 'on' : '' ?>">
                <span><?= e($c['name']) ?></span><span class="text-muted small"><?= (int)$c['cnt'] ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <div>
          <div class="mo-filter-title">Max price</div>
          <form method="get">
            <?php if ($search !== ''): ?><input type="hidden" name="q" value="<?= e($search) ?>"><?php endif; ?>
            <?php if ($category !== ''): ?><input type="hidden" name="category" value="<?= e($category) ?>"><?php endif; ?>
            <input type="range" class="form-range" name="max_price" min="100" max="<?= $priceMax ?>" step="100"
                   value="<?= $maxPrice > 0 ? $maxPrice : $priceMax ?>"
                   oninput="document.getElementById('priceOut').textContent='Rs. '+(+this.value).toLocaleString()">
            <div class="d-flex justify-content-between align-items-center">
              <output id="priceOut" class="fw-bold">Rs. <?= number_format($maxPrice > 0 ? $maxPrice : $priceMax) ?></output>
              <button class="btn btn-sm mo-btn-soft">Apply</button>
            </div>
          </form>
        </div>
      </div>
    </aside>

    <!-- Grid -->
    <div class="col-lg-9">
      <div class="d-flex justify-content-end mb-3">
        <select class="form-select w-auto" onchange="location.href=this.value">
          <?php foreach (['newest' => 'Newest first', 'rating' => 'Top rated', 'price_asc' => 'Price: low → high', 'price_desc' => 'Price: high → low', 'name' => 'Name A–Z'] as $k => $label): ?>
            <option value="<?= shop_url(['sort' => $k]) ?>" <?= $sort === $k ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <?php if (!$products): ?>
        <div class="mo-card mo-empty"><i class="bi bi-search"></i>
          <h3 class="h5">No products found</h3>
          <p>Try a different search or <a href="<?= url('shop.php') ?>">clear the filters</a>.</p>
        </div>
      <?php else: ?>
        <div class="row g-3 g-md-4 row-cols-2 row-cols-md-3">
          <?php foreach ($products as $p): ?>
            <div class="col"><?php include __DIR__ . '/components/product-card.php'; ?></div>
          <?php endforeach; ?>
        </div>

        <?php if ($pages > 1): ?>
          <nav class="mt-4">
            <?php
              $show = array_unique(array_filter([1, $page - 1, $page, $page + 1, $pages], fn($n) => $n >= 1 && $n <= $pages));
              sort($show);
            ?>
            <ul class="pagination justify-content-center align-items-center gap-1">
              <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= shop_url(['page' => max(1, $page - 1)]) ?>" aria-label="Previous"><i class="bi bi-chevron-left"></i></a>
              </li>
              <?php $prev = 0; foreach ($show as $i): ?>
                <?php if ($i - $prev > 1): ?><li class="page-item disabled"><span class="page-link border-0 bg-transparent">…</span></li><?php endif; ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                  <a class="page-link" href="<?= shop_url(['page' => $i]) ?>"><?= $i ?></a>
                </li>
              <?php $prev = $i; endforeach; ?>
              <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= shop_url(['page' => min($pages, $page + 1)]) ?>" aria-label="Next"><i class="bi bi-chevron-right"></i></a>
              </li>
            </ul>
            <p class="text-center text-muted small mt-2">Page <?= $page ?> of <?= $pages ?></p>
          </nav>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>
