		<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
			<!-- content ends -->
			</div><!--/#content.span10-->
		<?php } ?>
		</div><!--/fluid-row-->
		<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
		
		<hr />
        
        <!-- START OF: Configure Settings MessageBox -->
		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Settings</h3>
			</div>
            <?php
            $sql = "SELECT COUNT(*) AS total_images FROM tbl_image";
            $total_images = DB::getOne($sql);
            ?>
			<div class="modal-body">
				<p>General settings for image retrieval query...</p>
                <form class="form-horizontal" id="gallerySettingsForm" action="gallery.php" method="post">
                    <fieldset>
                        <div class="control-group">
                          <label class="control-label">Number of images in database</label>
                          <div class="controls">
                            <span class="input-xlarge uneditable-input"><?php echo $total_images[0]; ?></span>
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label" for="focusedInput">Nummer of results shown</label>
                          <div class="controls">
                            <input class="input-xlarge focused" id="focusedInput" name="topk" type="text" value="<?php echo $threshold = (isset($_SESSION['threshold']) && !empty($_SESSION['threshold'])) ? $_SESSION['threshold'] : 14; ?>" />
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label" for="selectError3">Distance function</label>
                          <div class="controls">
                            <select id="selectError3" name="distanceFunction">
                              <option <?php echo $Lp = (isset($_SESSION['distanceFunction']) && ($_SESSION['distanceFunction'] == "L2"))        ? "selected='L2'"        : ""; ?>>L2</option>
                              <option <?php echo $Lp = (isset($_SESSION['distanceFunction']) && ($_SESSION['distanceFunction'] == "L1"))        ? "selected='L1'"        : ""; ?>>L1</option>
                              <option <?php echo $Lp = (isset($_SESSION['distanceFunction']) && ($_SESSION['distanceFunction'] == "JDV"))       ? "selected='JDV'"       : ""; ?>>JDV</option>
                              <option <?php echo $Lp = (isset($_SESSION['distanceFunction']) && ($_SESSION['distanceFunction'] == "ChiSquare")) ? "selected='ChiSquare'" : ""; ?>>ChiSquare</option>
                              <option <?php echo $Lp = (isset($_SESSION['distanceFunction']) && ($_SESSION['distanceFunction'] == "Chebychev")) ? "selected='Chebychev'" : ""; ?>>Chebychev</option>
                              <option <?php echo $Lp = (isset($_SESSION['distanceFunction']) && ($_SESSION['distanceFunction'] == "Tanimoto"))  ? "selected='Tanimoto'"  : ""; ?>>Tanimoto</option>
                              <option <?php echo $Lp = (isset($_SESSION['distanceFunction']) && ($_SESSION['distanceFunction'] == "Cosine"))    ? "selected='Cosine'"    : ""; ?>>Cosine</option>
                            </select>
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">Color Space</label>
						   <div class="controls">
						     <label class="radio">
							   <input type="radio" name="colorSpace" id="colorSpace" value="RGB" <?php echo $colorSpace = (isset($_SESSION['colorSpace']) && ($_SESSION['colorSpace'] == "RGB")) ? "checked=''" : "checked=''"; ?> /> RGB
				             </label>
				             <div style="clear:both"></div>
                             <label class="radio">
								<input type="radio" name="colorSpace" id="colorSpace" value="HSV" <?php echo $colorSpace = (isset($_SESSION['colorSpace']) && ($_SESSION['colorSpace'] == "HSV")) ? "checked=''" : ""; ?> /> HSV
						     </label>
                           </div>
					    </div>
                        <div class="control-group">
                          <label class="control-label">Search by</label>
                          <div class="controls">
                            <label class="checkbox inline">
                              <input type="checkbox" id="inlineCheckbox1" name="histogram" value="Histogram" checked=""> Histogram
                            </label>
                            <label class="checkbox inline">
                              <input type="checkbox" id="inlineCheckbox2" name="shape" value="Shape" disabled=""> Shape
                            </label>
                            <label class="checkbox inline">
                              <input type="checkbox" id="inlineCheckbox3" name="texture" value="Texture" disabled=""> Texture
                            </label>
                          </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-primary" id="settings" name="settings" value="save_settings">Save changes</button>
                            <!--<a href="#" class="btn btn-primary" id="btn-save-changes">Save changes</a>-->
                        </div>
                    </fieldset>
                </form>
			</div>
		</div>
        <!-- END OF: Configure Settings MessageBox -->
		<footer>
			<p class="pull-left">&copy; <a href="http://mycompany.com.gr/cbires" target="_blank">CBIRES</a> <?php echo date('Y') ?></p>
		</footer>
		<?php } ?>

	</div><!--/.fluid-container-->

	<!-- external javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->

	<!-- jQuery -->
	<script src="js/jquery-1.7.2.min.js"></script>
	<!-- jQuery UI -->
	<script src="js/jquery-ui-1.8.21.custom.min.js"></script>
	<!-- transition / effect library -->
	<script src="js/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="js/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<script src="js/bootstrap-modal.js"></script>
	<!-- custom dropdown library -->
	<script src="js/bootstrap-dropdown.js"></script>
	<!-- scrolspy library -->
	<script src="js/bootstrap-scrollspy.js"></script>
	<!-- library for creating tabs -->
	<script src="js/bootstrap-tab.js"></script>
	<!-- library for advanced tooltip -->
	<script src="js/bootstrap-tooltip.js"></script>
	<!-- popover effect library -->
	<script src="js/bootstrap-popover.js"></script>
	<!-- button enhancer library -->
	<script src="js/bootstrap-button.js"></script>
	<!-- accordion library (optional, not used in demo) -->
	<script src="js/bootstrap-collapse.js"></script>
	<!-- carousel slideshow library (optional, not used in demo) -->
	<script src="js/bootstrap-carousel.js"></script>
	<!-- autocomplete library -->
	<script src="js/bootstrap-typeahead.js"></script>
	<!-- tour library -->
	<script src="js/bootstrap-tour.js"></script>
	<!-- library for cookie management -->
	<script src="js/jquery.cookie.js"></script>
	<!-- calander plugin -->
	<script src='js/fullcalendar.min.js'></script>
	<!-- data table plugin -->
	<script src='js/jquery.dataTables.min.js'></script>

	<!-- chart libraries start -->
	<script src="js/excanvas.js"></script>
	<script src="js/jquery.flot.min.js"></script>
	<script src="js/jquery.flot.pie.min.js"></script>
	<script src="js/jquery.flot.stack.js"></script>
	<script src="js/jquery.flot.resize.min.js"></script>
	<!-- chart libraries end -->

	<!-- select or dropdown enhancer -->
	<script src="js/jquery.chosen.min.js"></script>
	<!-- checkbox, radio, and file input styler -->
	<script src="js/jquery.uniform.min.js"></script>
	<!-- plugin for gallery image view -->
	<script src="js/jquery.colorbox.min.js"></script>
	<!-- rich text editor library -->
	<script src="js/jquery.cleditor.min.js"></script>
	<!-- notification plugin -->
	<script src="js/jquery.noty.js"></script>
	<!-- file manager library -->
	<script src="js/jquery.elfinder.min.js"></script>
	<!-- star rating plugin -->
	<script src="js/jquery.raty.min.js"></script>
	<!-- for iOS style toggle switch -->
	<script src="js/jquery.iphone.toggle.js"></script>
	<!-- autogrowing textarea plugin -->
	<script src="js/jquery.autogrow-textarea.js"></script>
	<!-- multiple file upload plugin -->
	<script src="js/jquery.uploadify-3.1.min.js"></script>
	<!-- history.js for cross-browser state change on ajax -->
	<script src="js/jquery.history.js"></script>
	<!-- application script for CBIRES demo -->
    <script src="js/cbires.js"></script>
	
	<?php //Google Analytics code for tracking cbires demo site, you can remove this.
		if($_SERVER['HTTP_HOST']=='cbires.com') { ?>
		<script>
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-26532312-1']); // Chage the UA-2653.... according to your will
			_gaq.push(['_trackPageview']);
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
			})();
		</script>
	<?php } ?>
	
</body>
</html>
