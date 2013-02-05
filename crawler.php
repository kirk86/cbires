<?php require_once('header.php'); ?>


			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="crawler.php">Web Crawler</a>
					</li>
				</ul>
			</div>

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i> Web Crawler </h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
                            <legend>Start the crawler</legend>
							<div class="control-group">
								<label class="control-label" for="url_input">URL</label>
								<div class="controls">
									<?php
										if(isset($_GET['url_input']))
										{
											$url = $_GET['url_input'];
										}
										else
										{
											$url = "http://www.skai.gr";
										}
									?>
									<input class="input-xlarge focused" id="url_input" name="url_input" type="text" value="<?php echo $url;?>" />
								</div>
							</div>
								<div class="form-actions">
									<button type="submit" class="btn btn-primary" name="submit" value="crawler">Start Crawling</button>
									<button class="btn">Cancel</button>
								</div>
							</fieldset>
						</form>
                          
						<?php
						if (Tools::isSubmit('submit') && Tools::getIsset('submit') && Tools::getValue('submit') == 'crawler')
						{
							$url = $_GET['url_input'];
							
							$images = CrawlImagesFromUrl($url);

							require_once('misc/download_from_url.php');

							if(isset($images))
							{
								//print the result
								foreach ($images as $value)
								{
								    set_time_limit(0);
									download_img($value);
								}
                                ob_flush();
                                flush();
                                set_time_limit(30);
							}
						}
						?>
					</div>
				</div><!--/span-->
			</div><!--/row-->
    
<?php require_once('footer.php'); ?>
