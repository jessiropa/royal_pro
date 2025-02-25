<!DOCTYPE html>
<html>
<head>
	<?php
	include('header.php');
	?>
	<title>Cek Info Pasien</title>
</head>
<body>
	<?php
	include('sidebar.php');
	?>
	<?php
	include('section.php');
	?>
	<form method="POST" action="" class="form-inline mt-3">
		<div class="col-md-12">
			<div class="form-group" style="width: 60%;">
				<label for="tgl_mulai_n">Tanggal mulai </label>
				<input type="date" name="tgl_mulai_reg" id="tgl_mulai_reg" class="form-control" style="margin-left: 1%;">
				<label for="tgl_akhir_n" style="margin-left: 1%;">sampai </label>
				<input type="date" name="tgl_akhir_reg" id="tgl_akhir_reg" class="form-control" style="margin-left: 1%;">
				<a class="btn btn-primary" onclick="insert_pasien_rj()" style="margin-left: 1%;">Info Pasien RJ</a>
				<a class="btn btn-primary" onclick="insert_pasien_ri()" style="margin-left: 1%;">Info Pasien RI</a>
				<a class="btn btn-primary" onclick="insert_pasien_rd()" style="margin-left: 1%;">Info Pasien RD</a>
			</div>
			<br>
			<br>
			<div class="form-group col" style="width:75%;">
				<label for="data_ri_n" class="col-md-2 col-form-label" style="justify-content: left;">Data RI: </label>
				<input type="text" name="data_ri" id="data_ri" class="form-control" style="width: 35%;">
			</div>
			<br>
			<div class="form-group col" style="width:75%;">
				<label for="data_rj_n" class="col-md-2 col-form-label" style="justify-content: left;">Data RJ: </label>
				<input type="text" name="data_rj" id="data_rj" class="form-control" style="width: 35%;">
			</div>
			<br>
			<div class="form-group col" style="width:75%;">
				<label for="data_rd_n" class="col-md-2 col-form-label" style="justify-content: left;">Data RD: </label>
				<input type="text" name="data_rd" id="data_rd" class="form-control" style="width: 35%;">
			</div>
			<br>
			<div class="form-group col" style="width:75%;">
				<label for="data_rd_n" class="col-md-2 col-form-label" style="justify-content: left;">Data RJ Kunj EMR: </label>
				<input type="text" name="data_rj_emr" id="data_rj_emr" class="form-control" style="width: 35%;">
			</div>
			<br>
			<div class="form-group col" style="width:75%;">
				<label for="data_rd_n" class="col-md-2 col-form-label" style="justify-content: left;">Data Lengkap RI Reg: </label>
				<input type="text" name="data_ri_emr_lengkap" id="data_ri_emr_lengkap" class="form-control" style="width: 35%;">
			</div>
		</div>
	</form>
	<?php
	include('main_footer.php');
	include('foot.php');
	?>
	<script>
		// function submit_info_pasien_reg(){
		// 	// var tgl_mulai = document.getElementById("tgl_mulai").value;
		// 	// var tgl_akhir = document.getElementById("tgl_akhir").value;
		// 	insert_pasien_ri();
		// 	insert_pasien_rj();
		// 	insert_pasien_rd();
		// }

		// setInterval(function(){
		// 	insert_pasien_ri();
		// }, 6000);

		// setInterval(function(){
		// 	insert_pasien_rj();
		// }, 8000);

		// setInterval(function(){
		// 	insert_pasien_rd();
		// }, 10000);


		// setInterval(function(){
		// 	insert_pasien_rd();
		// }, 10000);

		// setInterval(function(){
		// 	update_waktu_tdk_valid_5();
		// }, 3000);

		setInterval(function(){
			insert_rj_emr();
		}, 2000);

		setInterval(function(){
			insert_rd_emr();
		}, 3500);

		setInterval(function(){
			insert_ri_emr();
		}, 5000);

		setInterval(function(){
			insert_antrian_rj();
		}, 4500);


		setInterval(function(){
			insert_noapp_rj();
		}, 6500);


		// setInterval(function(){
		// 	insert_ri_emr_lengkap();
		// }, 10000);


		function insert_noapp_rj(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/insert_noapp_rj",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					document.getElementById("data_rj_emr").value = data;
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert("Gagal");
					document.getElementById("data_rj_emr").value = data;
				},
			});
		}


		function update_waktu_tdk_valid_5(){
			var waktu_task = 'waktu_task5';
			var task = 'task5';
			var error = 'error_5';
			var error_task = 'error_task_5';
			var taskid = '5';

			$.ajax({
				type: 'POST',
				data: { 
					waktu_task : waktu_task,
					task : task,
					error : error,
					error_task : error_task,
					taskid : taskid

				},
				url: "<?php echo base_url(); ?>index.php/list_task_id_bpjs/update_waktu_tdk_valid_5",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					// document.getElementById("waktu_tdk_valid").value = data;
					// window.location.reload();
				},
				error:function(xhr, status, error){
					console.log(error);
				// alert("Gagal");
				// document.getElementById("waktu_tdk_valid").value = error;
			},
		});
		}


		function insert_pasien_ri(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/submit_info_pasien_reg",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					document.getElementById("data_ri").value = data;
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert("Gagal");
					document.getElementById("data_ri").value = data;
				},
			});
		}

		function insert_pasien_rj(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '2';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/submit_info_pasien_reg",
			// dataType : "JSON",
			success:function(data){ 
				console.log(data);
				document.getElementById("data_rj").value = data;

			},
			error:function(xhr, status, error){
				console.log(error);
					// alert("Gagal");
					document.getElementById("data_rj").value = data;
				},
			});
		}
		
		
		function insert_pasien_rd(){
			document.getElementById("data_rd").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '3';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/submit_info_pasien_reg",
			// dataType : "JSON",
			success:function(data){ 
				console.log(data);
				document.getElementById("data_rd").value = data;
				
			},
			error:function(xhr, status, error){
				console.log(error);
				// alert("Gagal");
				document.getElementById("data_rd").value = data;
			},
		});
		}

		function insert_antrian_rj(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/insert_antrian_rj",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					document.getElementById("data_rj_emr").value = data;
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert("Gagal");
					document.getElementById("data_rj_emr").value = data;
				},
			});
		}


		function insert_rj_emr(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/insert_rj_emr",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					document.getElementById("data_rj_emr").value = data;
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert("Gagal");
					document.getElementById("data_rj_emr").value = data;
				},
			});
		}

		function insert_rd_emr(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/insert_rd_emr",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					document.getElementById("data_rj_emr").value = data;
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert("Gagal");
					document.getElementById("data_rj_emr").value = data;
				},
			});
		}

		function insert_ri_emr(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/insert_ri_emr",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					document.getElementById("data_rj_emr").value = data;
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert("Gagal");
					document.getElementById("data_rj_emr").value = data;
				},
			});
		}

		function insert_ri_emr_lengkap(){
			document.getElementById("data_rj").value = '';
			var tgl_mulai = document.getElementById("tgl_mulai_reg").value;
			var tgl_akhir = document.getElementById("tgl_akhir_reg").value;
			var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					tgl_mulai: tgl_mulai,
					tgl_akhir: tgl_akhir,
					keterangan: keterangan
				},
				url: "<?php echo base_url(); ?>index.php/cek_info_pasien/insert_ri_emr_lengkap",
				// dataType : "JSON",
				success:function(data){ 
					console.log(data);
					document.getElementById("data_ri_emr_lengkap").value = data;
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert("Gagal");
					document.getElementById("data_ri_emr_lengkap").value = data;
				},
			});
		}
	</script>
</body>
</html>
