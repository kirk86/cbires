<?php

/**
 * @author lolkittens
 * @copyright 2013
 */

//package net.semanticmetadata.lire.imageanalysis;

//import net.semanticmetadata.lire.utils.ConversionUtils;
//import net.semanticmetadata.lire.utils.MetricsUtils;
//import net.semanticmetadata.lire.utils.SerializationUtils;

//import java.awt.color.ColorSpace;
//import java.awt.image.BufferedImage;
//import java.awt.image.WritableRaster;
//import java.util.StringTokenizer;

/**
 * This class provides a simple color histogram for content based image retrieval.
 * Number of bins is configurable, histogram is normalized to 8 bit per bin (0-255). <br>
 * Defaults are given in the final fields. Available options are given by the enums.
 */
class SimpleColorHistogram
{
    public static $DEFAULT_NUMBER_OF_BINS = 64;
    public static $DEFAULT_HISTOGRAM_TYPE = 'RGB';
    public static $DEFAULT_DISTANCE_FUNCTION = 'JSD';

    private static $quantTable = array (
            1, 32, 4, 8, 16, 4, 16, 4, 16, 4,            // Hue, Sum - subspace 0,1,2,3,4 for 256 levels
            1, 16, 4, 4, 8, 4, 8, 4, 8, 4,            // Hue, Sum - subspace 0,1,2,3,4 for 128 levels
            1, 8, 4, 4, 4, 4, 8, 2, 8, 1,            // Hue, Sum - subspace 0,1,2,3,4 for  64 levels
            1, 8, 4, 4, 4, 4, 4, 1, 4, 1);           // Hue, Sum - subspace 0,1,2,3,4 for  32 levels

    public static $rgbPalette64 = array(
            array(0, 0, 0),
            array(0, 0, 85),
            array(0, 0, 170),
            array(0, 0, 255),
            array(0, 85, 0),
            array(0, 85, 85),
            array(0, 85, 170),
            array(0, 85, 255),
            array(0, 170, 0),
            array(0, 170, 85),
            array(0, 170, 170),
            array(0, 170, 255),
            array(0, 255, 0),
            array(0, 255, 85),
            array(0, 255, 170),
            array(0, 255, 255),
            array(85, 0, 0),
            array(85, 0, 85),
            array(85, 0, 170),
            array(85, 0, 255),
            array(85, 85, 0),
            array(85, 85, 85),
            array(85, 85, 170),
            array(85, 85, 255),
            array(85, 170, 0),
            array(85, 170, 85),
            array(85, 170, 170),
            array(85, 170, 255),
            array(85, 255, 0),
            array(85, 255, 85),
            array(85, 255, 170),
            array(85, 255, 255),
            array(170, 0, 0),
            array(170, 0, 85),
            array(170, 0, 170),
            array(170, 0, 255),
            array(170, 85, 0),
            array(170, 85, 85),
            array(170, 85, 170),
            array(170, 85, 255),
            array(170, 170, 0),
            array(170, 170, 85),
            array(170, 170, 170),
            array(170, 170, 255),
            array(170, 255, 0),
            array(170, 255, 85),
            array(170, 255, 170),
            array(170, 255, 255),
            array(255, 0, 0),
            array(255, 0, 85),
            array(255, 0, 170),
            array(255, 0, 255),
            array(255, 85, 0),
            array(255, 85, 85),
            array(255, 85, 170),
            array(255, 85, 255),
            array(255, 170, 0),
            array(255, 170, 85),
            array(255, 170, 170),
            array(255, 170, 255),
            array(255, 255, 0),
            array(255, 255, 85),
            array(255, 255, 170),
            array(255, 255, 255)
    );

    // upper borders for quantization.
    public static $quant512 = array(18, 55, 91, 128, 165, 201, 238, 256);

//    public static int[][] rgbPalette512 = new int[512][3];
//
//    public static int[][][] quantTable512 = new int[256][256][256];

//    static {
//        System.out.println("Creating quantization tables ...");
//        int count = 0;
//        for (int i = 0; i < quant512.length; i++) {
//            for (int j = 0; j < quant512.length; j++) {
//                for (int k = 0; k < quant512.length; k++) {
//                    rgbPalette512[count][0] = quant512[i];
//                    rgbPalette512[count][1] = quant512[j];
//                    rgbPalette512[count][2] = quant512[k];
//                    count++;
//                }
//            }
//        }
//
//        // Todo: This is a no go  ... check faster method ...
//        System.out.println("Now the big one ...");
//        for (int i = 0; i < 256; i++) {
//            for (int j = 0; j < 256; j++) {
//                for (int k = 0; k < 256; k++) {
//                    double minDist = Math.abs((rgbPalette512[0][0] - i) + Math.abs((rgbPalette512[0][1] - j)) + Math.abs(rgbPalette512[0][2] - k));
//                    int pos = 0;
//                    for (int l = 1; l < rgbPalette512.length; l++) {
//                        double tmp = Math.abs((rgbPalette512[l][0] - i) + Math.abs((rgbPalette512[l][1] - j)) + Math.abs(rgbPalette512[l][2] - k));
//                        if (tmp <= minDist) {
//                            minDist = tmp;
//                            pos = l;
//                        }
//                        quantTable512[i][j][k] = pos;
//                    }
//                }
//            }
//            System.out.print('.');
//        }
//        System.out.println("static method finished");
//
//    }

