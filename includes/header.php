<?php
$currentPage = basename($_SERVER['PHP_SELF']);
if (empty($currentPage)) $currentPage = 'index.php';

$seoMeta = [
    'index.php' => [
        'title' => 'Green Pyramids Export | Premium Egyptian Agricultural Produce',
        'description' => 'Leading Egyptian agricultural export company. Premium oranges, mangoes, pomegranates, potatoes, onions & fresh produce delivered to Europe, Gulf & global B2B buyers.',
        'keywords' => 'Egyptian agricultural export, fresh produce Egypt, Egyptian oranges, Egyptian mangoes, Egyptian pomegranates, B2B produce supplier Egypt'
    ],
    'about.php' => [
        'title' => 'About Green Pyramids | Trusted Egyptian Produce Exporter',
        'description' => 'Learn about Green Pyramids, connecting global importers to Egyptian farms with traceable origins, cold-chain management & export-certified quality.',
        'keywords' => 'About Green Pyramids, Egyptian farm sourcing, agricultural exporters Cairo'
    ],
    'products.php' => [
        'title' => 'Export Catalog | Fresh Egyptian Fruits, Vegetables & Citrus',
        'description' => 'Explore Green Pyramids export catalog. Premium Egyptian citrus, fresh vegetables, seasonal crops, and tropical fruits prepared for international markets.',
        'keywords' => 'Egyptian fresh produce catalog, Egyptian citrus exporter, export vegetables Egypt'
    ],
    'process.php' => [
        'title' => 'Export Supply Chain Journey | Green Pyramids',
        'description' => 'Trace our agricultural export journey from Nile Delta harvest, quality control, cold-chain packaging to global sea & air logistics.',
        'keywords' => 'Egyptian produce supply chain, cold chain agricultural export'
    ],
    'quality.php' => [
        'title' => 'Quality Standards & Certifications | Green Pyramids',
        'description' => 'Strict quality control, MRL compliance, and international food safety certifications for Egyptian fruit and vegetable exports.',
        'keywords' => 'Egyptian produce quality control, export certifications Egypt'
    ],
    'contact.php' => [
        'title' => 'Contact Green Pyramids | Request B2B Export Quote',
        'description' => 'Get in touch with Green Pyramids export team for custom B2B quotes, seasonal produce availability, and regional distribution partnerships.',
        'keywords' => 'Request Egyptian produce quote, B2B agricultural export contact'
    ]
];

$pageSeo = $seoMeta[$currentPage] ?? $seoMeta['index.php'];
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$currentUrl = $protocol . '://' . $domain . $_SERVER['REQUEST_URI'];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pageSeo['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageSeo['description']) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($pageSeo['keywords']) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($currentUrl) ?>">

    <!-- Open Graph / Facebook Meta -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageSeo['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageSeo['description']) ?>">
    <meta property="og:image" content="<?= $protocol ?>://<?= $domain ?>/assets/images/static/hero_background.png">

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageSeo['title']) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageSeo['description']) ?>">
    <meta name="twitter:image" content="<?= $protocol ?>://<?= $domain ?>/assets/images/static/hero_background.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              emerald: {
                DEFAULT: '#173F35',
                dark: '#0d2a24',
                light: '#1f5245'
              },
              sage: '#8FAE5D',
              sand: '#F6F3EC',
              gold: '#D8C7A1'
            },
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
              serif: ['Playfair Display', 'serif']
            }
          }
        }
      }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/animations.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">

    <!-- Schema.org JSON-LD Structured Data for B2B Agricultural Exporter -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Green Pyramids Export",
      "url": "<?= htmlspecialchars($currentUrl) ?>",
      "logo": "<?= $protocol ?>://<?= $domain ?>/assets/images/favicon.svg",
      "description": "<?= htmlspecialchars($pageSeo['description']) ?>",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Cairo",
        "addressCountry": "EG"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+20-2-000-0000",
        "contactType": "sales",
        "areaServed": ["EU", "GCC", "Asia", "Worldwide"]
      }
    }
    </script>
  </head>
  <body class="font-sans bg-[#F6F3EC] text-[#173F35] pt-[80px]">
    <?php include 'includes/navigation.php'; ?>
    <main class="flex-1">
