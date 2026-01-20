<?php
// This file generates a sample image for the freebies
// Usage: include this and call generate_og_image($title, $filename)

function generate_og_image($title = 'Sample Image', $filename = null): string
{
    $width = 1200;
    $height = 630;

    $image = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($image, 102, 126, 234); // Purple gradient base
    $accentColor = imagecolorallocate($image, 118, 75, 162); // Darker purple
    $textColor = imagecolorallocate($image, 255, 255, 255); // White text

    // Create gradient effect
    for ($i = 0; $i < $height; $i++) {
        $ratio = $i / $height;
        $r = (int)(102 + ($ratio * 16));
        $g = (int)(126 - ($ratio * 51));
        $b = (int)(234 - ($ratio * 72));
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $i, $width, $i, $color);
    }

    // Add decorative circles
    for ($i = 0; $i < 5; $i++) {
        $x = rand(0, $width);
        $y = rand(0, $height);
        $radius = rand(50, 200);
        $opacity = rand(10, 30);
        imagefilledellipse($image, $x, $y, $radius, $radius, $bgColor);
    }

    // Add text
    $fontPath = __DIR__ . '/../../fonts/arial.ttf';
    $fontSize = 60;

    imagettftext(
        $image,
        $fontSize,
        0,
        50,
        250,
        $textColor,
        $fontPath ?? '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        $title
    );

    // Add subtitle
    imagettftext(
        $image,
        30,
        0,
        50,
        450,
        $textColor,
        $fontPath ?? '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
        'Free Sample Image - Use for Your Projects'
    );

    if ($filename) {
        imagepng($image, $filename);
    }

    imagedestroy($image);

    return $filename ?? 'image/png';
}

// Generate image if this file is accessed directly
if (php_sapi_name() !== 'cli' && !headers_sent()) {
    header('Content-Type: image/png');
    header('Cache-Control: max-age=86400');

    $filename = null;
    $title = $_GET['title'] ?? 'Sample Freebie';

    generate_og_image($title, $filename);
}
