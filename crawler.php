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
								<label class="control-label" for="focusedInput">URL</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="focusedInput" type="text" value="http://www.geegz.com/" />
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
                            $mycrawler = new Crawler();
                            $url = 'http://www.geegz.com/';
                            $image = $mycrawler->crawlImage($url);
                            
                            //print the result
                            
                            echo "<table width=\"100%\" border=\"1\">
                              <tr>
                                <td width=\"30%\"><div align=\"center\"><b>Image</b></div></td>
                                <td width=\"30%\"><div align=\"center\"><b>Link</b></div></td>
                                <td width=\"40%\"><div align=\"center\"><b>Image Link</b> </div></td>
                              </tr>";
                            for ($i = 0; $i < sizeof($image['link']); $i++)
                            {
                                echo "<tr>
                                <td><div align=\"center\"><img src=\"" . $image['src'][$i] . "\"/></div></td>";
                                if (($image['link'][$i]) == null)
                                {
                                    echo "<td width=\"30%\"><div align=\"center\">No Link</div></td>
                                    	   <td width=\"40%\"><div align=\"center\">No Link</div></td>
                                        </tr>";
                                } else
                                {
                                    echo "<td><div align=\"center\">" . $image['link'][$i] . "</div></td>
                                    	   <td><div align=\"center\"><a href=\"" . $image['link'][$i] . "\">Go to link.</a></div></td>
                             	         </tr>";
                                }
                            
                            }
                            echo "</table>";
                          }
                          ?>
					
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
    
<?php require_once('footer.php'); ?>
