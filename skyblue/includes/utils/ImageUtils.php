<?php defined('SKYBLUE') or die('Bad File Request');

/**
 * @version      2.0 2009-04-14 23:50:00 $
 * @package      SkyBlueCanvas
 * @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

/**
 * @author Scott Lewis
 * @date   June 22, 2009
 */

class ImageUtils {

    /**
     * This function determines which of the width or height is larger, 
     * then determines the scale ratio and scales the image values so that
     * the larger of the image dimensions does not exceed the maximum
     * desired dimension.
     * @param array   An array of current array(width, height) values of the image.
     * @param int     The maximum width of the image.
     * @param int     The maximum height of the image.
     * @return array  The width & height of the image
     */
    
    function imageDimsToMaxDim($dims, $maxwidth, $maxheight) {
    
        $maxLoop = 1000;
    
        $width  = $dims[0];
        $height = $dims[1];
        
        /**
         * Determine the maxwidth ratio to the full width
         */
        
        $widthratio = 1;
        if ($width > $maxwidth) {
            $widthratio = $maxwidth/$width;
        }
        
        /**
         * Determine the maxheight ratio to the full height
         */
        
        $heightratio = 1;
        if ($height > $maxheight) {
            $heightratio = $maxheight/$height;
        }
        
        /**
         * Set the scale ratio to the lower of the two ratio
         */
        
        $ratio = $heightratio;
        if ($widthratio < $heightratio) {
            $ratio = $widthratio;
        }
        
        /**
         * Scale the images
         */
        
        $width  = ceil($width * $ratio);
        $height = ceil($height * $ratio);
        
        /**
         * Tweak the scale so the new dims match the max dims exactly
         * If the ratio == 1, no need
         */
        
        if ($ratio != 1) {
            if ($ratio == $heightratio) {
                $n=0;
                if ($height < $maxheight) {
                    while ($height < $maxheight && $n < $maxLoop) {
                        $ratio = $ratio * 1.01;
                        $height = ceil($height * $ratio);
                        $n++;
                    }
                }
            }
            if ($ratio == $widthratio) {
                if ($width < $maxwidth) {
                    $n=0;
                    while ($width < $maxwidth && $n < $maxLoop) {
                        $ratio = $ratio * 1.01;
                        $width = ceil($width * $ratio);
                        $n++;
                    }
                }
            }
        }
        return array($width, $height);
    }
    
    /**
     * Get the width and height of an image
     * @param string   The path to the image.
     * @return array   The width and height of the image
     */
    
    function imageDims($fp) {
        static $dims;
        static $file;
        
        if (!file_exists($fp) || is_dir($fp)) {
            return array(0, 0);
        }
        else if ($file != $fp || !is_array($dims)) {
            $file = $fp;
            $dims = getimagesize($fp);
        }
        return $dims;
    }
    
    /**
     * Get the width and height of an image
     * @param string   The path to the image.
     * @return array   The width and height of the image
     */
    
    function dimensions($fp) {
        static $dims;
        static $file;
        
        if (!file_exists($fp) || is_dir($fp)) {
            return array(0, 0);
        }
        else if ($file != $fp || !is_array($dims)) {
            $file = $fp;
            $dims = getimagesize($fp);
        }
        return $dims;
    }
    
    /**
     * Returns the width of an image.
     * @param string   The path to the image.
     * @return int     The width of the image
     */
    
    function width($fp) {
        return Filter::get(ImageUtils::dimensions($fp), 0, 0);
    }
    
    /**
     * Returns the height of an image.
     * @param string   The path to the image.
     * @return int     The height of the image
     */
    
    function height($fp) {
        return Filter::get(ImageUtils::dimensions($fp), 1, 0);
    }

}