-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 07:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medi-o`
--

-- --------------------------------------------------------

--
-- Table structure for table `best_selling_items`
--

CREATE TABLE `best_selling_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_paths` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `best_selling_items`
--

INSERT INTO `best_selling_items` (`id`, `item_name`, `image_path`, `price`, `rating`, `created_at`, `updated_at`, `image_paths`) VALUES
(9, 'Panadol', '', 502.56, 5.0, '2025-08-03 03:18:06', '2025-08-03 03:18:06', 'uploads/images/688ed4eec1bd5-panadol.jpeg'),
(10, 'Vitamin-C 1 Tablet', '', 30.00, 5.0, '2025-08-03 03:20:43', '2025-08-03 03:20:43', 'uploads/images/688ed58bb1b01-vitamin-c.jpg'),
(11, 'Rapadene', '', 122.00, 5.0, '2025-08-03 03:21:04', '2025-08-03 03:21:04', 'uploads/images/688ed5a07fbb9-rapadene.png'),
(12, 'Gripe Water Mix', '', 250.00, 5.0, '2025-08-03 03:21:34', '2025-08-03 03:21:34', 'uploads/images/688ed5bebad50-gripe-water.jpg'),
(13, 'Brufen 400mg 20 tablets', '', 1200.00, 5.0, '2025-08-03 03:23:18', '2025-08-03 03:23:18', 'uploads/images/688ed62677d95-brufen-400mg-20-tablets.jpg'),
(14, 'Neolorodin', '', 1500.00, 5.0, '2025-08-03 03:24:55', '2025-08-03 03:24:55', 'uploads/images/688ed68709e95-neo-loradin.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(7, 4, 88, 2, '2025-08-03 04:04:00', '2025-08-03 04:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Samathri Abhayapala', 'samathri.15@gmail.com', NULL, 'Hello', '2025-08-03 04:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`) VALUES
(1, 4, '2025-08-03 09:58:12', 300.00, 'Pending'),
(2, 4, '2025-08-03 10:00:14', 300.00, 'Pending'),
(3, 4, '2025-08-03 10:01:55', 300.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 88, 1, 300.00),
(2, 2, 88, 1, 300.00),
(3, 3, 88, 1, 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions_2`
--

CREATE TABLE `prescriptions_2` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_ready` tinyint(1) NOT NULL DEFAULT 0,
  `is_picked_up` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions_2`
--

INSERT INTO `prescriptions_2` (`id`, `user_id`, `file_path`, `created_at`, `is_ready`, `is_picked_up`) VALUES
(1, 5, 'uploads/prescriptions/688ea4e5a6323-About-hero.jpg', '2025-08-02 23:53:09', 0, 0),
(4, 4, 'uploads/prescriptions/688ec91645f31-Blue and White Flat Illustrative Health Products Logo.png', '2025-08-03 02:27:34', 0, 0),
(5, 4, 'uploads/prescriptions/688ed022054b1-Eventone-C-Cream.jpg', '2025-08-03 02:57:38', 0, 0),
(6, 4, 'uploads/prescriptions/688ed09e5327c-Eventone-C-Cream.jpg', '2025-08-03 02:59:42', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `image_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` decimal(3,2) DEFAULT NULL COMMENT 'Average product rating (0.00 to 5.00)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category`, `image_path`, `created_at`, `rating`) VALUES
(19, '3M™ LITTMANN® STETHOSCOPE SPARE PARTS KITS, 40016 CLASSIC III™, CARDIOLOGY IV™ & CORE, BLACK', 'Quality and reliability in one convienient package\r\nContains one tunable single piece diaphragm for the adult side of the stethoscope chest piece, Black\r\nContains one tunable single piece diaphragm for the pediatric side of the stethoscope chest piece, Black\r\nContains one non-chill bell sleeve, Black', 11390.00, 1, 'Other Accessories', 'uploads/40005-600x450.jpg', '2025-08-02 17:56:38', 3.39),
(20, '3M™ LITTMANN® STETHOSCOPE SPARE PARTS KIT, CLASSIC II S.E., GRAY, 40006', 'Quality and reliability in one convienient package\r\nContains Small (1/2 inch) non chill bell sleeve, Gray\r\nContains one tunable diaphragm and rim assembly for the stethoscope chest piece, Gray', 16690.00, 1, 'Other Accessories', 'uploads/40006-600x450.jpg', '2025-08-02 18:10:36', 3.99),
(21, '3M™ LITTMANN® STETHOSCOPE SPARE PARTS KIT, CLASSIC II S.E., BLACK, 40005', 'Quality and reliability in one convenient package\r\nContains Small (1/2 inch) non-chill bell sleeve, Black\r\nContains one tunable diaphragm and rim assembly for the stethoscope chest piece, Black', 16390.00, 10, 'Other Accessories', 'uploads/40012-600x450.jpg', '2025-08-02 18:11:38', 4.77),
(22, '3M™ LITTMANN® STETHOSCOPE SPARE PARTS KIT, CLASSIC II PEDIATRIC DIAPHRAGM ASSEMBLY, 40012', 'A dual-sided chest piece with a small 3.3cm diaphragm is ideal for pediatric patients.\r\nContains one floating diaphragm for the stethoscope chest piece\r\nContains a black rim for assembly of the diaphragm to the chest piece\r\nContains a Gray rim for assembly of the diaphragm to the chest piece', 9190.00, 1, 'Other Accessories', 'uploads/40016-600x450.jpg', '2025-08-02 18:30:24', 4.86),
(23, 'WELLWOMAN SPORT', '(Per pack)\r\n\r\nPremium nutritional support for women actively involved in fitness, sports, and gym. Includes 28 nutrients that support performance, including iron which contributes to the reduction of tiredness and fatigue. From Wellwoman, the UK’s number 1 women’s supplement brand.\r\n\r\nEnergy Levels – contains vitamin B6 and B12 which contribute to normal energy release and red blood cell formation.\r\nAll-round Health & Vitality – Wellwoman sport also doubles up as a comprehensive multivitamin to support general health & vitality.\r\nTested by LGC, a world-class anti-doping laboratory.', 5000.00, 2, 'Women\'s Personal Care', 'uploads/Wellwoman-Sport-600x600.jpg', '2025-08-02 18:32:31', 3.01),
(24, 'WELLWOMAN 70+', '(Per card of 15 capsules)\r\n\r\nA carefully formulated supplement for women 70 and over, scientifically developed to support overall health & vitality, along with areas of health particularly relevant to those in the later stages of life, such as heart, brain, vision, immune, and energy. From the UK’s No.1 women’s supplement brand.\r\n\r\nImmune Support – contains vitamin C and D which contribute to the normal function of the immune system\r\nCognitive Function – with iron, iodine, and zinc which contribute to normal cognitive function\r\nHeart & Circulation – includes vitamin B1 which contributes to the normal function of the heart\r\nVision – vitamins C and E plus mineral zinc contributes to the maintenance of normal vision\r\nEnergy levels – with thiamin, copper, and iron which contribute to normal energy release', 4859.00, 3, 'Women\'s Personal Care', 'uploads/Wellwoman-70-600x600.jpg', '2025-08-02 18:33:04', 3.47),
(25, 'WELLWOMAN 50+', '(Per pack)\r\n\r\nOur comprehensive vitamin & mineral formula for women over 50, is specially developed to help maintain overall vitality and areas of health particularly relevant to those in middle age, such as heart, brain, energy, and immune. From the UK’s No.1 women’s supplement brand.\r\n\r\nBrain – with pantothenic acid which contributes to normal mental performance.\r\nEnergy levels – with thiamin, copper, and iron which contribute to normal energy release.\r\nHeart & Circulation – includes vitamin B1 which helps maintain normal heart function.\r\nImmune Support – contains zinc, selenium, and vitamin D which contribute to normal immune system function.', 4759.00, 4, 'Women\'s Personal Care', 'uploads/Wellwoman-50-600x600.jpg', '2025-08-02 18:34:46', 3.32),
(26, 'WELLTEEN HER', '(Per pack)\r\nWhen nutrient requirements are changing fast, Wellteen Her safeguards the nutrition that helps young women take the world on…\r\n\r\nExtra dietary help for sports, studies and busy social lives.\r\nWith Iron which contributes to normal formation of red blood cells & hemoglobin.\r\nHelps maintain vitality and wellness from 13 to 19 years.\r\nFrom the UK’s No.1 vitamin company.', 6248.00, 2, 'Women\'s Personal Care', 'uploads/Wellteen-Her-600x600.jpg', '2025-08-02 18:35:22', 3.18),
(27, 'SUNCOTE GEL 100g', 'Suncote gel is a water-based gel with hydro technology that protects the skin against UVA and UVB rays by providing a chemical barrier to sunlight. It is non-comedogenic with a high SPF value of 30.', 5117.50, 10, 'Sun Protection', 'uploads/Suncote-600x611.jpg', '2025-08-02 18:36:12', 4.94),
(28, 'SOLARIS SC SPF40 TRANSPARENT SUN SCREEN GEL 45ml', 'Transparent Sun Screen Gel for oily/combination skin types.\r\n\r\nNon Whitening.\r\n\r\nNon Tanning.\r\n\r\nNon Greasy.\r\n\r\nNon Comedogenic.\r\n\r\nParaben Free.\r\n\r\nWater Resistant.\r\n\r\nHypoallergenic.\r\n\r\n(Sebum Control Treatment)', 5400.00, 3, 'Sun Protection', 'uploads/SOLARISsc-GEL-600x600.jpeg', '2025-08-02 18:38:01', 4.18),
(29, 'SCAR-FADE UV GEL With SPF 30', 'Most people who use lighteners do so to treat skin problems such as age spots, acne scars, or discoloration related to hormones.', 12600.00, 1, 'Sun Protection', 'uploads/Scar-Fade-600x600.jpg', '2025-08-02 18:40:30', 3.09),
(30, 'Rowill Sunscreen Gel SPF 50 60ml', '50 SPF Sunscreen gel, Non-Comedogenic, Oil Free. Suitable for all skin Types. For Face & Body.', 3100.00, 10, 'Sun Protection', 'uploads/Rowill-Sunscreen-Gel-SPF-50-60ml-600x600.jpg', '2025-08-02 18:41:06', 3.89),
(31, 'PERFECTIL HAIR EXTRA SUPPORT 60’s', 'With Perfectil Hair’s advanced nutrition, get the comprehensive support of Perfectil while giving your hair the special attention it deserves.\r\n\r\n28 micronutrients, optimized to give your hair extra support\r\nWith extra biotin & selenium which contribute to normal hair\r\nWith copper which contributes to normal hair pigmentation\r\nThe UK’s No.1 beauty supplement brand', 9800.00, 2, 'Skin, Hair & Nails', 'uploads/Perfectil-Hair-600x600.jpg', '2025-08-02 18:41:56', 3.20),
(32, 'NEOCELL® SUPER COLLAGEN+ C WITH BIOTIN – 90 TABLETS', 'Collagen type 1 & 3\r\n\r\nVitamin C\r\n\r\nBiotin\r\nAids in collagen formation for healthy looking hair , skin & nails.', 7500.00, 10, 'Skin, Hair & Nails', 'uploads/NEOCELL-SUPER-COLLAGEN.jpg', '2025-08-02 18:43:51', 3.31),
(33, 'NEOCELL MARINE COLLAGEN 120 CAPSULES', 'NeoCell Marine Collagen offers a premium blend of Collagen Types 1 & 3 combined with Hyaluronic Acid, designed to promote strong, healthy, and hydrated skin. This supplement is enriched with a vita-mineral blend that works synergistically to support healthy collagen formation, contributing to youthful and radiant skin. Certified Paleo-Friendly and Gluten-Free, NeoCell Marine Collagen is free from soy, lactose, and artificial flavors, and contains only non-GMO ingredients. Incorporate 4 capsules daily into your routine to experience the beauty and wellness benefits from within.', 10200.00, 10, 'Skin, Hair & Nails', 'uploads/Neocell-marine-collagen-600x600.png', '2025-08-02 18:44:23', 3.97),
(34, 'NATURE’S WAY BEAUTY HAIR,SCALP,SKIN & NAILS 60 Capsules', 'Nature’s Way Hair, Scalp, Skin & Nails is an innovative 4-in-1 beauty complex that deeply nourishes and supports luscious looking hair, healthy glowing skin and strong nails with the added benefit of supporting a healthy scalp.*', 10900.00, 10, 'Skin, Hair & Nails', 'uploads/Natures-way-beauty-hair-skin-nails.webp', '2025-08-02 18:44:55', 4.92),
(35, 'CETAPHIL MOISTURISING CREAM 80g', 'Moisturizing Cream For Face & Body.\r\n\r\nAll Skin Types.\r\n\r\nNon-comedogenic.\r\n\r\nDermatologically Tested.', 3571.00, 10, 'Skin Care', 'uploads/CETAPHIL-MOISTURISING-CREAM-80g-3.jpg', '2025-08-02 18:45:49', 3.68),
(36, 'CETAPHIL GENTLE SKIN CLEANSER', 'Gentle Skin Cleanser For Face & Body.\r\n\r\nAll Skin Types\r\n\r\npH Balanced.\r\n\r\nFragrance-Free.\r\n\r\nNon-Comedogenic.\r\n\r\nDermatologically Tested.', 3040.00, 5, 'Skin Care', 'uploads/CETAPHIL-GENTLE-SKIN-CLEANSER-2.jpg', '2025-08-02 18:46:50', 4.62),
(37, 'CETAPHIL DAM ULTRA HYDRATING LOTION 100g', 'Daily Advance Ultra Hydrating Lotion For Face & Body.\r\nAll Skin Types.\r\nNon-Comedogenic.\r\nNon-Irritation.\r\nFragrance-free.\r\nDermatologically Tested.', 4767.00, 1, 'Skin Care', 'uploads/CETAPHIL-DAM-ULTRA-HYDRATING-LOTION-100g-1.jpg', '2025-08-02 18:47:28', 3.09),
(38, 'CETAPHIL CENTLE FOAMING CLEANSER 236ML', 'For dry to normal, sensitive skin\r\nA gentle daily cleanser that rinses away dirt, oil and makeup without stripping skin’s natural moisture balance.\r\nFormulated with glycerin, pro-vitamin B5 and essential vitamin E to soften as it cleans\r\nGently cleanses without stripping skin of its natural moisture balance\r\nDermatologist tested to be gentle on sensitive skin', 9168.00, 2, 'Skin Care', 'uploads/Cetaphil-GFC-236ml-1-600x600.png', '2025-08-02 18:48:41', 4.59),
(39, 'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTNESS REVEAL CREAMY CLEANSER 100g', '', 7316.00, 2, 'Skin Care', 'uploads/CETAPHIL-BRIGHT-HEALTHY-RADIANCE-BRIGHTNESS-REVEAL-CREAMY-CLEANSER-100g-1.jpg', '2025-08-02 18:49:21', 4.66),
(40, 'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTNESS REFRESH TONER 150ml', 'Instant luminosity and hydration for a soft and smooth skin', 8055.00, 1, 'Skin Care', 'uploads/Cetaphil-toner-600x600.webp', '2025-08-02 18:50:00', 4.56),
(41, 'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTENING NIGHT COMFORT CREAM 50g', '', 9897.00, 1, 'Skin Care', 'uploads/CETAPHIL-BRIGHT-HEALTHY-RADIANCE-BRIGHTENING-NIGHT-COMFORT-CREAM-50g-1.jpg', '2025-08-02 18:50:44', 3.80),
(42, 'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTENING LOTION 245ml', '', 11759.00, 1, 'Skin Care', 'uploads/CETAPHIL-BRIGHT-HEALTHY-RADIANCE-BRIGHTENING-LOTION-245ml-1.jpg', '2025-08-02 18:51:15', 4.31),
(43, 'STAMINA PLUS -3 Dotted Condoms', 'Stamina Plus for longer lasting pleasure condom in a natural rubber latex, 4.5% benzocaine lubricated male dotted condom with reservoir end that helps delay the climax and prolong sexual excitement for longer-lasting lovemaking. Benzocaine also helps prevent premature ejaculation and provides greater staying power.', 300.00, 9, 'Sexual Wellness', 'uploads/stamina-plus-600x623.jpg', '2025-08-02 18:51:44', 3.18),
(44, 'ROUGH RIDER CONDOMS', '3 Individually Tested Latex Condoms', 250.00, 12, 'Sexual Wellness', 'uploads/ROUGH-RIDER-600x600.jpg', '2025-08-02 18:52:11', 3.94),
(45, 'PREETHI SUPER', 'Studded condoms made with natural latex rubber, with over 100 raised rubber “studs” in all the right places to intensify friction and sensitivity. There is more stimulation and satisfaction for both parties.', 120.00, 1, 'Sexual Wellness', 'uploads/7f889e4f7dc2652333ffb8ff855a6c4b-1-600x600.png', '2025-08-02 18:54:04', 3.18),
(46, 'NATURES AID ACTIVE MAN ADVANCED FORMULA (60 TABLETS)', 'An advanced formulation to help aid sexual wellness.\r\nContains\r\nArginine\r\nDamiana\r\nKorean Ginseng\r\nMaca', 7800.00, 1, 'Sexual Wellness', 'uploads/Active-Man-Natures-Aid-600x600.jpg', '2025-08-02 18:54:47', 3.06),
(47, 'MOODS CONDOMS-DOTTED (3’s)', '3 Condoms for new dimensions of pleasure', 308.00, 10, 'Sexual Wellness', 'uploads/moods-dotted-3-600x600.jpg', '2025-08-02 18:55:30', 4.76),
(48, 'SENSODYNE WHITENING 70g', 'Dentists recommend Sensodyne Toothpaste for sensitivity relief\r\nSensodyne Whitening toothpaste helps in relieving tooth sensitivity while restoring natural whiteness of the teeth\r\nIt has a formula that delivers sensitive teeth whitening by polishing away the surface stains, while working deep inside the teeth to build soothing protection around the nerves\r\nBrushing with this toothpaste leaves you with a pleasant taste, fresh breath and a whiter smile\r\nThis toothpaste also contains fluoride that strengthens teeth and prevents tooth decay\r\nProven to provide all the benefits of a regular toothpaste', 900.00, 1, 'Oral Care', 'uploads/41tvUeOhFcL._SX522_.jpg', '2025-08-02 18:56:08', 3.63),
(49, 'REXIDIN MOUTH WASH 100ml', 'This medication is a chemical antiseptic, prescribed for gingivitis, cleansing skin and wound areas. It decreases the amount of bacteria in the mouth.', 594.00, 2, 'Oral Care', 'uploads/Rexidine-Mouth-Wash-600x600.jpg', '2025-08-02 18:56:35', 4.86),
(50, 'ORREPASTE MOUTH ULCER GEL TREATMENT 5g', '', 470.34, 2, 'Oral Care', 'uploads/s-l500.jpg', '2025-08-02 18:57:14', 4.43),
(51, 'Oraleez Gel 10g', '', 370.00, 1, 'Oral Care', 'uploads/e23bf5a4abed09937c4765286a66e15b.png', '2025-08-02 18:57:41', 4.57),
(52, 'HEXIDINE® MOUTHWASH 160ml', 'Chlorhexidine Mouthwash B.P', 857.00, 10, 'Oral Care', 'uploads/Hexidine-600x800.jpg', '2025-08-02 18:58:37', 4.56),
(53, 'CORSODYL MINT MOUTHWASH 200ml', 'Treats gum problems\r\nBleeding gums\r\nIrritated gums\r\nMouth ulcers', 625.00, 1, 'Oral Care', 'uploads/corsodyl-mint-mouthwash-200ml-600x600.jpg', '2025-08-02 18:59:20', 4.09),
(54, 'BIOREPAIR PLUS SENSITIVE TEETH – 75ml', 'Forms a natural barrier against hypersensitivity on the tooth\r\nGives a quick relief from the pain caused by a high dentinal sensitivity\r\nCloses the dentinal tubules reducing the sensitivity from the first application', 2260.00, 1, 'Oral Care', 'uploads/Biorepair_Plus_Denti_Sensibili_astuccio_-1-600x257.png', '2025-08-02 19:00:00', 3.75),
(55, 'BIOREPAIR PLUS PROTEZIONE TOTALE 75 ml TOOTHPASTE', 'Total protection toothpaste, every day protects the natural health of the teeth from the erosion of acids and bacteria that cause plaque, tartar and caries. Without fluoride', 2260.00, 1, 'Oral Care', 'uploads/Biorepair_Plus_ProtezioneTotale_astuccio-600x257.png', '2025-08-02 19:00:34', 3.50),
(56, 'NATUROPATHICA COLLAGENIX BEAUTY SHOT 10,000MG (10 x 50ml)', 'Discover the secret to beautiful skin with Collagenix Beauty Shot 10,000mg – ultimate strength collagen in a delicious peach drink.\r\nCollagenix Beauty Shot 10000mg Can Help:\r\n-Boost and re-build collagen\r\n-Keep skin firm, plump and hydrated\r\n-Support collagen repair from within\r\n-Increase skin moisture and elasticity\r\n-Reduce wrinklesdone Beautiful hair and stronger nails', 17900.00, 10, 'Nail Care', 'uploads/Collagenix-beauty-shot.webp', '2025-08-02 19:01:05', 3.25),
(57, 'WELLTEEN HIM', '(Per pack)\r\nFrom studies to sports, Wellteen Him provides a broad range of important vitamins and minerals for increasingly hectic lifestyles.\r\nExclusively formulated for men aged 13 to 19.\r\n24 nutrients to help maintain vitality and wellness.\r\nIncludes Vitamins B6 and B12 which contribute to the reduction of tiredness and fatigue.\r\nFrom the UK’s No.1 vitamin company.', 6248.00, 2, 'Men\'s Personal Care', 'uploads/Wellteen-Him-600x600.jpg', '2025-08-02 19:01:44', 4.75),
(58, 'WELLMAN SPORT', '(Per pack)\r\nA specialist supplement developed for men involved in sports, fitness, and gym. Includes 27 nutrients that support performance, including iron which contributes to the reduction of tiredness and fatigue. Wellman is also the UK’s No.1 men’s supplement brand.\r\nEnergy Levels – Contains vitamin B6 and B12 which contribute to normal energy release and red blood cell formation.\r\nAll Round Health & Vitality – Wellman sport doubles up as a comprehensive multivitamin to support general health & vitality.\r\nLGC Tested – Tested by LGC, a world-class anti-doping laboratory.', 4500.00, 10, 'Men\'s Personal Care', 'uploads/Wellwoman-Sport-600x600.jpg', '2025-08-02 19:02:16', 3.02),
(59, 'WELLMAN ORIGINAL', '(Per pack)\r\nOur flagship multivitamin for men with 29 nutrients, specially developed to help maintain overall health and vitality. Wellman is also the UK’s No.1 men’s supplement brand.\r\nEnergy levels – with vitamins B6 and B12 which contribute to normal energy release.\r\nImmune system – with vitamins C and D which contribute to normal immune system function.', 5775.00, 7, 'Men\'s Personal Care', 'uploads/Wellman-Original-2-1-600x600.jpg', '2025-08-02 19:02:52', 3.83),
(60, 'WELLMAN 70+', '(Per card of 15 capsules)\r\nA carefully formulated supplement for men 70 and over, specially developed to support areas of health particularly relevant to men in the later stages of life, such as; heart, brain, energy, vision, immune, and general health & vitality. From Wellman, the UK’s number 1 men’s supplement brand.\r\nBrain Function – With iron, iodine, and zinc contribute to normal cognitive function.\r\nHeart & Circulation – Includes vitamin B1 which contributes to the normal function of the heart.\r\nImmune Support – Contains vitamins C and D which contribute to the normal function of the immune system.\r\nVision – Contains vitamins C, E, and zinc which contribute to the maintenance of normal vision.', 4884.00, 1, 'Men\'s Personal Care', 'uploads/Wellman-70-600x600.jpg', '2025-08-02 19:03:25', 3.07),
(61, 'CERAVE THERAPEUTIC HAND CREAM 85g', 'CeraVe Therapeutic Hand Cream is a fast-absorbing, non-greasy formula that helps prevent and temporarily protect chafed, chapped, or cracked skin and contains three essential ceramides and hyaluronic acid to deliver intense moisture and help restore the protective skin barrier.\r\nMade For: Normal to Dry Skin\r\nHelps With: Dry Skin', 6250.00, 4, 'Hand & Foot Care', 'uploads/cerave-hand-cream.png', '2025-08-02 19:03:59', 4.89),
(62, 'Triticum Biotin Plus Advanced Hair Serum 50ml', '\"✓ Infused with natural oils: Rosemary, Castor, Jojoba, Peppermint, Eucalyptus, Coconut & Aloe Extract.\r\n✓ Deeply nourishes and hydrates the scalp.\r\n✓ Revitalizes hair follicles for healthy growth.\r\n✓ Enhanced with Ginger, Lavender & Biotin for hair vitality.\r\n✓ Fortified with essential Vitamins & Antioxidants.\r\n✓ Strengthens hair from root to tip.\r\n✓ Restores natural thickness, shine & softness.\"', 6300.00, 2, 'Hair Care', 'uploads/T_01-600x600.webp', '2025-08-02 19:04:27', 4.24),
(63, 'SATINY 30ml', 'SATINY is a proven product that combines traditional wisdom and modern technology. Developed through research with the University of Colombo, this unique hair stimulator has nourishing ingredients. Its pleasant fragrance, inspired by global aroma therapy, promotes safer and healthier hair growth, improving overall quality of life.', 3900.00, 3, 'Hair Care', 'uploads/Satiny-20ml-600x600.jpg', '2025-08-02 19:05:35', 3.50),
(64, 'Rowill Energizing Shampoo 150ml', 'To maintain healthy Hair and Scalp. Reduce Hair Thinning, For Weeks and Falling Hair, and Improves Hair texture and thickness.', 2594.00, 10, 'Hair Care', 'uploads/Rowill-Energizing-Shampoo-150ml-600x600.jpg', '2025-08-02 19:06:07', 3.81),
(65, 'REDWIN COAL TAR FRAGRANCE SHAMPOO 250ml', 'Dry Scalp Management\r\nFor Healthy Hair and scalp\r\nRemoves Flacking Scalp\r\nRestores and maintains healthy clean hair and scalp\r\nSoothes and moisturises an itchy/dry scalp\r\npH balanced\r\nExtra Soothing, contains Aloe Vera', 5930.00, 10, 'Hair Care', 'uploads/redwin-coal-tar-shampoo-600x600.webp', '2025-08-02 19:06:36', 3.52),
(66, 'PILOPEPTAN® WOMAN PROTEOKEL', 'Pilopeptan® Woman Proteokel\r\nRegenerative Anti-Hair Loss Ampoules\r\nPilopeptan® Woman Proteokel is a revolutionary topical treatment designed to effectively combat hair loss and regenerate hair follicle health.\r\nIts new mechanism of action, with patented technology based on proteoglycans, makes it an innovative option.\r\nIts ingredients also include Inositol and Caffeine, known for stimulating hair growth, and red apple extract, which acts as a powerful antioxidant, promoting stronger and healthier hair and preventing hair aging.\r\nIts convenient ampoule format to stimulate hair growth contains a non-greasy formula that is easily absorbed, leaving no residue on the scalp and without the need to rinse.\r\nIn addition to being the perfect complement to your hair anti-aging routine, this innovative hair regeneration treatment is especially suitable for people with sensitive scalps and is safe for use during pregnancy and breastfeeding.', 19890.00, 1, 'Hair Care', 'uploads/PROTEOKEL-600x600.jpg', '2025-08-02 19:07:10', 3.19),
(67, 'PHOTONIC TAR SCALP TREATMENT 120ml', 'Fights Itching, scaling, and redness\r\nTreats Psoriasis, Dandruff, and Seborrheic dermatitis\r\nHelps treat Psoriasis symptoms recurrence\r\nSlows down production and controls the buildup of new scales on the scalp', 4950.00, 1, 'Hair Care', 'uploads/Photonic-Tar-Shampoo-100ml-600x600.jpg', '2025-08-02 19:07:43', 4.41),
(68, 'PHOTONIC MAX SHAMPOO 120ml', 'Hair fall prevention energizing shampoo\r\nSmooth & Silky long Hair\r\nMaximum conditioning effect\r\nMoisturizer for daily use', 4950.00, 1, 'Hair Care', 'uploads/Photonic-Max-1-600x600.jpg', '2025-08-02 19:08:17', 3.45),
(69, 'PHARMACERIS SPECIALIST ANTI-DANDRUFF SHAMPOO 250ml H-PURIN SPECIAL', 'Detail\r\nHigh tolerance and efficacy\r\nClinically and dermatologically tested\r\nDry and Oily Dandruff', 5750.00, 10, 'Hair Care', 'uploads/H-Purin-special.jpg', '2025-08-02 19:09:17', 3.04),
(70, 'PHARMACERIS SOOTHING AND  MOISTURIZING MICELLAR SHAMPOO 250ML H-SENSITONIN', 'Detail\r\n86% soothes irritations\r\n79% relieves scalp itching\r\nhigh tolerance and efficacy\r\ndermatologically tested\r\nINDICATIONS\r\nThe shampoo is recommended for daily care of sensitive scalp prone to irritation and fine and delicate hair.', 5950.00, 10, 'Hair Care', 'uploads/H-Sensitonin-600x800.jpg', '2025-08-02 19:09:47', 3.82),
(71, 'PHARMACERIS PROFESSIONAL DOUBLE ACTION SHAMPOO  250ml H-STIMUCLARIS', 'Professional Double action Shampoo\r\nHair Growth Stimulating\r\nAnti-Dandruff\r\nHigh Tolerance and efficacy\r\nDermatology Tested\r\nAdvanced formula designed for Very Sensitive skin\r\nEfficacy confirmed by tests on scalps with a tendency for dandruff and hair loss\r\nContains:\r\nnatural Growth factor FGF\r\nCaffeine\r\nPiroctone Olamine\r\nCitric Acid\r\nD-Panthenol', 6100.00, 10, 'Hair Care', 'uploads/photo_2021-11-04_09-35-51-600x800.jpg', '2025-08-02 19:10:26', 3.00),
(72, 'PHARMACERIS INTENSIVE HAIR GROWTH STIMULATING SPRAY 125ml H-STIMUFORTEN', 'For weak & falling hair\r\nStops periodic and premature hair loss in men and women.\r\nHigh tolerance and efficacy.\r\nClinically and dermatologically tested.\r\nAdvanced formula designed for very sensitive skin/scalp.\r\n79% inhibits hair loss.\r\n75% increases hair volume.\r\n67% the scalp becomes less visible.\r\n63% stimulates the hair regrowth.\r\n83% does not electrify the hair.', 7000.00, 10, 'Hair Care', 'uploads/Pharmaceris-Intensive-Hair-Growth-Stimulating-Spray-1-600x600.jpg', '2025-08-02 19:10:59', 4.54),
(73, 'Spirit Table Top Large Face Aneroid Sphygmomanometer (CK-143)', 'CK-143\r\nTable Top Model\r\nFeatures a space-saving designed metal base and a convenient storage for inflation system.\r\nSpecifications\r\nMeasurement Range: 0-300 mmHg\r\nInflation Method: Inflation and Air Release by Manual\r\nDisplay: Round Aneroid Scale (0-300mmHg)\r\nScale: Glow-in-Dark\r\nCuff Size: 511 x 145mm (Nurse Type)\r\nCuff Material: Deluxe Nylon\r\nBase color: Black\r\nSelf-zero function\r\nSpecialize: Heavy Base and Save Lots of Space\r\n \r\nWarranty: 6 months', 28980.00, 10, 'Spirit', 'uploads/Table-Top-Large-Face-Aneroid-Sphygmomanometer-CK-143-600x400.jpg', '2025-08-02 19:12:18', 4.69),
(74, 'PHARMACERIS HAIR GROWTH STIMULATING CONDITIONER 150ml H-STIMULINUM', 'Stimulates hir growth & density\r\nStops temporary or Premature balding\r\nStimulates Healthy, Thicker hair Growth\r\nContains:\r\nNatural Growth Factor FGF\r\nCaffeine\r\nBiotin\r\nD-Panthenol\r\nVitamin PP', 5900.00, 1, 'Hair Care', 'uploads/photo_2021-11-04_09-35-51-600x800.jpg', '2025-08-02 19:13:08', 4.83),
(75, 'SPIRIT SPHYGMOMANOMETER – CK 153', 'CK-153\r\nTable Top Model\r\nIdentical features as CK-151A, with heavy base with perfect size less space usage', 30000.00, 10, 'Spirit', 'uploads/CK-153-600x450.jpg', '2025-08-02 19:13:53', 3.09),
(76, 'Spirit Majestic Series Adult Dual Head Stethoscope (CK-601P) – Black', 'CK-601P', 9360.00, 3, 'Spirit', 'uploads/Spirit-CK-601P-Black-2-600x600.jpg', '2025-08-02 19:14:22', 3.96),
(77, 'Spirit Majestic Series Adult Dual Head Stethoscope (CK-601P) – Gray', 'CK-601P', 9360.00, 1, 'Spirit', 'uploads/CK-601P.jpg', '2025-08-02 19:14:51', 3.54),
(78, 'Rossmax V3 Suction Unit', 'Ideal for tracheostomized patients, minor surgical applications, and post-operative therapy in home care and hospital.\r\nComfortable\r\nThe high-performance pump delivers -a 550mmHg vacuum and 16 liter/min flow rate.\r\nTriple Protection\r\nOverflow valve plus anti-bacterial filter and fool-proof design that prevents backflow of fluid and airborne contaminants from entering the pump.\r\nConvenient\r\nEasy to store using tubing holder and accessory compartment.\r\nErgonomic\r\nA comprehensive dashboard with an ergonomic view angle ensures smooth operation.\r\n2 years warranty\r\nThe catheter is not included', 68400.00, 3, 'Rossmax', 'uploads/Suction-Unit-V3-600x400.jpg', '2025-08-02 19:15:19', 4.80),
(79, 'Rossmax Stethoscope', 'EB600', 10800.00, 1, 'Rossmax', 'uploads/Rossmax-EB600-3-600x733.png', '2025-08-02 19:15:58', 4.40),
(80, 'Rossmax Portable Mesh Nebulizer NC200', 'MMAD ≤4 μm; Fine Particle Dose (FPD): 70-75%\r\nNebulization Rate ≥ 0.30 ml/min\r\nConsistent fine particle size for efficient respiratory treatment\r\nMESH Technology\r\nNoise level lower than 20dB\r\nHandheld design (42 x 70 x 117 mm)\r\nMouthpiece for adult and mask for child included\r\nCompatible with rechargeable batteries\r\nMicroUSB port\r\nBPA free medication bottle', 32400.00, 1, 'Rossmax', 'uploads/NC200MeshNebulizer-600x600.webp', '2025-08-02 19:16:30', 4.60),
(81, 'Rossmax Piston Nebulizer NE 100', 'NE100\r\n– 2in1 cup for easy cleaning, assembly and disassembly\r\n– MMAD ≤2.6 μm; Fine Particle Dose (FPD): 80-85%\r\n– Nebulization Rate ≥ 0.30 ml/min\r\n– Consistent fine particle size for efficient respiratory treatment\r\n– Powerful Piston compressor\r\n– Mouthpiece and masks for adult and child included\r\n– Economic Design', 13200.00, 2, 'Rossmax', 'uploads/Piston-Nebulizer-NE100-600x401.jpg', '2025-08-02 19:17:05', 4.77),
(82, 'Rossmax Piston Nebulizer NA 100', 'NA100\r\nParticle size created by our VAT bottle is around 2.19 μm (MMAD) tested by Cascade Impactor and 3.221 μm tested at Dv(50) by Malvern Spraytec. Compared to most bottles in the market, our proprietary bottle ensures more efficient and effective performance.\r\n– MMAD ≤2.2 μm; Fine Particle Dose (FPD): 80-85%\r\n– Consistent fine particle size for efficient respiratory treatment\r\n– Patented Valve Adjustable nebulizer bottle (VAT)\r\n– Powerful Piston compressor\r\n– Mouthpiece and masks for adult and child included\r\n– Built-in Compartment for Accessories', 17040.00, 4, 'Rossmax', 'uploads/Piston-Nebulizer-NA100-600x400.jpg', '2025-08-02 19:17:35', 3.08),
(83, 'Rossmax Peak Flow Meter (Child)', 'PF120C\r\n– Compact, lightweight and portable\r\n– Built-in flexible three –zone management system\r\n– Easy – to- read numbers\r\n– Measurement Range 50-400 L/min\r\n– Accuracy and repeatability assurance\r\n– Suitable for use with disposable mouthpiece\r\n– Design for child\r\n– Washable', 1920.00, 1, 'Rossmax', 'uploads/Rossmax-Peak-Flow-Meter-PF120C-Child-600x400.jpg', '2025-08-02 19:18:07', 4.08),
(84, 'ROSSMAX HANDHELD PULSE OXIMETER SA120', '%Spo2 & Pulse Rate – Backlight LCD for easy reading %Spo2 & Pulse Rate\r\nPersonal SpO2, Pulse Rate, Memory Interval thresholds setting\r\nShielded Design blocks Ambient Light\r\nBiocompatibility & Anti-Allergic Design\r\nAlarm Function – The device delivers an audible warning when the measurement result exceeds the previously set SpO2/Pulse rate thresholds.\r\nSilent mode – Disables the beep sound during the measurement by pressing the “Sound” button.\r\nUp to 288 Memories – Could be programmed with 5 intervals: 0/5/10/30/60-minutes (“0” means no memory setup)\r\nPulse Strength Indicator\r\nIdeal for oxygen therapy assessment\r\nSuitable for sleep apnea screening\r\nIdeal for health management\r\nIdeal for spot-check & continuous monitoring', 83100.00, 1, 'Rossmax', 'uploads/Rossmax-SA120-600x600.jpg', '2025-08-02 19:18:42', 4.17),
(85, 'Rossmax HA500 Infrared Non-Contact Thermometer', 'HA500\r\n-Measurement distance within 5cm\r\n-1-second measurement\r\n-Temple Temperature\r\n-Object temperature\r\n-Fever alarm\r\n-9 memories\r\n-°C / °F switchable\r\n-Backlight\r\n-Auto shut-off\r\n-Low battery indicator\r\n-Self-diagnosis for malfunction', 12000.00, 1, 'Rossmax', 'uploads/Rossmax-HA500-600x600.jpg', '2025-08-02 19:19:19', 3.59),
(86, 'MOODS CONDOMS-DOTTED (12’s)', '12 Condoms for new dimensions of pleasure', 840.00, 10, 'Sexual Wellness', 'uploads/moods-dotted-600x600.jpg', '2025-08-02 19:20:19', 4.45),
(87, 'MOODS CONDOMS-CHOCO (3’s)', '3 condoms for the explorer at heart.', 308.00, 1, 'Sexual Wellness', 'uploads/moods-dotted-600x600.jpg', '2025-08-02 19:20:47', 4.47),
(88, 'MOODS CONDOMS-BANANA (3’s)', '3 Condoms for surprising pleasures.', 300.00, 10, 'Sexual Wellness', 'uploads/moods-banana-600x600.jpg', '2025-08-02 19:21:14', 4.02),
(89, 'EVENTONE-C CREAM 30g', 'L-Glutathione + Vitamin C Cream\r\nTo treat Skin Darkness\r\nIntensive skin fairness\r\nFor Men & Women', 6750.00, 12, 'Skin Care', 'uploads/Eventone-C-Cream.jpg', '2025-08-03 00:31:49', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `products1`
--

CREATE TABLE `products1` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `image_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products1`
--

INSERT INTO `products1` (`id`, `name`, `description`, `price`, `stock`, `category`, `image_path`, `created_at`) VALUES
(11, 'Test', 'Test', 2.00, 2, 'Test', 'uploads/Warframe0001.jpg', '2025-08-02 08:59:55'),
(12, 'Test2', 'Test2', 10.00, 10, 'Test2', 'uploads/Warframe0012.jpg', '2025-08-02 09:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('customer','admin','pharmacist') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `created_at`, `reset_token`, `reset_expires`) VALUES
(1, 'sam', 'sam@gmail.com', '$2y$10$6OwMHkVhQhmzUCSo0eHxuOrsN3/v4PvqxZFw.t4FrQjgnf8NWJHGa', '0767170438', 'adfjlfdj jdflaj asjfl', 'customer', '2025-08-01 15:20:02', NULL, NULL),
(2, 'testing 1', 'test@test.com', '$2y$10$0AHpLEyh7r3Vjyo7OyLo8O4BIkgb3DlRnQWiupz/fP/dXRNhvT/Jq', '0767170438', 'test@test.com', 'customer', '2025-08-01 15:40:02', NULL, NULL),
(4, 'Samathri Abhayapala', 'admin@gmail.com', '$2y$10$S/GZtVmFRpp/zRLuELcLuO8Wmb6ceJuhZNK.DprNyac2ZOod6atH2', '0767170438', 'adfjlfdj jdflaj asjfl', 'customer', '2025-08-01 15:51:11', '3b727a0a88228a89282f0379dd619e29a0951a6aec3a99cd4ed16aceff0917d0', '2025-08-01 23:33:41'),
(5, 'samathri Abhayapala', 'samathri.15@gmail.com', '$2y$10$VXyJnRDQcprdeejg41eqOeHFT.TVvqCVZ71VDNf7/.BhJbJ4AHq1u', '0767170438', 'sajith sajith sajith sajith', 'customer', '2025-08-01 20:36:48', 'aff09456089a6e51ec77d34c02294534ff1c13477019a63ab1adebb138e7f1ef', '2025-08-02 00:21:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `best_selling_items`
--
ALTER TABLE `best_selling_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `prescriptions_2`
--
ALTER TABLE `prescriptions_2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products1`
--
ALTER TABLE `products1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `best_selling_items`
--
ALTER TABLE `best_selling_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prescriptions_2`
--
ALTER TABLE `prescriptions_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `products1`
--
ALTER TABLE `products1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `prescriptions_2`
--
ALTER TABLE `prescriptions_2`
  ADD CONSTRAINT `prescriptions_2_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
