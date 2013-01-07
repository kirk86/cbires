<?php

/**
 * @category Classes, Class DistanceMetrics
 * @author John Mitros
 * @copyright 2013
 */

class DistanceMetrics
{
    #region Histogram-based comparisons
    /**
     * Finds the mean histogram of two histograms
     * 
     * @param $a First histogram
     * @param $b Second histogram
     * @return Mean histogram
     */
    private static function meanHistogram($a, $b)
    {
        if (count($a) != count($b))
            //return null;
            trigger_error("The two histograms must be of equal size in: fcn -> meanHistogram()!", E_USER_ERROR);

        $mean = array();

        for ($i = 0; $i < count($a); $i++)
            $mean[$i] = ($a[$i] + $b[$i]) / 2;

        return $mean;
    }

    /**
     * Finds the Chi-square similarity between two histograms
     * 
     * @param $a First histogram
     * @param $b Second histogram
     * @return Similarity measure
     */
    public static function chiSquare($a, $b)
    {
        if (count($a) != count($b))
            //return - 1;
            trigger_error("The two histograms must be of equal size in: fcn -> chiSquare()!", E_USER_ERROR);

        $dist = 0.0;
        $mean = self::meanHistogram($a, $b);
        for ($i = 0; $i < count($a); $i++) {
            if ($mean[$i] == 0) // don't want to divide by zero
                continue;

            $dist += pow(($a[$i] - $mean[$i]), 2) / $mean[$i];
        }
        return $dist;
    }

    /**
     * Finds the Jeffrey divergence between two histograms
     * 
     * @param $a First histogram
     * @param $b Second histogram
     * @return Similarity measure
     */
    public static function jeffrey($a, $b)
    {
        if (count($a) != count($b))
            //return - 1;
            trigger_error("The two histograms must be of equal size in: fcn -> jeffrey()!", E_USER_ERROR);

        $dist = 0.0;
        $mean = self::meanHistogram($a, $b); // find the mean histogram of a and b
        for ($i = 0; $i < count($a); $i++) {
            if ($mean[$i] == 0 || $a[$i] == 0 || $b[$i] == 0) //dont' want to devide by zero
                continue;

            $dist += ($a[$i] * log10($a[$i] / $mean[$i])) + ($b[$i] * log10($b[$i] / $mean[$i]));
        }
        return $dist;
    }
    #endregion

    #region Point-based comparisons
    /**
     * Calculates the Euclidean distance between two floating-point vectors
     * 
     * @param $a First vector
     * @param $b Second vector
     * @return Floating-point distance between input vectors
     */
    public static function euclidean($a, $b)
    {
        if (count($a) != count($b))
            //return 0.0;
            trigger_error("The two histograms must be of equal size in: fcn -> euclidean()!", E_USER_ERROR);
        $dist = 0.0;
        for ($i = 0; $i < count($a); $i++)
            $dist += pow($a[$i] - $b[$i], 2);

        $dist = sqrt($dist);
        return $dist;
    }

    /**
     * Calculates the Manhattan distance between two floating-point vectors
     * 
     * @param $a First vector
     * @param $b Second vector
     * @return Floating-point distance between input vectors
     */
    public static function manhattan($a, $b)
    {
        if (count($a) != count($b))
            //return 0.0;
            trigger_error("The two histograms must be of equal size in: fcn -> manhattan()!", E_USER_ERROR);
        $dist = 0.0;
        for ($i = 0; $i < count($a); $i++)
            $dist += abs($a[$i] - $b[$i]);

        return $dist;
    }

    /**
     * Calculates the Chebychev distance between two floating-point vectors
     * 
     * @param $a First vector
     * @param $b Second vector
     * @return Floating-point distance between input vectors
     */
    public static function chebychev($a, $b)
    {
        if (count($a) != count($b))
            //return 0.0;
            trigger_error("The two histograms must be of equal size in: fcn -> chebychev()!", E_USER_ERROR);
        $dist = abs($a[0] - $b[0]);
        for ($i = 1; $i < count($a); $i++)
            $dist = max($dist, abs($a[$i] - $b[$i]));

        return $dist;
    }
    #endregion
    
    /**
     * Calculates the tanimoto distance between two floating-point vectors
     * 
     * @param $a First vector
     * @param $b Second vector
     * @return Floating-point distance between input vectors
     */
    public static function tanimoto($a, $b)
    {
        $dist = 0.0;
        $tmp1 = 0.0;
        $tmp2 = 0.0;

        $tmpCnt1 = 0;
        $tmpCnt2 = 0;
        $tmpCnt3 = 0;

        for ($i = 0; $i < count($a); $i++) {
            $tmp1 += $a[$i];
            $tmp2 += $b[$i];
        }

        if ($tmp1 == 0 || $tmp2 == 0) $dist = 100;
        if ($tmp1 == 0 && $tmp2 == 0) $dist = 0;

        if ($tmp1 > 0 && $tmp2 > 0) {
            for ($i = 0; $i < count($a); $i++) {
                $tmpCnt1 += ($a[$i] / $tmp1) * ($b[$i] / $tmp2);
                $tmpCnt2 += ($b[$i] / $tmp2) * ($b[$i] / $tmp2);
                $tmpCnt3 += ($a[$i] / $tmp1) * ($a[$i] / $tmp1);

            }

            $dist = (100 - 100 * ($tmpCnt1 / ($tmpCnt2 + $tmpCnt3 - $tmpCnt1))); //Tanimoto
        }
        return $dist;
    }
    
