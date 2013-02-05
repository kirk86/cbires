<?php

/**
 * @author John Mitros
 * @copyright 2013
 */

class PopulateImages
{
    private function __construct() {}
    
    public static function populateColorSpaceImages($sortedImageArray, $threshold = false)
    {
        //var_dump($imgIdsArray);
        $counter = 0;
        $i = 0;
        $maxcols = 7;
        $image = 1;
        $temp = 0;
        if (isset($threshold) && !empty($threshold))
            $_SESSION['threshold'] = $threshold;
        $remainder = floor($_SESSION['threshold'] / 7);
        $images_remaind = ($_SESSION['threshold'] - (7 * $remainder) );
        
        $img_filaname_hash = array();
        $count = 0;
        foreach ($sortedImageArray as $key => $value)
        {
            if ($count < $_SESSION['threshold'])
                $img_filaname_hash[] = $value;
                
            $count++;
        }
        $pg_array_filename = Tools::phpArray2PostgressSQL($img_filaname_hash);
        $sql = "SELECT id_image, filename_hash
                FROM ". DB_PREFIX. "image 
                WHERE filename_hash = ANY('$pg_array_filename'::text[])
                ORDER BY id_image ASC";
        $result = DB::getAll($sql);
        
        $distances = array();
        $img_ids = array();
        $ids = array();
        foreach ($result as $key => $value)
        {
            $distances[] = array_search($result[$key]['filename_hash'], $sortedImageArray);
            $img_ids[] = $result[$key]['id_image'];
        }
        unset($sortedImageArray);
        $combDistAndFilenames = array_combine($distances, $img_filaname_hash); // Combine distances and filenames
        unset($distances);
        $good = ksort($combDistAndFilenames); // Sort them based on distance
        
        $sortedFilenames = array_values($combDistAndFilenames); // Extract sorted filenames based on distance
        $combIdsAndFilenames = array_combine($img_ids, $img_filaname_hash); // Transform the 2-D assoc $result array to 1-D for simplicity
        unset($img_ids); unset($img_filaname_hash); unset($combDistAndFilenames);
        
        for ($p = 0; $p < count($sortedFilenames); $p++)
            $ids[] = array_search($sortedFilenames[$p], $combIdsAndFilenames);
        
        $combSortedIdsAndFilenames = array_combine($ids, $sortedFilenames);
        unset($ids); unset($sortedFilenames); unset($combIdsAndFilenames);
        
        $sorted_ids = array_keys($combSortedIdsAndFilenames);
        foreach ($combSortedIdsAndFilenames as $key => $value)
        {
            if ($counter < $_SESSION['threshold'])
            {
                if ($i == $maxcols)
                {
                    $i = 0;
                    echo "</tr><tr>";
                    for ($j = 0; $j < 7; $j++)
                    {
                        echo "<td>
                                <label class='radio'>
                                   <input type='radio' name='{$sorted_ids[$temp]}' id='positive_feedback{$temp}' value='1' />
                                   <span class='icon icon-color icon-add'></span>
                             	 </label>
                                 <label class='radio'>
                              	   <input type='radio' name='{$sorted_ids[$temp]}' id='negative_feedback{$temp}' value='-1' />
                                   <span class='icon icon-color icon-remove'></span>
                                  </label>
                               </td>";
                        
                        $temp++;
                    }
                    echo "</tr><tr>";
                }
                
                $image_id = $counter + 1;
                echo "<td>
                        <li id='image-{$image_id}' class='thumbnail'>
 							<a style='background:url(img/gallery/thumbs/{$value});background-size:100px 100px;background-repeat:no-repeat;' title='Sample Image {$value}' href='img/gallery/{$value}' >
                            <img src='img/gallery/thumbs/{$value}' alt='Sample Image {$value}' />
                            </a>
                        </li>
                      </td>";
                
                if ( ($image == $_SESSION['threshold']) && ($_SESSION['threshold'] % 7 == 0) ) // execute on 14, 21, 28, 35, 42...
                {
                    echo "</tr><tr>";
                    for ($l = 0; $l < 7; $l++)
                    {
                        echo "<td>
                                <label class='radio'>
                                   <input type='radio' name='{$sorted_ids[$temp]}' id='positive_feedback{$temp}' value='1' />
                                   <span class='icon icon-color icon-add'></span>
                             	 </label>
                                 <label class='radio'>
                              	   <input type='radio' name='{$sorted_ids[$temp]}' id='negative_feedback{$temp}' value='-1' />
                                   <span class='icon icon-color icon-remove'></span>
                                  </label>
                               </td>";
                        
                        $temp++;
                    }
                    echo "</tr><tr>";
                }
                if ( ($image - (7 * $remainder)) == $images_remaind )
                {
                    echo "</tr><tr>";
                    for ($k = 0; $k < $images_remaind; $k++)
                    {
                        echo "<td>
                                <label class='radio'>
                                   <input type='radio' name='{$sorted_ids[$temp]}' id='positive_feedback{$temp}' value='1' />
                                   <span class='icon icon-color icon-add'></span>
                             	 </label>
                                 <label class='radio'>
                              	   <input type='radio' name='{$sorted_ids[$temp]}' id='negative_feedback{$temp}' value='-1' />
                                   <span class='icon icon-color icon-remove'></span>
                                  </label>
                               </td>";
                        
                        $temp++;
                    }
                }
                
                $i++;
                $image++;
            }
            
            $counter++;
        }
        //Add empty <td>'s to even up the amount of cells in a row:
        while ($i < $maxcols)
        {
            echo "<td>&nbsp;</td>";
            $i++;
        }
        //Close the table row and the table
        echo "</tr>";
    }
    
