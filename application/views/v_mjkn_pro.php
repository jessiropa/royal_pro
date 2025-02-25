<!DOCTYPE html>
<html>

<head>
	<?php
	include('header.php');
	?>
	<title>Mobile JKN</title>
	<style type="text/css">
	#footer {
		position: absolute;
		bottom: 0;
		width: 100%;
		height: 2.5rem;
	}
</style>
</head>
<header style="background: #e8ffd3;padding-bottom: 1%;">
	<img src="<?php echo base_url() . 'assets/img/royal.png'; ?>" height="100" width="240" onclick="window.location.reload();">
	<div style="float: right;">
		<img src="<?php echo base_url() . 'assets/img/logo_bpjs.png'; ?>" height="100" width="400" />
	</div>

</header>

<body style="background: #fbfad1;">

	<!-- Content Wrapper. Contains page content -->
	<div class="">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<form method="POST" action="" class="form-inline mt-3">
					<div class="col-md-12">
						<center style="margin-right: 10%;">

							<b style="font-size: 40px;">Pendaftaran Mandiri Pasien Mobile JKN Pro Royal</b>
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
								<a class="btn btn-primary" onclick="ubah_surkon_apm()" style="margin-left: 0%;margin-top: 2%;width: 15%;height: 45px;background-color: #a8996b;">Surat Kontrol BPJS <i class="fa-solid fa-barcode"></i></a>
								<a class="btn btn-primary" onclick="cek_kartu_pasien()" style="margin-left: 1%;margin-top: 2%;width: 15%;height: 45px;background-color: #000000;">Kartu Pasien <i class="fa fa-id-card"></i></a>
								<a class="btn btn-success" onclick="cetak_sep()" style="margin-left: 1%;margin-top: 2%;width: 13%;height: 45px;">Cetak <i class="fa-solid fa-print"></i></a>
								<a class="btn btn-default" onclick="setNumber(4)" style="margin-left: 5%;margin-top: 1%;width: 10%;height: 45px;">4</a>
								<a class="btn btn-default" onclick="setNumber(5)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">5</a>
								<a class="btn btn-default" onclick="setNumber(6)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">6</a>
							</div>
							<div class="form-group col" style="width:75%;">
								<a class="btn btn-primary" onclick="antrian_farmasi()" style="margin-left: 0%;margin-top: 2%;width: 15%;height: 45px;background-color: #00bc8c;">Antrian Farmasi BPJS</a>
								<a class="btn btn-danger" onclick="refresh_halaman()" style="margin-left: 1%;margin-top: 2%;width: 15%;height: 45px;background-color: #000000;">Refresh <i class="fa fa-id-card"></i></a>
								<a class="btn btn-default" onclick="setNumber(7)" style="margin-left: 19%;margin-top: 1%;width: 10%;height: 45px;">7</a>
								<a class="btn btn-default" onclick="setNumber(8)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">8</a>
								<a class="btn btn-default" onclick="setNumber(9)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">9</a>
							</div>
							<div class="form-group col" style="width:75%;">
								<a class="btn btn-default" onclick="setNumber('-')" style="margin-left: 50%;margin-top: 1%;width: 10%;height: 45px;">-</a>
								<a class="btn btn-default" onclick="setNumber(0)" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;">0</a>
								<a class="btn btn-default" onclick="setDelete()" style="margin-left: 1%;margin-top: 1%;width: 10%;height: 45px;"><i class="fa-solid fa-arrow-left"></i></a>
								<br>
								<a class="btn btn-danger" onclick="clearInput()" style="margin-left: 50%;margin-top: 2%;width: 21%">Clear</a>
								<a class="btn btn-warning" onclick="cek_kode_booking()" style="margin-left: 1%;margin-top: 2%;width: 10%;"><i class="fa-solid fa-lock"></i></a>
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
					<!-- <a class="btn btn-danger" onclick="susah_finger()" style="width: 30%;height: 45px;">Susah Finger</a> -->
					<a class="btn btn-primary" onclick="cek_blacklist()" style="width: 30%; height: 45px;">Daftar</a>
					<!-- <a class="btn btn-warning" onclick="cek_queue()" style="width: 30%; height: 45px;">Insert Queue</a> -->
					<a class="btn btn-success" class="btn btn-default" data-dismiss="modal" style="width: 30%; height: 45px;">Tutup</a>
					<!-- <button type="button" style="width: 30%; height: 45px; class="btn btn-default" data-dismiss="modal">Tutup</button> -->
				</div>
			</div>

		</div>
	</div>


	<div id="modalLompatInputSurkon" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="blue modal-title">
						<b> Input data surat kontrol BPJS</b>
					</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">

					<div class="form-group col" style="width:100%;;">
						<input type="text" name="no_surkon" id="no_surkon" placeholder="No surat kontrol barcode" class="form-control" style="width: 45%;height: 45px;display: unset">
						<a class="btn btn-primary" onclick="tinjau_no_surat()" style=" margin-left: 1%; width: 40%;height: 45px;">via no surat kontrol</a>
					</div>
					<br />

					<div class="form-group" style="width: 100%;">

						<select class="form-control" style="width: 45%; margin-left: 1%;display: unset" name="bulan_surkon" id="bulan_surkon">
							<?php
							$five_db = $this->load->database('five', TRUE);
							date_default_timezone_set("Asia/Bangkok");
							$numdig = $five_db->query("SELECT
								emb.kode_bulan, emb.nama_bulan,emb.keterangan, emb.selected
								FROM dbo.EMR_MASTER_BULAN AS emb
								WHERE
								Keterangan = 'Surat kontrol BPJS'")->num_rows();
							$datdig = $five_db->query("SELECT
								emb.kode_bulan, emb.nama_bulan,emb.keterangan, emb.selected
								FROM dbo.EMR_MASTER_BULAN AS emb
								WHERE
								Keterangan = 'Surat kontrol BPJS'");
								for ($shows_ = 0; $shows_ < $numdig; $shows_++) { ?>
									<option value="<?php echo $datdig->row($shows_)->kode_bulan; ?>" <?php echo $datdig->row($shows_)->selected; ?>>
										<?php echo $datdig->row($shows_)->nama_bulan; ?></option>
										<?php
									}

									?>
								</select>


								<select class="form-control" style="width: 45%; margin-left: 1%;display: unset" name="tahun_surkon" id="tahun_surkon">
									<?php

									$numdig = $five_db->query("SELECT
										ett.kode_tahun, ett.nama_tahun, ett.selected
										FROM
										dbo.EMR_TAHUN AS ett
										WHERE
										Keterangan = 'Surat kontrol BPJS'
										ORDER BY ett.nama_tahun ASC")->num_rows();
									$datdig = $five_db->query("SELECT
										ett.kode_tahun, ett.nama_tahun, ett.selected
										FROM
										dbo.EMR_TAHUN AS ett
										WHERE
										Keterangan = 'Surat kontrol BPJS'
										ORDER BY ett.nama_tahun ASC");
										for ($shows_ = 0; $shows_ < $numdig; $shows_++) { ?>
											<option value="<?php echo $datdig->row($shows_)->kode_tahun; ?>" <?php echo $datdig->row($shows_)->selected; ?>>
												<?php echo $datdig->row($shows_)->nama_tahun; ?></option>
												<?php
											}

											?>
										</select>
									</div>

									<div class="form-group" style="width: 100%;">
										<input type="text" name="no_kartu_px" id="no_kartu_px" placeholder="No kartu BPJS" class="form-control" style="width: 45%;height: 45px;display: unset">
										<a class="btn btn-primary" onclick="check_surkon()" style=" margin-left: 1%; width: 40%;height: 45px;">via no kartu</a>
									</div>

								</div>
								<div class="modal-footer">
									<!-- <a class="btn btn-danger" onclick="susah_finger()" style="width: 30%;height: 45px;">Susah Finger</a> -->

									<!-- <a class="btn btn-warning" onclick="cek_queue()" style="width: 30%; height: 45px;">Insert Queue</a> -->
									<a class="btn btn-success" class="btn btn-default" data-dismiss="modal" style="width: 30%; height: 45px;">Tutup</a>
									<!-- <button type="button" style="width: 30%; height: 45px; class="btn btn-default" data-dismiss="modal">Tutup</button> -->
								</div>
							</div>

						</div>
					</div>

					<div id="modal_edit_kontrol" class="modal fade" role="dialog" data-width="90%">
						<div class="modal-dialog modal-lg">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="blue modal-title">
										<b> Informasi kontrol pasien</b>
									</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
									<div id="data_surat_kontrol">

									</div>
									<span style="color: red;"> *<b>Edit Surkon</b> digunakan untuk pasien yang <b><u>tidak memiliki</u></b> aplikasi MJKN</span> <br>
									<!-- <span style="color: red;"> *<b>Edit & Daftar Surkon</b> digunakan untuk pasien yang <b><u>memiliki</u></b> aplikasi MJKN</span> <br> -->
								</div>
								<div class="modal-footer">
									<a class="btn btn-info" onclick="check_antrean()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Simpan perubahan <i class="fa fa-address-card" aria-hidden="true"></i></a>
									<!-- <a class="btn btn-danger" onclick="" style="margin-left: 1%;margin-top: 5%;width: 30%;">Edit & Daftar Surkon <i class="fa fa-address-card" aria-hidden="true"></i></a> -->
									<a class="btn btn-success" data-dismiss="modal" style="margin-top: 5%;width: 30%;">Tutup</a>
								</div>
							</div>

						</div>
					</div>

					<!-- <div id="modalSuratKontrol" class="modal fade" role="dialog" data-width="110%"> -->
						<div class="modal fade" id="modalSuratKontrol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-xl">
								<!-- Modal content-->
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="blue modal-title">
											<b> Informasi Surat Kontrol</b>
										</h4>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-body">
										<div id="list_surat_kontrol_kartu">
								<!-- <table class="table table-striped table-bordered order-column" id="sample_3">
									<thead class="btn-success">
										<tr>
											<th>
												<center>No Surat Kontrol</center>
											</th>
											<th>
												<center>Tanggal Kontrol</center>
											</th>
											<th>
												<center>Nama Pasien</center>
											</th>
											<th>
												<center>Poliklinik</center>
											</th>
											<th>
												<center>Dokter DPJP</center>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr class="dd gradeX">
											<td>0217R0771124K004210</td>
											<td>2024-12-20</td>
											<td>YANIE</td>
											<td>PENYAKIT DALAM</td>
											<td>dr. JEFFRY ADIJAYA SUSATYO, Sp.PD</td>
										</tr>
									</tbody>
								</table> -->
								<!-- <textarea name="hasil_json_get_no_surat_kontrol" id="hasil_json_get_no_surat_kontrol" class="form-control" style="width: 30%; height: 300px;" disabled=""></textarea> -->
							</div>
						</div>
						<div class="modal-footer">
							<!-- <a class="btn btn-warning" onclick="edit_surkon()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Ubah data <i class="fa fa-address-card" aria-hidden="true"></i></a> -->
							<a class="btn btn-success" data-dismiss="modal" style="margin-top: 5%;width: 30%;">Tutup</a>
						</div>
					</div>

				</div>
			</div>

			<div id="modalLompatAntrianFarmasi" class="modal fade" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="blue modal-title">
								<b> Input data antrian farmasi BPJS</b>
							</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">

							<div class="form-group col" style="width:100%;;">
								<input type="text" name="no_rm" id="no_rm" placeholder="No RM" class="form-control" style="width: 45%;height: 45px;display: unset">
								<a class="btn btn-primary" onclick="tinjau_no_rm()" style=" margin-left: 1%; width: 40%;height: 45px;">via no RM</a>
							</div>

							<div class="form-group col" style="width: 100%;">
								<input type="text" name="no_kartu_bpjs" id="no_kartu_bpjs" placeholder="No kartu BPJS" class="form-control" style="width: 45%;height: 45px;display: unset">
								<a class="btn btn-primary" onclick="check_no_kartu()" style=" margin-left: 1%; width: 40%;height: 45px;">via no kartu</a>
							</div>

							<div class="form-group col" style="width: 100%;">
								<input type="text" name="nama_bpjs" id="nama_bpjs" placeholder="Nama" class="form-control" style="width: 45%;height: 45px;display: unset">
								<a class="btn btn-primary" onclick="check_nama()" style=" margin-left: 1%; width: 40%;height: 45px;">via nama</a>
							</div>

						</div>
						<div class="modal-footer">
							<a class="btn btn-success" class="btn btn-default" data-dismiss="modal" style="width: 30%; height: 45px;">Tutup</a>
						</div>
					</div>

				</div>
			</div>

			<div class="modal fade" id="modalPendaftaranPasien" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-xl">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="blue modal-title">
								<b> Informasi Pendaftaran Pasien Antrian Farmasi</b>
							</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<div id="list_pendaftaran_pasien">

							</div>
						</div>
						<div class="modal-footer">
							
						</div>
					</div>

				</div>
			</div>

			<div id="modalDetailDaftarFarmasi" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<style>
				.modal-lg {
					max-width: 100%;
				}

				.table-responsive {
					width: 100%;
					overflow-x: auto;
				}

				.table {
					width: 100%;
					min-width: 1000px;
					table-layout: auto;
				}

				.table th, .table td {
					padding: 8px;
				}

				.modal-body {
					padding: 10px;
				}
			</style>
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="blue modal-title">
							<b> Informasi Pendaftaran Pasien Antrian Farmasi</b>
						</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<div class="row">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover order-column" id="detailfarmasi" style="background-color: beige;" width="100%">
										<thead>
											<tr>
												<th>
													<center>No Reg</center>
												</th>
												<th>
													<center>No RM</center>
												</th>
												<th>
													<center>Nama Pasien</center>
												</th>
												<th>
													<center>Poliklinik</center>
												</th>
												<th>
													<center>Nama Dokter</center>
												</th>
												<th>
													<center>Alamat</center>
												</th>
												<th>
													<center>Tgl Lahir</center>
												</th>
												<th>
													<center>Aksi</center>
												</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div id="modalLompatInfoKartu" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="blue modal-title">
							<b> Informasi kartu pasien</b>
						</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<!-- <input type="text" name="no_booking" id="no_booking" class="form-control" style="width: 50%;height: 35px;" disabled> -->
						<input type="text" name="kartu_pasien_f" id="kartu_pasien_f" class="form-control" style="width: 70%;height: 50px;" placeholder="Kartu pasien BPJS">
						<br>
						<div class="form-group col" style="width:100%;">
							<a class="btn btn-default" onclick="setNumberk(1)" style="margin-left: 1%;width: 30%;height: 45px;">1</a>
							<a class="btn btn-default" onclick="setNumberk(2)" style="margin-left: 1%;width: 30%;height: 45px;">2</a>
							<a class="btn btn-default" onclick="setNumberk(3)" style="margin-left: 1%;width: 30%;height: 45px;">3</a>
						</div>
						<div class="form-group col" style="width:100%;">
							<a class="btn btn-default" onclick="setNumberk(4)" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">4</a>
							<a class="btn btn-default" onclick="setNumberk(5)" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">5</a>
							<a class="btn btn-default" onclick="setNumberk(6)" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">6</a>
						</div>
						<div class="form-group col" style="width:100%;">
							<a class="btn btn-default" onclick="setNumberk(7)" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">7</a>
							<a class="btn btn-default" onclick="setNumberk(8)" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">8</a>
							<a class="btn btn-default" onclick="setNumberk(9)" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">9</a>
						</div>
						<div class="form-group col" style="width:100%;">
							<a class="btn btn-default" onclick="setNumberk('-')" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">-</a>
							<a class="btn btn-default" onclick="setNumberk(0)" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;">0</a>
							<a class="btn btn-default" onclick="setDeletek()" style="margin-left: 1%;margin-top: 1%;width: 30%;height: 45px;"><i class="fa-solid fa-arrow-left"></i></a>
							<br>
							<a class="btn btn-danger" onclick="clearInputk()" style="margin-left: 1%;margin-top: 2%;width: 50%">Clear</a>
						</div>
						<br>
						<br>

						<!-- <input type="text" name="pass" id="pass" class="form-control" style="width: 50%;height: 35px;" placeholder="Password"> -->
					</div>
					<div class="modal-footer">
						<a class="btn btn-warning" onclick="cek_info_kartu()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Info kartu <i class="fa fa-address-card" aria-hidden="true"></i></a>
						<a class="btn btn-success" data-dismiss="modal" style="margin-top: 5%;width: 30%;">Tutup</a>
					</div>
				</div>

			</div>
		</div>

		<div id="modalLompatInfoBooking" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="blue modal-title">
							<b> Akses Finger</b>
						</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<input type="text" name="no_booking" id="no_booking" class="form-control" style="width: 50%;height: 35px;" disabled>
						<br>
						<p>Alasan approval finger</p>
						<select class="form-control" name="alasan_finger" id="alasan_finger" data-style="btn-default" style="width: 80%;;">
							<option value="Pilih Alasan" selected disabled>Pilih Alasan </option>
							<?php
							$three_db = $this->load->database('three', TRUE);
							$data_alasan_finger = $three_db->query("SELECT bpk.kd_list, bpk.keterangan_list FROM bpjs_pengajuan_list_keterangan bpk");
							foreach ($data_alasan_finger->result() as $row) {
								echo '<option value="' . trim($row->keterangan_list) . '">' . $row->keterangan_list . '</option>';
							}
							?>
						</select>
						<!-- <p id="alasan_finger_wajib" style="color: red;"></p> -->
						<br>
						<input type="text" name="pass" id="pass" class="form-control" style="width: 50%;height: 35px;" placeholder="Password">
					</div>
					<div class="modal-footer">
						<a class="btn btn-warning" onclick="cek_finger()" style="margin-left: 1%;margin-top: 5%;width: 30%;"><i class="fa-solid fa-unlock"></i></a>
						<a class="btn btn-success" data-dismiss="modal" style="margin-top: 5%;width: 30%;">Tutup</a>
					</div>
				</div>

			</div>
		</div>

		<div id="modalLompatCetak" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="blue modal-title">
							<b> Cetak Antrian</b>
						</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<a class="btn btn-danger" onclick="cetak_sep_merah()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Cetak Merah <i class="fa-solid fa-print"></i></a>
						<a class="btn btn-success" onclick="cetak_sep_hijau()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Cetak Hijau <i class="fa-solid fa-print"></i></a>
						<a class="btn btn-warning" onclick="cetak_sep_kuning()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Cetak Kuning <i class="fa-solid fa-print"></i></a>
						<a class="btn btn-default" onclick="cetak_sep_putih()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Cetak Putih <i class="fa-solid fa-print"></i></a>
						<a class="btn btn-primary" onclick="cetak_sep_all()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Cetak Semua <i class="fa-solid fa-print"></i></a>
					</div>
					<div class="modal-footer">
						<a class="btn btn-success" data-dismiss="modal" style="margin-top: 5%;width: 30%;">Tutup</a>
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
		<!-- /.content-wrapper -->
		<?php
		include('foot.php');
		?>
		<footer id="footer" style="background-color: #e8ffd3;">
			<?php $tahun = date('Y'); ?>
			<div class="page-footer-inner"> <?php echo $tahun; ?> &copy; TIM SIM RS Royal Surabaya
			</div>
		</footer>
		<script>

			function setNumber(clickedNumber) {
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

			function setNumberk(clickedNumber) {
				document.getElementById("kartu_pasien_f").value += clickedNumber;
				document.getElementById("kartu_pasien_f").focus();
			}

			function setDeletek() {
				document.getElementById("kartu_pasien_f").value = document.getElementById("kartu_pasien_f").value.slice(0, -1);
				document.getElementById("kartu_pasien_f").focus();
			}

			function clearInputk() {
				document.getElementById("kartu_pasien_f").value = '';
				document.getElementById("kartu_pasien_f").focus();
			}

			function susah_finger() {
				var no_app = document.getElementById("no_mjkn").value;
				var e = document.getElementById("alasan_finger");
				var value_alasan_finger = e.value;
				var text_alasan_finger = e.options[e.selectedIndex].text;
				// var alasan_finger = document.getElementById("alasan_finger").value;
				
				$.ajax({
					type: 'POST',
					data: {
						no_mjkn: no_app,
						value_alasan_finger : value_alasan_finger
					},
					url: "<?php echo base_url(); ?>index.php/mjkn_pro/susah_finger",
					dataType: "JSON",
					success: function(data) {
						console.log(data);
							// $("#MJKN_halaman").html(data);
							// $("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							// $("#modalLompatMenuRajal").modal("show");
							if (data.data_finger == '1') {
								$("#MJKN_halaman").html("Berhasil Approval sidik jari, silahkan melakukan pendaftaran pada mesin APM");
								// $("#EMR_warning_msg").html("Berhasil");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");
							} else if (data.data_finger == '2') {
								$("#MJKN_halaman").html("Mohon maaf, terjadi kegagalan akses finger BPJS, " + data.string_mess + ", silahkan melakukan pendaftaran pada mesin APM");
								// $("#EMR_warning_msg").html("Gagal");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");
							} else {
								$("#MJKN_halaman").html("Mohon maaf, terjadi kegagalan akses finger BPJS, " + data.string_mess + ", pasien diharapkan menuju Admision");
								// $("#EMR_warning_msg").html("Gagal");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");
							}

						},
						error: function(xhr, status, error) {
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

			function cek_data_booking() {
				var no_app = document.getElementById("no_mjkn").value;

				$.ajax({
					type: 'POST',
					data: {
						no_mjkn: no_app
					},
					url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_data_booking",
					dataType: "JSON",
					success: function(data) {
						console.log(data);
						if (data.data_book == '1') {
							document.getElementById("no_mjkn_press").value = data.noapp;
							$("#nama_pasien").html("Nama Pasien : " + data.nama);
							$("#no_kartu").html("No Kartu : " + data.nokartu);
							$("#tgl_daftar").html("Tgl Booking : " + data.tgl);
							$("#nama_dokter").html("Nama Dokter : " + data.nmdokter);
							$("#nama_poli").html("Nama Poli : " + data.nmpoli);
							$("#no_antrian").html("No Antrian : " + data.noantrian);
							$("#modalLompatInfoPasien").modal("show");
						} else {
							$("#MJKN_halaman").html("Mohon maaf, data booking tidak ditemukan, periksa kembali data booking pasien");
							$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
							$("#modalLompatMenuRajal").modal("show");
						}

					},
					error: function(xhr, status, error) {
						console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");
						},
					});

			}

			function refresh_halaman(){
				location.reload();
			}

			function cek_kode_booking() {
				var no_app = document.getElementById("no_mjkn").value;

				if (no_app != '') {
					document.getElementById("no_booking").value = no_app;
					document.getElementById("pass").focus();
					$("#modalLompatInfoBooking").modal("show");
				} else {
					$("#MJKN_halaman").html("Mohon maaf, kode booking tidak ditemukan, periksa kembali kode booking pasien");
					$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
					$("#modalLompatMenuRajal").modal("show");
				}
			}

			function cek_kartu_pasien() {
				$("#modalLompatInfoKartu").modal("show");
			}

			function cek_finger() {
				var pass = document.getElementById("pass").value;
				var e = document.getElementById("alasan_finger");
				var value_alasan_finger = e.value;
				var text_alasan_finger = e.options[e.selectedIndex].text;
				// var alasan_finger = document.getElementById("alasan_finger").value;
				if(value_alasan_finger == 'Pilih Alasan'){
					alert("Mohon maaf, harap memilih alasan approval pasien");
					return
				}

				$.ajax({
					type: 'POST',
					data: {
						pass: pass
					},
					url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_finger",
					dataType: "JSON",
					success: function(data) {
						console.log(data);
						if (data.data_pass == '1') {
							susah_finger();
						} else {
							$("#MJKN_halaman").html("Mohon maaf, password tidak sesuai, silahkan ulangi lagi");
							$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
							$("#modalLompatMenuRajal").modal("show");
						}

					},
					error: function(xhr, status, error) {
						console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");
						},
					});

			}

			function cek_blacklist() {
				var no_app = document.getElementById("no_mjkn").value;

				$.ajax({
					type: 'POST',
					data: {
						no_mjkn: no_app
					},
					url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_blacklist",
					dataType: "JSON",
					success: function(data) {
						console.log(data);
						if (data.blacklist == 'ya') {
							$("#MJKN_halaman").html("Mohon maaf, terdapat administrasi yang belum selesai, pasien harap diarahkan menuju Admisi Pendaftaran");
								// $("#EMR_warning_msg").html("Gagal");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								var string_mess = "Mohon maaf, terdapat administrasi yang belum selesai";
								var no_sep = '';
								var status = '2';

								cek_queue(string_mess, no_sep, status);
							} else if (data.blacklist == 'bukan') {
								$("#MJKN_halaman").html("Mohon maaf, pasien tidak mendaftar melalui MJKN Pro");
								// $("#EMR_warning_msg").html("Gagal");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								// $("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								var string_mess = "Mohon maaf, pasien tidak mendaftar melalui MJKN";
								var no_sep = '';
								var status = '2';

								cek_queue(string_mess, no_sep, status);
							} else {
								insert_no_mjkn();
							}

						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
						},
					});
			}



			function insert_no_mjkn() {
					// document.getElementById("no_mjkn").value = '';
					var no_app = document.getElementById("no_mjkn").value;
					// var keterangan = '1';

					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/insert_noapp_rj",
						dataType: "JSON",
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
							console.log(data);
							if (data.string_code == '200') {

								$("#MJKN_halaman").html("Berhasil simpan data pasien, pasien dapat menuju poli pemeriksaan");
								// $("#EMR_warning_msg").html("Berhasil");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								var string_mess = "";
								var no_sep = data.noSep;
								var status = '1';

								cek_queue(string_mess, no_sep, status);
								cetak_sep_all();

								$("#modalProses").modal('hide');

							} else if (data.string_code == '888') {

								$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + " ");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");

								$("#modalProses").modal('hide');

							} else if (data.string_code == '889') {

								$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + ", pasien harap diarahkan menuju Admisi Pendaftaran");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");

								var string_mess = "Mohon maaf, " + data.string_mess + " ";
								var no_sep = '-';
								var status = '2';
								var warna = '-';

								cek_queue(string_mess, no_sep, status);
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								$("#modalProses").modal('hide');

							} else {
								// alert(data.string_mess);
								$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + ", pasien harap diarahkan menuju Admisi Pendaftaran");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");

								var string_mess = "Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + " ";
								var no_sep = '-';
								var status = '2';
								var warna = '-';

								cek_queue(string_mess, no_sep, status);
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								$("#modalProses").modal('hide');
							}

							// document.getElementById("no_mjkn").value = data;
							// document.getElementById("no_mjkn").value = '';
						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");

							var string_mess = "Mohon maaf terdapat kesalahan pada program MJKN";
							var no_sep = '-';
							var status = '2';
							var warna = '-';

							cek_queue(string_mess, no_sep, status);
							window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

							$("#modalProses").modal('hide');
						},
					});
				}

				function cek_info_kartu() {
					var kartu_pasien_f = document.getElementById("kartu_pasien_f").value;
					document.getElementById("no_mjkn").value = '';
					$.ajax({
						type: 'POST',
						data: {
							kartu_pasien_f: kartu_pasien_f
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_info_kartu",
						dataType: "JSON",
						success: function(data) {
							if (kartu_pasien_f != '') {
								if (data.data_book == '1') {
									// $("#modalLompatCetak").modal("show");
									document.getElementById("no_mjkn").value = data.noapp;
									$("#modalLompatInfoKartu").modal("hide");
									setTimeout(function() {
										cek_data_booking()()
									}, 2000);

								} else {
									$("#MJKN_halaman").html("Mohon maaf, tidak terdapat kode booking pada pasien, periksa kembali nomor kartu pasien");
									$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
									$("#modalLompatMenuRajal").modal("show");
								}
							} else {
								$("#MJKN_halaman").html("Mohon maaf, terdapat kesalahan pada no kartu pasien");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");
							}

						}

					});

				}

				function ubah_surkon_apm() {
					document.getElementById("no_surkon").value = '';
					document.getElementById("no_kartu_px").value = '';
					$("#modalLompatInputSurkon").modal("show");
				}

				function tinjau_no_surat() {
					// $("#modalSuratKontrol").modal("show");
					var no_surkon = $('#no_surkon').val();
					var data = {
						no_surkon: no_surkon
					};
					// console.log(data);
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/tinjau_no_surat",
						data: data,
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
							console.log(data);

							$("#data_surat_kontrol").html(data);
							$("#modal_edit_kontrol").modal("show");
							// document.getElementById("hasil_json_get_no_surat_kontrol").value = data;
							setTimeout(function() {
								$("#modalProses").modal('hide');
							}, 1000);
						},
						error: function(xhr, status, error) {
							console.log(error);
						}
					});

				}

				// check surat kontrol berdasarkan kartu
				function check_surkon() {
					// $("#modalSuratKontrol").modal("show");
					var no_surkon = $('#no_kartu_px').val();
					var data = {
						no_surkon: no_surkon,
						tahun_surkon: $('#tahun_surkon').val(),
						bulan_surkon: $('#bulan_surkon').val(),
					};
					// console.log(data);
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/get_surat_kontrol_kartu",
						data: data,
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
						// console.log(data);

						$("#list_surat_kontrol_kartu").html(data);
						$("#modalSuratKontrol").modal("show");
						// document.getElementById("hasil_json_get_no_surat_kontrol").value = data;
						setTimeout(function() {
							$("#modalProses").modal('hide');
						}, 1000);
					},
					error: function(xhr, status, error) {
						console.log(error);
					}
				});

				}

				// ambil data rencana kontrol berdasarkan nosuratkontrol
				function get_via_nosurkon(nosuratkontol) {
					var data = {
						nosuratkontol: nosuratkontol
					};
					// console.log(data);
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/get_nosurkon",
						data: data,
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
							$("#data_surat_kontrol").html(data);
							$("#modalSuratKontrol").modal("hide");
							$("#modal_edit_kontrol").modal("show");
							// $("#modalProses").modal('hide');

							setTimeout(function() {
								$("#modalProses").modal('hide');
							}, 1000);
						},
						error: function(xhr, status, error) {
							console.log(error);
						}
					});

				}

				function check_antrean() {
					// console.log('ini fungsi untuk check antrian');
					var no_surkon = $('#no_kontrol').val();
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/check_antrean",
						data: {
							no_surkon: no_surkon
						},
						dataType: "JSON",
						// beforeSend: function() {
						// 	$("#modalProses").modal('show');
						// },
						success: function(data) {
							console.log(data);
							if (data.kode == '200') {
								// console.log('tidak ada di bpjs antrean');

								$("#MJKN_halaman").html("Pasien telah mendaftarkan online untuk tanggal " + data.tgl + ". Jika ingin mengubah tanggal kontrol silahkan batalkan Antrian");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								// window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_ubah_surkon/" + no_surkon, "_blank").focus();
							} else {
								edit_surkon();
								// $("#MJKN_halaman").html(data.pesan);
								// $("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								// $("#modalLompatMenuRajal").modal("show");

								// setTimeout(function() {
								// 	$("#modalProses").modal('hide');
								// }, 1000);
								// console.log('ini ada di bpjs antrean');

							}
						},
						error: function(xhr, status, error) {
							console.log(error);
						}
					});

				}

				function cetak_surkon_list(noSuratKontrol) {
					window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_ubah_surkon/" + noSuratKontrol, "_blank").focus();
				}

				function edit_surkon() {
					var tanggal_surkon = $('#edit_tanggal_surkon').val();
					var no_surkon = $('#no_kontrol').val();
					var noSEP_kontrol = $('#noSEP_kontrol').val();
					var kd_dpjp_bpjs = $('#kd_dpjp_bpjs').val();
					var kd_poli_bpjs = $('#kd_poli_bpjs').val();
					var nmdpjpBPJS = $('#nmdpjpBPJS').val();
					var data = {
						tanggal_surkon: tanggal_surkon,
						no_surkon: no_surkon,
						nosep: noSEP_kontrol,
						kodedokter: kd_dpjp_bpjs,
						kodepoli: kd_poli_bpjs
					}
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_kuota_surkon",
						data: data,
						dataType: "JSON",
						success: function(data) {
							if(data.code_sukses == '200'){
								edit_surkon_nn();
                         		// alert("mantap");
                         	}else{
                         		alert("Mohon maaf, kuota pasien BPJS pada tanggal " +data.tanggal_kontrol+ " telah penuh untuk dokter " +nmdpjpBPJS+ ", silahkan memilih tanggal kontrol lain");
                         	}
                         },
                         error: function(xhr, status, error) {
                         	console.log(error);
                         }

                     });


				}

				// edit surkon data dari bridging  bpjs
				function edit_surkon_nn() {
					var tanggal_surkon = $('#edit_tanggal_surkon').val();
					var no_surkon = $('#no_kontrol').val();
					var noSEP_kontrol = $('#noSEP_kontrol').val();
					var kd_dpjp_bpjs = $('#kd_dpjp_bpjs').val();
					var kd_poli_bpjs = $('#kd_poli_bpjs').val();

					var data = {
						tanggal_surkon: tanggal_surkon,
						no_surkon: no_surkon,
						nosep: noSEP_kontrol,
						kodedokter: kd_dpjp_bpjs,
						kodepoli: kd_poli_bpjs
					}

					// console.log(data);
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/update_surkon_bpjs",
						data: data,
						dataType: "JSON",
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
							if (data.kode == '200') {

								$("#MJKN_halaman").html("Surat Kontrol Berhasil di Update. Tanggal Kontrol Terbaru Pasien : " + data.tglRencanaKontrol);
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_ubah_surkon/" + no_surkon, "_blank").focus();
							} else {
								$("#MJKN_halaman").html(data.pesan);
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);

							}
						},
						error: function(xhr, status, error) {
							console.log(error);
						}
					});

				}

				function antrian_farmasi() {
					// document.getElementById("no_rm").value = '';
					document.getElementById("no_kartu_bpjs").value = '';
					$("#modalLompatAntrianFarmasi").modal("show");
				}

				function tinjau_no_rm() {
					var no_rm = $('#no_rm').val();
					var data = {
						no_rm: no_rm
					};
					console.log(data);
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/tinjau_no_rm",
						data: data,
						dataType: "JSON",
						success: function(data) {

							if (data.daftar == '1') {

								$("#list_pendaftaran_pasien").html(data.list);
								$("#modalPendaftaranPasien").modal("show");

							} else {
								$("#MJKN_halaman").html("Mohon maaf, tidak ada pendaftaran pasien hari ini");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");

							}

						},
						error: function(xhr, status, error) {
							console.log(error);
						}
					});
				}

				function check_no_kartu() {
					var no_kartu_bpjs = $('#no_kartu_bpjs').val();
					var data = {
						no_kartu_bpjs: no_kartu_bpjs
					};
					console.log(data);
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/check_no_kartu",
						data: data,
						dataType: "JSON",
						success: function(data) {

							if (data.daftar == '1') {

								$("#list_pendaftaran_pasien").html(data.list);
								$("#modalPendaftaranPasien").modal("show");

							} else {
								$("#MJKN_halaman").html("Mohon maaf, tidak ada pendaftaran pasien hari ini");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");

							}

						},
						error: function(xhr, status, error) {
							console.log(error);
						}
					});
				}

				function check_nama() {
					var nama_bpjs = $('#nama_bpjs').val();
					var data = {
						nama_bpjs: nama_bpjs
					};
					console.log(data);
					$.ajax({
						type: 'POST',
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/check_nama",
						data: data,
						dataType: "JSON",
						success: function(data) {

							if (data.daftar == '1') {

								$("#modalDetailDaftarFarmasi").modal("show");
								if ($.fn.DataTable.isDataTable('#detailfarmasi')) {
									$('#detailfarmasi').DataTable().destroy();
								}

            					// Isi data yang diterima dari response ke dalam tbody
            					$("#detailfarmasi tbody").html(data.list);

            					// Inisialisasi ulang DataTables setelah data diisi
            					$('#detailfarmasi').DataTable({
            						"ordering": true,
            						"autoWidth": true,
            					});

            				} else {
            					$("#MJKN_halaman").html("Mohon maaf, tidak ada pendaftaran pasien hari ini");
            					$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
            					$("#modalLompatMenuRajal").modal("show");

            				}

            			},
            			error: function(xhr, status, error) {
            				console.log(error);
            			}
            		});
				}

				function ambil_hari_ini(noreg, norm, nokartu) {

					var keterangan = 'Ambil hari ini';
					var kode_form = 'B';

					$.ajax({
						type: 'POST',
						data: {
							noreg: noreg,
							norm: norm,
							nokartu: nokartu,
							keterangan: keterangan,
							kode_form : kode_form
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/insert_antrian_farmasi",
						dataType: "JSON",
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
							console.log(data);
							if (data.daftar == '0') {
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_farmasi/" + noreg + "/" + norm, "_blank").focus();

								$("#MJKN_halaman").html("Berhasil simpan");
								// $("#EMR_warning_msg").html("Berhasil");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								setTimeout(function() {
									$("#modalLompatMenuRajal").modal('hide');
								}, 3000);

							} else {
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_farmasi/" + noreg + "/" + norm + "/" + keterangan, "_blank").focus();

								$("#MJKN_halaman").html("Ok");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								setTimeout(function() {
									$("#modalLompatMenuRajal").modal('hide');
								}, 3000);
							}
						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");

							setTimeout(function() {
								$("#modalProses").modal('hide');
							}, 1000);
						},
					});
				}

				function ambil_besok(noreg, norm, nokartu) {

					var keterangan = 'Ambil besok';
					var kode_form = 'C';

					$.ajax({
						type: 'POST',
						data: {
							noreg: noreg,
							norm: norm,
							nokartu: nokartu,
							keterangan: keterangan,
							kode_form : kode_form
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/insert_antrian_farmasi",
						dataType: "JSON",
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
							console.log(data);
							if (data.daftar == '0') {
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_farmasi/" + noreg + "/" + norm, "_blank").focus();

								$("#MJKN_halaman").html("Berhasil simpan");
								// $("#EMR_warning_msg").html("Berhasil");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								setTimeout(function() {
									$("#modalLompatMenuRajal").modal('hide');
								}, 3000);

							} else {
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_farmasi/" + noreg + "/" + norm + "/" + keterangan, "_blank").focus();

								$("#MJKN_halaman").html("Ok");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								setTimeout(function() {
									$("#modalLompatMenuRajal").modal('hide');
								}, 3000);
							}
						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");

							setTimeout(function() {
								$("#modalProses").modal('hide');
							}, 1000);
						},
					});
				}

				function kirim_beetu(noreg, norm, nokartu) {

					var keterangan = 'Kirim Obat';
					var kode_form = 'D';

					$.ajax({
						type: 'POST',
						data: {
							noreg: noreg,
							norm: norm,
							nokartu: nokartu,
							keterangan: keterangan,
							kode_form : kode_form
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/insert_antrian_farmasi",
						dataType: "JSON",
						beforeSend: function() {
							$("#modalProses").modal('show');
						},
						success: function(data) {
							console.log(data);
							if (data.daftar == '0') {
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_farmasi/" + noreg + "/" + norm, "_blank").focus();

								$("#MJKN_halaman").html("Berhasil simpan");
								// $("#EMR_warning_msg").html("Berhasil");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								setTimeout(function() {
									$("#modalLompatMenuRajal").modal('hide');
								}, 3000);

							} else {
								window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_farmasi/" + noreg + "/" + norm, "_blank").focus();

								$("#MJKN_halaman").html("Ok");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
								$("#modalLompatMenuRajal").modal("show");

								setTimeout(function() {
									$("#modalProses").modal('hide');
								}, 1000);
								setTimeout(function() {
									$("#modalLompatMenuRajal").modal('hide');
								}, 3000);
							}
						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");

							setTimeout(function() {
								$("#modalProses").modal('hide');
							}, 1000);
						},
					});
				}

				$(document).on("change", "#EMR_nama_dokter_kontrol_bpjs", function() {
					// Ambil value dari option yang dipilih
					var kdDPJP = $(this).val(); 
					// Mengambil value (kode bpjs dokter) dari option
					var namaDokter = $(this).find(":selected").text(); 
					// Mengambil nama dokter dari option yang dipilih

					// Set nilai ke input field
					$("#kd_dpjp_bpjs").val(kdDPJP);
					$("#nama_dokter_bpjs").val(namaDokter);

					// console.log("Kode DPJP:", kdDPJP, "Nama Dokter:", namaDokter);
				});


				function cetak_sep() {
					var no_app = document.getElementById("no_mjkn").value;
					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_booking_mjkn",
						dataType: "JSON",
						success: function(data) {
							if (no_app != '') {
								if (data.data_book == '1') {
									$("#modalLompatCetak").modal("show");
								} else {
									$("#MJKN_halaman").html("Mohon maaf, kode booking tidak ditemukan, periksa kembali kode booking pasien");
									$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
									$("#modalLompatMenuRajal").modal("show");
								}
							} else {
								$("#MJKN_halaman").html("Mohon maaf, kode booking tidak ditemukan, periksa kembali kode booking pasien");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');

							}

						}

					});

				}

				function cetak_sep_all() {
					var no_app = document.getElementById("no_mjkn").value;
					var warna = '-';

					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app,
							warna: warna
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_sukses_mjkn",
						dataType: "JSON",
						success: function(data) {
							console.log(data);
							if (data.booking == '1') {
								if (data.sukses == '1') {
									var string_mess = data.string_mess;
									var no_sep = data.noSep;
									var status = '1';

									cetak_sep_merah();
									cetak_sep_hijau();
									cetak_sep_kuning();
									cetak_sep_putih();


								} else {
									$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + ", pasien harap diarahkan menuju Admisi Pendaftaran");
									$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
									$("#modalLompatMenuRajal").modal("show");

									var string_mess = "Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + " ";
									var no_sep = '-';
									var status = '2';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();
								}

							} else {
								$("#MJKN_halaman").html("Mohon maaf, pasien belum didaftarkan, pasien harap diarahkan menuju Admisi Pendaftaran");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");
							}
						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");
						},
					});
				}

				function cetak_sep_merah() {
					var no_app = document.getElementById("no_mjkn").value;
					var warna = 'Merah';

					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app,
							warna: warna
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_sukses_mjkn",
						dataType: "JSON",
						success: function(data) {
							console.log(data);
							if (data.booking == '1') {
								if (data.sukses == '1') {
									var string_mess = data.string_mess;
									var no_sep = data.noSep;
									var status = '1';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								} else {
									$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + ", pasien harap diarahkan menuju Admisi Pendaftaran");
									$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
									$("#modalLompatMenuRajal").modal("show");

									var string_mess = "Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + " ";
									var no_sep = '-';
									var status = '2';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								}

							} else {
								$("#MJKN_halaman").html("Mohon maaf, pasien belum didaftarkan, pasien harap diarahkan menuju Admisi Pendaftaran");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");
							}

						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");
						},
					});
				}

				function cetak_sep_hijau() {
					var no_app = document.getElementById("no_mjkn").value;
					var warna = 'Hijau';

					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app,
							warna: warna
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_sukses_mjkn",
						dataType: "JSON",
						success: function(data) {
							console.log(data);
							if (data.booking == '1') {
								if (data.sukses == '1') {
									var string_mess = data.string_mess;
									var no_sep = data.noSep;
									var status = '1';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								} else {
									$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + ", pasien harap diarahkan menuju Admisi Pendaftaran");
									$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
									$("#modalLompatMenuRajal").modal("show");

									var string_mess = "Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + " ";
									var no_sep = '-';
									var status = '2';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								}

							} else {
								$("#MJKN_halaman").html("Mohon maaf, pasien belum didaftarkan, pasien harap diarahkan menuju Admisi Pendaftaran");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");
							}


						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");
						},
					});
				}

				function cetak_sep_kuning() {
					var no_app = document.getElementById("no_mjkn").value;
					var warna = 'Kuning';

					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app,
							warna: warna
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_sukses_mjkn",
						dataType: "JSON",
						success: function(data) {
							console.log(data);
							if (data.booking == '1') {
								if (data.sukses == '1') {
									var string_mess = data.string_mess;
									var no_sep = data.noSep;
									var status = '1';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								} else {
									$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + ", pasien harap diarahkan menuju Admisi Pendaftaran");
									$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
									$("#modalLompatMenuRajal").modal("show");

									var string_mess = "Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + " ";
									var no_sep = '-';
									var status = '2';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();
								}

							} else {
								$("#MJKN_halaman").html("Mohon maaf, pasien belum didaftarkan, pasien harap diarahkan menuju Admisi Pendaftaran");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");
							}


						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");

						},
					});
				}

				function cetak_sep_putih() {
					var no_app = document.getElementById("no_mjkn").value;
					var warna = 'Putih';

					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app,
							warna: warna
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_sukses_mjkn",
						dataType: "JSON",
						success: function(data) {
							console.log(data);
							if (data.booking == '1') {
								if (data.sukses == '1') {
									var string_mess = data.string_mess;
									var no_sep = data.noSep;
									var status = '1';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();

								} else {
									$("#MJKN_halaman").html("Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + ", pasien harap diarahkan menuju Admisi Pendaftaran");
									$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
									$("#modalLompatMenuRajal").modal("show");

									var string_mess = "Mohon maaf, pasien terdapat kendala pembentukan SEP BPJS : " + data.string_mess + " ";
									var no_sep = '-';
									var status = '2';

									window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + no_app + "/" + no_sep + "/" + status + "/" + warna, "_blank").focus();
								}

							} else {
								$("#MJKN_halaman").html("Mohon maaf, pasien belum didaftarkan, pasien harap diarahkan menuju Admisi Pendaftaran");
								$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
								$("#modalLompatMenuRajal").modal("show");
							}
						},
						error: function(xhr, status, error) {
							console.log(error);
							// alert(error);
							$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
							$("#MJKN_error_msg").html(error);
							$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
							$("#modalLompatError").modal("show");
						},
					});
				}

				function cek_queue(string_mess, no_sep, status) {
					var no_app = document.getElementById("no_mjkn").value;
					// var keterangan = '1';

					$.ajax({
						type: 'POST',
						data: {
							no_mjkn: no_app,
							string_mess: string_mess,
							no_sep: no_sep,
							status: status
						},
						url: "<?php echo base_url(); ?>index.php/mjkn_pro/cek_queue",
						dataType: "JSON",
						success: function(data) {
							console.log(data);
							// window.open("<?php echo base_url(); ?>index.php/mjkn_pro/print_antrian_queue/" + data.noapp + "/" + data.noSep + "/" + data.status, "_blank").focus();

						},
						error: function(xhr, status, error) {
							console.log(error);
						},
					});
				}
			</script>
		</body>

		</html>