    /**
     * Temporary pixel field ... re used for speed and memory issues ...
     */
    //private $pixel = array_fill(0, 3, 0);
    //private static $rgb;
    //private static $histogram;
    private static $histogramType;
    private static $distFunc;


    /**
     * Default constructor
     */
    public function __construct($histogramType = null, $distFunction = null)
    {
        if (is_null($histogramType) && is_null($distFunction))
        {
            self::$histogramType = self::$DEFAULT_HISTOGRAM_TYPE;
            //self::$histogram[] = array_fill(0, self::$DEFAULT_NUMBER_OF_BINS, 0);
            self::$distFunc = self::$DEFAULT_DISTANCE_FUNCTION;
        }
         /**
         * Constructor for selecting different color spaces as well as a different distance function.
         * Histogram has 256 bins.
         *
         * @param histogramType
         * @param distFunction
         */
        else
        {
            self::$histogramType = $histogramType;
            self::$distFunc = $distFunction;
            //self::$histogram = array();
        }
    }
    /**
     * Extracts the color histogram from the given image.
     *
     * @param image
     */
    public function extract($image)
    {
        $histogram = array();
//        if ($image.getColorModel().getColorSpace().getType() != ColorSpace.TYPE_RGB)
//            throw new UnsupportedOperationException("Color space not supported. Only RGB.");
        $img_info = getimagesize($image);
        $img = imagecreatefromjpeg($image);
        for ($x = 0; $x < imagesx($img); $x++)
        {
            for ($y = 0; $y < imagesy($img); $y++)
            {
                //$pxl_color[$x][$y] = imagecolorat($img, $x, $y);
                $rgb = imagecolorsforindex ($img, imagecolorat($img, $x, $y));
                
                if (self::$histogramType == 'HSV')
                {
                    rgb2hsv($rgb['red'], $rgb['green'], $rgb['blue'], $rgb);
                    $histogram[quant($rgb)]++;
                }
                elseif (self::$histogramType == 'Luminance')
                {
                    rgb2yuv($rgb['red'], $rgb['green'], $rgb['blue'], $rgb);
                }
                elseif (self::$histogramType == 'HMMD')
                {
                    $histogram[quantHmmd(rgb2hmmd($rgb['red'], $rgb['green'], $rgb['blue']), self::$DEFAULT_NUMBER_OF_BINS)]++;
                }
                else // RGB 
                    $histogram[self::quant($rgb)]++;
            }
        }
        self::normalize($histogram, $img_info[0] * $img_info[1]);
        
        return $histogram;
    }

//    public function getByteArrayRepresentation() {
//        return SerializationUtils.toByteArray(self::$histogram);
//    }
//
//    public function setByteArrayRepresentation($in) {
//        self::$histogram = SerializationUtils.toIntArray($in);
//    }
//
//    public function setByteArrayRepresentation($in, $offset, $length) {
//        self::$histogram = SerializationUtils.toIntArray($in, $offset, $length);
//    }
//
//    public function getDoubleHistogram() {
//        return ConversionUtils.toDouble(self::$histogram);
//    }

    public static function normalize($histogram, $numPixels) {
        $max = 0;
        for ($i = 0; $i < count($histogram); $i++) {
            $max = max($histogram[$i], $max);
        }
        for ($i = 0; $i < count($histogram); $i++) {
            $histogram[$i] = ($histogram[$i] * 256) / $max;
        }
    }