    /**
     * Calculates the cosine coefficient between two floating-point vectors
     * 
     * @param $a First vector
     * @param $b Second vector
     * @return Floating-point distance between input vectors
     */
    public static function cosineCoefficient($a, $b) {
        $dist = 0.0;
        $tmp1 = 0.0;
        $tmp2 = 0.0;
        for ($i = 0; $i < count($a); $i++) {
            $dist += $a[$i] * $b[$i];
            $tmp1 += $a[$i] * $a[$i];
            $tmp2 += $b[$i] * $b[$i];
        }
        if ($tmp1 * $tmp2 > 0) {
            return ($dist / (sqrt($tmp1) * sqrt($tmp2)));
        } else return 0.0;
    }
    
    /**
     * Computes the mean value from the NxN matrix
     * 
     * @param $data Input matrix
     * @return Mean
     */
    public static function mean($data)
    {
        $sum = 0;
        foreach ($data as $key => $value)
            $sum += $value;
            
        return $sum /= count($data);
    }
    
    /**
     * Computes the standard deviation from the NxN matrix
     * 
     * @param $data Input Matrix
     * @return Standard deviation
     */
    public static function std($data)
    {
        $mean = self::mean($data);
        $sum = 0;
        foreach ($data as $key => $value)
            $sum += pow(($value - $mean), 2);
            
        return sqrt($sum / (count($data) - 1));
    }
    
    /**
     * Computes a histogram of the NxN matrix
     * 
     * @param $data Matrix to create the histogram from
     * @param $histBins Number of bins to use
     * @param $histMinVal Minimum cutoff value for the histogram
     * @param $histMaxVal Maximum cutoff value for the histogram
     * @return Histogram of the intensity values
     */
    public static function computeHistogram($data, $histBins, $histMinVal, $histMaxVal, $normalize = true)
    {
        $hist = array_fill(0, $histBins, null);
        
        $minval = 0;
        $binrange = ($histMaxVal - $minval) / $histBins;
        $binnum;
        $count = 0;

        foreach ($data as $key => $value)
        {
            if (!is_nan($value))
            {
                $count++;
                // anything above the maximum value is put into the top bin
                if ($value >= $histMaxVal)
                {
                    $hist[$histBins - 1]++;
                    continue;
                }
                // anything below the minimum value is put into the bottom bin
                if ($value <= $minval)
                {
                    $hist[0]++;
                    continue;
                }

                $binnum = (int) floor(($value - $minval) / $binrange);
                if ($binnum > ($histBins - 1))
                    $binnum = $binnum - 1;
                
                $hist[$binnum]++;
            }
            else
                break;
        }
        if ($normalize)
            return self::normalizeHistogram($hist, $count);
        else
            return $hist;
    }
    
    /**
     * Compute the binned histogram from a 2-D array
     * 
     * @param $data 2-D array
     * @param $histBins length of histogram bins
     * @return $H the binned histogram from value 0-$histBins
     */
    public function binnedHistogram($data, $histBins)
    {
        $K = 256; // number of intensity values
        $H = array_fill(0, $histBins, null); // histogram array
        $width = count($data);
        $height = count($data[1]);
        for ($v = 0; $v < $height; $v++)
        {
            for ($u = 0; $u < $width; $u++)
            {
                $i = $data[$u][$v] * $histBins / $K; // integer operations only!
                $H[$i] = $H[$i] + 1;
            }
        }
            // return binned histogram
            return $H;
    }
    
    /**
     * Normalizes the histogram so that each bin has values between 0 and 1
     * 
     * @param $hist The histogram to normalize
     * @param $n Total number of intensity values in the origional image
     * @return Normalized histogram
     */
    public static function normalizeHistogram($hist, $n)
    {
        for ($i = 0; $i < count($hist); $i++)
            $hist[$i] /= $n;
        
        return $hist;
    }
    
    /**
     * Normalize histogram 2nd way
     * 
     * @param $hist The histogram to normalize
     * @param $numPixels Total number of pixesl in the image
     * @return Normalized histogram
     */
     public static function normalize($hist)
     {
        $max = 0;
        for ($i = 0; $i < count($hist); $i++)
            $max = max($hist[$i], $max);
        
        for ($i = 0; $i < count($hist); $i++)
            $hist[$i] = ($hist[$i] * 256) / $max;
        
        return $hist;        
    }
}
