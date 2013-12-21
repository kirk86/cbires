<?php

require_once('../config/config.php');
require_once(CB_DB_DIR . 'DB.php');
require_once(CB_INCLUDES_DIR . 'Tools.php');
require_once(CB_CORE_DIR . 'ColorSpaceConversion.php');
require_once(CB_CORE_DIR . 'Histogram.php');
require_once(CB_CORE_DIR . 'Tamura.php');
require_once(CB_CORE_DIR . 'DistanceMetrics.php');
require_once(CB_CORE_DIR . 'Image.php');

// Define a destination
$targetFolder = '/cbires/img/gallery/thumbs'; // Relative to the root
$galleryFolder = '/cbires/img/gallery';
$tmpFolder = '/cbires/img/tmp';

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES))
{
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
    $galleryPath = $_SERVER['DOCUMENT_ROOT'] . $galleryFolder;
    $tmpPath = $_SERVER['DOCUMENT_ROOT'] . $tmpFolder;
	
	// Validate the file type
	$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
    
    $filesize = filesize($tempFile);
    $image_info = getimagesize($tempFile);
    $filepath = $fileParts['dirname'].$targetFolder;
    
    $date      = date_create();
    $timestamp = date_format($date, 'Y-m-d H:i:s');
    $timestamp1 = date_format($date, 'Y-m-d-H-i-s');
    $filename_hash = $timestamp1.'_'.$fileParts['basename'];
    $targetFile = rtrim($targetPath,'/') . '/' . $filename_hash;
    $galleryFile = rtrim($galleryPath,'/') . '/' . $filename_hash;
    
	if ( in_array( Tools::strtolower( $fileParts['extension'] ), $fileTypes) )
    {
        $filemime = 'image/'.$fileParts['extension'];
                
        // Image class example
        $img = new Image($tempFile);
        $tmp_img = new Image($tempFile);
        $tmp_img->resize(64, 64, false);
        $img->resize(300, 0, true); // Lower quality image created using width ratio
        $full_dim_img = $img->file_name;
        
        # Generate RGB Tamura texture histogram
        //$tmp_img = new Image($tempFile);
        //$tmp_img->resize(64, 64, false);
        switch ( Tools::strtolower($fileParts['extension']) )
        {
            case 'jpg':
            case 'jpeg':
            $tmp_img->save($tmpPath . '/temp.jpg');
            $objRGBTexture = new Histogram($tmpPath . '/temp.jpg');
            break;
            
            case 'gif':
            $tmp_img->save($tmpPath.'/temp.gif');
            $objRGBTexture = new Histogram($tmpPath . '/temp.gif');
            break;
            
            case 'png':
            $tmp_img->save($tmpPath.'/temp.png');
            $objRGBTexture = new Histogram($tmpPath . '/temp.png');
            break;
        }
        $histRGB_2D = $objRGBTexture->generateHistogram2D();
        //parameters $image_info[0] = imgWidth, $image_info[1] = imgHeight, 2D-grayScale histogram
        $objTamura = new Tamura(64, 64, $histRGB_2D);
        $texture_histogram = array_fill(0, 18, 0);
        $texture_histogram[0] = $objTamura->coarseness(); // This creates problem and doesn't insert in db neither copies images in gallery nor in thumbs
        $texture_histogram[1] = $objTamura->contrast();
        $directionality = $objTamura->directionality();
        
        for ($i = 2; $i < count($texture_histogram); $i++)
            $texture_histogram[$i] = $directionality[$i - 2];
        
        $pg_arrayRGBTamura = Tools::phpArray2PostgressSQL($texture_histogram);
        
        # Delete Texture Variables
        unset($tmp_img); unset($objRGBTexture); unset($histRGB_2D);
        unset($objTamura); unset($directionality); unset($texture_histogram);
        
        $objRGB = new Histogram($full_dim_img);
        $objHSV = new Histogram($full_dim_img);
        
        # Generate RGB histogram
        $histRGB = $objRGB->generateHistogram();        
        $normHistRGB = DistanceMetrics::computeHistogram($histRGB, 64, min($histRGB), max($histRGB));
        $meanRGB = DistanceMetrics::mean($normHistRGB);
        $stdRGB = DistanceMetrics::std($normHistRGB);
        $pg_arrayRGB = Tools::phpArray2PostgressSQL($normHistRGB);
        
        # Generate HSV histogram
        $histHSV = $objHSV->generateHistogram(true);
        $normHistHSV = DistanceMetrics::computeHistogram($histHSV, 64, min($histHSV), max($histHSV));
        //$binHistHSV = DistanceMetrics::computeHistogram($histHSV, 64, min($histHSV), max($histHSV), false);
        //$normHistHSV = DistanceMetrics::normalize($binHistHSV);
        $meanHSV = DistanceMetrics::mean($normHistHSV);
        $stdHSV = DistanceMetrics::std($normHistHSV);
        $pg_arrayHSV = Tools::phpArray2PostgressSQL($normHistHSV);
        
        $sql = "INSERT INTO ". DB_PREFIX ."image(id_image, filename, filepath, filemime, filesize, 
                                      timestamp, filename_hash, color_histogram, mean, std, 
                                      hsv_histogram, hsv_mean, hsv_std, rgb_texture)
                VALUES(DEFAULT, '{$fileParts['basename']}', '{$filepath}', '{$filemime}', 
                                '{$filesize}', '{$timestamp}', '{$filename_hash}', '$pg_arrayRGB', 
                                '{$meanRGB}', '{$stdRGB}', '$pg_arrayHSV', '{$meanHSV}', '{$stdHSV}', '$pg_arrayRGBTamura')";
        $rowCount = DB::execute($sql);
        copy($tempFile, $galleryFile);
        $img->save($targetFile);
        unset($histRGB);  unset($normHistRGB);   unset($pg_arrayRGB);
        unset($histHSV);  unset($normHistHSV);   unset($pg_arrayHSV);
        unset($img);      unset($objRGB);        unset($objHSV);
		echo 'file uploaded successfully '.$rowCount;
    }
	else
		echo 'Invalid file type.';
}
?>
