-- Copies the 71 real products (and their categories) from the old `medi-o`
-- database into the new `medio` database. Safe to re-run: it skips products
-- that already exist by name. Old database is not modified.
USE `medio`;

INSERT IGNORE INTO categories (name)
SELECT DISTINCT TRIM(category) FROM `medi-o`.products
WHERE category IS NOT NULL AND TRIM(category) <> '';

INSERT INTO products (category_id, name, description, price, stock, image_path, rating)
SELECT c.id,
       p.name,
       p.description,
       p.price,
       p.stock,
       SUBSTRING_INDEX(p.image_path, ',', 1),  -- first image of comma-joined list
       COALESCE(p.rating, 0)
FROM `medi-o`.products p
LEFT JOIN categories c ON c.name = TRIM(p.category) COLLATE utf8mb4_unicode_ci
WHERE p.name COLLATE utf8mb4_unicode_ci NOT IN (SELECT name FROM products);
