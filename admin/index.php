<?php
$pageTitle = 'Analytics';
$active = 'index.php';
require_once __DIR__ . '/../components/staff-header.php';
if (!is_admin()) { http_response_code(403); exit('Admin only.'); }

$totals = [
    'revenue' => (float)q_val("SELECT COALESCE(SUM(total),0) FROM orders WHERE payment_status='paid'"),
    'orders' => (int)q_val('SELECT COUNT(*) FROM orders'),
    'customers' => (int)q_val("SELECT COUNT(*) FROM users WHERE role='customer'"),
    'products' => (int)q_val('SELECT COUNT(*) FROM products WHERE is_active=1'),
];

// Last 14 days revenue + orders
$days = q_all("SELECT DATE(created_at) AS d,
                      COUNT(*) AS orders_cnt,
                      COALESCE(SUM(CASE WHEN payment_status='paid' THEN total END),0) AS revenue
               FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
               GROUP BY DATE(created_at)");
$byDate = array_column($days, null, 'd');
$labels = $revData = $ordData = [];
for ($i = 13; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('j M', strtotime($d));
    $revData[] = (float)($byDate[$d]['revenue'] ?? 0);
    $ordData[] = (int)($byDate[$d]['orders_cnt'] ?? 0);
}

$topProducts = q_all('SELECT product_name, SUM(quantity) AS qty, SUM(price*quantity) AS value
                      FROM order_items GROUP BY product_name ORDER BY qty DESC LIMIT 6');
$rxStats = [];
foreach (q_all('SELECT status, COUNT(*) AS c FROM prescriptions GROUP BY status') as $r) {
    $rxStats[$r['status']] = (int)$r['c'];
}
?>
<h1 class="h3 fw-bold mb-4">Pharmacy analytics</h1>

<div class="row g-3 mb-4">
  <?php foreach ([
      ['bi-cash-stack', '#dcfce7;color:#15803d', money($totals['revenue']), 'Paid revenue'],
      ['bi-box-seam', '#dbeafe;color:#1d4ed8', $totals['orders'], 'Total orders'],
      ['bi-people', '#fef3c7;color:#b45309', $totals['customers'], 'Customers'],
      ['bi-capsule', '#e2ecf4;color:#28506e', $totals['products'], 'Active products'],
  ] as [$icon, $style, $num, $label]): ?>
    <div class="col-sm-6 col-xl-3">
      <div class="mo-stat">
        <span class="ic" style="background:<?= $style ?>"><i class="bi <?= $icon ?>"></i></span>
        <span><span class="num d-block"><?= $num ?></span><span class="lbl"><?= $label ?></span></span>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Last 14 days</h2>
      <canvas id="salesChart" height="110"></canvas>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="mo-card p-4 mb-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Prescriptions</h2>
      <?php foreach (['pending', 'approved', 'ready', 'collected', 'rejected'] as $s): ?>
        <div class="d-flex justify-content-between py-1 small">
          <span class="mo-status mo-status-<?= $s ?>"><?= ucfirst($s) ?></span>
          <span class="fw-bold"><?= $rxStats[$s] ?? 0 ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Top sellers</h2>
      <?php if (!$topProducts): ?>
        <div class="text-muted small">No sales yet</div>
      <?php else: foreach ($topProducts as $tp): ?>
        <div class="d-flex justify-content-between py-1 small border-bottom">
          <span class="text-truncate me-2"><?= e($tp['product_name']) ?></span>
          <span class="fw-semibold text-nowrap"><?= (int)$tp['qty'] ?> sold</span>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('salesChart'), {
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [
      {type: 'bar', label: 'Revenue (Rs.)', data: <?= json_encode($revData) ?>, backgroundColor: 'rgba(51,101,138,.72)', borderRadius: 6, yAxisID: 'y'},
      {type: 'line', label: 'Orders', data: <?= json_encode($ordData) ?>, borderColor: '#b7791f', backgroundColor: '#b7791f', tension: .35, yAxisID: 'y1'}
    ]
  },
  options: {
    scales: {
      y: {beginAtZero: true, ticks: {callback: v => 'Rs.' + (v >= 1000 ? (v/1000) + 'k' : v)}},
      y1: {beginAtZero: true, position: 'right', grid: {drawOnChartArea: false}, ticks: {precision: 0}}
    },
    plugins: {legend: {labels: {usePointStyle: true}}}
  }
});
</script>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>