    public static function quant($rgb)
    {
        if (self::$histogramType == 'HSV')
        {
            $qH = (int) floor($rgb['red'] / 11.25);    // more granularity in color
            if ($qH == 32)
                $qH--;
            
            $qV = $rgb['green'] / 90;
            
            if ($qV == 4)
                $qV--;
            
            $qS = $rgb['blue'] / 25;
            
            if ($qS == 4)
                $qS--;
            
            return $qH * 16 + $qV * 4 + $qS;
        }
        elseif (self::$histogramType == 'HMMD')
        {
            return quantHmmd(rgb2hmmd($rgb['red'], $rgb['green'], $rgb['blue']), 255);
        }
        elseif (self::$histogramType == 'Luminance')
        {
            //return ($rgb['red'] * count($histogram)) / (256);
            return ($rgb['red'] * self::$DEFAULT_NUMBER_OF_BINS) / (256);
        }
        else
        {
            // just for 512 bins ...
            $bin = 0;
            if (self::$DEFAULT_NUMBER_OF_BINS == 512)
            {
                for ($i = 0; $i < count(self::quant512) - 1; $i++) {
                    if (self::$quant512[$i] <= $rgb['red'] && $rgb['red']     < self::$quant512[$i + 1]) $bin += ($i + 1);
                    if (self::$quant512[$i] <= $rgb['green'] && $rgb['green'] < self::$quant512[$i + 1]) $bin += ($i + 1) * 8;
                    if (self::$quant512[$i] <= $rgb['blue'] && $rgb['blue']   < self::$quant512[$i + 1]) $bin += ($i + 1) * 8 * 8;
                }
                return $bin;
            }
            // and for 64 bins ...
            else
            {
                $pos = (int) round((double) $rgb['blue'] / 85) +
                        (int) round((double) $rgb['green'] / 85) * 4 +
                        (int) round((double) $rgb['red'] / 85) * 4 * 4;
                return $pos;
            }
        }
    }

//    public function getDistance(LireFeature vd)
//    {
//        // Check if instance of the right class ...
//        if (!(vd instanceof SimpleColorHistogram))
//            throw new UnsupportedOperationException("Wrong descriptor.");
//
//        // casting ...
//        SimpleColorHistogram ch = (SimpleColorHistogram) vd;
//
//        // check if parameters are fitting ...
//        if ((ch.histogram.length != histogram.length) || (ch.histogramType != histogramType))
//            throw new UnsupportedOperationException("Histogram lengths or color spaces do not match");
//
//        // do the comparison ...
//        $sum = 0;
//        if (self::distFunc == 'JSD')
//            return (float) MetricsUtils.jsd(histogram, ch.histogram);
//        else if (self::distFunc == DistanceFunction.TANIMOTO)
//            return (float) MetricsUtils.tanimoto(histogram, ch.histogram);
//        else if (self::distFunc == DistanceFunction.L1)
//            return (float) MetricsUtils.distL1(histogram, ch.histogram);
//        else
//            return (float) MetricsUtils.distL2(histogram, ch.histogram);
//    }

//    public function getStringRepresentation() {
//        StringBuilder sb = new StringBuilder(histogram.length * 4);
//        sb.append(histogramType.name());
//        sb.append(' ');
//        sb.append(histogram.length);
//        sb.append(' ');
//        for (int i = 0; i < histogram.length; i++) {
//            sb.append(histogram[i]);
//            sb.append(' ');
//        }
//        return sb.toString().trim();
//    }

//    public function setStringRepresentation(String s) {
//        StringTokenizer st = new StringTokenizer(s);
//        histogramType = HistogramType.valueOf(st.nextToken());
//        histogram = new int[Integer.parseInt(st.nextToken())];
//        for (int i = 0; i < histogram.length; i++) {
//            if (!st.hasMoreTokens())
//                throw new IndexOutOfBoundsException("Too few numbers in string representation!");
//            histogram[i] = Integer.parseInt(st.nextToken());
//        }
//    }

    /* **************************************************************
   * Color Conversion routines ...
   ************************************************************** */

    /**
     * Adapted from ImageJ documentation:
     * http://www.f4.fhtw-berlin.de/~barthel/ImageJ/ColorInspector//HTMLHelp/farbraumJava.htm
     *
     * @param r
     * @param g
     * @param b
     * @param yuv
     */
    public static function rgb2yuv($r, $g, $b, $yuv) {
        $y = (int) (0.299 * $r + 0.587 * $g + 0.114 * $b);
        $u = (int) (($b - $y) * 0.492);
        $v = (int) (($r - $y) * 0.877);

        $yuv[0] = $y;
        $yuv[1] = $u;
        $yuv[2] = $v;
    }

