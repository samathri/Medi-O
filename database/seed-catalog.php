<?php
/**
 * Replaces the whole product catalogue with database/catalog.php and
 * renders a packaging-style image for every product.
 *
 *   php database/seed-catalog.php            (wipe + reseed + images)
 *   php database/seed-catalog.php --sql      (also write database/catalog-seed.sql)
 *
 * Past orders keep their item names/prices (order_items.product_id → NULL).
 */
if (PHP_SAPI !== 'cli') exit('CLI only');
require dirname(__DIR__) . '/config.php';

const IMG_DIR = 'uploads/products/catalog';
const FONT_BOLD = 'C:/Windows/Fonts/arialbd.ttf';
const FONT_REG = 'C:/Windows/Fonts/arial.ttf';

$catalog = require __DIR__ . '/catalog.php';

function slugify(string $s): string
{
    return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $s))), '-');
}

/* ---------- image rendering ---------- */
const S = 2; // supersampling factor
const W = 600;

function hexc($im, string $hex, int $alpha = 0)
{
    [$r, $g, $b] = sscanf(ltrim($hex, '#'), '%02x%02x%02x');
    return imagecolorallocatealpha($im, $r, $g, $b, $alpha);
}
function shade(string $hex, float $f): string
{
    [$r, $g, $b] = sscanf(ltrim($hex, '#'), '%02x%02x%02x');
    $m = fn($c) => (int)max(0, min(255, $f < 0 ? $c * (1 + $f) : $c + (255 - $c) * $f));
    return sprintf('#%02x%02x%02x', $m($r), $m($g), $m($b));
}
function rrect($im, int $x1, int $y1, int $x2, int $y2, int $r, $col): void
{
    $r = min($r, (int)(($x2 - $x1) / 2), (int)(($y2 - $y1) / 2));
    imagefilledrectangle($im, $x1 + $r, $y1, $x2 - $r, $y2, $col);
    imagefilledrectangle($im, $x1, $y1 + $r, $x2, $y2 - $r, $col);
    foreach ([[$x1 + $r, $y1 + $r], [$x2 - $r, $y1 + $r], [$x1 + $r, $y2 - $r], [$x2 - $r, $y2 - $r]] as [$cx, $cy]) {
        imagefilledellipse($im, $cx, $cy, $r * 2, $r * 2, $col);
    }
}
/** Wraps text to fit $maxW, shrinking the font until it fits $maxLines. */
function fit_text(string $text, int $maxW, int $size, int $maxLines, string $font): array
{
    for (; $size > 9 * S; $size -= S) {
        $lines = [''];
        foreach (explode(' ', $text) as $word) {
            $try = trim(end($lines) . ' ' . $word);
            $box = imagettfbbox($size, 0, $font, $try);
            if ($box[2] - $box[0] > $maxW && end($lines) !== '') $lines[] = $word;
            else $lines[count($lines) - 1] = $try;
        }
        $widest = max(array_map(fn($l) => imagettfbbox($size, 0, $font, $l)[2], $lines));
        if (count($lines) <= $maxLines && $widest <= $maxW) return [$lines, $size];
    }
    return [array_slice($lines, 0, $maxLines), $size];
}
function text_block($im, string $text, int $cx, int $top, int $maxW, int $size, int $maxLines, $col, string $font = FONT_BOLD): int
{
    [$lines, $size] = fit_text($text, $maxW, $size, $maxLines, $font);
    $lh = (int)($size * 1.3);
    foreach ($lines as $i => $l) {
        $box = imagettfbbox($size, 0, $font, $l);
        imagettftext($im, $size, 0, (int)($cx - ($box[2] - $box[0]) / 2), $top + $size + $i * $lh, $col, $font, $l);
    }
    return $top + count($lines) * $lh;
}

