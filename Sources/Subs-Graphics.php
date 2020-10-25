<?php
/**********************************************************************************
 * Subs-Graphics.php                                                               *
 * **********************************************************************************
 * SMF: Simple Machines Forum                                                      *
 * Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                    *
 * =============================================================================== *
 * Software Version:           SMF 1.1.2                                           *
 * Software by:                Simple Machines (http://www.simplemachines.org)     *
 * Copyright 2006 by:          Simple Machines LLC (http://www.simplemachines.org) *
 *           2001-2006 by:     Lewis Media (http://www.lewismedia.com)             *
 * Support, News, Updates at:  http://www.simplemachines.org                       *
 * **********************************************************************************
 * This program is free software; you may redistribute it and/or modify it under   *
 * the terms of the provided license as published by Simple Machines LLC.          *
 *                                                                                 *
 * This program is distributed in the hope that it is and will be useful, but      *
 * WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
 * or FITNESS FOR A PARTICULAR PURPOSE.                                            *
 *                                                                                 *
 * See the "license.txt" file for details of the Simple Machines license.          *
 * The latest version can always be found at http://www.simplemachines.org.        *
 * **********************************************************************************
 * Gif Util copyright 2003 by Yamasoft (S/C). All rights reserved.                 *
 * Do not remove this portion of the header, or use these functions except         *
 * from the original author. To get it, please navigate to:                        *
 * http://www.yamasoft.com/php-gif.zip                                             *
 * **********************************************************************************
 * TrueType fonts supplied by www.LarabieFonts.com                                 *
 ***********************************************************************************/

if (!defined('SMF')) {
    die('Hacking attempt...');
}

/*	This whole file deals almost exclusively with handling avatars,
    specifically uploaded ones.  It uses, for gifs at least, Gif Util... for
    more information on that, please see its website, shown above.  The other
    functions are as follows:

    bool downloadAvatar(string url, int ID_MEMBER, int max_width,
            int max_height)
        - downloads file from url and stores it locally for avatar use
          by ID_MEMBER.
        - supports GIF, JPG, PNG, BMP and WBMP formats.
        - detects if GD2 is available.
        - if GIF support isn't present in GD, handles GIFs with gif_loadFile()
          and gif_outputAsPng().
        - uses resizeImage() to resize to max_width by max_height, if needed,
          and saves the result to a file.
        - updates the database info for the member's avatar.
        - returns whether the download and resize was successful.

    bool createThumbnail(string source, int max_width, int max_height)
        // !!!

    void resizeImage(resource src_img, string destination_filename,
            int src_width, int src_height, int max_width, int max_height)
        - resizes src_img proportionally to fit within max_width and
          max_height limits if it is too large.
        - if GD2 is present as detected in downloadAvatar(), it'll use it to
          achieve better quality.
        - saves the new image to destination_filename.
        - saves as a PNG or JPEG depending on the avatar_download_png setting.

    void imagecopyresamplebicubic(resource dest_img, resource src_img,
            int dest_x, int dest_y, int src_x, int src_y, int dest_w,
            int dest_h, int src_w, int src_h)
        - used when imagecopyresample() is not available.

    resource gif_loadFile(string filename, int animation_index)
        - loads a gif file with the Yamasoft GIF utility class.
        - returns a new GD image.

    bool gif_outputAsPng(resource gif, string destination_filename,
            int bgColor = -1)
        - writes a gif file to disk as a png file.
        - returns whether it was successful or not.

    bool imagecreatefrombmp(string filename)
        - is set only if it doesn't already exist (for forwards compatiblity.)
        - only supports uncompressed bitmaps.
        - returns an image identifier representing the bitmap image obtained
          from the given filename.

    bool showCodeImage(string code)
        - show an image containing the visual verification code for registration.
        - requires the GD extension.
        - uses a random font for each letter from default_theme_dir/fonts.
        - outputs a gif or a png (depending on whether gif ix supported).
        - returns false if something goes wrong.

    bool showLetterImage(string letter)
        - show a letter for the visual verification code.
        - alternative function for showCodeImage() in case GD is missing.
        - includes an image from a random sub directory of
          default_theme_dir/fonts.
*/

function downloadAvatar($url, $memID, $max_width, $max_height)
{
    global $modSettings, $db_prefix, $sourcedir, $gd2;

    $destName = 'avatar_' . $memID . '.' . (!empty($modSettings['avatar_download_png']) ? 'png' : 'jpeg');

    $default_formats = [
        '1' => 'gif',
        '2' => 'jpeg',
        '3' => 'png',
        '6' => 'bmp',
        '15' => 'wbmp',
    ];

    // Check to see if GD is installed and what version.

    $testGD = get_extension_funcs('gd');

    // If GD is not installed, this function is pointless.

    if (empty($testGD)) {
        return false;
    }

    // Just making sure there is a non-zero member.

    if (empty($memID)) {
        return false;
    }

    // GD 2 maybe?

    $gd2 = in_array('imagecreatetruecolor', $testGD, true) && function_exists('imagecreatetruecolor');

    unset($testGD);

    require_once $sourcedir . '/ManageAttachments.php';

    removeAttachments('a.ID_MEMBER = ' . $memID);

    db_query(
        "
		INSERT INTO {$db_prefix}attachments
			(ID_MEMBER, attachmentType, filename, size)
		VALUES ($memID, " . (empty($modSettings['custom_avatar_enabled']) ? '0' : '1') . ", '$destName', 1)",
        __FILE__,
        __LINE__
    );

    $attachID = db_insert_id();

    $destName = (empty($modSettings['custom_avatar_enabled']) ? $modSettings['attachmentUploadDir'] : $modSettings['custom_avatar_dir']) . '/' . $destName . '.tmp';

    $success = false;

    $sizes = url_image_size($url);

    require_once $sourcedir . '/Subs-Package.php';

    $fp = fopen($destName, 'wb');

    if ($fp && 'http://' == mb_substr($url, 0, 7)) {
        fwrite($fp, fetch_web_data($url));

        fclose($fp);
    } elseif ($fp) {
        $fp2 = fopen($url, 'rb');

        while (!feof($fp2)) {
            fwrite($fp, fread($fp2, 8192));
        }

        fclose($fp2);

        fclose($fp);
    } // We can't get to the file.

    else {
        $sizes = [-1, -1, -1];
    }

    // Gif? That might mean trouble if gif support is not available.

    if (1 == $sizes[2] && !function_exists('imagecreatefromgif') && function_exists('imagecreatefrompng')) {
        // Download it to the temporary file... use the special gif library... and save as png.

        if ($img = @gif_loadFile($destName) && gif_outputAsPng($img, $destName)) {
            $sizes[2] = 3;
        }
    }

    // A known and supported format?

    if (isset($default_formats[$sizes[2]]) && function_exists('imagecreatefrom' . $default_formats[$sizes[2]])) {
        $imagecreatefrom = 'imagecreatefrom' . $default_formats[$sizes[2]];

        if ($src_img = @$imagecreatefrom($destName)) {
            resizeImage($src_img, $destName, imagesx($src_img), imagesy($src_img), $max_width, $max_height);

            $success = true;
        }
    }

    // Remove the .tmp extension.

    $destName = mb_substr($destName, 0, -4);

    if ($success) {
        // Remove the .tmp extension from the attachment.

        if (rename($destName . '.tmp', $destName)) {
            [$width, $height] = getimagesize($destName);

            // Write filesize in the database.

            db_query(
                "
				UPDATE {$db_prefix}attachments
				SET size = " . filesize($destName) . ', width = ' . (int)$width . ', height = ' . (int)$height . "
				WHERE ID_ATTACH = $attachID
				LIMIT 1",
                __FILE__,
                __LINE__
            );

            return true;
        }
  

        return false;
    }  

    db_query(
            "
			DELETE FROM {$db_prefix}attachments
			WHERE ID_ATTACH = $attachID
			LIMIT 1",
            __FILE__,
            __LINE__
        );

    @unlink($destName . '.tmp');

    return false;
}

