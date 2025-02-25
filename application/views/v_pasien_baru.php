<!DOCTYPE html>
<html>

<head>
	<?php
	include('header.php');
	?>
	<title>Website RS Royal</title>
	<style type="text/css">
		#footer {
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 2.5rem;
		}

		@media (max-width: 768px) {
			.form-group {
				display: flex;
				flex-direction: column;
			}
		}
	</style>
</head>
<header style="background: #e8ffd3;padding-bottom: 1%;">
	<img src="<?php echo base_url() . 'assets/img/royal.png'; ?>" height="100" width="240" />
</header>

<body style="background: #fbfad1;">

	<!-- Content Wrapper. Contains page content -->
	<div class="page-container">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<form method="POST" class="form-inline mt-3">
					<div class="col-md-12">
						<center style="margin-right: 10%;">
							<b style="font-size: 40px;">Pasien Baru Rs Royal</b>
						</center>
						<!-- <a class="btn btn-success" onclick="daftar_offline()" style="margin-left: 1%;width: 16%;height: 45px;">Daftar Offline</a>
						<br>
						<br> -->
						<br>
						<br>

						<div class="container">

							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="nik" style="float: left;">NIK <span style="color: red;">*</span></label>
										</div>
										<div class="col-md-9">
											<input class="form-control" type="text" id="nik" name="nik" placeholder="NIK" style="width: 100%;" required>

											<p id="nikwajib" style="color: red;"></p>
										</div>
									</div>
								</div>
							</div><br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="namapasien" style="float: left;">Nama Pasien <span style="color: red;">*</span></label>
										</div>
										<div class="col-md-9">
											<input class="form-control" type="text" id="namapasien" name="namapasien" placeholder="Nama" style="width: 100%;" required>
											<p id="namawajib" style="color: red;"></p>
										</div>
									</div>
								</div>
							</div> <br>

							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="jk" style="float: left;">Jenis Kelamin</label>
										</div>
										<div class="col-md-9">
											<div class="dropdown bootstrap-select" style="width: 100%;">
												<select class="" id="jk" name="jk" data-live-search="true" data-style="btn-default" style="width: 100%;" required>
													<option selected disabled>Pilih Jenis Kelamin</option>
													<?php
													$three_db = $this->load->database('three', TRUE);
													$data_jk = $three_db->query("SELECT LTRIM(RTRIM(nmkduser)) AS jk, LTRIM(RTRIM(kduser)) AS kdjk FROM stdfielddt WHERE 1=1 AND kdfield = 'JNKELAMIN' AND aktif ='1'");
													foreach ($data_jk->result() as $row) {
														echo '<option value="' . trim($row->kdjk) . '">' . $row->jk . '</option>';
													}
													?>
												</select>
												<p id="jkwajib" style="color: red;"></p>
											</div>
										</div>
									</div>
								</div>
							</div><br>

							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="tempatLahir" style="float: left;">Tempat Lahir</label>
										</div>
										<div class="col-md-9">
											<input class="form-control" type="text" name="tmplahir" id="tmplahir" placeholder="Tempat Lahir" style="width: 100%;" required>
											<p id="tempatwajib" style="color: red;"></p>
										</div>
									</div>

								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="tgllahir" style="float: left;">Tanggal Lahir <br>(dd-mm-yyyy)<span style="color: red;">*</span></label>
										</div>
										<!-- <label for="jk" style="margin-right: 58px;">Tanggal Lahir : <br>(dd-mm-yyyy) </label> -->
										<div class="col-md-9">
											<input class="form-control" type="date" name="tgllahir" id="tgllahir" placeholder="Tempat Lahir" style="width: 100%;" required>
											<p id="tgllahirwajib" style="color: red;"></p>
										</div>
										<!-- <input class="form-control" type="date" id="tgllahir" placeholder="Tanggal Lahir" style="width: 350px;"> -->
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="tgllahir" style="float: left;">Alamat <span style="color: red;">*</span></label>
										</div>
										<div class="col-md-9">
											<input class="form-control" type="text" name="alamat" id="alamat" placeholder="Alamat" style="width: 100%;" required>
											<p id="alamatwajib" style="color: red;"></p>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Kota </label>
										</div>
										<div class="col-md-9">
											<input class="form-control" type="text" name="kota" id="kota" placeholder="Kota" style="width: 100%;" required>
											<p id="kotawajib" style="color: red;"></p>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Provinsi </label>
										</div>
										<div class="col-md-9">
											<div class="dropdown bootstrap-select" style="width: 100%;">
												<select class="" name="provinsi" id="provinsi" data-live-search="true" data-size="8" data-style="btn-default" required>
													<option selected disabled>Pilih Provinsi </option>
													<?php
													$three_db = $this->load->database('three', TRUE);
													$data_provinsi = $three_db->query("SELECT LTRIM(RTRIM(nmkduser)) AS provinsi, LTRIM(RTRIM(kduser)) AS kdprovinsi FROM stdfielddt WHERE 1=1 AND kdfield = 'PROPINSI' AND aktif ='1'");
													foreach ($data_provinsi->result() as $row) {
														echo '<option value="' . trim($row->kdprovinsi) . '">' . $row->provinsi . '</option>';
													}
													?>
												</select>
												<p id="provinsiwajib" style="color: red;"></p>
											</div>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Pendidikan </label>
										</div>
										<div class="col-md-9">
											<div class="dropdown bootstrap-select" style="width: 100%;">
												<select class="" name="pendidikan" id="pendidikan" data-live-search="true" data-size="8" data-style="btn-default" required>
													<option selected disabled>Pilih Pendidikan</option>
													<?php
													$three_db = $this->load->database('three', TRUE);
													$data_pendidikan = $three_db->query("SELECT LTRIM(RTRIM(nmkduser)) AS pendidikan, LTRIM(RTRIM(kduser)) AS kdpendidikan FROM stdfielddt WHERE 1=1 AND kdfield = 'PDDN' AND aktif ='1'");
													foreach ($data_pendidikan->result() as $row) {
														echo '<option value="' . trim($row->kdpendidikan) . '">' . $row->pendidikan . '</option>';
													}
													?>
												</select>
												<p id="pendidikanwajib" style="color: red;"></p>
											</div>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Agama </label>
										</div>
										<div class="col-md-9">
											<div class="dropdown bootstrap-select" style="width: 100%;">
												<select class="" name="agama" id="agama" data-live-search="true" data-size="8" data-style="btn-default" required>
													<option selected disabled>Pilih Agama </option>
													<?php
													$three_db = $this->load->database('three', TRUE);
													$data_agama = $three_db->query("SELECT LTRIM(RTRIM(nmkduser)) AS agama, LTRIM(RTRIM(kduser)) AS kdagama FROM stdfielddt WHERE 1=1 AND kdfield = 'AGAMA' AND aktif ='1'");
													foreach ($data_agama->result() as $row) {
														echo '<option value="' . trim($row->kdagama) . '">' . $row->agama . '</option>';
													}
													?>
												</select>
												<p id="agamawajib" style="color: red;"></p>
											</div>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Status Perkawinan </label>
										</div>
										<div class="col-md-9">
											<div class="dropdown bootstrap-select" style="width: 100%;">
												<select class="" name="status_kawin" id="status_kawin" data-live-search="true" data-size="8" data-style="btn-default" required>
													<option selected disabled>Pilih Status Perkawinan </option>
													<?php
													$three_db = $this->load->database('three', TRUE);
													$data_kawin = $three_db->query("SELECT LTRIM(RTRIM(nmkduser)) AS stat_kawin, LTRIM(RTRIM(kduser)) AS kdkawin FROM stdfielddt WHERE 1=1 AND kdfield = 'STKAWIN' AND aktif ='1'");
													foreach ($data_kawin->result() as $row) {
														echo '<option value="' . trim($row->kdkawin) . '">' . $row->stat_kawin . '</option>';
													}
													?>
												</select>
												<p id="status_kawinwajib" style="color: red;"></p>
											</div>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="tgllahir" style="float: left;">No Telepon / HP <span style="color: red;">*</span></label>
										</div>
										<div class="col-md-9">
											<input class="form-control" type="text" name="notelp" id="notelp" placeholder="No Telepon" style="width: 100%;" required>
											<p id="notelpwajib" style="color: red;"></p>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Pekerjaan </label>
										</div>
										<div class="col-md-9">
											<div class="dropdown bootstrap-select" style="width: 100%;">
												<select class="" name="pekerjaan" id="pekerjaan" data-live-search="true" data-size="8" data-style="btn-default" required>
													<option selected disabled>Pilih Pekerjaan </option>
													<?php
													$three_db = $this->load->database('three', TRUE);
													$data_pekerjaan = $three_db->query("SELECT LTRIM(RTRIM(nmkduser)) AS pekerjaan, LTRIM(RTRIM(kduser)) AS kdpekerjaan FROM stdfielddt WHERE 1=1 AND kdfield = 'PEKERJAAN' AND aktif ='1'");
													foreach ($data_pekerjaan->result() as $row) {
														echo '<option value="' . trim($row->kdpekerjaan) . '">' . $row->pekerjaan . '</option>';
													}
													?>
												</select>
												<p id="pekerjaanwajib" style="color: red;"></p>
											</div>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="tgllahir" style="float: left;">Nama Keluarga </label>
										</div>
										<div class="col-md-9">
											<input class="form-control" type="text" id="nama_keluarga" placeholder="Nama Keluarga" style="width: 100%;" required>
											<p id="nama_keluargawajib" style="color: red;"></p>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Hubungan <br>Keluarga</label>
										</div>
										<div class="col-md-9">
											<div class="dropdown bootstrap-select" style="width: 100%;">
												<select class="" name="hub_keluarga" id="hub_keluarga" data-live-search="true" data-size="8" data-style="btn-default" required>
													<option selected disabled>Pilih Hub Keluarga </option>
													<?php
													$three_db = $this->load->database('three', TRUE);
													$data_hub_keluarga = $three_db->query("SELECT LTRIM(RTRIM(nmkduser)) AS hub_keluarga, LTRIM(RTRIM(kduser)) AS kdhub_keluarga FROM stdfielddt WHERE 1=1 AND kdfield = 'HUBKLG' AND aktif ='1'");
													foreach ($data_hub_keluarga->result() as $row) {
														echo '<option value="' . trim($row->kdhub_keluarga) . '">' . $row->hub_keluarga . '</option>';
													}
													?>
												</select>
												<p id="hub_keluargawajib" style="color: red;"></p>
											</div>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Poli Tujuan <span style="color: red;">*</span></label>
										</div>
										<div class="col-md-2">
											<input class="form-control" type="text" id="nmpoli" placeholder="Nama Poli" style="width: 100%;" disabled>
										</div>
										<div class="col-md-3">
											<input class="form-control" type="text" id="nmdokter" placeholder="Nama Dokter" style="width: 105%;" disabled>
										</div>
										<div class="col-md-5">
											<a class="btn btn-info" onclick="cari_poli()" style="width: 26%;">Cari Poli</a>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<input class="form-control" type="hidden" id="kdpoli" placeholder="Kode Poli" style="width: 100%;" disabled>
										</div>
										<div class="col-md-2">
											<input class="form-control" type="hidden" id="kddokter" placeholder="Kode Dokter" style="width: 100%;" disabled>
										</div>
										<div class="col-md-2">
											<input class="form-control" type="hidden" id="serviceid" placeholder="Kode Dokter" style="width: 100%;" disabled>
										</div>
										<div class="col-md-2">
											<input class="form-control" type="hidden" id="doctorid" placeholder="Nama Dokter" style="width: 100%;" disabled>
										</div>
									</div>
								</div>
							</div> <br>
							<div class="row">
								<div class="form-group col-md-12">
									<div style="display: contents;">
										<div class="col-md-2">
											<label for="kota" style="float: left;">Penjamin</label>
										</div>
										<div class="col-md-1">
											<div class="form-check">
												<input type="radio" class="form-check-input" id="pribadi" name="penjamin" value="">Pribadi
												<label class="form-check-label" for="pribadi"></label>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-check">
												<input type="radio" class="form-check-input" id="bpjstk" name="penjamin" value="BPJ15">BPJS KETENAGAKERJAAN
												<label class="form-check-label" for="pribadi"></label>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-check">
												<input type="radio" class="form-check-input" id="asuransi" name="penjamin" value="PLN70">Asuransi
												<label class="form-check-label" for="pribadi"></label>
											</div>
										</div>

									</div>
								</div>
							</div>

						</div> <br>
						<div class="row">
							<div class="col-md-2">
								<!-- <a class="btn btn-primary" onclick="daftar_pasien()" style="width: 100%;">Daftar</a> -->
							</div>
							<div class="col-md-8">
								<a class="btn btn-primary" onclick="daftar_pasien()" style="width: 100%;">Daftar</a>
							</div>
							<div class="col-md-2">
								<!-- <a class="btn btn-primary" onclick="daftar_pasien()" style="width: 100%;">Daftar</a> -->
							</div>
						</div>


					</div>

			</div>
			</form>
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
	</div>

	<div id="modalProses" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Proses, <br />harap menunggu ...</h4>
				</div>
				<div class="modal-body">
					<iframe src="https://giphy.com/embed/jAYUbVXgESSti" width="100%" height="270" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>

		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-blue bg-font-blue">
					<h5 class="modal-title blue" id="exampleModalLabel">
						<center><b>Data Berhasil Disimpan</b></center>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<center>
						<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">
						<h5 id="EMR_pr_rawin_halaman"></h5>
					</center>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
				</div>
			</div>
		</div>
	</div>

	<div id="modalLompatCariPoli" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg" style="max-width: 75%;">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="blue modal-title">
						<b> Info Poli</b>
					</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="daftar_poli">

				</div>
				<div class="modal-footer">
					<a class="btn btn-secondary" data-dismiss="modal" style="width: 30%; height: 45px;">Tutup</a>
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
					<div id="EMR_img_warning"></div>
					<h5 id="MJKN_halaman"></h5>
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
	<!-- <footer id="footer" style="background-color: #e8ffd3;">
		<?php $tahun = date('Y'); ?>
		<div class="page-footer-inner"> <?php echo $tahun; ?> &copy; TIM SIM RS Royal Surabaya
		</div>
	</footer> -->
	<script>
		$(function() {
			$('select').selectpicker();
		});


		function daftar_pasien() {
			// $('#exampleModal').modal('show');
			// var no_app = document.getElementById("no_booking_website").value;
			// $("#EMR_pr_rawin_halaman").html("Data berhasil disimpan");
			var nikInput = document.getElementById("nik").value;
			var namapasien = document.getElementById("namapasien").value;
			var tmplahirpasien = document.getElementById("tmplahir").value;
			var alamatpasien = document.getElementById("alamat").value;
			var kotapasien = document.getElementById("kota").value;
			var tlppasien = document.getElementById("notelp").value;
			var keluargapasien = document.getElementById("nama_keluarga").value;
			var jkpasien = document.getElementById("jk").value;
			var tgllahirpasien = document.getElementById("tgllahir").value;
			var provinsipasien = document.getElementById("provinsi").value;
			var pendidikanpasien = document.getElementById("pendidikan").value;
			var agamapasien = document.getElementById("agama").value;
			var status_kawinpasien = document.getElementById("status_kawin").value;
			var pekerjaanpasien = document.getElementById("pekerjaan").value;
			var hub_keluargapasien = document.getElementById("hub_keluarga").value;
			var nama_poli = document.getElementById("nmpoli").value;
			var nama_dokter = document.getElementById("nmdokter").value;
			if (nikInput == '') {
				// alert("NIK wajib diisi!");
				// $("#nikwajib").html("*NIK wajib diisi!");
				alert("NIK wajib diisi!");
				return false;
			} else {
				if (nikInput.length !== 16) {
					// $("#nikwajib").html("*NIK harus terdiri dari 16 digit!");
					alert("NIK harus terdiri dari 16 digit!");
					return false;
				}
			}

			if (namapasien == '') {
				// $("#namawajib").html("*Nama wajib diisi!");
				alert("Nama wajib diisi!");
				return false;
			}
			if (jkpasien === 'Pilih Jenis Kelamin') {
				$("#jkwajib").html("*Jenis Kelamin Wajib Dipilih!");
			}

			if (tmplahirpasien == '') {
				$("#tempatwajib").html("*Tempat Lahir wajib diisi!");
			}
			if (tgllahirpasien == '') {
				// $("#tgllahirwajib").html("*Tanggal lahir wajib diisi!");
				alert("Tanggal lahir wajib diisi!");
				return false;
			}
			if (alamatpasien == '') {
				// $("#alamatwajib").html("*Alamat wajib diisi!");
				alert("Alamat wajib diisi!");
				return false;
			}
			if (nama_poli == '' && nama_dokter == '') {
				// $("#alamatwajib").html("*Alamat wajib diisi!");
				alert("Poli Tujuan wajib diisi!");
				return false;
			}
			// if (kotapasien == '') {
			// 	$("#kotawajib").html("*Kota wajib diisi!");
			// }
			// if (provinsipasien === 'Pilih Provinsi') {
			// 	$("#provinsiwajib").html("*Provinsi Wajib Dipilih!");
			// }
			// if (pendidikanpasien === 'Pilih Pendidikan') {
			// 	$("#pendidikanwajib").html("*Pendidik Wajib Dipilih!");
			// }
			// if (agamapasien === 'Pilih Agama') {
			// 	$("#agamawajib").html("*Agama Wajib Dipilih!");
			// }
			// if (status_kawinpasien === 'Pilih Status Perkawinan') {
			// 	$("#status_kawinwajib").html("*Status Perkawinan Wajib Dipilih!");
			// }
			// if (pekerjaanpasien === 'Pilih Pekerjaan') {
			// 	$("#pekerjaanwajib").html("*Pekerjaan Wajib Dipilih!");
			// }
			// if (hub_keluargapasien === 'Pilih Hub Keluarga') {
			// 	$("#hub_keluargawajib").html("*Hubungan Keluarga Wajib Dipilih!");
			// }

			if (tlppasien == '') {
				// $("#notelpwajib").html("*Nomor Telp wajib diisi!");
				alert("Nomor Telp wajib diisi!");
				return false;

			}
			if (keluargapasien == '') {
				$("#nama_keluargawajib").html("*Nomor Telp wajib diisi!");
			}





			var data = {
				nik_pasien: $('#nik').val(),
				nama_pasien: $('#namapasien').val(),
				jk: $('#jk').val(),
				tmplahir: $('#tmplahir').val(),
				tgllahir: $('#tgllahir').val(),
				alamat: $('#alamat').val(),
				kota: $('#kota').val(),
				provinsi: $('#provinsi').val(),
				pendidikan: $('#pendidikan').val(),
				agama: $('#agama').val(),
				status_kawin: $('#status_kawin').val(),
				notelp: $('#notelp').val(),
				pekerjaan: $('#pekerjaan').val(),
				nama_keluarga: $('#nama_keluarga').val(),
				hub_keluarga: $('#hub_keluarga').val(),
				kdpoli: $('#kdpoli').val(),
				nmpoli: $('#nmpoli').val(),
				kddokter: $('#kddokter').val(),
				nmdokter: $('#nmdokter').val(),
				serviceid: $('#serviceid').val(),
				doctorid: $('#doctorid').val(),
				penjamin: $("input[name ='penjamin']:checked").val(),
			}

			// console.log(data);

			$.ajax({
				type: 'POST',
				data: data,
				url: "<?php echo base_url(); ?>index.php/pasien_baru/daftar_pasien",
				// dataType: "JSON",
				success: function(data) {
					$("#MJKN_halaman").html(data);
					$("#modalLompatMenuRajal").modal("show");
				},
				error: function(xhr, status, error) {
					// console.log(error);
					// alert(error);
					$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada Sistem, pasien harap diarahkan menuju Admisi Pendaftaran");
					$("#MJKN_error_msg").html(error);
					$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
					$("#modalLompatError").modal("show");
				},
			});
		}

		function cari_poli() {
			tgl_hari_ini = '<?php echo date('Y-m-d'); ?>';

			var data = {
				tgl_hari_ini: tgl_hari_ini
			};

			$.ajax({
				type: 'POST',
				url: "<?php echo base_url(); ?>index.php/website_pro/view_cari_poli",
				data: data,
				success: function(data) {
					$("#modalLompatCariPoli").modal("show");
					$("#daftar_poli").html(data);
				},
				error: function(xhr, status, error) {
					console.log(error);
					// alert(error);
					$("#MJKN_error_halaman").html(error);
					$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
					$("#modalLompatError").modal("show");
				}
			});
		}

		function set_poli(kode_poli, poli, kode_dokter, dokter, service_id, doctor_id) {

			$("#kdpoli").val(kode_poli);
			$("#nmpoli").val(poli);
			$("#kddokter").val(kode_dokter);
			$("#nmdokter").val(dokter);
			$("#serviceid").val(service_id);
			$("#doctorid").val(doctor_id);
			$("#modalLompatCariPoli").modal("hide");
		}
	</script>
</body>

</html>