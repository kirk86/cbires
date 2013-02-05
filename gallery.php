<?php require_once('header.php'); ?>
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
                    <?php
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
                            unset($_SESSION['rgb_histogram']);
                            unset($_SESSION['hsv_histogram']);
                            session_write_close();
                            echo MESSAGE_SUCCESS_SETTINGS;
                        }
                    }					
                    if (Tools::isSubmit('submit') && Tools::getIsset('submit'))
                    {
						/* rocchio algorimthm start */

						$sum_nr = 0;
						$difference_nr = 0;
						$sum_image_histogram_sum = array_fill(0, 64, '0');
						$difference_image_histogram_sum = array_fill(0, 64, '0');
                        
						foreach ($_POST as $key => $id) :
							if($key != "submit")
							{
                                if (isset($_SESSION['rgb_histogram']) && !empty($_SESSION['rgb_histogram']))
                                {
                                    $sql_image = "SELECT array_to_json(color_histogram) AS image_histogram 
											  FROM tbl_image
											  WHERE id_image = ".$key;
                                }
                                if (isset($_SESSION['hsv_histogram']) && !empty($_SESSION['hsv_histogram']))
                                {
                                    $sql_image = "SELECT array_to_json(hsv_histogram) AS image_histogram 
											  FROM tbl_image
											  WHERE id_image = ".$key;
                                }
								$qresult_image = DB::getAll($sql_image);
								$image_histogram = json_decode($qresult_image[0]['image_histogram']);				
								if($id == 1)
								{
									$sum_image_histogram_sum = DistanceMetrics::sumVectors($image_histogram, $sum_image_histogram_sum);
									$sum_nr++;
								}
								elseif($id == -1)
								{
									$difference_image_histogram_sum = DistanceMetrics::sumVectors($image_histogram, $difference_image_histogram_sum);
									$difference_nr++;
								}

							}
						endforeach;
												
						if($sum_nr != 0)
						{
							for ($i = 0; $i < count($sum_image_histogram_sum); $i++)
							{
								$sum_image_histogram_sum[$i] = $sum_image_histogram_sum[$i] / $sum_nr * 0.75;
							}
						}
						
						if($difference_nr != 0)
						{
							for ($i = 0; $i < count($difference_image_histogram_sum); $i++)
							{
								$difference_image_histogram_sum[$i] = $difference_image_histogram_sum[$i] / $difference_nr * 0.25;
							}
						}

						if(isset($_SESSION['rgb_histogram']) || isset($_SESSION['hsv_histogram']))
						{
                            if (!empty($_SESSION['rgb_histogram']))
                                $original_image_histogram = $_SESSION['rgb_histogram'];
                            elseif (!empty($_SESSION['hsv_histogram']))
                                $original_image_histogram = $_SESSION['hsv_histogram'];

							
                            $query_image_histogram_sum = array_fill(0, 64, 0);
							//$query_image_histogram_sum = 0;
							$query_image_histogram_sum = DistanceMetrics::sumVectors($original_image_histogram, $query_image_histogram_sum);
							$query_image_histogram_sum = DistanceMetrics::sumVectors($sum_image_histogram_sum, $query_image_histogram_sum);
                            //$query_image_histogram_sum = DistanceMetrics::sumVectors($sum_image_histogram_sum, $original_image_histogram);
							$query_image_histogram_sum = DistanceMetrics::diffVectors($query_image_histogram_sum, $difference_image_histogram_sum);

							// uncoment this if you want to requery multiple times
                            if (!empty($_SESSION['rgb_histogram']))
                                $_SESSION['rgb_histogram'] = $query_image_histogram_sum;
                            elseif (!empty($_SESSION['hsv_histogram']))
                                $_SESSION['hsv_histogram'] = $query_image_histogram_sum;
                            
                            session_write_close();
						}
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
                                            unset($_SESSION['hsv_histogram']);
                                            session_write_close();
											$meanRGB     = DistanceMetrics::mean($normHistRGB);
											$stdRGB      = DistanceMetrics::std($normHistRGB);
											
											$combRGB = PopulateImages::computeRgbImages($normHistRGB);
											
											// Retrieval Results
											PopulateImages::retrievalResults($combRGB);
											
											break;
										}
                                        
                                        case 'HSV':
										{
											$objHSV      = new Histogram($resized_img);
											$histoHSV    = $objHSV->generateHistogram(true);
											$binHistHSV  = DistanceMetrics::computeHistogram($histoHSV, 64, min($histoHSV), max($histoHSV), false);
											$normHistHSV = DistanceMetrics::normalize($binHistHSV);
											$_SESSION['hsv_histogram'] = $normHistHSV;
                                            unset($_SESSION['rgb_histogram']);
                                            session_write_close();
											$meanHSV     = DistanceMetrics::mean($normHistHSV);
											$stdHSV      = DistanceMetrics::std($normHistHSV);
											
											$combHSV = PopulateImages::computeHsvImages($normHistHSV);
											
											// Retrieval Results
											PopulateImages::retrievalResults($combHSV);
											
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
                                    unset($_SESSION['hsv_histogram']);
                                    session_write_close();
									$meanRGB     = DistanceMetrics::mean($normHistRGB);
									$stdRGB      = DistanceMetrics::std($normHistRGB);
									
									$combRGB = PopulateImages::computeRgbImages($normHistRGB, true);
									
									// Retrieval Results
									PopulateImages::retrievalResults($combRGB);
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
                                        if ( empty( $_FILES['fileInput']['name'] ) && empty( $_FILES['fileInput']['type'] ) && 
                                             empty( $_FILES['fileInput']['tmp_name']) && Tools::getValue('submit') != "relevance_feedback" )
                                             echo MESSAGE_EMPTY_FILE_UPLOAD;
                                        else
                                        {                                         
                                            $combRGB = PopulateImages::computeRgbImages($query_image_histogram_sum);
                                            // Retrieval Results
                                            PopulateImages::retrievalResults($combRGB);
                                        }										
										break;
									}
									
									case 'HSV':
									{
                                        if ( empty( $_FILES['fileInput']['name'] ) && empty( $_FILES['fileInput']['type'] ) && 
                                             empty( $_FILES['fileInput']['tmp_name']) && Tools::getValue('submit') != "relevance_feedback" )
                                             echo MESSAGE_EMPTY_FILE_UPLOAD;
                                        else
                                        {
                                            $normHistHSV = DistanceMetrics::normalize($query_image_histogram_sum);
                                            $combHSV = PopulateImages::computeHsvImages($normHistHSV);
                                            // Retrieval Results
                                            PopulateImages::retrievalResults($combHSV);
                                        }						
										break;
									}
								}
							}
							else 
							{
    							 if ( empty( $_FILES['fileInput']['name'] ) && empty( $_FILES['fileInput']['type'] ) && 
                                      empty( $_FILES['fileInput']['tmp_name'] ) && Tools::getValue('submit') != "relevance_feedback" )
                                    echo MESSAGE_EMPTY_FILE_UPLOAD;
                                 else
                                 {
                                    $combRGB = PopulateImages::computeRgbImages($query_image_histogram_sum, true);
    								
    								// Retrieval Results
    								PopulateImages::retrievalResults($combRGB);
                                 }
							}
						}
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