function render_product(string $file, string $title, string $pack, string $type, bool $rx, string $color, string $catLabel): void
{
    $w = W * S;
    $im = imagecreatetruecolor($w, $w);
    imageantialias($im, true);
    // Background: soft vertical gradient tinted by the product colour
    $top = shade($color, 0.93); $bot = shade($color, 0.82);
    [$r1, $g1, $b1] = sscanf(ltrim($top, '#'), '%02x%02x%02x');
    [$r2, $g2, $b2] = sscanf(ltrim($bot, '#'), '%02x%02x%02x');
    for ($y = 0; $y < $w; $y++) {
        $t = $y / $w;
        imageline($im, 0, $y, $w, $y, imagecolorallocate($im, (int)($r1 + ($r2 - $r1) * $t), (int)($g1 + ($g2 - $g1) * $t), (int)($b1 + ($b2 - $b1) * $t)));
    }
    imagefilledellipse($im, (int)($w * 0.82), (int)($w * 0.18), (int)($w * 0.5), (int)($w * 0.5), hexc($im, '#ffffff', 95));
    // Floor shadow
    imagefilledellipse($im, $w / 2, (int)($w * 0.87), (int)($w * 0.62), (int)($w * 0.07), hexc($im, '#000000', 105));

    $main = hexc($im, $color);
    $dark = hexc($im, shade($color, -0.28));
    $light = hexc($im, shade($color, 0.25));
    $white = hexc($im, '#ffffff');
    $ink = hexc($im, '#1f2d3d');
    $muted = hexc($im, '#5a6b7c');
    $u = $w / 100; // 1% unit

    // Shape + label area per package type: [x1,y1,x2,y2] of white label
    switch ($type) {
        case 'box':
            $x1 = 22 * $u; $x2 = 70 * $u; $y1 = 20 * $u; $y2 = 86 * $u; $d = 9 * $u;
            imagefilledpolygon($im, [$x2, $y1, $x2 + $d, $y1 - $d * 0.6, $x2 + $d, $y2 - $d * 0.6, $x2, $y2], $dark);
            imagefilledpolygon($im, [$x1, $y1, $x1 + $d, $y1 - $d * 0.6, $x2 + $d, $y1 - $d * 0.6, $x2, $y1], $light);
            imagefilledrectangle($im, $x1, $y1, $x2, $y2, $main);
            imagefilledrectangle($im, $x1, $y2 - 9 * $u, $x2, $y2, $dark);
            $label = [$x1 + 3 * $u, $y1 + 12 * $u, $x2 - 3 * $u, $y2 - 13 * $u];
            break;
        case 'bottle':
            $x1 = 31 * $u; $x2 = 69 * $u;
            rrect($im, 36 * $u, 14 * $u, 64 * $u, 27 * $u, 2 * $u, $dark);              // cap
            imagefilledrectangle($im, 39 * $u, 27 * $u, 61 * $u, 31 * $u, $light);      // neck
            rrect($im, $x1, 30 * $u, $x2, 86 * $u, 6 * $u, $main);
            $label = [$x1 + 2 * $u, 40 * $u, $x2 - 2 * $u, 78 * $u];
            break;
        case 'liquid':
            rrect($im, 41 * $u, 8 * $u, 59 * $u, 20 * $u, 2 * $u, $dark);
            imagefilledpolygon($im, [43 * $u, 20 * $u, 57 * $u, 20 * $u, 66 * $u, 33 * $u, 34 * $u, 33 * $u], $main);
            rrect($im, 32 * $u, 30 * $u, 68 * $u, 87 * $u, 5 * $u, $main);
            imagefilledrectangle($im, 35 * $u, 34 * $u, 37 * $u, 82 * $u, $light);
            $label = [34 * $u, 42 * $u, 66 * $u, 79 * $u];
            break;
        case 'tube':
            imagefilledpolygon($im, [30 * $u, 14 * $u, 70 * $u, 14 * $u, 63 * $u, 78 * $u, 37 * $u, 78 * $u], $main);
            imagefilledrectangle($im, 29 * $u, 11 * $u, 71 * $u, 16 * $u, $dark);         // crimp
            for ($i = 30; $i < 71; $i += 3) imageline($im, $i * $u, 11 * $u, $i * $u, 16 * $u, $light);
            rrect($im, 40 * $u, 77 * $u, 60 * $u, 88 * $u, 2 * $u, $dark);              // cap
            $label = [34 * $u, 26 * $u, 66 * $u, 66 * $u];
            break;
        case 'jar':
            rrect($im, 24 * $u, 30 * $u, 76 * $u, 42 * $u, 3 * $u, $dark);              // lid
            rrect($im, 26 * $u, 40 * $u, 74 * $u, 86 * $u, 7 * $u, $main);
            $label = [29 * $u, 48 * $u, 71 * $u, 79 * $u];
            break;
        case 'pump':
            imagefilledrectangle($im, 47 * $u, 10 * $u, 53 * $u, 22 * $u, $dark);
            rrect($im, 40 * $u, 8 * $u, 64 * $u, 13 * $u, 2 * $u, $dark);               // nozzle
            rrect($im, 41 * $u, 20 * $u, 59 * $u, 28 * $u, 2 * $u, $dark);
            rrect($im, 32 * $u, 27 * $u, 68 * $u, 87 * $u, 6 * $u, $main);
            $label = [34 * $u, 38 * $u, 66 * $u, 79 * $u];
            break;
        case 'dropper':
            imagefilledellipse($im, 50 * $u, 20 * $u, 12 * $u, 16 * $u, $ink);          // bulb
            rrect($im, 42 * $u, 25 * $u, 58 * $u, 38 * $u, 2 * $u, $dark);
            rrect($im, 35 * $u, 37 * $u, 65 * $u, 87 * $u, 5 * $u, $main);
            $label = [37 * $u, 45 * $u, 63 * $u, 80 * $u];
            break;
        case 'spray':
            rrect($im, 38 * $u, 9 * $u, 62 * $u, 24 * $u, 4 * $u, $dark);
            imagefilledrectangle($im, 60 * $u, 14 * $u, 67 * $u, 17 * $u, $ink);
            rrect($im, 36 * $u, 22 * $u, 64 * $u, 87 * $u, 3 * $u, $main);
            $label = [38 * $u, 34 * $u, 62 * $u, 80 * $u];
            break;
        case 'sachet':
            $x1 = 22 * $u; $x2 = 78 * $u; $y1 = 18 * $u; $y2 = 84 * $u;
            imagefilledrectangle($im, $x1, $y1, $x2, $y2, $main);
            $teeth = [];
            for ($x = $x1; $x <= $x2; $x += 2 * $u) { $teeth[] = $x; $teeth[] = $y1 - (($x - $x1) / (2 * $u) % 2) * $u; }
            $teeth[] = $x2; $teeth[] = $y1 + $u; $teeth[] = $x1; $teeth[] = $y1 + $u;
            imagefilledpolygon($im, $teeth, $main);
            imagefilledrectangle($im, $x1, $y1, $x2, $y1 + 5 * $u, $dark);
            imagefilledrectangle($im, $x1, $y2 - 5 * $u, $x2, $y2, $dark);
            $label = [$x1 + 4 * $u, $y1 + 12 * $u, $x2 - 4 * $u, $y2 - 12 * $u];
            break;
        default: // device
            rrect($im, 20 * $u, 22 * $u, 80 * $u, 86 * $u, 8 * $u, hexc($im, '#e9edf2'));
            rrect($im, 20 * $u, 22 * $u, 80 * $u, 32 * $u, 4 * $u, $main);
            imagefilledrectangle($im, 20 * $u, 28 * $u, 80 * $u, 32 * $u, $main);
            rrect($im, 30 * $u, 72 * $u, 70 * $u, 80 * $u, 4 * $u, $main);              // button bar
            $label = [24 * $u, 36 * $u, 76 * $u, 69 * $u];
            break;
    }

    // White label with product text
    [$lx1, $ly1, $lx2, $ly2] = array_map('intval', $label);
    rrect($im, $lx1, $ly1, $lx2, $ly2, 2 * $u, $white);
    imagefilledrectangle($im, $lx1, $ly1, $lx2, $ly1 + (int)(1.6 * $u), $main);
    $cx = (int)(($lx1 + $lx2) / 2);
    $innerW = $lx2 - $lx1 - 4 * $u;
    // Measure title + pack, then centre the block vertically inside the label
    [$tLines, $tSize] = fit_text($title, (int)$innerW, 22 * S, 4, FONT_BOLD);
    [, $pSize] = fit_text($pack, (int)$innerW, 15 * S, 1, FONT_REG);
    $blockH = count($tLines) * (int)($tSize * 1.3) + 2 * $u + (int)($pSize * 1.3);
    $start = (int)($ly1 + 1.6 * $u + max(2 * $u, ($ly2 - $ly1 - 1.6 * $u - $blockH) / 2));
    $y = text_block($im, $title, $cx, $start, (int)$innerW, 22 * S, 4, $ink);
    text_block($im, $pack, $cx, (int)($y + 2 * $u), (int)$innerW, 15 * S, 1, $muted, FONT_REG);

    // Badges
    if ($rx) {
        rrect($im, 6 * $u, 6 * $u, 20 * $u, 14 * $u, 3 * $u, hexc($im, '#b42318'));
        text_block($im, 'Rx', (int)(13 * $u), (int)(6.6 * $u), (int)(12 * $u), 18 * S, 1, $white);
    }
    text_block($im, 'MEDI-O · ' . strtoupper($catLabel), $w / 2, (int)(92 * $u), (int)(90 * $u), 11 * S, 1, $muted, FONT_BOLD);

    $out = imagecreatetruecolor(W, W);
    imagecopyresampled($out, $im, 0, 0, 0, 0, W, W, $w, $w);
    imagewebp($out, $file, 82);
    imagedestroy($im); imagedestroy($out);
}

