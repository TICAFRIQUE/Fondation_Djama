<?php

namespace Tests\Unit;

use App\Support\ImageOptimizer;
use App\Support\Site;
use Tests\TestCase;

class SiteHelpersTest extends TestCase
{
    public function test_title_highlights_words_between_stars_and_escapes_html(): void
    {
        $this->assertSame('Des vies <span>transformées</span>', (string) Site::title('Des vies *transformées*'));
        $this->assertSame('&lt;b&gt;x&lt;/b&gt; <span>y</span>', (string) Site::title('<b>x</b> *y*'));
        $this->assertSame('Des vies transformées', Site::plain('Des vies *transformées*'));
    }

    public function test_link_accepts_urls_paths_and_home_anchors(): void
    {
        $home = route('index');

        $this->assertSame('https://exemple.org/page', Site::link('https://exemple.org/page'));
        $this->assertSame('/galerie', Site::link('/galerie'));
        $this->assertSame('mailto:a@b.c', Site::link('mailto:a@b.c'));
        $this->assertSame("$home#agir", Site::link('agir'));
        $this->assertSame("$home#agir", Site::link('#agir'));
        $this->assertSame($home, Site::link(null));
    }

    public function test_excerpt_returns_clean_plain_text(): void
    {
        $this->assertSame('Titre Un texte & plus', Site::excerpt("<h2>Titre</h2>\n<p>Un   texte &amp; plus</p>"));
        $this->assertSame('abcde...', Site::excerpt('abcdefghij', 5));
    }

    public function test_rich_keeps_admin_html_but_escapes_plain_text(): void
    {
        $this->assertSame("a &lt; b<br />\nc", (string) Site::rich("a < b\nc"));
        $this->assertSame('<p>ok</p>', (string) Site::rich('<p>ok</p><script>alert(1)</script>'));
    }

    public function test_image_optimizer_resizes_large_photos_and_leaves_small_or_unknown_files(): void
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'djama-test-' . uniqid();
        mkdir($dir);

        $large = "$dir/large.jpg";
        imagejpeg(imagecreatetruecolor(3000, 1500), $large, 100);
        $this->assertTrue(ImageOptimizer::optimize($large, 1200));
        $this->assertSame([1200, 600], array_slice(getimagesize($large), 0, 2));

        $png = "$dir/logo.png";
        $image = imagecreatetruecolor(2400, 1200);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagepng($image, $png);
        $this->assertTrue(ImageOptimizer::optimize($png, 800));
        $this->assertSame(800, getimagesize($png)[0]);
        $resized = imagecreatefrompng($png);
        $this->assertSame(127, (imagecolorat($resized, 10, 10) >> 24) & 0x7F, 'La transparence du PNG doit être conservée');

        $video = "$dir/clip.mp4";
        file_put_contents($video, 'pas une image');
        $this->assertFalse(ImageOptimizer::optimize($video));
        $this->assertSame('pas une image', file_get_contents($video));

        array_map('unlink', glob("$dir/*"));
        rmdir($dir);
    }
}
