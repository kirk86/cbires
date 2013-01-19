<?php require_once('header.php'); ?>

<?php
/*
echo "<pre>";
print_r($_POST);
echo "--------------";
//print_r($_SESSION);
echo "</pre>";
*/
?>
			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="gallery.php">Gallery</a>
					</li>
				</ul>
			</div>

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-picture"></i> Gallery</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
                    <!-- START OF: fullscreen button -->
                    <!--
						<p class="center">
							<button id="toggle-fullscreen" class="btn btn-large btn-primary visible-desktop" data-toggle="button">Toggle Fullscreen</button>
						</p>
                    -->
                    <!-- END OF: fullscreen button -->
                    <?php
                    //var_dump($_POST);
                    if ( Tools::getIsset('topk')             && 
                         Tools::getIsset('distanceFunction') && 
                         Tools::getIsset('colorSpace')       && 
                         Tools::getIsset('histogram')        &&
                         Validate::isUnsignedInt(Tools::getValue('topk'))
                        )
                    {
                        $sqlThres = "SELECT COUNT(*) AS total_images FROM tbl_image";
                        $imageThres = DB::getOne($sqlThres);
                        if (Tools::getValue('topk') <= 0 || Tools::getValue('topk') > $imageThres[0])
                            echo MESSAGE_INVALID_TOPK;
                        else
                        {
                            $_SESSION['threshold']        = Tools::getValue('topk');
                            $_SESSION['distanceFunction'] = Tools::getValue('distanceFunction');
                            $_SESSION['colorSpace']       = Tools::getValue('colorSpace');
                            session_write_close();
                            echo MESSAGE_SUCCESS_SETTINGS;
                        }
                    }
					
					/*
                    if ( Tools::isSubmit('requery') && 
                         Tools::getIsset('requery') && 
                         Tools::getValue('requery') == 'relevance_feedback' )
                    {
                        echo "teste";
                    }
					*/
					
                    if (Tools::isSubmit('submit') && Tools::getIsset('submit'))
                    {
						/* rocchio algorimthm start */

						$sum_nr = 0;
						$difference_nr = 0;
						$sum_image_histogram_sum = 0;
						$difference_image_histogram_sum = 0;

						foreach ($_POST as $key => $id) :
							if($key != "submit")
							{
								//echo $key." = ".$id."<br />";
								$sql_image = "SELECT array_to_json(color_histogram) AS rgb_histogram 
											  FROM tbl_image
											  WHERE id_image = ".$key;
								$qresult_image = DB::getAll($sql_image);
								
								$image_histogram = json_decode($qresult_image[0]['rgb_histogram']);
								
								//echo "<pre>";
								//print_r($image_histogram);
								//echo "</pre>";
																
								if($id == 1)
								{
									$sum_image_histogram_sum = sum_arrays($image_histogram, $sum_image_histogram_sum);
									$sum_nr++;
								}
								elseif($id == -1)
								{
									$difference_image_histogram_sum = sum_arrays($image_histogram, $difference_image_histogram_sum);
									$difference_nr++;
								}

							}
						endforeach;
												
						if($sum_image_histogram_sum != 0)
						{
							for ($i=0;$i<count($sum_image_histogram_sum);$i++)
							{
								$sum_image_histogram_sum[$i] = $sum_image_histogram_sum[$i] / $sum_nr * 0.75;
							}
						}
						
						//echo "<pre>";
						//print_r($sum_image_histogram_sum);
						//echo "</pre>";
						//echo $sum_nr;
						
						if($difference_image_histogram_sum != 0)
						{
							for ($i=0;$i<count($difference_image_histogram_sum);$i++)
							{
								$difference_image_histogram_sum[$i] = $difference_image_histogram_sum[$i] / $difference_nr * 0.25;
							}
						}
						
						//echo "<pre>";
						//print_r($difference_image_histogram_sum);
						//echo "</pre>";
						//echo $difference_nr;

						if(isset($_SESSION['rgb_histogram']))
						{
							$original_image_histogram = $_SESSION['rgb_histogram'];
						}
						
						//echo "<pre>";
						//print_r($_SESSION['rgb_histogram']);
						//echo "</pre>";
						
						$query_image_histogram_sum = 0;
						$query_image_histogram_sum = sum_arrays($original_image_histogram, $query_image_histogram_sum);
						$query_image_histogram_sum = sum_arrays($sum_image_histogram_sum, $query_image_histogram_sum);
						$query_image_histogram_sum = difference_arrays($difference_image_histogram_sum, $query_image_histogram_sum);
						
						//echo "<pre>query_histogram";
						//print_r($query_image_histogram_sum);
						//echo "</pre>";
						
						//$combRGB = PopulateImages::computeRgbImages($query_image_histogram_sum);
						
						/* rocchio algorimthm end */
						
                        $targetFolder = '/cbires/img/gallery/thumbs/query_image.jpg';
                        $galleryFolder = '/cbires/img/gallery/query_image.jpg';
                        
                        if ( !empty( $_FILES['fileInput']['name'] ) && 
                             !empty( $_FILES['fileInput']['type'] ) && 
                             !empty( $_FILES['fileInput']['tmp_name'] ) )
                        {
                            $tempFile = $_FILES['fileInput']['tmp_name'];
                            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                            $galleryPath = $_SERVER['DOCUMENT_ROOT'] . $galleryFolder;
                            
                            // Validate the file type
                            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
                            $fileParts = pathinfo($_FILES['fileInput']['name']);
                            
                            if ( in_array( Tools::strtolower( $fileParts['extension'] ), $fileTypes ) )
                            {
                                $img = new Image($tempFile);
                                $img->save($galleryPath, false);
                                $img->resize(300, 0); // Lower quality image created using width ratio otherwise $img->resize(300, 0, false); for quality not speed
                                $img->save($targetPath);
                                $resized_img = $img->file_name;
                                $_SESSION['resized_img'] = $resized_img;
                                 
                                //$filemime = 'image/'.$fileParts['extension'];
                                if ( isset($_SESSION['colorSpace'])       && !empty($_SESSION['colorSpace'])       &&
                                     isset($_SESSION['distanceFunction']) && !empty($_SESSION['distanceFunction']) &&
                                     isset($_SESSION['threshold'])        && !empty($_SESSION['threshold'])
                                   )
                                {
                                    switch ($_SESSION['colorSpace'])
                                    {
                                        case 'RGB':
										{
											$objRGB      = new Histogram($resized_img);
											$histoRGB    = $objRGB->generateHistogram();
											$normHistRGB = DistanceMetrics::computeHistogram($histoRGB, 64, min($histoRGB), max($histoRGB));
											$_SESSION['rgb_histogram'] = $normHistRGB;
											$meanRGB     = DistanceMetrics::mean($normHistRGB);
											$stdRGB      = DistanceMetrics::std($normHistRGB);
											
											$combRGB = PopulateImages::computeRgbImages($normHistRGB);
											break;
										}
                                        
                                        case 'HSV':
										{
											$objHSV      = new Histogram($resized_img);
											$histoHSV    = $objHSV->generateHistogram(true);
											$binHistHSV  = DistanceMetrics::computeHistogram($histoHSV, 64, min($histoHSV), max($histoHSV), false);
											$normHistHSV = DistanceMetrics::normalize($binHistHSV);
											$_SESSION['hsv_histogram'] = $normHistHSV;
											$meanHSV     = DistanceMetrics::mean($normHistHSV);
											$stdHSV      = DistanceMetrics::std($normHistHSV);
											
											$combHSV = PopulateImages::computeHsvImages($normHistHSV);
											break;
										}
                                    }
                                }
                                else 
								{
									$objRGB      = new Histogram($resized_img);
									$histoRGB    = $objRGB->generateHistogram();
									$normHistRGB = DistanceMetrics::computeHistogram($histoRGB, 64, min($histoRGB), max($histoRGB));
									$_SESSION['rgb_histogram'] = $normHistRGB;
									$meanRGB     = DistanceMetrics::mean($normHistRGB);
									$stdRGB      = DistanceMetrics::std($normHistRGB);
									
									$combRGB = PopulateImages::computeRgbImages($normHistRGB, true);
								}
                                unset($img);
							}
                        	else
							{
								echo MESSAGE_INVALID_FILE_TYPE;
							}
                        }
						else
						{
							//$combRGB = PopulateImages::computeRgbImages($query_image_histogram_sum);
							if ( isset($_SESSION['colorSpace'])       && !empty($_SESSION['colorSpace'])       &&
                                     isset($_SESSION['distanceFunction']) && !empty($_SESSION['distanceFunction']) &&
                                     isset($_SESSION['threshold'])        && !empty($_SESSION['threshold'])
                                   )
							{
								switch ($_SESSION['colorSpace'])
								{
									case 'RGB':
									{
										$combRGB = PopulateImages::computeRgbImages($query_image_histogram_sum);
										break;
									}
									
									case 'HSV':
									{
										$normHistHSV = DistanceMetrics::normalize($query_image_histogram_sum);
										$combHSV = PopulateImages::computeHsvImages($normHistHSV);
										break;
									}
								}
							}
							else 
							{
								$combRGB = PopulateImages::computeRgbImages($query_image_histogram_sum, true);
							}
						}
						
						//echo "<pre>comborgb";
						//print_r($combRGB);
						//echo "</pre>";
						
						?>
						
						<!-- START OF: fullscreen button -->
							<p class="center">
								<button id="toggle-fullscreen" class="btn btn-large btn-primary visible-desktop" data-toggle="button">Toggle Fullscreen</button>
							</p>
						<!-- END OF: fullscreen button -->
						
						<!-- START OF: query message 1 -->    
							<div class="alert alert-info">
								<h2><p class="center">Query Image</p></h2>
							</div>
						<!-- END OF: query message 1 -->
						
							<ul class="thumbnails gallery">
								<li id="image-query" class="thumbnail">
									<a style="background:url(img/gallery/thumbs/query_image.jpg);background-size:100px 100px;background-repeat:no-repeat;" title="Sample Image query_image.jpg" href="img/gallery/query_image.jpg">
										<img src="img/gallery/thumbs/query_image.jpg" alt="Sample Image query_image.jpg" />
									</a>
								</li>
							</ul>
							
						<!-- START OF: info message 1 -->    
							<div class="alert alert-info">
								<p class="center" style="color: blue; font-size: x-large;">Retrieval Results</p>
							</div>
						<!-- END OF: info message 1 -->
						<form class="form-horizontal" action="gallery.php" method="post">
							<fieldset>
									<ul class="thumbnails gallery">
									<!-- Query image -->
									<!--
										<li id="image-query" class="thumbnail">
											<a style="background:url(img/gallery/thumbs/query_image.jpg)" title="Sample Image Query" href="img/gallery/query_image.jpg">
												<img src="img/gallery/thumbs/query_image.jpg" alt="Sample Image Query" />
											</a>
										</li>
										-->
									<!-- Query image -->
										<table id="results_images_table">
											<tr>
										<?php
										if ( isset($_SESSION['threshold']) && !empty($_SESSION['threshold']) && 
											 isset($_SESSION['colorSpace']) && !empty($_SESSION['colorSpace'])
										   )
										{
												switch ($_SESSION['colorSpace'])
												{
													case 'RGB':
													PopulateImages::populateColorSpaceImages($combRGB);
													break;
													 
													case 'HSV':
													PopulateImages::populateColorSpaceImages($combHSV);
													break;
												}
												//session_unset();
												//session_destroy();
										 }
										 else { PopulateImages::populateColorSpaceImages($combRGB, 14); }
										?>
										</table>
									</ul>
									</table>
									<p style="text-align: center;">
										<button class="btn btn-warning btn-round" type="submit" name="submit" value="relevance_feedback">Requery</button>
									</p>
							  </fieldset>
						</form>
						<?php
						
					}
                 //session_unset();
                 ?> 
                    
                    <legend class="center"></legend>
                    
                    <!-- START OF: info message 3 -->    
                        <div class="alert alert-info">
							<p class="center" style="color: blue; font-size: x-large;">Upload - Use query image from your file system</p>
						</div>
                        
                        <div class="box-content">
						<form class="form-horizontal" action="gallery.php" method="post" target="_self" enctype="multipart/form-data">
						  <fieldset>
							<div class="control-group">
								<p class="center">File input: &nbsp;
                                    <!--<input type="hidden" name="submit-value" id="submit-value" value="submited" />-->
                                    <input class="input-file uniform_on" type="file" id="fileInput" name="fileInput" /> &nbsp;
                                    <button class="btn btn-small btn-danger btn-round" type="submit" id="submit" name="submit">Query :-)</button>
                                </p>
							</div>
						  </fieldset>
						</form>
					   </div>
                    <!-- END OF: info message 3 -->
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
    
<?php require_once('footer.php'); ?>

<?php
// functions
function sum_arrays($array1, $array2)
{
	for ($i = 0; $i <= (count($array1) -1); $i++)
	{
		$temp[$i] = $array1[$i] + $array2[$i];
	}
	return $temp;
}

function difference_arrays($array1, $array2)
{
	for ($i = 0; $i <= (count($array1) -1); $i++)
	{
		$temp[$i] = $array1[$i] - $array2[$i];
	}
	return $temp;
}
?>