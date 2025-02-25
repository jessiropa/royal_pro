<!DOCTYPE html>
<html>
<head>
	<?php
	include('header.php');
	?>
	<title>Mobile JKN</title>
</head>
<header style="background: #e8ffd3;padding-bottom: 1%;">
	<img src="<?php echo base_url() . 'assets/img/royal.png'; ?>" height="100" width="240" /> 
</header>
<body onclick="document.getElementById('no_mjkn').focus();" style="background: #fbfad1;">
	
	<!-- Content Wrapper. Contains page content -->
	<div class="">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<form method="POST" action="" class="form-inline mt-3">
					<div class="col-md-12">
						<center style="margin-right: 10%;">
							
							<b style="font-size: 40px;">Pendaftaran Mandiri Pasien Mobile JKN</b>
						</center>
						<br>
						<br>
						<br>
						<br>
						<center>
							<div class="form-group col" style="width:75%;">
								<input type="text" name="no_mjkn" id="no_mjkn" class="form-control" style="width: 28%;height: 45px;" placeholder="Kode Booking MJKN">
								<a class="btn btn-primary" onclick="cek_data_booking()" style="margin-left: 1%;width: 16%;height: 45px;">Info Kode Booking</a>
								<a class="btn btn-default" onclick="setNumber(1)" style="margin-left: 5%;width: 10%;height: 45px;">1</a>
								<a class="btn btn-default" onclick="setNumber(2)" style="margin-left: 1%;width: 10%;height: 45px;">2</a>
								<a class="btn btn-default" onclick="setNumber(3)" style="margin-left: 1%;width: 10%;height: 45px;">3</a>
							</div>
							<div class="form-group col" style="width:75%;">
								<!-- <a class="btn btn-danger" onclick="cek_blacklist()" style="margin-top: 2%;width: 22%">Daftar</a> -->
								<!-- <a class="btn btn-warning" onclick="cetak_sep()" style="margin-left: 4%;margin-top: 2%;width: 19%">Cetak SEP</a> -->
								<a class="btn btn-default" onclick="setNumber(4)" style="margin-left: 50%;margin-top: 1%;width: 10%;height: 45px;">4</a>
								<a class="btn btn-default" onclick="setNumber(5)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">5</a>
								<a class="btn btn-default" onclick="setNumber(6)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">6</a>
							</div>
							<div class="form-group col" style="width:75%;">
								<a class="btn btn-default" onclick="setNumber(7)" style="margin-left: 50%;margin-top: 1%;width: 10%;height: 45px;">7</a>
								<a class="btn btn-default" onclick="setNumber(8)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">8</a>
								<a class="btn btn-default" onclick="setNumber(9)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">9</a>
							</div>
							<div class="form-group col" style="width:75%;">
								<a class="btn btn-default" onclick="setNumber('-')" style="margin-left: 50%;margin-top: 1%;width: 10%;height: 45px;">-</a>
								<a class="btn btn-default" onclick="setNumber(0)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">0</a>
								<a class="btn btn-default" onclick="setDelete()" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;"><i class="fa-solid fa-arrow-left"></i></a>
								<br>
								<a class="btn btn-danger" onclick="clearInput()" style="margin-left: 50%;margin-top: 2%;width: 32%">Clear</a>
							</div>
							<br>
							<br>
							<!-- <div class="row" style="margin-left: 0%;">
								<div class="col">
									Hasil Json Rujukan
								</div>
							</div>


							<div class="row" style="margin-left: 0%;">
								<div class="col">
									<textarea name="hasil_json_ruj" id="hasil_json_ruj" class="form-control" style="width: 100%; height: 300px;" disabled=""></textarea>
								</div>
							</div> -->
						</center>
					</div>
				</form>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->
	</div>




	<div id="modalLompatInfoPasien" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">					
					<h4 class="blue modal-title">
						<b> Info Mobile JKN Pasien</b>
					</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="text" name="no_mjkn_press" id="no_mjkn_press" class="form-control" style="width: 50%;height: 35px;" disabled="">
					<p id="nama_pasien"></p>
					<p id="no_kartu"></p>
					<p id="tgl_daftar"></p>
					<p id="nama_dokter"></p>
					<p id="nama_poli"></p>		
					<p id="no_antrian"></p>			
				</div>
				<div class="modal-footer">
					<a class="btn btn-danger" onclick="susah_finger()" style="width: 30%;height: 45px;">Susah Finger</a>
					<a class="btn btn-primary" onclick="cek_blacklist()" style="width: 30%; height: 45px;">Daftar</a>
					<a class="btn btn-success" class="btn btn-default" data-dismiss="modal" style="width: 30%; height: 45px;">Tutup</a>
					<!-- <button type="button" style="width: 30%; height: 45px; class="btn btn-default" data-dismiss="modal">Tutup</button> -->
				</div>
			</div>

		</div>
	</div>

	<div id="modalLompatMenuRajal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">					
					<h4 class="blue modal-title" id="EMR_warning_msg">
						
					</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<center>
						<center>
							<div id="EMR_img_warning"></div>
							<h5 id="MJKN_halaman"></h5>
						</center>
					</center>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>

		</div>
	</div>

	<div id="modalLompatError" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">					
					<h4 class="blue modal-title" id="MJKN_warning_msg">
					</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<center>
						<div id="MJKN_error_img"></div>
						<p id="MJKN_error_msg"></p><br>
						<h5 id="MJKN_error_halaman"></h5>
					</center>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>

		</div>
	</div>
	<!-- /.content-wrapper -->
	<?php
	include('foot.php');
	?>
	<script>
		window.onload = function() {  
			document.getElementById("no_mjkn").focus();
		}

		function setNumber(clickedNumber){
			document.getElementById("no_mjkn").value += clickedNumber;
			document.getElementById("no_mjkn").focus();
		}
		function setDelete() {
			document.getElementById("no_mjkn").value = document.getElementById("no_mjkn").value.slice(0, -1);
			document.getElementById("no_mjkn").focus();
		}
		function clearInput() {
			document.getElementById("no_mjkn").value = '';
			document.getElementById("no_mjkn").focus();
		}

		function susah_finger(){
			var no_app = document.getElementById("no_mjkn").value;
			$.ajax({
				type: 'POST',
				data: { 
					no_mjkn: no_app
				},
				url: "<?php echo base_url(); ?>index.php/mjkn_pro/susah_finger",
				dataType : "JSON",
				success:function(data){ 
					console.log(data);
					if(data.data_finger == '1'){
						$("#MJKN_halaman").html("Berhasil Approval sidik jari, silahkan melakukan pendaftaran pada mesin APM");
						// $("#EMR_warning_msg").html("Berhasil");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
					} else if(data.data_finger == '2'){
						$("#MJKN_halaman").html("Mohon maaf, terjadi kegagalan akses finger BPJS, "+ data.string_mess +", silahkan melakukan pendaftaran pada mesin APM");
						// $("#EMR_warning_msg").html("Gagal");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
						// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
					} 
					else {
						$("#MJKN_halaman").html("Mohon maaf, terjadi kegagalan akses finger BPJS, "+ data.string_mess +", pasien diharapkan menuju Admision");
						// $("#EMR_warning_msg").html("Gagal");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
						// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
					}
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert(error);
					$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
					// $("#MJKN_warning_msg").html("Error");
					$("#MJKN_error_msg").html(error);
					$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
					$("#modalLompatError").modal("show");
				},
			});

		}

		function cek_data_booking(){
			var no_app = document.getElementById("no_mjkn").value;

			$.ajax({
				type: 'POST',
				data: { 
					no_mjkn: no_app
				},
				url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_data_booking",
				dataType : "JSON",
				success:function(data){ 
					console.log(data);
					if(data.data_book == '1'){
						document.getElementById("no_mjkn_press").value = data.noapp;
						$("#nama_pasien").html("Nama Pasien : " +data.nama);
						$("#no_kartu").html("No Kartu : " +data.nokartu);
						$("#tgl_daftar").html("Tgl Booking : " +data.tgl);
						$("#nama_dokter").html("Nama Dokter : " +data.nmdokter);
						$("#nama_poli").html("Nama Poli : " +data.nmpoli);
						$("#no_antrian").html("No Antrian : " +data.noantrian);
						$("#modalLompatInfoPasien").modal("show");
					} else {
						$("#MJKN_halaman").html("Mohon maaf, data booking tidak ditemukan, periksa kembali data booking pasien");
						// $("#EMR_warning_msg").html("Gagal");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
						// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
					}
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert(error);
					$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
					// $("#MJKN_warning_msg").html("Error");
					$("#MJKN_error_msg").html(error);
					$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
					$("#modalLompatError").modal("show");
				},
			});

			// $("#modalLompatInfoPasien").modal("show");

		}

		function cek_blacklist(){
			var no_app = document.getElementById("no_mjkn").value;

			$.ajax({
				type: 'POST',
				data: { 
					no_mjkn: no_app
				},
				url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_blacklist",
				dataType : "JSON",
				success:function(data){ 
					console.log(data);
					if(data.blacklist == 'ya'){
						$("#MJKN_halaman").html("Mohon maaf, terdapat administrasi yang belum selesai, pasien harap diarahkan menuju Admisi Pendaftaran");
						// $("#EMR_warning_msg").html("Gagal");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
						// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
					} else {
						insert_no_mjkn();
						// $("#MJKN_halaman").html("Berhasil simpan data pasien, pasien dapat menuju poli pemeriksaan");
						// // $("#EMR_warning_msg").html("Berhasil");
						// $("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						// $("#modalLompatMenuRajal").modal("show");
					}
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert(error);
					$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
					// $("#MJKN_warning_msg").html("Error");
					$("#MJKN_error_msg").html(error);
					$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
					$("#modalLompatError").modal("show");
				},
			});
		}		


		function insert_no_mjkn(){
			// document.getElementById("no_mjkn").value = '';
			var no_app = document.getElementById("no_mjkn").value;
			// var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					no_mjkn: no_app
				},
				url: "<?php echo base_url(); ?>index.php/mjkn_pro/insert_noapp_rj",
				dataType : "JSON",
				success:function(data){ 
					console.log(data);
					if(data.string_code == '200'){

						$("#MJKN_halaman").html("Berhasil simpan data pasien, pasien dapat menuju poli pemeriksaan");
						// $("#EMR_warning_msg").html("Berhasil");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");

						// $("#MJKN_halaman").html("Pasien berhasil didaftarkan dan terbentuk SEP BPJS pada hari tersebut");
						// // $("#EMR_warning_msg").html("Berhasil");
						// $("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						// $("#modalLompatMenuRajal").modal("show");
						
					} else {
						// alert(data.string_mess);
						$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : "+ data.string_mess +", pasien harap diarahkan menuju Admisi Pendaftaran");
						// $("#EMR_warning_msg").html("Gagal");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
						$("#modalLompatMenuRajal").modal("show");
					}

					// document.getElementById("no_mjkn").value = data;
					// document.getElementById("no_mjkn").value = '';
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert(error);
					$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
					// $("#MJKN_warning_msg").html("Error");
					$("#MJKN_error_msg").html(error);
					$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
					$("#modalLompatError").modal("show");
				},
			});
		}

		function cetak_sep(){
			// document.getElementById("hasil_json_ruj").value = '';
			var no_app = document.getElementById("no_mjkn").value;
			// var keterangan = '1';

			$.ajax({
				type: 'POST',
				data: { 
					no_mjkn: no_app
				},
				url: "<?php echo base_url(); ?>index.php/mjkn_pro/cetak_sep",
				dataType : "JSON",
				success:function(data){ 
					console.log(data);

					document.getElementById("no_mjkn").value = data;
					// document.getElementById("hasil_json_ruj").value = data;
					document.getElementById("no_mjkn").value = '';
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert(error);
					// document.getElementById("hasil_json_ruj").value = 'Data Not Found';
					document.getElementById("no_mjkn").value = error;
				},
			});
		}
	</script>
</body>
</html>