function createThumbnail($source, $max_width, $max_height)
{
    global $modSettings, $db_prefix, $gd2;

    $default_formats = [
        '1' => 'gif',
        '2' => 'jpeg',
        '3' => 'png',
        '6' => 'bmp',
        '15' => 'wbmp',
    ];

    // Is GD installed....?

    $testGD = get_extension_funcs('gd');

    // No GD?  Resizing to nothing?  Time to bail!

    if (empty($testGD) || (empty($max_width) && empty($max_height))) {
        return false;
    }

    // Do we have GD 2, even?

    $gd2 = in_array('imagecreatetruecolor', $testGD, true) && function_exists('imagecreatetruecolor');

    unset($testGD);

    $destName = $source . '_thumb.tmp';

    // Ask for more memory: we need it for this, and it'll only happen once!

    @ini_set('memory_limit', '48M');

    $success = false;

    $sizes = getimagesize($source);

    if (empty($sizes)) {
        return false;
    }

    // If we have to handle a gif, we might be able to... but maybe not :/.

    if (1 == $sizes[2] && !function_exists('imagecreatefromgif') && function_exists('imagecreatefrompng')) {
        // Try out a temporary file, if possible...

        if ($img = @gif_loadFile($source) && gif_outputAsPng($img, $destName)) {
            if ($src_img = imagecreatefrompng($destName)) {
                resizeImage($src_img, $destName, imagesx($src_img), imagesy($src_img), $max_width, $max_height);

                $success = true;
            }
        }
    } // Or is it one of the formats supported above?

    elseif (isset($default_formats[$sizes[2]]) && function_exists('imagecreatefrom' . $default_formats[$sizes[2]])) {
        $imagecreatefrom = 'imagecreatefrom' . $default_formats[$sizes[2]];

        if ($src_img = @$imagecreatefrom($source)) {
            resizeImage($src_img, $destName, imagesx($src_img), imagesy($src_img), $max_width, $max_height);

            $success = true;
        }
    }

    // Okay, we're done with the temporary stuff.

    $destName = mb_substr($destName, 0, -4);

    if ($success && @rename($destName . '.tmp', $destName)) {
        return true;
    }  

    @unlink($destName . '.tmp');

    @touch($destName);

    return false;
}

