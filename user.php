<?php require_once('header.php'); ?>

<?php
if(isset($_POST['savebtn']))
{
	$sql = "UPDATE tbl_user SET username = '".$_POST['username']."', password = '".$_POST['password']."', id_user_role = ".$_POST['optionsRadios'].", status = '".$_POST['selectStaus']."', date_registered = '".$_POST['date01']."' WHERE id_user = ".$_POST['userid'];
	$result = DB::execute($sql);
}
elseif(isset($_GET['id']))
{
	$sql = "DELETE FROM tbl_user WHERE id_user = ".$_GET['id'];
	$result = DB::execute($sql);
}
?>

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="user.php">Members</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Members</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
							<thead>
								<tr>
									<th>Username</th>
									<th>Date registered</th>
									<th>Role</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							
							<?php
							$sql = "SELECT * FROM public.tbl_user, public.tbl_user_role
									WHERE tbl_user_role.id_user_role = tbl_user.id_user_role";
							$result = DB::getAll($sql);
							?>
							
							<tbody>
								<?php foreach ($result as $key => $value) : ?>
								<tr>
									<td><?php echo ucfirst($result[$key]['username']); ?></td>
									<td class="center"><?php echo $result[$key]['date_registered']; ?></td>
									<td class="center"><?php echo ucfirst($result[$key]['role']); ?></td>
									<td class="center">
										<span class="<?php if ($result[$key]['status'] == 'active') echo 'label label-success';
                                                       elseif ($result[$key]['status'] == 'pending') echo 'label label-warning';
                                                       elseif ($result[$key]['status'] == 'banned') echo 'label label-important';
                                                       elseif ($result[$key]['status'] == 'inactive') echo 'label'; ?>">
                                                 <?php if ($result[$key]['status'] == 'active') echo 'Active';
                                                       elseif ($result[$key]['status'] == 'pending') echo 'Pending';
                                                       elseif ($result[$key]['status'] == 'banned') echo 'Banned';
                                                       elseif ($result[$key]['status'] == 'inactive') echo 'Inactive'; ?>
										</span>
									</td>
									<td class="center">
										<a class="btn btn-info" href="profile.php?id=<?php echo $result[$key]['id_user'];?>">
											<i class="icon-edit icon-white"></i>  
											Edit                                            
										</a>
										<a class="btn btn-danger" href="user.php?id=<?php echo $result[$key]['id_user'];?>">
											<i class="icon-trash icon-white"></i> 
											Delete
										</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>            
					</div>
				</div><!--/span-->
			</div><!--/row-->
<?php require_once('footer.php'); ?>