    public static function computeRgbImages($normHistRGB, $default = false)
    {
		/*
        $objRGB      = new Histogram($resized_img);
        $histoRGB    = $objRGB->generateHistogram();
        $normHistRGB = DistanceMetrics::computeHistogram($histoRGB, 64, min($histoRGB), max($histoRGB));
        $_SESSION['rgb_histogram'] = $normHistRGB;
        $meanRGB     = DistanceMetrics::mean($normHistRGB);
        $stdRGB      = DistanceMetrics::std($normHistRGB);
        */
		
		$sql         = "SELECT id_image, filename, filepath, filemime, filename_hash,
                        array_to_json(color_histogram) AS rgb_histogram 
                        FROM ". DB_PREFIX. "image";
        $qresult = DB::getAll($sql);
        $distArrayRGB = array();
        $img_ids      = array();
        $img_filename = array();
        if ($default == false)
        {
            foreach ($qresult as $key => $value)
            {
                if ($_SESSION['distanceFunction'] == 'L1')
                $distRGB = DistanceMetrics::manhattan($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                if ($_SESSION['distanceFunction'] == 'L2')
                $distRGB = DistanceMetrics::euclidean($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                if ($_SESSION['distanceFunction'] == 'JDV')
                $distRGB = DistanceMetrics::jeffrey($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                if ($_SESSION['distanceFunction'] == 'ChiSquare')
                $distRGB = DistanceMetrics::chiSquare($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                if ($_SESSION['distanceFunction'] == 'Chebychev')
                $distRGB = DistanceMetrics::chebychev($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                if ($_SESSION['distanceFunction'] == 'Tanimoto')
                $distRGB = DistanceMetrics::tanimoto($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                if ($_SESSION['distanceFunction'] == 'Cosine')
                $distRGB = DistanceMetrics::cosineCoefficient($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                
                $distArrayRGB[] = $distRGB;
                $img_ids[]      = $qresult[$key]['id_image'];
                $img_filename[] = $qresult[$key]['filename_hash'];
            }
        }
        else
        {
            foreach ($qresult as $key => $value)
            {
                $distRGB = DistanceMetrics::euclidean($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                $distArrayRGB[] = $distRGB;
                $img_ids[]      = $qresult[$key]['id_image'];
                $img_filename[] = $qresult[$key]['filename_hash'];
            }
        }
        
        $combRGB = array_combine($distArrayRGB, $img_filename);
        $ok = ksort($combRGB);        
        unset($objRGB); unset($histoRGB); unset($normHistRGB);
        unset($qresult); unset($distArrayRGB); unset($img_ids);
        unset($img_filename);
        
        return $combRGB;
    }
    
    public static function computeHsvImages($normHistHSV)
    {
		/*
        $objHSV      = new Histogram($resized_img);
        $histoHSV    = $objHSV->generateHistogram(true);
        $binHistHSV  = DistanceMetrics::computeHistogram($histoHSV, 64, min($histoHSV), max($histoHSV), false);
        $normHistHSV = DistanceMetrics::normalize($binHistHSV);
        $_SESSION['hsv_histogram'] = $normHistHSV;
        $meanHSV     = DistanceMetrics::mean($normHistHSV);
        $stdHSV      = DistanceMetrics::std($normHistHSV);
		*/
		
        $sql         = "SELECT id_image, filename, filepath, filemime, filename_hash,
                        array_to_json(hsv_histogram) AS hsv_histogram 
                        FROM ". DB_PREFIX ."image";
        $qresult = DB::getAll($sql);
        $distArrayHSV = array();
        $img_ids      = array();
        $img_filename = array();
        foreach ($qresult as $key => $value)
        {
            if ($_SESSION['distanceFunction'] == 'L1')
            $distHSV = DistanceMetrics::manhattan($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
            if ($_SESSION['distanceFunction'] == 'L2')
            $distHSV = DistanceMetrics::euclidean($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
            if ($_SESSION['distanceFunction'] == 'JDV')
            $distHSV = DistanceMetrics::jeffrey($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
            if ($_SESSION['distanceFunction'] == 'ChiSquare')
            $distHSV = DistanceMetrics::chiSquare($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
            if ($_SESSION['distanceFunction'] == 'Chebychev')
            $distHSV = DistanceMetrics::chebychev($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
            if ($_SESSION['distanceFunction'] == 'Tanimoto')
            $distHSV = DistanceMetrics::tanimoto($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
            if ($_SESSION['distanceFunction'] == 'Cosine')
            $distHSV = DistanceMetrics::cosineCoefficient($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            
            $distArrayHSV[] = $distHSV;
            $img_ids[]      = $qresult[$key]['id_image'];
            $img_filename[] = $qresult[$key]['filename_hash'];
        }
        $combHSV = array_combine($distArrayHSV, $img_filename);
        $ok = ksort($combHSV);
        unset($objHSV); unset($histoHSV); unset($binHistHSV);
        unset($normHistHSV); unset($qresult); unset($distArrayHSV);
        unset($img_ids); unset($img_filename);
        
        return $combHSV;
    }
    
    public static function retrievalResults($comb)
    {
       echo "
        <!-- START OF: fullscreen button -->
    		<p class='center'>
    			<button id='toggle-fullscreen' class='btn btn-large btn-primary visible-desktop' data-toggle='button'>Toggle Fullscreen</button>
    		</p>
    	<!-- END OF: fullscreen button -->
    	
    	<!-- START OF: query message 1 -->    
    		<div class='alert alert-info'>
    			<p class='center' style='color: blue; font-size: x-large;'>Query Image</p>
    		</div>
    	<!-- END OF: query message 1 -->
    	
    		<ul class='thumbnails gallery'>
    			<li id='image-query' class='thumbnail'>
    				<a style='background:url(img/gallery/thumbs/query_image.jpg);background-size:100px 100px;background-repeat:no-repeat;' title='Sample Image query_image.jpg' href='img/gallery/query_image.jpg'>
    					<img src='img/gallery/thumbs/query_image.jpg' alt='Sample Image query_image.jpg' />
    				</a>
    			</li>
    		</ul>
    		
    	<!-- START OF: info message 1 -->    
    		<div class='alert alert-info'>
    			<p class='center' style='color: blue; font-size: x-large;'>Retrieval Results</p>
    		</div>
    	<!-- END OF: info message 1 -->
    	<form class='form-horizontal' action='gallery.php' method='post'>
    		<fieldset>
    				<ul class='thumbnails gallery'>
    				<!-- Query image -->
    				<!--
    					<li id='image-query' class='thumbnail'>
    						<a style='background:url(img/gallery/thumbs/query_image.jpg)' title='Sample Image Query' href='img/gallery/query_image.jpg'>
    							<img src='img/gallery/thumbs/query_image.jpg' alt='Sample Image Query' />
    						</a>
    					</li>
    					-->
    				<!-- Query image -->
    					<table id='results_images_table'>
    						<tr>";
                            
    					if ( isset($_SESSION['threshold']) && !empty($_SESSION['threshold']) && 
    						 isset($_SESSION['colorSpace']) && !empty($_SESSION['colorSpace'])
    					   )
    					{
    							switch ($_SESSION['colorSpace'])
    							{
    								case 'RGB':
    								self::populateColorSpaceImages($comb);
    								break;
    								 
    								case 'HSV':
    								self::populateColorSpaceImages($comb);
    								break;
    							}
    							//session_unset();
    							//session_destroy();
    					}
    					else
    					{
    						self::populateColorSpaceImages($comb, 14);
    					}
       echo "
    					</table>
    				</ul>
    				<p style='text-align: center;'>
    					<button class='btn btn-warning btn-round' type='submit' name='submit' value='relevance_feedback'>Requery</button>
    				</p>
    		  </fieldset>
    	</form>";
    }
}