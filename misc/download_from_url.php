<?php
/*
require_once('../config/config.php');
require_once(CB_DB_DIR . 'DB.php');
require_once(CB_INCLUDES_DIR . 'Tools.php');
require_once(CB_CORE_DIR . 'ColorSpaceConversion.php');
require_once(CB_CORE_DIR . 'Histogram.php');
require_once(CB_CORE_DIR . 'DistanceMetrics.php');
require_once(CB_CORE_DIR . 'Image.php');
*/


function download_img($img_url)
{
	// Define a destination
	$targetFolder = '../cbires/img/gallery/thumbs'; // Relative to the root
	$galleryFolder = '../cbires/img/gallery';
	$crawlerFolder = '../cbires/img/crawler';

	//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

	//$tempFile = $_FILES['Filedata']['tmp_name'];
	$filenameURL = basename($img_url);
    $tempFile = $crawlerFolder."/".$filenameURL;
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
    $galleryPath = $_SERVER['DOCUMENT_ROOT'] . $galleryFolder;

	// Validate the file type
	$fileTypes = array('jpg','jpeg'); // File extensions	
	$fileParts = pathinfo($tempFile);
	$filepath = $crawlerFolder;
	
	if(isset($fileParts['extension']))
	{
		if ( in_array( Tools::strtolower( $fileParts['extension'] ), $fileTypes) )
		{
			// size from online (if exists)
			if(getimagesize($img_url))
			{
				$date      = date_create();
				$timestamp = date_format($date, 'Y-m-d H:i:s');
				$timestamp1 = date_format($date, 'Y-m-d-H-i-s');
				$filename_hash = $timestamp1.'_'.$fileParts['basename'];
				$targetFile = rtrim($targetPath,'/') . '/' . $filename_hash;
				$galleryFile = rtrim($galleryPath,'/') . '/' . $filename_hash;
				
				file_put_contents($tempFile, file_get_contents($img_url));
				$filesize = filesize($tempFile);
				
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
				//echo 'file uploaded successfully '.$rowCount;
				
				?>
				<div style="float:left;padding:10px;">
					<img src="<?php echo $img_url;?>" width="100" height="100" />
				</div>
				<?php

				// delete image
				unlink($tempFile);
			}
		}
	}
}
?>