/* ---------- seed ---------- */
$palette = ['#33658a', '#2f7d6d', '#b7791f', '#8b5a9f', '#c0504d', '#3b7dd8', '#5b8c3a',
            '#d1673f', '#1f8a9c', '#a8456b', '#6b6f9c', '#9a7b2f', '#3e8e7e', '#7a5c3e'];

$dir = ROOT_PATH . '/' . IMG_DIR;
if (!is_dir($dir)) mkdir($dir, 0775, true);
foreach (glob($dir . '/*') as $old) unlink($old);

$pdo = db();
$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
$pdo->exec('DELETE FROM cart_items');
$pdo->exec('UPDATE order_items SET product_id = NULL');
$pdo->exec('UPDATE prescription_items SET product_id = NULL');
$pdo->exec('DELETE FROM products');
$pdo->exec('DELETE FROM categories');
$pdo->exec('ALTER TABLE products AUTO_INCREMENT = 1');
$pdo->exec('ALTER TABLE categories AUTO_INCREMENT = 1');
$pdo->exec('SET FOREIGN_KEY_CHECKS=1');

$sqlCats = []; $sqlProducts = [];
$total = 0; $slugs = [];
foreach ($catalog as $catName => [$icon, $items]) {
    q('INSERT INTO categories (name, icon) VALUES (?, ?)', [$catName, $icon]);
    $catId = (int)$pdo->lastInsertId();
    $sqlCats[] = sprintf("(%s, %s)", $pdo->quote($catName), $pdo->quote($icon));
    $shortCat = explode(' ', str_replace([',', '&'], '', $catName))[0];
    $n = 0;
    foreach ($items as [$base, $desc, $type, $rx, $variants]) {
        $color = $palette[abs(crc32($base)) % count($palette)];
        foreach ($variants as [$pack, $price]) {
            $name = $base . ' — ' . $pack;
            $slug = slugify($name);
            while (isset($slugs[$slug])) $slug .= '-x';
            $slugs[$slug] = true;
            $img = IMG_DIR . '/' . $slug . '.webp';
            render_product(ROOT_PATH . '/' . $img, $base, $pack, $type, (bool)$rx, $color, $shortCat);
            $h = abs(crc32($name));
            $stock = $rx ? 20 + $h % 41 : 25 + $h % 126;
            $rating = number_format(3.9 + ($h % 11) / 10, 2);
            $fullDesc = $desc . ($rx ? ' PRESCRIPTION REQUIRED — upload a valid prescription at checkout.' : '');
            q('INSERT INTO products (category_id, name, description, price, stock, image_path, rating, requires_prescription, is_active)
               VALUES (?,?,?,?,?,?,?,?,1)', [$catId, $name, $fullDesc, $price, $stock, $img, $rating, $rx]);
            $sqlProducts[] = sprintf("((SELECT id FROM categories WHERE name=%s), %s, %s, %.2f, %d, %s, %s, %d)",
                $pdo->quote($catName), $pdo->quote($name), $pdo->quote($fullDesc), $price, $stock, $pdo->quote($img), $rating, $rx);
            $n++; $total++;
        }
    }
    printf("%-26s %3d products\n", $catName, $n);
}
echo "TOTAL: $total products, " . count($catalog) . " categories\n";

if (in_array('--sql', $argv, true)) {
    $sql = "INSERT INTO categories (name, icon) VALUES\n" . implode(",\n", $sqlCats) . ";\n\n"
         . "-- Product catalogue (generated by database/seed-catalog.php from database/catalog.php)\n"
         . "INSERT INTO products (category_id, name, description, price, stock, image_path, rating, requires_prescription) VALUES\n"
         . implode(",\n", $sqlProducts) . ";\n";
    file_put_contents(__DIR__ . '/catalog-seed.sql', $sql);
    echo "Wrote database/catalog-seed.sql\n";
}
