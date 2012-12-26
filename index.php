<?php require_once('header.php'); ?>

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="index.php">Dashboard</a>
					</li>
				</ul>
			</div>
			<div class="sortable row-fluid">
				<a data-rel="tooltip" title="6 new members." class="well span3 top-block" href="#">
					<span class="icon32 icon-red icon-user"></span>
					<div>Total Members</div>
					<div>507</div>
					<span class="notification">6</span>
				</a>

				<a data-rel="tooltip" title="4 new image categories." class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-star-on"></span>
					<div>Image Categories</div>
					<div>228</div>
					<span class="notification green">4</span>
				</a>

				<a data-rel="tooltip" title="34 new images." class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-image"></span>
					<div>Total Images</div>
					<div>13320</div>
					<span class="notification yellow">34</span>
				</a>
				
				<a data-rel="tooltip" title="12 new logs." class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-book"></span>
					<div>Logs</div>
					<div>25</div>
					<span class="notification red">12</span>
				</a>
			</div>
			
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Introduction</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<h1>CBIRES <small>free, open-source, online, content based image retrieval system.</small></h1>
						<p>It's a live demo of the CBIRES, which was created due to an assignement during our graduate degree in CS. :)</p>
                        <?php //show the following to users that are playing around with the live demo?>
						<p><b>At the moment you cannot delete images from the databse, you can only insert new ones !</b></p>
						<?php //end of message ?>
						<p class="center">
							<a href="https://github.com/kirk86/cbires" class="btn btn-large"><i class="icon-download-alt"></i> Download Page</a>
						</p>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
					
			<div class="row-fluid sortable">
            <!-- START OF Tabs -->
				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-th"></i> Tabs</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active"><a href="#info">Info</a></li>
							<li><a href="#custom">Custom</a></li>
							<li><a href="#messages">Messages</a></li>
						</ul>
						 
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane active" id="info">
								<h3>CBIRES <small>a content based image retrieval system</small></h3>
								<p>Its a responsive image retrieval system. Its optimized for tablet and mobile phones. Scan the QR code below to view it in your mobile device.</p> <img alt="QR Code" class="cbires_qr center" src="img/qrcode136.png" />
							</div>
							<div class="tab-pane" id="custom">
								<h3>Custom <small>small text</small></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor.</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla. Donec consectetur, velit a pharetra ultricies, diam lorem lacinia risus, ac commodo orci erat eu massa. Sed sit amet nulla ipsum. Donec felis mauris, vulputate sed tempor at, aliquam a ligula. Pellentesque non pulvinar nisi.</p>
							</div>
							<div class="tab-pane" id="messages">
								<h3>Messages <small>small text</small></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla. Donec consectetur, velit a pharetra ultricies, diam lorem lacinia risus, ac commodo orci erat eu massa. Sed sit amet nulla ipsum. Donec felis mauris, vulputate sed tempor at, aliquam a ligula. Pellentesque non pulvinar nisi.</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor.</p>
							</div>
						</div>
					</div>
				</div><!--/span-->
            <!-- END OF Tabs -->
						
				<div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Member Activity</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<div class="box-content">
							<ul class="dashboard-list">
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="John Doe" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Name:</strong> <a href="#">John Doe
									</a><br>
									<strong>Since:</strong> 17/05/2012<br>
									<strong>Status:</strong> <span class="label label-success">Approved</span>                                  
								</li>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="Jack" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Name:</strong> <a href="#">Jack
									</a><br>
									<strong>Since:</strong> 17/05/2012<br>
									<strong>Status:</strong> <span class="label label-warning">Pending</span>                                 
								</li>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="Gravatar" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Name:</strong> <a href="#">Maria
									</a><br>
									<strong>Since:</strong> 25/05/2012<br>
									<strong>Status:</strong> <span class="label label-important">Banned</span>                                  
								</li>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="Shanon" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Name:</strong> <a href="#">Shanon
									</a><br>
									<strong>Since:</strong> 17/05/2012<br>
									<strong>Status:</strong> <span class="label label-info">Updates</span>                                  
								</li>
							</ul>
						</div>
					</div>
				</div><!--/span-->
                
				<!-- YOU CAN ADD MORE GRIDS HERE BELOW -->
                
                <!-- START OF Image Categories -->
                <div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-star"></i> Image Categories </h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<div class="box-content">
							<ul class="dashboard-list">
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="Africa" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Category:</strong> Africa <br />
									<strong>Subcategory:</strong> Natives <br />
                                    <strong>Date:</strong> 17/05/2012<br />                                  
								</li>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="Beach" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Category:</strong> Beach <br/>
                                    <strong>Subcategory:</strong> California <br />
									<strong>Date:</strong> 17/05/2012 <br />                                 
								</li>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="Monuments" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Category:</strong> Monuments <br />
									<strong>Subcategory:</strong> Coloseum <br />
                                    <strong>Date:</strong> 25/05/2012 <br />                                  
								</li>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="Buses" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50"></a>
										<strong>Category:</strong> Buses <br />
									<strong>Sucategory:</strong> Public Trans <br />
                                    <strong>Date:</strong> 17/05/2012 <br />                                  
								</li>
							</ul>
						</div>
					</div>
				</div><!--/span-->
              <!-- END OF Image Categories -->
			</div><!--/row-->
            

			<div class="row-fluid sortable">
            
            <!-- START OF REALTIME TRAFFIC -->
            <div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Realtime Traffic</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<div id="realtimechart" style="height:190px;"></div>
							<p class="clearfix">You can update a chart periodically to get a real-time effect by using a timer to insert the new data in the plot and redraw it.</p>
							<p>Time between updates: <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> milliseconds</p>
					</div>
				</div><!--/span-->
            <!-- END OF REALTIME TRAFFIC -->
			
            <!-- START OF Weekly Stat -->		
				<div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list"></i> Weekly Stat</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<ul class="dashboard-list">
							<li>
								<a href="#">
									<i class="icon-arrow-up"></i>                               
									<span class="green">92</span>
									New Comments                                    
								</a>
							</li>
						  <li>
							<a href="#">
							  <i class="icon-arrow-down"></i>
							  <span class="red">15</span>
							  New Registrations
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-minus"></i>
							  <span class="blue">36</span>
							  New Articles                                    
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-comment"></i>
							  <span class="yellow">45</span>
							  User reviews                                    
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-arrow-up"></i>                               
							  <span class="green">112</span>
							  New Comments                                    
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-arrow-down"></i>
							  <span class="red">31</span>
							  New Registrations
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-minus"></i>
							  <span class="blue">93</span>
							  New Articles                                    
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-comment"></i>
							  <span class="yellow">254</span>
							  User reviews                                    
							</a>
						  </li>
						</ul>
					</div>
				</div><!--/span-->
            <!-- END OF Weekly Stat -->
                
                <!-- YOU CAN ADD MORE GRIDS BELOW HERE -->
            <!-- START OF Donut chart -->    
                <div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Donut</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						 <div id="donutchart" style="height: 300px;">
					</div>
				</div>
			</div>
           <!-- END OF Donut chart -->
			</div><!--/row-->
       
<?php require_once('footer.php'); ?>
