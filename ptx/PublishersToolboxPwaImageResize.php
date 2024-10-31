<?php
    
    namespace PT\ptx;
    
    use RuntimeException;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Resize images properly for application.
     *
     * Uses PHP library to resize images for use in application when needed.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaImageResize {
        
        /**
         * The image quality level.
         *
         * @access constant
         * @var integer PIC_QUALITY The default image quality.
         *
         * @since 2.1.0
         */
        const PIC_QUALITY = 80;
        
        /**
         * @param integer $id
         * @param integer $width
         * @param integer $height
         * @param bool $crop
         * @param array $config
         *
         * @return bool|mixed|string
         *
         * @since 2.0.0
         */
        public static function imageResize($id, $width, $height, $crop, $config) {
            if (isset($config['wp'])) {
                $imageSrc = wp_get_attachment_image_src($id, (isset($config['size']) ? $config['size'] : 'thumbnail'));
                
                return $imageSrc[0];
            }
            
            if (!$id && !array_key_exists('cache', $config)) {
                return false;
            }
            
            if ($id !== 0) {
                $url = wp_get_attachment_image_src($id, 'full');
                if (!$url) {
                    return false;
                }
                $url = parse_url($url[0]);
                $url = ltrim($url['path'], '/');
            } else {
                $url    = parse_url($config['cache']);
                $info   = getimagesize(ABSPATH . $url['path']);
                $width  = $info[0];
                $height = $info[1];
                $url    = ABSPATH . ltrim($url['path'], '/');
                $crop   = false;
            }
            
            $src = $url;
            
            if (!(int)$width && !(int)$height) {
                throw new RuntimeException('Please provide a width or height for the resize image!');
            }
            
            $config['src']    = $_SERVER['DOCUMENT_ROOT'] . '/' . $src;
            $config['width']  = $width;
            $config['height'] = $height;
            $config['crop']   = $crop;
            
            if (array_key_exists('copy', $config)) {
                $config['copy'] = strpos($config['copy'], '/') === 0 ? substr($config['copy'], 1) : $config['copy'];
                $config['copy'] = substr($config['copy'], -1) === '/' ? substr($config['copy'], 0, -1) : $config['copy'];
                
                $copyDirectory = $_SERVER['DOCUMENT_ROOT'] . '/copied/' . $config['copy'] . '/';
                $pathInfo      = pathinfo($config['src']);
                
                if (file_exists($copyDirectory . $pathInfo['basename'])) {
                    $config['src'] = $copyDirectory . $pathInfo['basename'];
                } else {
                    if (!is_dir($copyDirectory)) {
                        if (!mkdir($copyDirectory, 0755, true) && !is_dir($copyDirectory)) {
                            throw new RuntimeException(sprintf('Directory "%s" was not created', $copyDirectory));
                        }
                        chmod($copyDirectory, 0755);
                    }
                    if (copy($pathInfo['dirname'] . '/' . $pathInfo['basename'], $copyDirectory . $pathInfo['basename'])) {
                        $config['src'] = $copyDirectory . $pathInfo['basename'];
                    } else {
                        return false;
                    }
                }
            }
            
            if (function_exists('gd_info') && file_exists($config['src'])) {
                $info    = getimagesize($config['src']);
                $quality = array_key_exists('quality', $config) ? $config['quality'] : self::PIC_QUALITY;
                switch ($info[2]) {
                    case IMAGETYPE_GIF :
                        $createFromFunction = 'imagecreatefromgif';
                        $saveAsFunction     = 'imagegif';
                        break;
                    case IMAGETYPE_JPEG :
                        $createFromFunction = 'imagecreatefromjpeg';
                        $saveAsFunction     = 'imagejpeg';
                        break;
                    case IMAGETYPE_PNG :
                        $createFromFunction = 'imagecreatefrompng';
                        $saveAsFunction     = 'imagepng';
                        $quality            = ceil(($quality - 10) / 10);
                        break;
                    default    :
                        return 'No Image Type';
                }
                
                $pathInfo = pathinfo($config['src']);
                $extLen   = strlen($pathInfo['extension']) + 1;
                $newFile  = $pathInfo['dirname'] . '/' . substr($pathInfo['basename'], 0, ($extLen *= -1)) . '_' . $config['width'] . 'x' . $config['height'] . ($config['crop'] ? '_crop' : '') . (array_key_exists('greyscale', $config) ? '_grey' : '') . ($quality ? '_' . $quality : '') . '.' . $pathInfo['extension'];
                
                if (file_exists($newFile)) { //create it if not created already
                    return get_site_url(get_current_blog_id()) . '/' . str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $newFile);
                }
                
                $image = $createFromFunction($config['src']);
                
                $width  = imagesx($image);
                $height = imagesy($image);
                
                $destW = $config['width'];
                $destH = $config['height'];
                
                if ($destW > $width) {
                    $destW = $width;
                }
                if ($destH > $height) {
                    $destH = $height;
                }
                
                if ($destW && !$destH) {
                    $destH = $height * ($destW / $width);
                } elseif ($destH && !$destW) {
                    $destW = $width * ($destH / $height);
                } elseif (!$destW && !$destH) {
                    $destW = $width;
                    $destH = $height;
                }
                
                if ($config['crop']) {
                    $srcX = $srcY = 0;
                    $srcW = $width;
                    $srcH = $height;
                    
                    $cmp_x = $width / $destW;
                    $cmp_y = $height / $destH;
                    
                    if ($cmp_x > $cmp_y) {
                        $srcW = round($width / $cmp_x * $cmp_y);
                        $srcX = round(($width - ($width / $cmp_x * $cmp_y)) / 2);
                    } elseif ($cmp_y > $cmp_x) {
                        $srcH = round($height / $cmp_y * $cmp_x);
                        $srcY = round(($height - ($height / $cmp_y * $cmp_x)) / 2);
                    }
                    
                    $destX = 0;
                    $destY = 0;
                } else {
                    if ($destW && $destH) {
                        $destW = $destW < $info[0] ? $destW : $info[0];
                        $destH = $destH < $info[1] ? $destH : $info[1];
                        
                        $xPercentage = $destW / $info[0] * 100;
                        $yPercentage = $destH / $info[1] * 100;
                        
                        if ($xPercentage >= $yPercentage) {
                            $destW = round($info[0] * $yPercentage / 100);
                        } else {
                            $destH = round($info[1] * $xPercentage / 100);
                        }
                    } else if ($destW && !$destH) {
                        $destW      = $destW < $info[0] ? $destW : $info[0];
                        $percentage = $destW / $info[0] * 100;
                        $destH      = round($info[1] * $percentage / 100);
                    } else if (!$destW && $destH) {
                        $destH      = $destH < $info[1] ? $destH : $info[1];
                        $percentage = $destH / $info[1] * 100;
                        $destW      = round($info[0] * $percentage / 100);
                    }
                    
                    $destX = 0;
                    $destY = 0;
                    $srcX  = 0;
                    $srcY  = 0;
                    $srcW  = $width;
                    $srcH  = $height;
                }
                
                if (array_key_exists('greyscale', $config)) {
                    imagefilter($image, IMG_FILTER_GRAYSCALE);
                }
                
                $canvas = imagecreatetruecolor($destW, $destH);
                
                if ($info[2] === IMAGETYPE_GIF || $info[2] === IMAGETYPE_PNG) {
                    $transparentIndex = imagecolortransparent($image);
                    
                    if ($transparentIndex >= 0) {
                        $transparentColor = imagecolorsforindex($image, $transparentIndex);
                        $transparentIndex = imagecolorallocate($canvas, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);
                        imagefill($canvas, 0, 0, $transparentIndex);
                        imagecolortransparent($canvas, $transparentIndex);
                    } elseif (IMAGETYPE_PNG === $info[2]) {
                        imagealphablending($canvas, false);
                        $color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                        imagefill($canvas, 0, 0, $color);
                        imagesavealpha($canvas, true);
                    }
                }
                
                imagecopyresampled($canvas, $image, $destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH);
                
                $args = [$canvas, $newFile];
                if ($quality) {
                    $args[] = $quality;
                }
                
                call_user_func_array($saveAsFunction, $args);
                imagedestroy($image);
                imagedestroy($canvas);
                
                return get_site_url(get_current_blog_id()) . '/' . str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $newFile);
            }
            
            /**
             * Return wordpress version if gd library has issues.
             */
            $imageSrc = wp_get_attachment_image_src($id, (isset($config['size']) ? $config['size'] : 'thumbnail'));
            
            return $imageSrc[0];
        }
    }
