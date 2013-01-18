<?php

/**
 * Histogram class, Histogram.php
 * 
 * @category  classes
 * @author    John Mitros
 * @copyright 2012
 */

class Histogram extends ColorSpaceConversion
{
    /**
     * Initialize variables
     */
    private $_Image = array();
    private $_imageInfo;
    
    public function __construct($image)
    {
        try
        {
            $this->_Image['path'] = $image;
            $this->_imageInfo = getimagesize($this->_Image['path']);
        
            /** Init variables */
            $this->_Image['width']    = $this->_imageInfo[0];
            $this->_Image['height']   = $this->_imageInfo[1];
            $this->_Image['type']     = $this->_imageInfo[2]; // e.g. constant image type IMAGETYPE_JPEG
            $this->_Image['bits']     = $this->_imageInfo['bits'];
            $this->_Image['channels'] = $this->_imageInfo['channels'];
            $this->_Image['mime']     = $this->_imageInfo['mime']; // e.g. => image/jpeg
            
            /** Check memory limit */
            $MB = pow(1024,2);    // number of bytes in 1M
            $K64 = pow(2,16);     // number of bytes in 64K
            $TWEAKFACTOR = 1.8;   // Or whatever works for you
            $memoryNeeded = round( 
                                    ( $this->_Image['width'] * $this->_Image['height'] * 
                                     $this->_Image['bits'] * $this->_Image['channels'] / 8 + $K64 ) * $TWEAKFACTOR
                                 );
            //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
            //Default memory limit is 8MB so well stick with that.
            //To find out what yours is, view your php.ini file.
            $memoryHave = memory_get_usage();
            $memoryLimitMB = (int) ini_get('memory_limit');
            $memoryLimit = $memoryLimitMB * $MB;
            
            if ( function_exists('memory_get_usage') && $memoryHave + $memoryNeeded > $memoryLimit )
            {
               $newLimit = $memoryLimitMB + ceil( ( $memoryHave + $memoryNeeded - $memoryLimit ) / $MB );
               ini_set( 'memory_limit', $newLimit . 'M' );
            }
                
            $this->_Image['pixels'] = $this->_Image['width'] * $this->_Image['height'];
            
            if ( !empty( $this->_Image['width'] ) )
            {
                switch ($this->_Image['type'])
                {
                    case IMAGETYPE_JPEG:
                    $this->_Image['resource'] = imagecreatefromjpeg($this->_Image['path']);
                    break;
                    case IMAGETYPE_PNG:
                    $this->_Image['resource'] = imagecreatefrompng($this->_Image['path']);
                    break;
                    case IMAGETYPE_GIF:
                    $this->_Image['resource'] = imagecreatefromgif($this->_Image['path']);
                    break;
                }
            }
            if (empty($this->_Image['width']) || empty($this->_Image['resource']))
            {
                trigger_error("Error invalid image file name: {$this->_Image['path']}\n", E_USER_ERROR);
                return null;
            }
            
            
            if ($this->_Image['resource'])
            {
                $this->_Image['red'] = '';
                $this->_Image['green'] = '';
                $this->_Image['blue'] = '';
            }
        }
        catch(Exception $e)
        {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }    
    
    /**
     * Generate image histogram
     * 
     * @param
     * @return
     */
    public function generateHistogram($convertToHsv = false)
    {
        //$histo = array_fill(0, 256, null);
        $histo = array();
        
        for ($colindex = 0; $colindex <= 255; $colindex++)
        {
            $this->_Image['blue'][$colindex]['count'] = 0;
            $this->_Image['red'][$colindex]['count'] = 0;
            $this->_Image['green'][$colindex]['count'] = 0;
        }
        for ($x = 0; $x < $this->_Image['width']; $x++)
        {
            for ($y = 0; $y < $this->_Image['height']; $y++)
            {
                $rgb = imagecolorat($this->_Image['resource'], $x, $y);
                //$pix = imagecolorsforindex ($this->_Image['resource'], $rgb);
                // convert to indexed color
                // extract each value for r, g, b
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8)  & 0xFF;
                $b =  $rgb        & 0xFF;
                
                // maybe used later for statistical purposes
                $this->_Image['red'][$r]['count']++;
                $this->_Image['green'][$g]['count']++;
                $this->_Image['blue'][$b]['count']++;
                
                if ($convertToHsv == true)
                {
                    $hsv = $this->rgb2hsv($r, $g, $b);
                    $value = (8 * $hsv['H'] + 4 * $hsv['S'] + 4 * $hsv['V']);
                }
                else
                {
                    // get the Value from the RGB value and convert to greyscale
                    $value = round(( $r + $g + $b) / 3);
                }
                //$histo[$value]++;
                $histo[] = $value;
            }
        }
        imagedestroy($this->_Image['resource']);
        ini_restore('memory_limit');
        return $histo;
    }
    
    public function generateHistogram2D($convertToHsv = false)
    {
        //$histo = array_fill(0, 256, null);
        $histo = array();
        
        for ($colindex = 0; $colindex <= 255; $colindex++)
        {
            $this->_Image['blue'][$colindex]['count'] = 0;
            $this->_Image['red'][$colindex]['count'] = 0;
            $this->_Image['green'][$colindex]['count'] = 0;
        }
        for ($x = 0; $x < $this->_Image['width']; $x++)
        {
            for ($y = 0; $y < $this->_Image['height']; $y++)
            {
                $rgb = imagecolorat($this->_Image['resource'], $x, $y);
                //$pix = imagecolorsforindex ($this->_Image['resource'], $rgb);
                // convert to indexed color
                // extract each value for r, g, b
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8)  & 0xFF;
                $b = ($rgb >> 0)  & 0xFF;
                
                // maybe used later for statistical purposes
                $this->_Image['red'][$r]['count']++;
                $this->_Image['green'][$g]['count']++;
                $this->_Image['blue'][$b]['count']++;
                
                if ($convertToHsv == true)
                {
                    $hsv = $this->rgb2hsv($r, $g, $b);
                    $value = (16 * $hsv['H'] + 4 * $hsv['S'] + 4 * $hsv['V']);
                }
                else
                {   
                    // get the Value from the RGB value and convert to greyscale
                    $value = round(( $r + $g + $b) / 3);
                }
                
                //$histo[$value]++;
                $histo[$x][$y] = $value;
            }
        }
        imagedestroy($this->_Image['resource']);
        ini_restore('memory_limit');
        return $histo;
    }
    
    /**
     * Generate alternate imge histogram
     * 
     * @param
     * @return
     */
    public function generateAlternateHistogram($convertToHsv = false)
    {
        
        # Initialize the histogram counters:
        $histogram = array_fill(0, 256, null);
        //$histogram = array();
        
        # Process every pixel. Get the color components and compute the gray value.
        for ($y = 0; $y < $this->_Image['height']; $y++)
        {
            for ($x = 0; $x < $this->_Image['width']; $x++)
            {
                $pix = imagecolorsforindex($this->_Image['resource'], imagecolorat($this->_Image['resource'], $x, $y));
                if ($convertToHsv == true)
                {
                    $hsv = $this->rgb2hsv($pix['red'], $pix['green'], $pix['blue']);
                    $value = (16 * $hsv['H'] + 4 * $hsv['S'] + 4 * $hsv['V']);
                }
                else
                {
                    $value = (int)((30 * $pix['red'] + 59 * $pix['green'] + 11 * $pix['blue']) / 100); //convert to greyscale
                }
                
                $histogram[$value]++;
                //$histogram[] = $value;
            }
        }
        imagedestroy($this->_Image['resource']);
        ini_restore('memory_limit');
        return $histogram;
    }
}
