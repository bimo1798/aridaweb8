  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<section class="content-header">
  		<div class="container-fluid">
  			<div class="row mb-2">
  				<div class="col-sm-6">
  					<h1><?= $title , $shift['shift']?></h1>
  				</div>
  				<div class="col-sm-6">
  					<ol class="breadcrumb float-sm-right">
  						<li class="breadcrumb-item"><a href="#">Home</a></li>
  						<li class="breadcrumb-item active"><?= $title,  $shift['shift'] ?></li>
  					</ol>
  				</div>
  			</div>
  		</div><!-- /.container-fluid -->
  	</section>
  	<!-- SWEET-ALERT2 -->
  	<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"> 
  		<div class="message-data" data-message="<?= $this->session->flashdata('message'); ?>"> 
  			<script>
  				const flashdata = $('.flash-data').data('flashdata');
  				const message = $('.message-data').data('message');
  				if (flashdata) {
  					swal({
  						title: message,
  						text: 'Success ' + flashdata,
  						type: 'success'
  					});
  				}  
  			</script>
  		</div>
  	</div>
  	<!-- END SWEET-ALEERT2 -->
  	<!-- Main content -->
  	<section class="content">
  		<div class="row">
  			<div class="col-12">
  				<div class="card">
  					<div class="card-header">
  						<button data-toggle="modal" data-target="#additem" class="btn btn-primary float-right">
  							<i class="fas fa-plus"></i>Add</button>
  						</div>
  						<!-- /.card-header -->
  						<div class="card-body">
  							<table id="example1" class="table table-bordered table-striped">
  								<thead>
  									<tr>
  										<th style="width: 50px">No</th>
										<?php if ($shift['work_hour_id'] == '1') {?>
											<th>Time Start</th>
										<?php } ?>
  										<th>Activity</th>
  										<th style="width: 100px">Action</th>
  									</tr>
  								</thead>
  								<tbody>
  									<?php $i =1; ?> <!--ini buat angka -->
  									<?php foreach ($detail_shift as $r) : ?>
  										<tr>
  											<td><?= $i ?></td>
  											<?php if ($shift['work_hour_id'] == '1') {?>
												<td><?=$r['time_start']; ?></td>
											<?php } ?>
  											<td><?=$r['activity_shift']; ?></td>
  											<td >
  												<a href="<?=base_url('admin/edit_detail_shift/') ?>" id="<?= $r['id'].'/'. $shift['id']?>"  data-toggle="modal" data-target="#editModal" class="badge badge-success view_data">Edit</a> <!--class ini dpt getboostrap cari aja pils & pilih yg links-->
  												<a href="<?=base_url('detail_shift/delete/').$r['id'].'/'.$shift['id']; ?>" class="badge badge-danger tombol-hapus">Delete</a> 
  											</td>
  										</tr>
  										<?php $i++; ?> <!-- ini buat penambahan angka-->
  									<?php endforeach; ?>
  								</tbody>
  							</table>
  						</div>
  						<!-- /.card-body -->
  					</div>
  					<!-- /.card -->
  				</div>
  				<!-- /.col -->
  			</div>
  			<!-- /.row -->
  		</section>
  		<!-- /.content -->
  	</div>
  	<!-- /.content-wrapper -->

  	<!-- modal country large -->
  	<div class="modal fade" id="additem" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg" role="document">
  			<div class="modal-content">
  				<div class="modal-header">
  					<h5 class="modal-title" id="largeModalLabel">Add New Detail Shift</h5>
  					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  						<span aria-hidden="true">&times;</span>
  					</button>
  				</div>
  				<div class="modal-body">
  					<form action="<?= base_url('detail_shift/save/').$shift['id'] ?>" method="post" class="form-horizontal">
  						<div class="form-group">
  							<label>Time Start</label>
  							<input class="form-control" type="time" name="time_start" id="time_start" value="07:00:00" placeholder="Time Start">
  						</div>
  						<div class="form-group">
  							<label>Activity Shift</label>
  							<input class="form-control" type="text" name="activity" id="activity" placeholder="Activity ex : Service DB">
  						</div>
  					</div>
  					<div class="modal-footer">
  						<input type="submit" name="submit" value="Save" class="btn btn-primary "/>
  					</form>
  				</div>
  			</div>
  		</div>
  	</div>
  	<!-- end modal large -->


  	<!-- memulai modal nya. pada id="$myModal" harus sama dengan data-target="#myModal" pada tombol di atas -->
  	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg" role="document">
  			<div class="modal-content">
  				<div class="modal-header">
  					<h5 class="modal-title" id="myModalLabel">Edit Detail Shift</h5>
  					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  						<span aria-hidden="true">&times;</span>
  					</button>
  				</div>
  				<!-- memulai untuk konten dinamis -->
  				<!-- lihat id="data_siswa", ini yang di pangging pada ajax di bawah -->
  				<div class="modal-body" id="data">

  				</div>
  			</div>
  		</div>
  	</div>
