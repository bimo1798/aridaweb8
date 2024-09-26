 <form action="<?=base_url('job/update/').$job['id'] ?>" method="post" class="form-horizontal">
 	<div class="form-group">
 		<label for="job">Job Name</label>
 		<input class="form-control" type="text" name="job" id="job" value="<?=$job['job'] ?>" placeholder="Job Name">
 	</div>
	 <div class="form-group">
		 <label for="work">Work Hour</label>
		 <select class="form-control" name="work" id="work">
			 <?php foreach ($workHour as $n) : ?>
				 <option value="<?=$n['id']; ?>" <?= ($n['id'] == $job['work_hour_id']) ? ' selected' : ''; ?>><?=$n['name']; ?> </option>
			 <?php endforeach; ?>
		 </select>
	 </div>

 <div class="modal-footer">
 	<input type="submit" name="submit" value="Save" class="btn btn-primary "/>
 </form>
