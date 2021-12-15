<?php

namespace ContentEgg\application\helpers;

/**
 * ImageHelper class file
 * 
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 * 
 */
class ImageHelper {

    public static function saveImgLocaly($img_uri, $title = '', $check_image_type = true)
    {
        if (!defined('FS_CHMOD_FILE'))
            define('FS_CHMOD_FILE', ( fileperms(ABSPATH . 'index.php') & 0777 | 0644));

        $uploads = \wp_upload_dir();

        $ext = pathinfo(basename($img_uri), PATHINFO_EXTENSION);
        $newfilename = TextHelper::truncate($title);
        $newfilename = TextHelper::rus2latin($newfilename);
        $newfilename = preg_replace('/[^a-zA-Z0-9\-]/', '', $newfilename);
        $newfilename = strtolower($newfilename);
        if (!$newfilename)
            $newfilename = time();

        $response = \wp_remote_get($img_uri, array('timeout' => 3, 'redirection' => 1));
        if (\is_wp_error($response) || (int) \wp_remote_retrieve_response_code($response) !== 200)
            return false;

        if (!$ext)
        {
            $headers = \wp_remote_retrieve_headers($response);
            if (empty($headers['content-type']))
                return false;
            $types = array_search($headers['content-type'], \wp_get_mime_types());
            if (!$types)
                return false;

            $exts = explode('|', $types);
            $ext = $exts[0];
        }

        $newfilename .= '.' . $ext;
        $newfilename = \wp_unique_filename($uploads['path'], $newfilename);

        if ($check_image_type)
        {
            $filetype = \wp_check_filetype($newfilename, null);
            if (substr($filetype['type'], 0, 5) != 'image')
                return false;
        }

        $image_string = \wp_remote_retrieve_body($response);
        $file_path = $uploads['path'] . DIRECTORY_SEPARATOR . $newfilename;
        if (!file_put_contents($file_path, $image_string))
            return false;

        if ($check_image_type)
        {
            if (!self::isImage($file_path))
            {
                @unlink($file_path);
                return false;
            }
        }

        @chmod($file_path, FS_CHMOD_FILE);
        return $newfilename;
    }

    public static function isImage($path)
    {
        $a = getimagesize($path);
        $image_type = $a[2];

        if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)))
        {
            return true;
        }
        return false;
    }

    public static function getFullImgPath($img_path)
    {
        $uploads = \wp_upload_dir();
        return trailingslashit($uploads['basedir']) . $img_path;
    }

}
