<?php

/**
 * @category Classes, Class ColorSpaceConversion
 * @author John Mitross
 * @copyright 2012
 */

class ColorSpaceConversion
{
    // Here's a little simpler version, that also returns HSV values as degrees and percentages, similar to what Photoshop'
    //    s color picker uses . //
    // Note that the return values are not rounded, you can do that yourself if required. 
    // Keep in mind that H values of 359.5 and greater, round around to 0
    //
    // Heavily documented for learning purposes.

    public function RGBtoHSV($R, $G, $B) // RGB values:    0-255, 0-255, 0-255
    { // HSV values:    0-360, 0-100, 0-100
        // Convert the RGB byte-values to percentages
        $R = ($R / 255);
        $G = ($G / 255);
        $B = ($B / 255);

        // Calculate a few basic values, the maximum value of R,G,B, the
        //   minimum value, and the difference of the two (chroma).
        $maxRGB = max($R, $G, $B);
        $minRGB = min($R, $G, $B);
        $chroma = $maxRGB - $minRGB;

        // Value (also called Brightness) is the easiest component to calculate,
        //   and is simply the highest value among the R,G,B components.
        // We multiply by 100 to turn the decimal into a readable percent value.
        $computedV = 100 * $maxRGB;

        // Special case if hueless (equal parts RGB make black, white, or grays)
        // Note that Hue is technically undefined when chroma is zero, as
        //   attempting to calculate it would cause division by zero (see
        //   below), so most applications simply substitute a Hue of zero.
        // Saturation will always be zero in this case, see below for details.
        if ($chroma == 0)
            return array( 0, 0, $computedV);

        // Saturation is also simple to compute, and is simply the chroma
        //   over the Value (or Brightness)
        // Again, multiplied by 100 to get a percentage.
        $computedS = 100 * ($chroma / $maxRGB);

        // Calculate Hue component
        // Hue is calculated on the "chromacity plane", which is represented
        //   as a 2D hexagon, divided into six 60 degree sectors. We calculate
        //   the bisecting angle as a value 0 <= x < 6, that represents which
        //   portion of which sector the line falls on.
        if ($R == $minRGB)
            $h = 3 - (($G - $B) / $chroma);
        elseif ($B == $minRGB)
            $h = 1 - (($R - $G) / $chroma);
        else
            $h = 5 - (($B - $R) / $chroma);

        // After we have the sector position, we multiply it by the size of
        //   each sector's arc (60 degrees) to obtain the angle in degrees.
        $computedH = 60 * $h;

        return array($computedH, $computedS, $computedV);
    }
    
    /**
     *
     * Converts RGB to HSV.
     *
     * @param array $rgb RGB values: 0 => R, 1 => G, 2 => B
     *
     * @return array HSV values: 0 => H, 1 => S, 2 => V
     *
     */
    public function rgb2hsv($R, $G, $B) // RGB Values:Number 0-255
    {
        // HSV Results:Number 0-1
        $HSL = array();

        $var_R = ($R / 255);
        $var_G = ($G / 255);
        $var_B = ($B / 255);

        $var_Min = min($var_R, $var_G, $var_B);
        $var_Max = max($var_R, $var_G, $var_B);
        
        $V = $var_Max;
        $del_Max = $var_Max - $var_Min; //Delta RGB value
        
        // This is a gray, no chroma...
        if ($del_Max == 0)
        {
            // HSV results = 0 Γ· 1
            $H = 0;
            $S = 0;
        }
        else
        {
            // Chromatic data...
            $S = $del_Max / $var_Max;

            $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
            $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
            $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

            if ($var_R == $var_Max)
                $H = $del_B - $del_G;
            elseif ($var_G == $var_Max)
                $H = (1 / 3) + $del_R - $del_B;
            elseif ($var_B == $var_Max)
                $H = (2 / 3) + $del_G - $del_R;

            if ($H < 0)
                $H++;
            if ($H > 1)
                $H--;
        }

        $HSL['H'] = $H;
        $HSL['S'] = $S;
        $HSL['V'] = $V;
        
        // Returns agnostic values.
        // Range will depend on the application: e.g. $H*360, $S*100, $V*100.
        return $HSL;
    }
    
