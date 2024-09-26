  <form action="<?=base_url('shift/update/').$shift['id'] ?>" method="post" class="form-horizontal"
  id="editShift">
  	<div class="form-group">
  		<label>Shiftsss</label>
  		<input  class="form-control" type="text" name="shift" id="shift_" value="<?=$shift['shift']  ?>" placeholder="Shift">
  	</div>
  	<div class="form-group">
  		<label>Start Time</label>
  		<input class="form-control" type="time" name="start_time" id="start_time" value="<?=$shift['start_time']  ?>" placeholder="Start Time">
  	</div>
  	<div class="form-group">
  		<label>End Time</label>
  		<input class="form-control" type="time" name="end_time" id="end_time" value="<?=$shift['end_time']  ?>" placeholder="End Time">
  	</div>
	  <div class="form-group">
		  <label>Work Hour</label>
		  <select class="form-control" name="work_id" id="work_id">
			  <!--<option value="1">Office Hour</option>
			  <option value="2">MII_Engineer</option>-->
			  <?php foreach ($workHour as $n) : ?>
				  <option value="<?=$n['id']; ?>" <?= in_array($shift['work_hour_id'], $workHour) ? "selected" : "" ?> ><?=$n['name']; ?></option>
			  <?php endforeach; ?>
		  </select>
	  </div>
  </div>
  <div class="modal-footer">
  	<input type="submit" name="submit" value="Save" class="btn btn-primary "/>
  </form>