function resizeImage($src_img, $destName, $src_width, $src_height, $max_width, $max_height)
{
    global $gd2, $modSettings;

    // Determine whether to resize to max width or to max height (depending on the limits.)

    if (!empty($max_width) || !empty($max_height)) {
        if (!empty($max_width) && (empty($max_height) || $src_height * $max_width / $src_width <= $max_height)) {
            $dst_width = $max_width;

            $dst_height = floor($src_height * $max_width / $src_width);
        } elseif (!empty($max_height)) {
            $dst_width = floor($src_width * $max_height / $src_height);

            $dst_height = $max_height;
        }

        // Don't bother resizing if it's already smaller...

        if (!empty($dst_width) && !empty($dst_height) && ($dst_width < $src_width || $dst_height < $src_height)) {
            // (make a true color image, because it just looks better for resizing.)

            if ($gd2) {
                $dst_img = imagecreatetruecolor($dst_width, $dst_height);

                if (!empty($modSettings['avatar_download_png'])) {
                    imagealphablending($dst_img, false);

                    if (function_exists('imagesavealpha')) {
                        imagesavealpha($dst_img, true);
                    }
                }
            } else {
                $dst_img = imagecreate($dst_width, $dst_height);
            }

            // Resize it!

            if ($gd2) {
                imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
            } else {
                imagecopyresamplebicubic($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
            }
        } else {
            $dst_img = $src_img;
        }
    } else {
        $dst_img = $src_img;
    }

    // Save it!

    if (!empty($modSettings['avatar_download_png'])) {
        imagepng($dst_img, $destName);
    } else {
        imagejpeg($dst_img, $destName, 65);
    }

    // Free the memory.

    imagedestroy($src_img);

    if ($dst_img != $src_img) {
        imagedestroy($dst_img);
    }
}

function imagecopyresamplebicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
{
    $palsize = imagecolorstotal($src_img);

    for ($i = 0; $i < $palsize; $i++) {
        $colors = imagecolorsforindex($src_img, $i);

        imagecolorallocate($dst_img, $colors['red'], $colors['green'], $colors['blue']);
    }

    $scaleX = ($src_w - 1) / $dst_w;

    $scaleY = ($src_h - 1) / $dst_h;

    $scaleX2 = (int)$scaleX / 2;

    $scaleY2 = (int)$scaleY / 2;

    for ($j = $src_y; $j < $dst_h; $j++) {
        $sY = (int)$j * $scaleY;

        $y13 = $sY + $scaleY2;

        for ($i = $src_x; $i < $dst_w; $i++) {
            $sX = (int)$i * $scaleX;

            $x34 = $sX + $scaleX2;

            $color1 = imagecolorsforindex($src_img, imagecolorat($src_img, $sX, $y13));

            $color2 = imagecolorsforindex($src_img, imagecolorat($src_img, $sX, $sY));

            $color3 = imagecolorsforindex($src_img, imagecolorat($src_img, $x34, $y13));

            $color4 = imagecolorsforindex($src_img, imagecolorat($src_img, $x34, $sY));

            $red = ($color1['red'] + $color2['red'] + $color3['red'] + $color4['red']) / 4;

            $green = ($color1['green'] + $color2['green'] + $color3['green'] + $color4['green']) / 4;

            $blue = ($color1['blue'] + $color2['blue'] + $color3['blue'] + $color4['blue']) / 4;

            $color = imagecolorresolve($dst_img, $red, $green, $blue);

            if (-1 == $color) {
                if ($palsize++ < 256) {
                    imagecolorallocate($dst_img, $red, $green, $blue);
                }

                $color = imagecolorclosest($dst_img, $red, $green, $blue);
            }

            imagesetpixel($dst_img, $i + $dst_x - $src_x, $j + $dst_y - $src_y, $color);
        }
    }
}

if (!function_exists('imagecreatefrombmp')) {
    function imagecreatefrombmp($filename)
    {
        global $gd2;

        $fp = fopen($filename, 'rb');

        $errors = error_reporting(0);

        $header = unpack('vtype/Vsize/Vreserved/Voffset', fread($fp, 14));

        $info = unpack('Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vcolorimportant', fread($fp, 40));

        if (0x4D42 != $header['type']) {
            false;
        }

        if ($gd2) {
            $dst_img = imagecreatetruecolor($info['width'], $info['height']);
        } else {
            $dst_img = imagecreate($info['width'], $info['height']);
        }

        $palette_size = $header['offset'] - 54;

        $info['ncolor'] = $palette_size / 4;

        $palette = [];

        $palettedata = fread($fp, $palette_size);

        $n = 0;

        for ($j = 0; $j < $palette_size; $j++) {
            $b = ord($palettedata[$j++]);

            $g = ord($palettedata[$j++]);

            $r = ord($palettedata[$j++]);

            $palette[$n++] = imagecolorallocate($dst_img, $r, $g, $b);
        }

        $scan_line_size = ($info['bits'] * $info['width'] + 7) >> 3;

        $scan_line_align = $scan_line_size & 3 ? 4 - ($scan_line_size & 3) : 0;

        for ($y = 0, $l = $info['height'] - 1; $y < $info['height']; $y++, $l--) {
            fseek($fp, $header['offset'] + ($scan_line_size + $scan_line_align) * $l);

            $scan_line = fread($fp, $scan_line_size);

            if (mb_strlen($scan_line) < $scan_line_size) {
                continue;
            }

            if (32 == $info['bits']) {
                $x = 0;

                for ($j = 0; $j < $scan_line_size; $x++) {
                    $b = ord($scan_line[$j++]);

                    $g = ord($scan_line[$j++]);

                    $r = ord($scan_line[$j++]);

                    $j++;

                    $color = imagecolorexact($dst_img, $r, $g, $b);

                    if (-1 == $color) {
                        $color = imagecolorallocate($dst_img, $r, $g, $b);

                        // Gah!  Out of colors?  Stupid GD 1... try anyhow.

                        if (-1 == $color) {
                            $color = imagecolorclosest($dst_img, $r, $g, $b);
                        }
                    }

                    imagesetpixel($dst_img, $x, $y, $color);
                }
            } elseif (24 == $info['bits']) {
                $x = 0;

                for ($j = 0; $j < $scan_line_size; $x++) {
                    $b = ord($scan_line[$j++]);

                    $g = ord($scan_line[$j++]);

                    $r = ord($scan_line[$j++]);

                    $color = imagecolorexact($dst_img, $r, $g, $b);

                    if (-1 == $color) {
                        $color = imagecolorallocate($dst_img, $r, $g, $b);

                        // Gah!  Out of colors?  Stupid GD 1... try anyhow.

                        if (-1 == $color) {
                            $color = imagecolorclosest($dst_img, $r, $g, $b);
                        }
                    }

                    imagesetpixel($dst_img, $x, $y, $color);
                }
            } elseif (16 == $info['bits']) {
                $x = 0;

                for ($j = 0; $j < $scan_line_size; $x++) {
                    $b1 = ord($scan_line[$j++]);

                    $b2 = ord($scan_line[$j++]);

                    $word = $b2 * 256 + $b1;

                    $b = (($word & 31) * 255) / 31;

                    $g = ((($word >> 5) & 31) * 255) / 31;

                    $r = ((($word >> 10) & 31) * 255) / 31;

                    // Scale the image colors up properly.

                    $color = imagecolorexact($dst_img, $r, $g, $b);

                    if (-1 == $color) {
                        $color = imagecolorallocate($dst_img, $r, $g, $b);

                        // Gah!  Out of colors?  Stupid GD 1... try anyhow.

                        if (-1 == $color) {
                            $color = imagecolorclosest($dst_img, $r, $g, $b);
                        }
                    }

                    imagesetpixel($dst_img, $x, $y, $color);
                }
            } elseif (8 == $info['bits']) {
                $x = 0;

                for ($j = 0; $j < $scan_line_size; $x++) {
                    imagesetpixel($dst_img, $x, $y, $palette[ord($scan_line[$j++])]);
                }
            } elseif (4 == $info['bits']) {
                $x = 0;

                for ($j = 0; $j < $scan_line_size; $x++) {
                    $byte = ord($scan_line[$j++]);

                    imagesetpixel($dst_img, $x, $y, $palette[(int)($byte / 16)]);

                    if (++$x < $info['width']) {
                        imagesetpixel($dst_img, $x, $y, $palette[$byte & 15]);
                    }
                }
            }  

            // Sorry, I'm just not going to do monochrome :P.
        }

        fclose($fp);

        error_reporting($errors);

        return $dst_img;
    }
}

function gif_loadFile($lpszFileName, $iIndex = 0)
{
    $gif = new gif_file();

    if (!$gif->loadFile($lpszFileName, $iIndex)) {
        return false;
    }

    return $gif;
}

function gif_outputAsPng($gif, $lpszFileName, $background_color = -1)
{
    if (!isset($gif) || 'cgif' != @get_class($gif) || !$gif->loaded || '' == $lpszFileName) {
        return false;
    }

    $fd = $gif->get_png_data($background_color);

    if (mb_strlen($fd) <= 0) {
        return false;
    }

    if (!($fh = @fopen($lpszFileName, 'wb'))) {
        return false;
    }

    @fwrite($fh, $fd, mb_strlen($fd));

    @fflush($fh);

    @fclose($fh);

    return true;
}

class gif_lzw_compression
{
    public $MAX_LZW_BITS;

    public $Fresh;

    public $CodeSize;

    public $SetCodeSize;

    public $MaxCode;

    public $MaxCodeSize;

    public $FirstCode;

    public $OldCode;

    public $ClearCode;

    public $EndCode;

    public $Next;

    public $Vals;

    public $Stack;

    public $sp;

    public $Buf;

    public $CurBit;

    public $LastBit;

    public $Done;

    public $LastByte;

    public function __construct()
    {
        $this->MAX_LZW_BITS = 12;

        unset($this->Next);

        unset($this->Vals);

        unset($this->Stack);

        unset($this->Buf);

        $this->Next = range(0, (1 << $this->MAX_LZW_BITS) - 1);

        $this->Vals = range(0, (1 << $this->MAX_LZW_BITS) - 1);

        $this->Stack = range(0, (1 << ($this->MAX_LZW_BITS + 1)) - 1);

        $this->Buf = range(0, 279);
    }

    public function decompress($data, $datLen)
    {
        $stLen = mb_strlen($data);

        $datLen = 0;

        $ret = '';

        $this->LZWCommand($data, true);

        while (($iIndex = $this->LZWCommand($data, false)) >= 0) {
            $ret .= chr($iIndex);
        }

        $datLen = $stLen - mb_strlen($data);

        if (-2 != $iIndex) {
            return false;
        }

        return $ret;
    }

    public function LZWCommand(&$data, $bInit)
    {
        if ($bInit) {
            $this->SetCodeSize = ord($data[0]);

            $data = mb_substr($data, 1);

            $this->CodeSize = $this->SetCodeSize + 1;

            $this->ClearCode = 1 << $this->SetCodeSize;

            $this->EndCode = $this->ClearCode + 1;

            $this->MaxCode = $this->ClearCode + 2;

            $this->MaxCodeSize = $this->ClearCode << 1;

            $this->GetCode($data, $bInit);

            $this->Fresh = 1;

            for ($i = 0; $i < $this->ClearCode; $i++) {
                $this->Next[$i] = 0;

                $this->Vals[$i] = $i;
            }

            for (; $i < (1 << $this->MAX_LZW_BITS); $i++) {
                $this->Next[$i] = 0;

                $this->Vals[$i] = 0;
            }

            $this->sp = 0;

            return 1;
        }

        if ($this->Fresh) {
            $this->Fresh = 0;

            do {
                $this->FirstCode = $this->GetCode($data, $bInit);

                $this->OldCode = $this->FirstCode;
            } while ($this->FirstCode == $this->ClearCode);

            return $this->FirstCode;
        }

        if ($this->sp > 0) {
            $this->sp--;

            return $this->Stack[$this->sp];
        }

        while (($Code = $this->GetCode($data, $bInit)) >= 0) {
            if ($Code == $this->ClearCode) {
                for ($i = 0; $i < $this->ClearCode; $i++) {
                    $this->Next[$i] = 0;

                    $this->Vals[$i] = $i;
                }

                for (; $i < (1 << $this->MAX_LZW_BITS); $i++) {
                    $this->Next[$i] = 0;

                    $this->Vals[$i] = 0;
                }

                $this->CodeSize = $this->SetCodeSize + 1;

                $this->MaxCodeSize = $this->ClearCode << 1;

                $this->MaxCode = $this->ClearCode + 2;

                $this->sp = 0;

                $this->FirstCode = $this->GetCode($data, $bInit);

                $this->OldCode = $this->FirstCode;

                return $this->FirstCode;
            }

            if ($Code == $this->EndCode) {
                return -2;
            }

            $InCode = $Code;

            if ($Code >= $this->MaxCode) {
                $this->Stack[$this->sp] = $this->FirstCode;

                $this->sp++;

                $Code = $this->OldCode;
            }

            while ($Code >= $this->ClearCode) {
                $this->Stack[$this->sp] = $this->Vals[$Code];

                $this->sp++;

                if ($Code == $this->Next[$Code]) { // Circular table entry, big GIF Error!
                    return -1;
                }

                $Code = $this->Next[$Code];
            }

            $this->FirstCode = $this->Vals[$Code];

            $this->Stack[$this->sp] = $this->FirstCode;

            $this->sp++;

            if (($Code = $this->MaxCode) < (1 << $this->MAX_LZW_BITS)) {
                $this->Next[$Code] = $this->OldCode;

                $this->Vals[$Code] = $this->FirstCode;

                $this->MaxCode++;

                if (($this->MaxCode >= $this->MaxCodeSize) && ($this->MaxCodeSize < (1 << $this->MAX_LZW_BITS))) {
                    $this->MaxCodeSize *= 2;

                    $this->CodeSize++;
                }
            }

            $this->OldCode = $InCode;

            if ($this->sp > 0) {
                $this->sp--;

                return $this->Stack[$this->sp];
            }
        }

        return $Code;
    }

    public function GetCode(&$data, $bInit)
    {
        if ($bInit) {
            $this->CurBit = 0;

            $this->LastBit = 0;

            $this->Done = 0;

            $this->LastByte = 2;

            return 1;
        }

        if (($this->CurBit + $this->CodeSize) >= $this->LastBit) {
            if ($this->Done) {
                // Ran off the end of my bits...

                if ($this->CurBit >= $this->LastBit) {
                    return 0;
                }

                return -1;
            }

            $this->Buf[0] = $this->Buf[$this->LastByte - 2];

            $this->Buf[1] = $this->Buf[$this->LastByte - 1];

            $count = ord($data[0]);

            $data = mb_substr($data, 1);

            if ($count) {
                for ($i = 0; $i < $count; $i++) {
                    $this->Buf[2 + $i] = ord($data[$i]);
                }

                $data = mb_substr($data, $count);
            } else {
                $this->Done = 1;
            }

            $this->LastByte = 2 + $count;

            $this->CurBit = ($this->CurBit - $this->LastBit) + 16;

            $this->LastBit = (2 + $count) << 3;
        }

        $iRet = 0;

        for ($i = $this->CurBit, $j = 0; $j < $this->CodeSize; $i++, $j++) {
            $iRet |= (($this->Buf[(int)($i / 8)] & (1 << ($i % 8))) != 0) << $j;
        }

        $this->CurBit += $this->CodeSize;

        return $iRet;
    }
}

class gif_color_table
{
    public $m_nColors;

    public $m_arColors;

    public function __construct()
    {
        unset($this->m_nColors);

        unset($this->m_arColors);
    }

    public function load($lpData, $num)
    {
        $this->m_nColors = 0;

        $this->m_arColors = [];

        for ($i = 0; $i < $num; $i++) {
            $rgb = mb_substr($lpData, $i * 3, 3);

            if (mb_strlen($rgb) < 3) {
                return false;
            }

            $this->m_arColors[] = (ord($rgb[2]) << 16) + (ord($rgb[1]) << 8) + ord($rgb[0]);

            $this->m_nColors++;
        }

        return true;
    }

    public function toString()
    {
        $ret = '';

        for ($i = 0; $i < $this->m_nColors; $i++) {
            $ret .= chr(($this->m_arColors[$i] & 0x000000FF)) . // R
                    chr(($this->m_arColors[$i] & 0x0000FF00) >> 8) . // G
                    chr(($this->m_arColors[$i] & 0x00FF0000) >> 16);  // B
        }

        return $ret;
    }

    public function colorIndex($rgb)
    {
        $rgb = (int)$rgb & 0xFFFFFF;

        $r1 = ($rgb & 0x0000FF);

        $g1 = ($rgb & 0x00FF00) >> 8;

        $b1 = ($rgb & 0xFF0000) >> 16;

        $idx = -1;

        for ($i = 0; $i < $this->m_nColors; $i++) {
            $r2 = ($this->m_arColors[$i] & 0x000000FF);

            $g2 = ($this->m_arColors[$i] & 0x0000FF00) >> 8;

            $b2 = ($this->m_arColors[$i] & 0x00FF0000) >> 16;

            $d = abs($r2 - $r1) + abs($g2 - $g1) + abs($b2 - $b1);

            if ((-1 == $idx) || ($d < $dif)) {
                $idx = $i;

                $dif = $d;
            }
        }

        return $idx;
    }
}

class gif_file_header
{
    public $m_lpVer;

    public $m_nWidth;

    public $m_nHeight;

    public $m_bGlobalClr;

    public $m_nColorRes;

    public $m_bSorted;

    public $m_nTableSize;

    public $m_nBgColor;

    public $m_nPixelRatio;

    public $m_colorTable;

    public function __construct()
    {
        unset($this->m_lpVer);

        unset($this->m_nWidth);

        unset($this->m_nHeight);

        unset($this->m_bGlobalClr);

        unset($this->m_nColorRes);

        unset($this->m_bSorted);

        unset($this->m_nTableSize);

        unset($this->m_nBgColor);

        unset($this->m_nPixelRatio);

        unset($this->m_colorTable);
    }

    public function load($lpData, $hdrLen)
    {
        $hdrLen = 0;

        $this->m_lpVer = mb_substr($lpData, 0, 6);

        if (('GIF87a' != $this->m_lpVer) && ('GIF89a' != $this->m_lpVer)) {
            return false;
        }

        [$this->m_nWidth, $this->m_nHeight] = array_values(unpack('v2', mb_substr($lpData, 6, 4)));

        if (!$this->m_nWidth || !$this->m_nHeight) {
            return false;
        }

        $b = ord(mb_substr($lpData, 10, 1));

        $this->m_bGlobalClr = ($b & 0x80) ? true : false;

        $this->m_nColorRes = ($b & 0x70) >> 4;

        $this->m_bSorted = ($b & 0x08) ? true : false;

        $this->m_nTableSize = 2 << ($b & 0x07);

        $this->m_nBgColor = ord(mb_substr($lpData, 11, 1));

        $this->m_nPixelRatio = ord(mb_substr($lpData, 12, 1));

        $hdrLen = 13;

        if ($this->m_bGlobalClr) {
            $this->m_colorTable = new gif_color_table();

            if (!$this->m_colorTable->load(mb_substr($lpData, $hdrLen), $this->m_nTableSize)) {
                return false;
            }

            $hdrLen += 3 * $this->m_nTableSize;
        }

        return true;
    }
}

class gif_image_header
{
    public $m_nLeft;

    public $m_nTop;

    public $m_nWidth;

    public $m_nHeight;

    public $m_bLocalClr;

    public $m_bInterlace;

    public $m_bSorted;

    public $m_nTableSize;

    public $m_colorTable;

    public function __construct()
    {
        unset($this->m_nLeft);

        unset($this->m_nTop);

        unset($this->m_nWidth);

        unset($this->m_nHeight);

        unset($this->m_bLocalClr);

        unset($this->m_bInterlace);

        unset($this->m_bSorted);

        unset($this->m_nTableSize);

        unset($this->m_colorTable);
    }

    public function load($lpData, $hdrLen)
    {
        $hdrLen = 0;

        // Get the width/height/etc. from the header.

        [$this->m_nLeft, $this->m_nTop, $this->m_nWidth, $this->m_nHeight] = array_values(unpack('v4', mb_substr($lpData, 0, 8)));

        if (!$this->m_nWidth || !$this->m_nHeight) {
            return false;
        }

        $b = ord($lpData[8]);

        $this->m_bLocalClr = ($b & 0x80) ? true : false;

        $this->m_bInterlace = ($b & 0x40) ? true : false;

        $this->m_bSorted = ($b & 0x20) ? true : false;

        $this->m_nTableSize = 2 << ($b & 0x07);

        $hdrLen = 9;

        if ($this->m_bLocalClr) {
            $this->m_colorTable = new gif_color_table();

            if (!$this->m_colorTable->load(mb_substr($lpData, $hdrLen), $this->m_nTableSize)) {
                return false;
            }

            $hdrLen += 3 * $this->m_nTableSize;
        }

        return true;
    }
}

class gif_image
{
    public $m_disp;

    public $m_bUser;

    public $m_bTrans;

    public $m_nDelay;

    public $m_nTrans;

    public $m_lpComm;

    public $m_gih;

    public $m_data;

    public $m_lzw;

    public function __construct()
    {
        unset($this->m_disp);

        unset($this->m_bUser);

        unset($this->m_nDelay);

        unset($this->m_nTrans);

        unset($this->m_lpComm);

        unset($this->m_data);

        $this->m_gih = new gif_image_header();

        $this->m_lzw = new gif_lzw_compression();
    }

    public function load($data, $datLen)
    {
        $datLen = 0;

        while (true) {
            $b = ord($data[0]);

            $data = mb_substr($data, 1);

            $datLen++;

            switch ($b) {
                // Extension...
                case 0x21:
                    if (!$this->skipExt($data, $len = 0)) {
                        return false;
                    }

                    $datLen += $len;
                    break;
                // Image...
                case 0x2C:
                    // Load the header and color table.
                    if (!$this->m_gih->load($data, $len = 0)) {
                        return false;
                    }

                    $data = mb_substr($data, $len);
                    $datLen += $len;

                    // Decompress the data, and ride on home ;).
                    if (!($this->m_data = $this->m_lzw->decompress($data, $len = 0))) {
                        return false;
                    }

                    $data = mb_substr($data, $len);
                    $datLen += $len;

                    if ($this->m_gih->m_bInterlace) {
                        $this->deInterlace();
                    }

                    return true;
                case 0x3B: // EOF
                default:
                    return false;
            }
        }

        return false;
    }

    public function skipExt(&$data, $extLen)
    {
        $extLen = 0;

        $b = ord($data[0]);

        $data = mb_substr($data, 1);

        $extLen++;

        switch ($b) {
            // Graphic Control...
            case 0xF9:
                $b = ord($data[1]);
                $this->m_disp = ($b & 0x1C) >> 2;
                $this->m_bUser = ($b & 0x02) ? true : false;
                $this->m_bTrans = ($b & 0x01) ? true : false;
                [$this->m_nDelay] = array_values(unpack('v', mb_substr($data, 2, 2)));
                $this->m_nTrans = ord($data[4]);
                break;
            // Comment...
            case 0xFE:
                $this->m_lpComm = mb_substr($data, 1, ord($data[0]));
                break;
            // Plain text...
            case 0x01:
                break;
            // Application...
            case 0xFF:
                break;
        }

        // Skip default as defs may change.

        $b = ord($data[0]);

        $data = mb_substr($data, 1);

        $extLen++;

        while ($b > 0) {
            $data = mb_substr($data, $b);

            $extLen += $b;

            $b = ord($data[0]);

            $data = mb_substr($data, 1);

            $extLen++;
        }

        return true;
    }

    public function deInterlace()
    {
        $data = $this->m_data;

        for ($i = 0; $i < 4; $i++) {
            switch ($i) {
                case 0:
                    $s = 8;
                    $y = 0;
                    break;
                case 1:
                    $s = 8;
                    $y = 4;
                    break;
                case 2:
                    $s = 4;
                    $y = 2;
                    break;
                case 3:
                    $s = 2;
                    $y = 1;
                    break;
            }

            for (; $y < $this->m_gih->m_nHeight; $y += $s) {
                $lne = mb_substr($this->m_data, 0, $this->m_gih->m_nWidth);

                $this->m_data = mb_substr($this->m_data, $this->m_gih->m_nWidth);

                $data = mb_substr($data, 0, $y * $this->m_gih->m_nWidth) . $lne . mb_substr($data, ($y + 1) * $this->m_gih->m_nWidth);
            }
        }

        $this->m_data = $data;
    }
}

class gif_file
{
    public $header;

    public $image;

    public $data = '';

    public $loaded = false;

    public function __construct()
    {
        $this->header = new gif_file_header();

        $this->image = new gif_image();
    }

    public function loadFile($filename, $iIndex)
    {
        if ($iIndex < 0) {
            return false;
        }

        $this->data = @file_get_contents($filename);

        if (false === $this->data) {
            return false;
        }

        // Tell the header to load up....

        if (!$this->header->load($this->data, $len = 0)) {
            return false;
        }

        $this->data = mb_substr($this->data, $len);

        // Keep reading (at least once) so we get to the actual image we're looking for.

        for ($j = 0; $j <= $iIndex; $j++) {
            if (!$this->image->load($this->data, $imgLen = 0)) {
                return false;
            }

            $this->data = mb_substr($this->data, $imgLen);
        }

        $this->loaded = true;

        return true;
    }

    public function get_png_data($background_color)
    {
        if (!$this->loaded) {
            return false;
        }

        // Prepare the color table.

        if ($this->image->m_gih->m_bLocalClr) {
            $colors = $this->image->m_gih->m_nTableSize;

            $pal = $this->image->m_gih->m_colorTable->toString();

            if (-1 != $background_color) {
                $background_color = $this->image->m_gih->m_colorTable->colorIndex($background_color);
            }
        } elseif ($this->header->m_bGlobalClr) {
            $colors = $this->header->m_nTableSize;

            $pal = $this->header->m_colorTable->toString();

            if (-1 != $background_color) {
                $background_color = $this->header->m_colorTable->colorIndex($background_color);
            }
        } else {
            $colors = 0;

            $background_color = -1;
        }

        if (-1 == $background_color) {
            $background_color = $this->header->m_nBgColor;
        }

        $data = &$this->image->m_data;

        $header = &$this->image->m_gih;

        $i = 0;

        $bmp = '';

        // Prepare the bitmap itself.

        for ($y = 0; $y < $this->header->m_nHeight; $y++) {
            $bmp .= "\x00";

            for ($x = 0; $x < $this->header->m_nWidth; $x++, $i++) {
                // Is this in the proper range?  If so, get the specific pixel data...

                if ($x >= $header->m_nLeft && $y >= $header->m_nTop && $x < ($header->m_nLeft + $header->m_nWidth) && $y < ($header->m_nTop + $header->m_nHeight)) {
                    $bmp .= $data[$i];
                } // Otherwise, this is background...

                else {
                    $bmp .= chr($background_color);
                }
            }
        }

        $bmp = gzcompress($bmp, 9);

        // Output the basic signature first of all.

        $out = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";

        // Now, we want the header...

        $out .= "\x00\x00\x00\x0D";

        $tmp = 'IHDR' . pack('N', (int)$this->header->m_nWidth) . pack('N', (int)$this->header->m_nHeight) . "\x08\x03\x00\x00\x00";

        $out .= $tmp . pack('N', crc32($tmp));

        // The palette, assuming we have one to speak of...

        if ($colors > 0) {
            $out .= pack('N', (int)$colors * 3);

            $tmp = 'PLTE' . $pal;

            $out .= $tmp . pack('N', crc32($tmp));
        }

        // Do we have any transparency we want to make available?

        if ($this->image->m_bTrans && $colors > 0) {
            $out .= pack('N', (int)$colors);

            $tmp = 'tRNS';

            // Stick each color on - full transparency or none.

            for ($i = 0; $i < $colors; $i++) {
                $tmp .= $i == $this->image->m_nTrans ? "\x00" : "\xFF";
            }

            $out .= $tmp . pack('N', crc32($tmp));
        }

        // Here's the data itself!

        $out .= pack('N', mb_strlen($bmp));

        $tmp = 'IDAT' . $bmp;

        $out .= $tmp . pack('N', crc32($tmp));

        // EOF marker...

        $out .= "\x00\x00\x00\x00IEND\xAE\x42\x60\x82";

        return $out;
    }
}

// Create the image for the visual verification code.
function showCodeImage($code)
{
    global $settings, $user_info, $modSettings;

    // What type are we going to be doing?

    $imageType = empty($modSettings['disable_visual_verification']) ? 0 : $modSettings['disable_visual_verification'];

    // Special case to allow the admin center to show samples.

    if ($user_info['is_admin'] && isset($_GET['type'])) {
        $imageType = (int)$_GET['type'];
    } // Just incase PM is on, reg is off.

    elseif (1 == $imageType) {
        $imageType = 0;
    }

    // Some quick references for what we do.

    // Do we show no, low or high noise?

    $noiseType = 0 == $imageType ? 'low' : (4 == $imageType ? 'high' : 'none');

    // Can we have more than one font in use?

    $varyFonts = 4 == $imageType ? true : false;

    // Just a plain white background?

    $simpleBGColor = 4 != $imageType ? true : false;

    // Plain black foreground?

    $simpleFGColor = 1 == $imageType ? true : false;

    // High much to rotate each character.

    $rotationType = 2 == $imageType ? 'none' : (4 != $imageType ? 'high' : 'low');

    // Do we show some characters inversed?

    $showReverseChars = 4 == $imageType ? true : false;

    // Special case for not showing any characters.

    $disableChars = 1 == $imageType ? true : false;

    // What do we do with the font colours. Are they one color, close to one color or random?

    $fontColorType = 2 == $imageType ? 'plain' : (4 == $imageType ? 'random' : 'cyclic');

    // Are the fonts random sizes?

    $fontSizeRandom = 4 == $imageType ? true : false;

    // How much space between characters?

    $fontHorSpace = 4 == $imageType ? 'high' : (2 == $imageType ? 'medium' : 'minus');

    // Where do characters sit on the image? (Fixed position or random/very random)

    $fontVerPos = 2 == $imageType ? 'fixed' : (4 == $imageType ? 'vrandom' : 'random');

    // Make font semi-transparent?

    $fontTrans = 3 == $imageType || 0 == $imageType ? true : false;

    // Give the image a border?

    $hasBorder = $simpleBGColor;

    // Is this GD2? Needed for pixel size.

    $testGD = get_extension_funcs('gd');

    $gd2 = in_array('imagecreatetruecolor', $testGD, true) && function_exists('imagecreatetruecolor');

    unset($testGD);

    // The amount of pixels inbetween characters.

    $character_spacing = 1;

    // What color is the background - generally white unless we're on "hard".

    if ($simpleBGColor) {
        $background_color = [255, 255, 255];
    } else {
        $background_color = $settings['verification_background'] ?? [236, 237, 243];
    }

    // The color of the characters shown (red, green, blue).

    if ($simpleFGColor) {
        $foreground_color = [0, 0, 0];
    } else {
        $foreground_color = $settings['verification_foreground'] ?? [64, 101, 136];
    }

    if (!is_dir($settings['default_theme_dir'] . '/fonts')) {
        return false;
    }

    // Get a list of the available fonts.

    $font_dir = dir($settings['default_theme_dir'] . '/fonts');

    $font_list = [];

    $ttfont_list = [];

    while ($entry = $font_dir->read()) {
        if (1 === preg_match('~^(.+)\.gdf$~', $entry, $matches)) {
            $font_list[] = $entry;
        } elseif (1 === preg_match('~^(.+)\.ttf$~', $entry, $matches)) {
            $ttfont_list[] = $entry;
        }
    }

    if (empty($font_list)) {
        return false;
    }

    // For non-hard things don't even change fonts.

    if (!$varyFonts) {
        $font_list = [$font_list[0]];

        // Try use Screenge if we can - it looks good!

        if (in_array('Screenge.ttf', $ttfont_list, true)) {
            $ttfont_list = ['Screenge.ttf'];
        } else {
            $ttfont_list = empty($ttfont_list) ? [] : [$ttfont_list[0]];
        }
    }

    // Create a list of characters to be shown.

    $characters = [];

    $loaded_fonts = [];

    for ($i = 0, $iMax = mb_strlen($code); $i < $iMax; $i++) {
        $characters[$i] = [
            'id' => $code[$i],
            'font' => array_rand($font_list),
        ];

        $loaded_fonts[$characters[$i]['font']] = null;
    }

    // Load all fonts and determine the maximum font height.

    foreach ($loaded_fonts as $font_index => $dummy) {
        $loaded_fonts[$font_index] = imageloadfont($settings['default_theme_dir'] . '/fonts/' . $font_list[$font_index]);
    }

    // Determine the dimensions of each character.

    $total_width = $character_spacing * mb_strlen($code) + 20;

    $max_height = 0;

    foreach ($characters as $char_index => $character) {
        $characters[$char_index]['width'] = imagefontwidth($loaded_fonts[$character['font']]);

        $characters[$char_index]['height'] = imagefontheight($loaded_fonts[$character['font']]);

        $max_height = max($characters[$char_index]['height'] + 5, $max_height);

        $total_width += $characters[$char_index]['width'];
    }

    // Create an image.

    $code_image = imagecreate($total_width, $max_height);

    // Draw the background.

    $bg_color = imagecolorallocate($code_image, $background_color[0], $background_color[1], $background_color[2]);

    imagefilledrectangle($code_image, 0, 0, $total_width - 1, $max_height - 1, $bg_color);

    // Randomize the foreground color a little.

    for ($i = 0; $i < 3; $i++) {
        $foreground_color[$i] = mt_rand(max($foreground_color[$i] - 3, 0), min($foreground_color[$i] + 3, 255));
    }

    $fg_color = imagecolorallocate($code_image, $foreground_color[0], $foreground_color[1], $foreground_color[2]);

    // Color for the dots.

    for ($i = 0; $i < 3; $i++) {
        $dotbgcolor[$i] = $background_color[$i] < $foreground_color[$i] ? mt_rand(0, max($foreground_color[$i] - 20, 0)) : mt_rand(min($foreground_color[$i] + 20, 255), 255);
    }

    $randomness_color = imagecolorallocate($code_image, $dotbgcolor[0], $dotbgcolor[1], $dotbgcolor[2]);

    // Fill in the characters.

    if (!$disableChars) {
        $cur_x = 0;

        foreach ($characters as $char_index => $character) {
            // Can we use true type fonts?

            $can_do_ttf = function_exists('imagettftext');

            // How much rotation will we give?

            if ('none' == $rotationType) {
                $angle = 0;
            } else {
                $angle = mt_rand(-100, 100) / ('high' == $rotationType ? 6 : 10);
            }

            // What colour shall we do it?

            if ('cyclic' == $fontColorType) {
                // Here we'll pick from a set of acceptance types.

                $colours = [
                    [10, 120, 95],
                    [46, 81, 29],
                    [4, 22, 154],
                    [131, 9, 130],
                    [0, 0, 0],
                    [143, 39, 31],
                ];

                if (!isset($last_index)) {
                    $last_index = -1;
                }

                $new_index = $last_index;

                while ($last_index == $new_index) {
                    $new_index = mt_rand(0, count($colours) - 1);
                }

                $char_fg_color = $colours[$new_index];

                $last_index = $new_index;
            } elseif ('random' == $fontColorType) {
                $char_fg_color = [mt_rand(max($foreground_color[0] - 2, 0), $foreground_color[0]), mt_rand(max($foreground_color[1] - 2, 0), $foreground_color[1]), mt_rand(max($foreground_color[2] - 2, 0), $foreground_color[2])];
            } else {
                $char_fg_color = [$foreground_color[0], $foreground_color[1], $foreground_color[2]];
            }

            if (!empty($can_do_ttf)) {
                // GD2 handles font size differently.

                if ($fontSizeRandom) {
                    $font_size = $gd2 ? mt_rand(17, 19) : mt_rand(18, 25);
                } else {
                    $font_size = $gd2 ? 18 : 24;
                }

                // Work out the sizes - also fix the character width cause TTF not quite so wide!

                $font_x = 'minus' == $fontHorSpace && $cur_x > 0 ? $cur_x - 3 : $cur_x + 5;

                $font_y = $max_height - ('vrandom' == $fontVerPos ? mt_rand(2, 8) : ('random' == $fontVerPos ? mt_rand(3, 5) : 5));

                // What font face?

                if (!empty($ttfont_list)) {
                    $fontface = $settings['default_theme_dir'] . '/fonts/' . $ttfont_list[mt_rand(0, count($ttfont_list) - 1)];
                }

                // What color are we to do it in?

                $is_reverse = $showReverseChars ? mt_rand(0, 1) : false;

                $char_color = function_exists('imagecolorallocatealpha') && $fontTrans ? imagecolorallocatealpha($code_image, $char_fg_color[0], $char_fg_color[1], $char_fg_color[2], 50) : imagecolorallocate($code_image, $char_fg_color[0], $char_fg_color[1], $char_fg_color[2]);

                $fontcord = @imagettftext($code_image, $font_size, $angle, $font_x, $font_y, $char_color, $fontface, $character['id']);

                if (empty($fontcord)) {
                    $can_do_ttf = false;
                } elseif ($is_reverse) {
                    imagefilledpolygon($code_image, $fontcord, 4, $fg_color);

                    // Put the character back!

                    imagettftext($code_image, $font_size, $angle, $font_x, $font_y, $randomness_color, $fontface, $character['id']);
                }

                if ($can_do_ttf) {
                    $cur_x = max($fontcord[2], $fontcord[4]) + (0 == $angle ? 0 : 3);
                }
            }

            if (!$can_do_ttf) {
                // Rotating the characters a little...

                if (function_exists('imagerotate')) {
                    $char_image = function_exists('imagecreatetruecolor') ? imagecreatetruecolor($character['width'], $character['height']) : imagecreate($character['width'], $character['height']);

                    $char_bgcolor = imagecolorallocate($char_image, $background_color[0], $background_color[1], $background_color[2]);

                    imagefilledrectangle($char_image, 0, 0, $character['width'] - 1, $character['height'] - 1, $char_bgcolor);

                    imagechar($char_image, $loaded_fonts[$character['font']], 0, 0, $character['id'], imagecolorallocate($char_image, $char_fg_color[0], $char_fg_color[1], $char_fg_color[2]));

                    $rotated_char = imagerotate($char_image, mt_rand(-100, 100) / 10, $char_bgcolor);

                    imagecopy($code_image, $rotated_char, $cur_x, 0, 0, 0, $character['width'], $character['height']);

                    imagedestroy($rotated_char);

                    imagedestroy($char_image);
                } // Sorry, no rotation available.

                else {
                    imagechar($code_image, $loaded_fonts[$character['font']], $cur_x, floor(($max_height - $character['height']) / 2), $character['id'], imagecolorallocate($code_image, $char_fg_color[0], $char_fg_color[1], $char_fg_color[2]));
                }

                $cur_x += $character['width'] + $character_spacing;
            }
        }
    } // If disabled just show a cross.

    else {
        imageline($code_image, 0, 0, $total_width, $max_height, $fg_color);

        imageline($code_image, 0, $max_height, $total_width, 0, $fg_color);
    }

    // Make the background color transparent on the hard image.

    if (!$simpleBGColor) {
        imagecolortransparent($code_image, $bg_color);
    }

    if ($hasBorder) {
        imagerectangle($code_image, 0, 0, $total_width - 1, $max_height - 1, $fg_color);
    }

    // Add some noise to the background?

    if ('none' != $noiseType) {
        for ($i = mt_rand(0, 2); $i < $max_height; $i += mt_rand(1, 2)) {
            for ($j = mt_rand(0, 10); $j < $total_width; $j += mt_rand(1, 15)) {
                imagesetpixel($code_image, $j, $i, mt_rand(0, 1) ? $fg_color : $randomness_color);
            }
        }

        // Put in some lines too?

        if ('high' == $noiseType) {
            $num_lines = 2;

            for ($i = 0; $i < $num_lines; $i++) {
                if (mt_rand(0, 1)) {
                    $x1 = mt_rand(0, $total_width);

                    $x2 = mt_rand(0, $total_width);

                    $y1 = 0;

                    $y2 = $max_height;
                } else {
                    $y1 = mt_rand(0, $max_height);

                    $y2 = mt_rand(0, $max_height);

                    $x1 = 0;

                    $x2 = $total_width;
                }

                imageline($code_image, $x1, $y1, $x2, $y2, mt_rand(0, 1) ? $fg_color : $randomness_color);
            }
        }
    }

    // Show the image.

    if (function_exists('imagegif')) {
        header('Content-type: image/gif');

        imagegif($code_image);
    } else {
        header('Content-type: image/png');

        imagepng($code_image);
    }

    // Bail out.

    imagedestroy($code_image);

    die();
}

// Create a letter for the visual verification code.
function showLetterImage($letter)
{
    global $settings;

    if (!is_dir($settings['default_theme_dir'] . '/fonts')) {
        return false;
    }

    // Get a list of the available font directories.

    $font_dir = dir($settings['default_theme_dir'] . '/fonts');

    $font_list = [];

    while ($entry = $font_dir->read()) {
        if ('.' !== $entry[0] && is_dir($settings['default_theme_dir'] . '/fonts/' . $entry) && file_exists($settings['default_theme_dir'] . '/fonts/' . $entry . '.gdf')) {
            $font_list[] = $entry;
        }
    }

    if (empty($font_list)) {
        return false;
    }

    // Pick a random font.

    $random_font = $font_list[array_rand($font_list)];

    // Check if the given letter exists.

    if (!file_exists($settings['default_theme_dir'] . '/fonts/' . $random_font . '/' . $letter . '.gif')) {
        return false;
    }

    // Include it!

    header('Content-type: image/gif');

    include $settings['default_theme_dir'] . '/fonts/' . $random_font . '/' . $letter . '.gif';

    // Nothing more to come.

    die();
}