     /**
     *
     * Converts HSV to RGB.
     *
     * @param array $hsv HSV values: 0 => H, 1 => S, 2 => V
     *
     * @return array RGB values: 0 => R, 1 => G, 2 => B
     *
     */
    public function hsv2rgb($H, $S, $V) // HSV Values:Number 0-1
    { // RGB Results:Number 0-255
        $RGB = array();
        
        // HSV values = 0 Γ· 1
        if ($S == 0)
        {
            $R = $V * 255;
            $G = $V * 255;
            $B = $V * 255;
        }
        else
        {
            $var_H = $H * 6;
            // H must be < 1
            if ( $var_H == 6 ) {
                $var_H = 0;
            }
            // Or ... $var_i = floor( $var_H )
            $var_i = floor($var_H);
            $var_1 = $V * (1 - $S);
            $var_2 = $V * (1 - $S * ($var_H - $var_i));
            $var_3 = $V * (1 - $S * (1 - ($var_H - $var_i)));

            if ($var_i == 0)
            {
                $var_R = $V;
                $var_G = $var_3;
                $var_B = $var_1;
            }
            elseif ($var_i == 1)
            {
                $var_R = $var_2;
                $var_G = $V;
                $var_B = $var_1;
            }
            elseif ($var_i == 2)
            {
                $var_R = $var_1;
                $var_G = $V;
                $var_B = $var_3;
            }
            elseif ($var_i == 3)
            {
                $var_R = $var_1;
                $var_G = $var_2;
                $var_B = $V;
            }
            elseif ($var_i == 4)
            {
                $var_R = $var_3;
                $var_G = $var_1;
                $var_B = $V;
             }
             else
             {
                $var_R = $V;
                $var_G = $var_1;
                $var_B = $var_2;
              }

            $R = $var_R * 255;
            $G = $var_G * 255;
            $B = $var_B * 255;
        }

        $RGB['R'] = $R;
        $RGB['G'] = $G;
        $RGB['B'] = $B;

        return $RGB;
    }
    /**
     * -----------------------------------------------------------------------------------------------------
     */
    public function toHSV($R, $G, $B) // Convert to hsv according to photoshop, H in degrees, S in %, V in %
    {
        $HSV = array();
        
        $red   = $R / 255;
        $green = $G / 255;
        $blue  = $B / 255;


        $min = min($red, $green, $blue);
        $max = max($red, $green, $blue);

        $V = $max;
        $delta = $max - $min;

        if ($delta == 0) {
            $H = 0;
            $S = 0;
            $V = $V * 100;
        }

        $S = 0;

        if ($max != 0) {
            $S = $delta / $max;
        } else {
            $S = 0;
            $H = -1;
            $V = $V;
        }
        if ($red == $max) {
            $H = ($green - $blue) / $delta;
        } else {
            if ($green == $max) {
                $H = 2 + ($blue - $red) / $delta;
            } else {
                $H = 4 + ($red - $green) / $delta;
            }
        }
        $H *= 60;
        if ($H < 0) {
            $H += 360;
        }
        
        $HSV['H'] = $H;
        $HSV['S'] = $S * 100;
        $HSV['V'] = $V * 100;

        return $HSV;
    }
    
    public function toRGB($H, $S, $V)
    {
        $RGB = array();
        
        $hue        = $H / 360;
        $saturation = $S / 100;
        $value      = $V / 100;
        
        if ($saturation == 0) {
            $red = $value * 255;
            $green = $value * 255;
            $blue = $value * 255;
        } else {
            $var_h = $hue * 6;
            $var_i = floor($var_h);
            $var_1 = $value * (1 - $saturation);
            $var_2 = $value * (1 - $saturation * ($var_h - $var_i));
            $var_3 = $value * (1 - $saturation * (1 - ($var_h - $var_i)));

            if ($var_i == 0) {
                $var_r = $value;
                $var_g = $var_3;
                $var_b = $var_1;
            } elseif ($var_i == 1) {
                $var_r = $var_2;
                $var_g = $value;
                $var_b = $var_1;
            } elseif ($var_i == 2) {
                $var_r = $var_1;
                $var_g = $value;
                $var_b = $var_3;
            } elseif ($var_i == 3) {
                $var_r = $var_1;
                $var_g = $var_2;
                $var_b = $value;
            } else {
                if ($var_i == 4) {
                    $var_r = $var_3;
                    $var_g = $var_1;
                    $var_b = $value;
                } else {
                    $var_r = $value;
                    $var_g = $var_1;
                    $var_b = $var_2;
                }
            }

            $red = $var_r * 255;
            $green = $var_g * 255;
            $blue = $var_b * 255;
        }
        $RGB['R'] = $red;
        $RGB['G'] = $green;
        $RGB['B'] = $blue;
        
        return $RGB;
    }

}
