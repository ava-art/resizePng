<?php
$GLOBALS['dataInput'] = json_decode(file_get_contents("php://input"));


if (in_array(900, $GLOBALS['dataInput']->canvas))
    $_POST['size900'] = true;

if (in_array(1000, $GLOBALS['dataInput']->canvas))
    $_POST['size1000'] = true;

if ($GLOBALS['dataInput']->NF_holod)
    $_POST['nf-refregirator'] = true;

if ($GLOBALS['dataInput']->NF_detaly)
    $_POST['nf-detaly'] = true;

header('Content-type: text/html; charset=utf-8');
require_once __DIR__ . './lib/thumbs.php';

$errors = array();
$succesfull = array();

function create_resize(string $src)
{

    $src_dir = $GLOBALS['dataInput']->path;
    $src = "$src_dir" . "/$src";
    $name = pathinfo($src, PATHINFO_FILENAME);

    if ($GLOBALS['dataInput']->company === 'hiberg') {

        if (mime_content_type($src) == 'image/png') {
            $img = @imagecreatefrompng($src);
            $img = imagecropauto($img, IMG_CROP_SIDES);

            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);

            if (is_dir("{$src_dir}/2000x2000")) {
                imagepng($img, "{$src_dir}/2000x2000/{$name}_2000x2000.png");
            } else {
                mkdir("{$src_dir}/2000x2000", 0777, true);
                imagepng($img, "{$src_dir}/2000x2000/{$name}_2000x2000.png");
            }
            imagedestroy($img);
            if (isset($_POST['size900']) || isset($_POST['size1000'])) {

                $img22 = imagecreatefrompng("{$src_dir}/2000x2000/{$name}_2000x2000.png");
                $bg = imagecreatetruecolor(imagesx($img22), imagesy($img22));
                imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                imagealphablending($bg, TRUE);
                imagecopy($bg, $img22, 0, 0, 0, 0, imagesx($img22), imagesy($img22));
                imagedestroy($img22);

                if (isset($_POST['size900'])) {
                    if (is_dir("{$src_dir}/900x1200")) {
                        imagejpeg($bg, "{$src_dir}/900x1200/{$name}_900x1200.jpg", 90);
                       
                    } else {
                        mkdir("{$src_dir}/900x1200", 0777, true);
                        imagejpeg($bg, "{$src_dir}/900x1200/{$name}_900x1200.jpg", 90);
                        
                    }
                }
                if (isset($_POST['size1000'])) {
                    if (is_dir("{$src_dir}/1000x1000")) {
                        imagejpeg($bg, "{$src_dir}/1000x1000/{$name}_1000x1000.jpg", 90);
                        imagedestroy($bg);
                    } else {
                        mkdir("{$src_dir}/1000x1000", 0777, true);
                        imagejpeg($bg, "{$src_dir}/1000x1000/{$name}_1000x1000.jpg", 90);
                        imagedestroy($bg);
                    }
                }
            }

            list($width, $height) = getimagesize("{$src_dir}/2000x2000/{$name}_2000x2000.png");

            if ($width < 2000 && $height < 2000) {
                if ($height >= $width) {
                    $image = new Thumbs("{$src_dir}/2000x2000/{$name}_2000x2000.png");
                    $image->resize(0, 2000);
                    $image->resizeCanvas(2000, 2000);
                    $image->save();
                } else {
                    $image = new Thumbs("{$src_dir}/2000x2000/{$name}_2000x2000.png");
                    $image->resize(2000, 0);
                    $image->resizeCanvas(2000, 2000);
                    $image->save();
                }
            } else {
                $image = new Thumbs("{$src_dir}/2000x2000/{$name}_2000x2000.png");
                $image->resizeCanvas(2000, 2000);
                $image->save();
            }
            if (isset($_POST['size1000'])) {
                $image = new Thumbs("{$src_dir}/1000x1000/{$name}_1000x1000.jpg");
                $image->resizeCanvas(1000, 1000, array(255, 255, 255));
                $image->save();
            }
            if (isset($_POST['size900'])) {
                $image = new Thumbs("{$src_dir}/900x1200/{$name}_900x1200.jpg");
                $image->resizeCanvas(900, 1200, array(255, 255, 255));
                $image->save();
            }
            return ['succesfull' => $name];
        } else {
            return ['error' => $name];
        }
    } else if ($GLOBALS['dataInput']->company === 'nordfrost') {
        if (mime_content_type($src) == 'image/png') {

            $img22 = @imagecreatefrompng($src);
            $img22 = imagecropauto($img22, IMG_CROP_SIDES);
            $bg = imagecreatetruecolor(imagesx($img22), imagesy($img22));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            // imagealphablending($bg, TRUE);
            imagecopy($bg, $img22, 0, 0, 0, 0, imagesx($img22), imagesy($img22));
            imagedestroy($img22);

            if (isset($_POST['nf-refregirator'])) {

                if (is_dir("{$src_dir}/3200")) {
                    imagejpeg($bg, "{$src_dir}/3200/{$name}_3200.jpg", 90);
                } else {
                    mkdir("{$src_dir}/3200", 0777, true);
                    imagejpeg($bg, "{$src_dir}/3200/{$name}_3200.jpg", 90);
                }
            } else {

                if (is_dir("{$src_dir}/2000x2000")) {
                    imagejpeg($bg, "{$src_dir}/2000x2000/{$name}_2000x2000.jpg", 90);
                } else {
                    mkdir("{$src_dir}/2000x2000", 0777, true);
                    imagejpeg($bg, "{$src_dir}/2000x2000/{$name}_2000x2000.jpg", 90);
                }
            }
            if (isset($_POST['size900']) || isset($_POST['size1000'])) {
                if (isset($_POST['size900'])) {
                    if (is_dir("{$src_dir}/900x1200")) {
                        imagejpeg($bg, "{$src_dir}/900x1200/{$name}_900x1200.jpg", 90);
                    } else {
                        mkdir("{$src_dir}/900x1200", 0777, true);
                        imagejpeg($bg, "{$src_dir}/900x1200/{$name}_900x1200.jpg", 90);
                    }
                }
                if (isset($_POST['size1000'])) {
                    if (is_dir("{$src_dir}/1000x1000")) {
                        imagejpeg($bg, "{$src_dir}/1000x1000/{$name}_1000x1000.jpg", 90);
                        imagedestroy($bg);
                    } else {
                        mkdir("{$src_dir}/1000x1000", 0777, true);
                        imagejpeg($bg, "{$src_dir}/1000x1000/{$name}_1000x1000.jpg", 90);
                        imagedestroy($bg);
                    }
                }
            }
            if (isset($_POST['nf-refregirator'])) {
                list($width, $height) = getimagesize("{$src_dir}/3200/{$name}_3200.jpg");
            } else {
                list($width, $height) = getimagesize("{$src_dir}/2000x2000/{$name}_2000x2000.jpg");
            }
            if (isset($_POST['nf-refregirator'])) {
                if ($height >= $width) {
                    $image = new Thumbs("{$src_dir}/3200/{$name}_3200.jpg");
                    $image->resize(0, 3000);

                    if (isset($_POST['nf-detaly'])){

                    } else{
                        $newWidth = 3200 / ($height / 100);
                        $newWidth = $width * ($newWidth / 100);
                        $image->resizeCanvas($newWidth + 100, 3200, array(255, 255, 255));
                    }
                    $image->save();
                } else {
                    $image = new Thumbs("{$src_dir}/3200/{$name}_3200.jpg");
                    $image->resize(3200, 0);
                    $newHeight = 3200 / ($width / 100);
                    $newHeight = $height * ($newHeight / 100);
                    $image->resizeCanvas(3200, $newHeight, array(255, 255, 255));
                    $image->save();
                }
            } else {
                if ($width < 2000 && $height < 2000) {
                    if ($height >= $width) {
                        $image = new Thumbs("{$src_dir}/2000x2000/{$name}_2000x2000.jpg");
                        $image->resize(0, 2000);
                        $image->resizeCanvas(2000, 2000, array(255, 255, 255));
                        $image->save();
                    } else {
                        $image = new Thumbs("{$src_dir}/2000x2000/{$name}_2000x2000.jpg");
                        $image->resize(2000, 0);
                        $image->resizeCanvas(2000, 2000, array(255, 255, 255));
                        $image->save();
                    }
                } else {
                    $image = new Thumbs("{$src_dir}/2000x2000/{$name}_2000x2000.jpg");
                    $image->resizeCanvas(2000, 2000, array(255, 255, 255));
                    $image->save();
                }
            }
            if (isset($_POST['size1000'])) {
                $image = new Thumbs("{$src_dir}/1000x1000/{$name}_1000x1000.jpg");
                $image->resize(0, 1000);
                $image->resizeCanvas(1000, 1000, array(255, 255, 255));
                $image->save();
            }
            if (isset($_POST['size900'])) {
                $image = new Thumbs("{$src_dir}/900x1200/{$name}_900x1200.jpg");
                $image->resize(0, 1200);
                $image->resizeCanvas(900, 1200, array(255, 255, 255));
                $image->save();
            }
            return ['succesfull' => $name];
        } else {
            return ['error' => $name];
        }
    }
}

foreach ($GLOBALS['dataInput']->files as $foto) {
    $res = create_resize($foto);

    if (isset($res['error']))
        $errors = $res;

    if (isset($res['succesfull']))
        $succesfull = $res;
}

    echo json_encode([$succesfull, $errors]);

