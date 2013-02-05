<?php

require_once('../config/config.php');
require_once(CB_DB_DIR . 'DB.php');
require_once(CB_INCLUDES_DIR . 'Tools.php');
require_once(CB_CORE_DIR . 'ColorSpaceConversion.php');
require_once(CB_CORE_DIR . 'Histogram.php');
require_once(CB_CORE_DIR . 'DistanceMetrics.php');
require_once(CB_CORE_DIR . 'Image.php');

// Define a destination
$targetFolder = '/cbires/img/gallery/thumbs'; // Relative to the root
$galleryFolder = '/cbires/img/gallery';

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES))
{
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
    $galleryPath = $_SERVER['DOCUMENT_ROOT'] . $galleryFolder;
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
    
    $filesize = filesize($tempFile);
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
        $img->resize(300, 0, true); // Lower quality image created using width ratio
        $resized_img = $img->file_name;
        
        $objRGB = new Histogram($resized_img);
        $objHSV = new Histogram($resized_img);
        
        # Generate RGB histogram
        $histRGB = $objRGB->generateHistogram();
        $normHistRGB = DistanceMetrics::computeHistogram($histRGB, 64, min($histRGB), max($histRGB));
        $meanRGB = DistanceMetrics::mean($normHistRGB);
        $stdRGB = DistanceMetrics::std($normHistRGB);
        $pg_arrayRGB = Tools::phpArray2PostgressSQL($normHistRGB);
        
        # Generate HSV histogram
        $histHSV = $objHSV->generateHistogram(true);
        $binHistHSV = DistanceMetrics::computeHistogram($histHSV, 64, min($histHSV), max($histHSV), false);
        $normHistHSV = DistanceMetrics::normalize($binHistHSV);
        $meanHSV = DistanceMetrics::mean($normHistHSV);
        $stdHSV = DistanceMetrics::std($normHistHSV);
        $pg_arrayHSV = Tools::phpArray2PostgressSQL($normHistHSV);
        
        $sql = "INSERT INTO ". DB_PREFIX ."image(id_image, filename, filepath, filemime, filesize, 
                                      timestamp, filename_hash, color_histogram, mean, std, 
                                      hsv_histogram, hsv_mean, hsv_std)
                VALUES(DEFAULT, '{$fileParts['basename']}', '{$filepath}', '{$filemime}', 
                                '{$filesize}', '{$timestamp}', '{$filename_hash}', '$pg_arrayRGB', 
                                '{$meanRGB}', '{$stdRGB}', '$pg_arrayHSV', '{$meanHSV}', '{$stdHSV}')";
        $rowCount = DB::execute($sql);
        copy($tempFile, $galleryFile);
        $img->save($targetFile);
        unset($histRGB);  unset($normHistRGB);   unset($pg_arrayRGB);
        unset($histHSV);  unset($normHistHSV);   unset($pg_arrayHSV);
        unset($img);       unset($objRGB);        unset($objHSV);
		echo 'file uploaded successfully '.$rowCount;
    }
	else
		echo 'Invalid file type.';
}
?>
