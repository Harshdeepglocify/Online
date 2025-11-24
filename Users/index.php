<?php include "../config/config.php"; ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo ADMIN_Text; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "../config/css.php"; ?> 
	<script type="text/javascript">
	
	function DeleteId(Delete1)
	{ var x;  
	  if (confirm("Are you Sure ? you want to Delete Comment!") == true) 
	  {	 var dataString = 'Delete='+ Delete1 ;
	  
		 $.ajax({
			 type: "POST",
			 url: "Robot.php",
			 data: dataString,
			 cache: false,
			 success: function(result){
			 
			   if(result== 1)
			   {
				$("#Message").html('<div class="alert alert-success fade in"><i class="icon-remove close" data-dismiss="alert"></i><strong>Your Selected Comment Deleted!</strong>.</div>');
				$("#RefreshPage").load("index.php #RefreshPage");
			   }
			 }
		  });
	  }else	{ x = "Your Data is safe!"; }		  
	}
	
	function ViewId(View)
	{ var x;  
	 	 var dataString = 'ViewComment='+ View ;
	  
		 $.ajax({
			 type: "POST",
			 url: "Robot.php",
			 data: dataString,
			 cache: false,
			 success: function(result){
			 
			   if(result== 1)
			   {
				$("#Message").html('<div class="alert alert-success fade in"><i class="icon-remove close" data-dismiss="alert"></i><strong>Your Selected User Deleted!</strong>.</div>');
				$("#RefreshPage").load("index.php #RefreshPage");
			   }
			 }
		  });
	}
	
	
	
	function Status(userstatus,userid)
	{ var x;  
	
	  if (confirm('Are you Sure ? you want to change status') == true) 
	  {	 var dataString = 'Status='+userstatus+'&User='+userid ;
	  	
		
		 $.ajax({
			 type: "POST",
			 url: "Robot.php",
			 data: dataString,
			 cache: false,
			 success: function(result){
			 
			   if(result== 2)
			   {			   
				$("#Message").html('<div class="alert alert-success fade in"><i class="icon-remove close" data-dismiss="alert"></i><strong>Your Selected User Active</strong>.</div>');
				$("#RefreshPage").load("index.php #RefreshPage");
				
			   }
			   if(result== 3)
			   {			   
				$("#Message").html('<div class="alert alert-success fade in"><i class="icon-remove close" data-dismiss="alert"></i><strong>Your Selected User Deactive</strong>.</div>');
				$("#RefreshPage").load("index.php #RefreshPage");
				
			   }
			 }
		  });
	  }else	{ x = "Your Data is safe!"; }		  
	}
	
	</script>
	
  </head>
  <body   class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

   		<?php include "../config/top-header.php"; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
      	<?php include "../config/left-sidebar.php"; ?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        	<h1><i class="fa fa-th-list"></i> Manage <small>Users List</small></h1>
        	<ul class="breadcrumb">
				<li><a href="<?php echo ADMIN_URL; ?>"><i class="fa fa-home"></i>Home</a></li>
				<li class="active">Users List</li>				
			</ul>
		</section>
        <!-- Main content -->
		
		
        <section class="content">
		<div id="Message"></div>
			
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-search"></i> View Users List</h3>
					<div class="box-tools pull-right"></div>
				</div>
    			<div class="box-body table-responsive">
					<div id="w0">						
						<style>
						.glyphicon-remove-circle {
						  color : #C9302C;
						}
						.glyphicon-ok-circle {
						  color : #449D44;
						}
						.box-body table tr th { color: #3c8dbc;}
						</style>

				<div id="w1" class="grid-view">   		              
                <div id="RefreshPage" class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Email</th>  
						<th>Phone</th>						
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php 
							$Blog = query("select * from contact_us order by id desc"); 

							while($rw = fetch($Blog)): ?>						
							
							  <tr>
							  	<td><?=$rw['name']?></td>
								<td><?=$rw['subject']?></td>
								<td><?=$rw['email'];?></td>
								<td><?=$rw['phone'];?></td>					   
								<td class="text-center">
								<?php if($rw['approve'] == 'No'){ ?>
								<!--	<a onClick="Status(this.name,this.id)"class="toggle-column" name="<?=$rw['approve']?>" id="<?=$rw['id']?>" href="javascrip:void(0)" title="Approve Comment" >
										<span class="glyphicon glyphicon-ok-circle fa-lg"></span>
									</a>&nbsp;
									<?php }else{ ?>
									<a onClick="Status(this.name,this.id)"class="toggle-column" name="<?=$rw['approve']?>" id="<?=$rw['id']?>" href="javascrip:void(0)" title="Not Approve Comment" >
										<span class="glyphicon glyphicon-remove-circle fa-lg"></span>
									</a>&nbsp; 
									<?php } ?>
									<!--<a href="Edit.php?UpdateId=<?=$rw['id']?>" title="Update" >
										<span class="glyphicon glyphicon-pencil"></span>
									</a> &nbsp;-->
									<a class="ajaxDelete"  onClick="DeleteId(this.id)" id="<?=$rw['id']?>" title="Delete" href="javascript:void(0)" >Delete
										<span class="glyphicon glyphicon-trash"></span>
									</a>&nbsp;
									<a class="ajaxDelete" href="View.php?view=<?php echo $rw['id'] ?> " title="View Comment" >View
							
						<!--	onClick="ViewId(this.id)" id="<?=$rw['id']?>" title="View" href="javascript:void(0)" > -->
										<span class="glyphicon glyphicon-edit"></span>
									</a>&nbsp;
								</td>
							  </tr>
					  <?php endwhile;	?>                   
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
     <?php include "../config/footer.php"; ?>

     <?php include "../config/setting.php"; ?>
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo ADMIN_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo ADMIN_URL; ?>bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="<?php echo ADMIN_URL; ?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php echo ADMIN_URL; ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo ADMIN_URL; ?>plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo ADMIN_URL; ?>dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo ADMIN_URL; ?>dist/js/demo.js"></script>
    <!-- page script -->
    <script>
      $(function () {
        $("#example1").DataTable({"ordering": false });
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
    </script>
  </body>
</html>
