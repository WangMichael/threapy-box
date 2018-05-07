<?php

namespace framework\image;

class image implements imageInterface{


    private $MIMEType = Array(
        'png'   => 'image/png',
        'gif'   => 'image/gif',
        'jpg'   => 'image/jpg, image/jpeg, image/pjpeg',
        'jpeg'  => 'image/jpg, image/jpeg, image/pjpeg'
    );


    public function getMiMEType(string $filePath): String
    {
        if(!file_exists($filePath))
            return '';

        if(!function_exists('finfo_open') || (false === $fInfo = finfo_open(FILEINFO_MIME)))
            return '';

        $mime = finfo_file($fInfo, $filePath);
        finfo_close($fInfo);
        return ($temp = strstr($mime, ';', true)) === false ? $mime : $temp;
    }

    public function getExtension(string $MiMEType) : String
    {
        if(!$MiMEType)
            return '';

        foreach($this->MIMEType AS $ext => $MIME){
            if(strpos($MIME, $MiMEType) !== false)
                return $ext;
        }

        return '';
    }


    public function resize_image($file, $w, $h, $crop=FALSE) : bool
    {

        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newWidth = $w;
            $newHeight = $h;
        } else {
            if ($w/$h > $r) {
                $newWidth = $h*$r;
                $newHeight = $h;
            } else {
                $newHeight = $w/$r;
                $newWidth = $w;
            }
        }

        $MIMEType   = $this->getMiMEType($file);
        $extension  = $this->getExtension($MIMEType);
        if(!in_array($extension, array_keys($this->MIMEType))){
            trigger_error(sprintf('Cannot process this %s', $file), E_USER_WARNING);
            return false;
        }

        $fun = 'imagecreatefrom';
        if($extension === 'jpg')
            $extension = 'jpeg';
        $fun .= $extension;
        if(!$src = $fun($file)){
            trigger_error(sprintf('Cannot create an image from %s', $file), E_USER_WARNING);
            return false;
        }

        if(!$dst = imagecreatetruecolor($newWidth, $newHeight)) {
            trigger_error('Cannot Initialize new GD image stream', E_USER_WARNING);
            return false;
        }

        if(!imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)){
            trigger_error('Cannot copy and resize part of an image with resampling', E_USER_WARNING);
            return false;
        }

        $fun = 'image'.$extension;
        if(!$fun($dst, $file)){
            trigger_error(sprintf('Cannot copy the image stream to %s', __METHOD__), E_USER_WARNING);
            return false;
        }

        return true;
    }


    public function getRandomFileName(string $extension): String
    {
        $token      = bin2hex(random_bytes(8));
        $fileName   = $token.'.'.$extension;
        return $fileName;
    }

    public function isImage(string $MIMEType): bool
    {
        if(!$MIMEType)
            return false;

        foreach($this->MIMEType AS $MIME){
            if(strpos($MIME, $MIMEType) !== false)
                return true;
        }

        return false;
    }
}