    /**
     * Adapted from ImageJ documentation:
     * http://www.f4.fhtw-berlin.de/~barthel/ImageJ/ColorInspector//HTMLHelp/farbraumJava.htm
     *
     * @param r
     * @param g
     * @param b
     * @param hsv
     */
    public static function rgb2hsv($r, $g, $b, $hsv) {

        $min;    //Min. value of RGB
        $max;    //Max. value of RGB
        $delMax; //Delta RGB value

        $min = min($r, $g);
        $min = min($min, $b);

        $max = max($r, $g);
        $max = max($max,$b);

        $delMax = $max - $min;

//        System.out.println("hsv = " + hsv[0] + ", " + hsv[1] + ", "  + hsv[2]);

        $H = 0.0;
        $S = 0.0;
        $V = $max / 255.0;

        if ($delMax == 0)
        {
            $H = 0.0;
            $S = 0.0;
        }
        else
        {
            $S = $delMax / 255.0;
            if ($r == $max)
            {
                if ($g >= $b)
                {
                    $H = (($g / 255.0 - $b / 255.0) / (float) $delMax / 255.0) * 60;
                }
                else
                {
                    $H = (($g / 255.0 - $b / 255.0) / (float) $delMax / 255.0) * 60 + 360;
                }
            }
            elseif ($g == $max)
            {
                $H = (2 + ($b / 255.0 - $r / 255.0) / (float) $delMax / 255.0) * 60;
            }
            elseif ($b == $max)
            {
                $H = (4 + ($r / 255.0 - $g / 255.0) / (float) $delMax / 255.0) * 60;
            }
        }
//        System.out.println("H = " + H);
        $hsv[0] = (int) ($H);
        $hsv[1] = (int) ($S * 100);
        $hsv[2] = (int) ($V * 100);
    }

    /**
     * Adapted under GPL from VizIR: author was adis@ims.tuwien.ac.at
     */
    private function rgb2hmmd($ir, $ig, $ib) {
        $hmmd[] = array_fill(0, 5, 0);

        $max = (float) max(max($ir, $ig), max($ig, $ib));
        $min = (float) min(min($ir, $ig), min($ig, $ib));

        $diff = ($max - $min);
        $sum = (float) (($max + $min) / 2.0);

        $hue = 0;
        if ($diff == 0) $hue = 0;
        elseif ($ir == $max && ($ig - $ib) > 0) $hue = 60 * ($ig - $ib) / ($max - $min);
        elseif ($ir == $max && ($ig - $ib) <= 0) $hue = 60 * ($ig - $ib) / ($max - $min) + 360;
        elseif ($ig == $max) $hue = (float) (60 * (2.0 + ($ib - $ir) / ($max - $min)));
        elseif ($ib == $max) $hue = (float) (60 * (4.0 + ($ir - $ig) / ($max - $min)));

        $diff /= 2;

        $hmmd[0] = (int) ($hue);
        $hmmd[1] = (int) ($max);
        $hmmd[2] = (int) ($min);
        $hmmd[3] = (int) ($diff);
        $hmmd[4] = (int) ($sum);

        return ($hmmd);
    }

    /**
     * Quantize hmmd values based on the MPEG-7 standard.
     *
     * @param hmmd               the HMMD color value
     * @param quantizationLevels only 256, 128, 64 and 32 are allowed.
     * @return the actual bin
     */
    private function quantHmmd($hmmd, $quantizationLevels)
    {
        $h = 0;
        $offset = 0;    // offset position in the quantization table
        $subspace = 0;
        $q = 0;

        // define the subspace along the Diff axis

        if ($hmmd[3] < 7) $subspace = 0;
        else if (($hmmd[3] > 6) && ($hmmd[3] < 21)) $subspace = 1;
        else if (($hmmd[3] > 19) && ($hmmd[3] < 61)) $subspace = 2;
        else if (($hmmd[3] > 59) && ($hmmd[3] < 111)) $subspace = 3;
        else if (($hmmd[3] > 109) && ($hmmd[3] < 256)) $subspace = 4;

        // HMMD Color Space quantization
        // see MPEG7-CSD.pdf

        if ($quantizationLevels == 256)
        {
            $offset = 0;
            $h = (int) (($hmmd[0] / $quantizationLevels) * self::$quantTable[$offset + $subspace] + 
                        ($hmmd[4] / $quantizationLevels) * self::$quantTable[$offset + $subspace + 1]);
        }
        elseif ($quantizationLevels == 128)
        {
            $offset = 10;
            $h = (int) (($hmmd[0] / $quantizationLevels) * self::$quantTable[$offset + $subspace] + 
                        ($hmmd[4] / $quantizationLevels) * self::$quantTable[$offset + $subspace + 1]);
        }
        elseif ($quantizationLevels == 64)
        {
            $offset = 20;
            $h = (int) (($hmmd[0] / $quantizationLevels) * self::$quantTable[$offset + $subspace] + 
                        ($hmmd[4] / $quantizationLevels) * self::$quantTable[$offset + $subspace + 1]);

        }
        elseif ($quantizationLevels == 32)
        {
            $offset = 30;
            $h = (int) (($hmmd[0] / $quantizationLevels) * self::$quantTable[$offset + $subspace] + 
                        ($hmmd[4] / $quantizationLevels) * self::$quantTable[$offset + $subspace + 1]);
        }


        return $h;
    }
}


?>