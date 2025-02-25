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
		.vl {
			border-left: 1px solid black;
			height: 120%;
			position: absolute;
			left: 50%;
			margin-left: 0%;
			top: 0;
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
							<b style="font-size: 40px;">Pendaftaran Mandiri Pasien Website</b>
						</center>
						<br>
						<br>
						<br>
						<br>
						<center>
							<div class="form-group" style="width:75%;">
								<input type="text" name="no_booking_website" id="no_booking_website" class="form-control" style="margin-left: 1%;width: 28%;height: 45px;" placeholder="Kode Booking Website">
								<!-- <a class="btn btn-primary" onclick="cek_data_booking()" style="margin-left: 1%;width: 16%;height: 45px;">Info Kode Booking</a> -->
								<a class="btn btn-primary" onclick="info_booking_web()" style="margin-left: 1%;width: 16%;height: 45px;">Info Booking</a>
								<a class="btn btn-danger" onclick="clearInput()" style="margin-left: 1%;width: 16%;height: 45px;">Clear</a>
								<a class="btn btn-success" onclick="daftar_offline()" style="margin-left: 1%;width: 16%;height: 45px;">Daftar via Offline</a>
							</div>
							<div class="form-group" style="width:75%;margin-top: 2%">
								<a class="btn btn-default" onclick="setNumber(1)" style="margin-left: 1%;width: 8%;height: 45px;">1</a>
								<a class="btn btn-default" onclick="setNumber(2)" style="margin-left: 1%;width: 8%;height: 45px;">2</a>
								<a class="btn btn-default" onclick="setNumber(3)" style="margin-left: 1%;width: 8%;height: 45px;">3</a>
								<a class="btn btn-default" onclick="setNumber(4)" style="margin-left: 1%;width: 8%;height: 45px;">4</a>
								<a class="btn btn-default" onclick="setNumber(5)" style="margin-left: 1%;width: 8%;height: 45px;">5</a>
								<a class="btn btn-default" onclick="setNumber(6)" style="margin-left: 1%;width: 8%;height: 45px;">6</a>
								<a class="btn btn-default" onclick="setNumber(7)" style="margin-left: 1%;width: 8%;height: 45px;">7</a>
								<a class="btn btn-default" onclick="setNumber(8)" style="margin-left: 1%;width: 8%;height: 45px;">8</a>
								<a class="btn btn-default" onclick="setNumber(9)" style="margin-left: 1%;width: 8%;height: 45px;">9</a>
								<a class="btn btn-default" onclick="setNumber(0)" style="margin-left: 1%;width: 8%;height: 45px;">0</a>
							</div>
							<div class="form-group" style="width:75%;margin-top: 1%">
								<a class="btn btn-default" onclick="setNumber('Q')" style="margin-left: 1%;width: 8%;height: 45px;">Q</a>
								<a class="btn btn-default" onclick="setNumber('W')" style="margin-left: 1%;width: 8%;height: 45px;">W</a>
								<a class="btn btn-default" onclick="setNumber('E')" style="margin-left: 1%;width: 8%;height: 45px;">E</a>
								<a class="btn btn-default" onclick="setNumber('R')" style="margin-left: 1%;width: 8%;height: 45px;">R</a>
								<a class="btn btn-default" onclick="setNumber('T')" style="margin-left: 1%;width: 8%;height: 45px;">T</a>
								<a class="btn btn-default" onclick="setNumber('Y')" style="margin-left: 1%;width: 8%;height: 45px;">Y</a>
								<a class="btn btn-default" onclick="setNumber('U')" style="margin-left: 1%;width: 8%;height: 45px;">U</a>
								<a class="btn btn-default" onclick="setNumber('I')" style="margin-left: 1%;width: 8%;height: 45px;">I</a>
								<a class="btn btn-default" onclick="setNumber('O')" style="margin-left: 1%;width: 8%;height: 45px;">O</a>
								<a class="btn btn-default" onclick="setNumber('P')" style="margin-left: 1%;width: 8%;height: 45px;">P</a>
							</div>
							<div class="form-group" style="width:75%;margin-top: 1%">
								<a class="btn btn-default" onclick="setNumber('A')" style="margin-left: 1%;width: 8%;height: 45px;">A</a>
								<a class="btn btn-default" onclick="setNumber('S')" style="margin-left: 1%;width: 8%;height: 45px;">S</a>
								<a class="btn btn-default" onclick="setNumber('D')" style="margin-left: 1%;width: 8%;height: 45px;">D</a>
								<a class="btn btn-default" onclick="setNumber('F')" style="margin-left: 1%;width: 8%;height: 45px;">F</a>
								<a class="btn btn-default" onclick="setNumber('G')" style="margin-left: 1%;width: 8%;height: 45px;">G</a>
								<a class="btn btn-default" onclick="setNumber('H')" style="margin-left: 1%;width: 8%;height: 45px;">H</a>
								<a class="btn btn-default" onclick="setNumber('J')" style="margin-left: 1%;width: 8%;height: 45px;">J</a>
								<a class="btn btn-default" onclick="setNumber('K')" style="margin-left: 1%;width: 8%;height: 45px;">K</a>
								<a class="btn btn-default" onclick="setNumber('L')" style="margin-left: 1%;width: 8%;height: 45px;">L</a>
								<a class="btn btn-default" onclick="setNumber('-')" style="margin-left: 1%;width: 8%;height: 45px;">-</a>
							</div>
							<div class="form-group" style="width:75%;margin-top: 1%">
								<a class="btn btn-default" onclick="setNumber('Z')" style="margin-left: 1%;width: 8%;height: 45px;">Z</a>
								<a class="btn btn-default" onclick="setNumber('X')" style="margin-left: 1%;width: 8%;height: 45px;">X</a>
								<a class="btn btn-default" onclick="setNumber('C')" style="margin-left: 1%;width: 8%;height: 45px;">C</a>
								<a class="btn btn-default" onclick="setNumber('V')" style="margin-left: 1%;width: 8%;height: 45px;">V</a>
								<a class="btn btn-default" onclick="setNumber('B')" style="margin-left: 1%;width: 8%;height: 45px;">B</a>
								<a class="btn btn-default" onclick="setNumber('N')" style="margin-left: 1%;width: 8%;height: 45px;">N</a>
								<a class="btn btn-default" onclick="setNumber('M')" style="margin-left: 1%;width: 8%;height: 45px;">M</a>
								<br>
								<a class="btn btn-default" onclick="setDelete()" style="margin-left: 1%;width: 17%;height: 45px;"><i class="fa-solid fa-arrow-left"></i></a>
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

	<div id="modalLompatInfoPasien" class="modal fade" role="dialog" data-backdrop="static">
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
					<!-- <input type="text" name="no_website_press" id="no_website_press" class="form-control" style="width: 50%;height: 35px;" disabled=""> -->
					<p id="kode_booking"></p>
					<p id="no_antrian"></p>
					<p id="nama_pasien"></p>
					<p id="nik_pasien"></p>
					<p id="tgl_daftar"></p>
					<p id="nama_dokter"></p>
				</div>
				<div class="modal-footer">
					<a class="btn btn-primary" onclick="daftar_booking()" style="width: 30%; height: 45px;">Daftar</a>
					<a class="btn btn-secondary" class="btn btn-default" data-dismiss="modal" style="width: 30%; height: 45px;">Tutup</a>
				</div>
			</div>

		</div>
	</div>

	<div id="modalLompatDaftarOffline" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">					
					<h4 class="blue modal-title">
						<b> Daftar via Offline</b>
					</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<a class="btn btn-danger" onclick="info_pasien_lama()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Pasien Lama</a>
					<a class="btn btn-success" onclick="info_pasien_baru()" style="margin-left: 1%;margin-top: 5%;width: 30%;">Pasien Baru</a>
				</div>
				<div class="modal-footer">
					<a class="btn btn-secondary" data-dismiss="modal" style="margin-top: 5%;width: 30%;">Tutup</a>
				</div>
			</div>

		</div>
	</div>

	<div id="modalLompatMenuPasienLama" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="blue modal-title">
						<b> Info Pasien Lama</b>
					</h4>					
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row col-md-12" style="margin-top: 3%;position: relative;">
						<div class="col-md-2">
							<label> No RM/NIK<br>Pasien : </label>  
						</div>
						<div class="col-md-4">
							<input type="text" name="no_pasien" id="no_pasien" class="form-control">
						</div>
						<div class="col-md-3">
							<a class="btn btn-warning" onclick="info_norm()" style="margin-left: 15%;">Info by No RM</a>
						</div>
						<div class="col-md-3">
							<a class="btn btn-success" onclick="info_nik()" style="margin-left: -5%;">Info by NIK</a>
						</div>
					</div>
					<div class="row col-md-12" style="width:100%;">
						<a class="btn btn-default" onclick="setNumberk(1)" style="margin-left: 1%;width: 15%;height: 45px;">1</a>
						<a class="btn btn-default" onclick="setNumberk(2)" style="margin-left: 1%;width: 15%;height: 45px;">2</a>
						<a class="btn btn-default" onclick="setNumberk(3)" style="margin-left: 1%;width: 15%;height: 45px;">3</a>
					</div>
					<div class="row col-md-12" style="width:100%;">
						<a class="btn btn-default" onclick="setNumberk(4)" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">4</a>
						<a class="btn btn-default" onclick="setNumberk(5)" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">5</a>
						<a class="btn btn-default" onclick="setNumberk(6)" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">6</a>
					</div>
					<div class="row col-md-12" style="width:100%;">
						<a class="btn btn-default" onclick="setNumberk(7)" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">7</a>
						<a class="btn btn-default" onclick="setNumberk(8)" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">8</a>
						<a class="btn btn-default" onclick="setNumberk(9)" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">9</a>
					</div>
					<div class="row col-md-12" style="width:100%;">
						<a class="btn btn-default" onclick="setNumberk('-')" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">-</a>
						<a class="btn btn-default" onclick="setNumberk(0)" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;">0</a>
						<a class="btn btn-default" onclick="setDeletek()" style="margin-left: 1%;margin-top: 1%;width: 15%;height: 45px;"><i class="fa-solid fa-arrow-left"></i></a>
					</div>
					<div class="row col-md-12" style="width:100%;">
						<a class="btn btn-danger" onclick="clearInputk()" style="margin-left: 1%;margin-top: 2%;width: 45%;">Clear</a>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			</div>

		</div>
	</div>

	<div id="modalLompatInfoNoRmPasien" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="blue modal-title">
						<b> Info Identitas Pasien</b>
					</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row col-md-12" style="margin-top: 0%;position: relative;">
						<div class="col-md-2">
							<label> NIK : </label>  
						</div>
						<div class="col-md-10">
							<input class="form-control" type="text" id="nik_pasien_rm" placeholder="NIK">
						</div>
					</div>
					<div class="row col-md-12" style="margin-top: 3%;position: relative;">
						<div class="col-md-2">
							<label> No. RM : </label>  
						</div>
						<div class="col-md-3">
							<input class="form-control" type="text" id="no_rm" placeholder="No. RM" disabled>
						</div>
						<div class="col-md-2">
							<label> Nama Pasien : </label>  
						</div>
						<div class="col-md-5">
							<input class="form-control" type="text" id="nama_pasien_rm" placeholder="Nama Pasien" disabled>
						</div>
						
					</div>
					<div class="row col-md-12" style="margin-top: 3%;position: relative;">
						<div class="col-md-2">
							<label> Jenis Kelamin : </label>  
						</div>
						<div class="col-md-3">
							<input class="form-control" type="text" id="kdseks_rm" placeholder="Jenis Kelamin" disabled>
						</div>
						<div class="col-md-2">
							<label> Tempat Lahir : </label>  
						</div>
						<div class="col-md-5">
							<input class="form-control" type="text" id="tmplahir_rm" placeholder="Tempat Lahir" disabled>
						</div>
					</div>
					<div class="row col-md-12" style="margin-top: 3%;position: relative;">
						<div class="col-md-2">
							<label> Umur (tahun) : </label>  
						</div>
						<div class="col-md-3">
							<input class="form-control" type="text" id="umurtahun_rm" placeholder="Umur" disabled>
						</div>
						<div class="col-md-2">
							<label> Tanggal Lahir : </label>  
						</div>
						<div class="col-md-5">
							<input class="form-control" type="text" id="tgllahir_rm" placeholder="Tanggal Lahir" disabled>
						</div>
					</div>
					<div class="row col-md-12" style="margin-top: 3%;position: relative;">
						<div class="col-md-2">
							<label> No. Telepon / HP : </label>  
						</div>
						<div class="col-md-3">
							<input class="form-control" type="text" id="no_telepon_rm" placeholder="No. Telepon / HP">
						</div>
						<div class="col-md-2">
							<label> Alamat : </label>  
						</div>
						<div class="col-md-5">
							<input class="form-control" type="text" id="alamat_rm" placeholder="Alamat" disabled>
						</div>
					</div>
					<div class="row col-md-12" style="margin-top: 3%;">
						<div style="display: contents;">
							<div class="col-md-2">
								<label for="kota" style="float: left;">Penjamin</label>
							</div>
							<div class="col-md-2">
								<div class="form-check">
									<input type="radio" class="form-check-input" id="pribadi" name="penjamin" value="">Pribadi
									<label class="form-check-label" for="pribadi"></label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-check">
									<input type="radio" class="form-check-input" id="bpjstk" name="penjamin" value="BPJ15">BPJS KETENAGAKERJAAN
									<label class="form-check-label" for="pribadi"></label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-check">
									<input type="radio" class="form-check-input" id="asuransi" name="penjamin" value="PLN70">Asuransi
									<label class="form-check-label" for="pribadi"></label>
								</div>
							</div>

						</div>
					</div>
					<input class="form-control" type="hidden" id="serviceid" disabled>
					<input class="form-control" type="hidden" id="doctorid" disabled>
					<div class="row col-md-12" style="margin-top: 2%;position: relative;">
						<div class="col-md-4">
							<input class="form-control" type="text" id="kdpoli" placeholder="Kode Poli" disabled>
						</div>
						<div class="col-md-4">
							<input class="form-control" type="text" id="nmpoli" placeholder="Nama Poli" disabled>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-primary form-control" onclick="cari_poli()">Cari Poli</button>
						</div>
					</div>
					<div class="row col-md-12" style="margin-top: 2%;position: relative;">
						<div class="col-md-6">
							<input class="form-control" type="text" id="kddokter" placeholder="Kode Dokter" disabled>
						</div>
						<div class="col-md-6">
							<input class="form-control" type="text" id="nmdokter" placeholder="Nama Dokter" disabled>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a class="btn btn-success" onclick="daftar_pasien()" style="width: 30%; height: 45px;">Daftar</a>
					<a class="btn btn-secondary" data-dismiss="modal" style="width: 30%; height: 45px;">Tutup</a>
				</div>
			</div>

		</div>
	</div>

	<div class="modal fade" id="modalLompatMenuPasienBaru" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-lg" style="width: 90%">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="blue modal-title">
						<b> Pembuatan Identitas Pasien Baru</b>
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row col-md-12">
						<div class="col-md-6" style="text-align: center;">
							<?php
							$five_db = $this->load->database('five', TRUE);
							$get_barcode = $five_db->query("SELECT keterangan, setting FROM emr_setting WHERE app = 'Barcode'");
							$barcode_img = $get_barcode->row()->keterangan;
							$barcode_link = $get_barcode->row()->setting;
							?>
							<img src='<?php echo base_url() . $barcode_img; ?>' style='height:75%;width: 60%;'/>
							<br>
							<br>
							<br>
							<a href="<?= $barcode_link; ?>" target="_blank"><?php echo $barcode_link; ?></a>
						</div>
						<div class="vl"></div>
						<div class="col-md-6">
							<p style="font-size: 18px"><b>Tata cara : </b><br>
								- Hubungkan perangkat dengan wifi (RS Royal Lt.1.1 / RS Royal Lt.1.2 / RS Royal Lt.1.3)<br>
								- Masukkan password wifi : royal123! <br>
								- Scan barcode untuk mendapatkan link pendaftaran sebagai pasien baru <br>
								- Klik pada link yang diberikan, dan isi identitas pasien dan pendaftaran pasien <br>
								- Klik daftar dan menunggu pemanggilan kasir untuk pembayaran dan pengarahan menuju poli
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<div id="modalLompatCariPoli" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg"  style="max-width: 75%;">

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
					<h4 class="modal-title">Proses, <br/>harap menunggu ...</h4>
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
		$(function () {
			$('select').selectpicker();
		});

		window.onload = function() {
			document.getElementById("no_booking_website").focus();
		}

		function setNumber(clickedNumber) {
			document.getElementById("no_booking_website").value += clickedNumber;
			document.getElementById("no_booking_website").focus();
		}

		function setDelete() {
			document.getElementById("no_booking_website").value = document.getElementById("no_booking_website").value.slice(0, -1);
			document.getElementById("no_booking_website").focus();
		}

		function clearInput() {
			document.getElementById("no_booking_website").value = '';
			document.getElementById("no_booking_website").focus();
		}

		function setNumberk(clickedNumber){
			document.getElementById("no_pasien").value += clickedNumber;
			document.getElementById("no_pasien").focus();
		}
		function setDeletek() {
			document.getElementById("no_pasien").value = document.getElementById("no_pasien").value.slice(0, -1);
			document.getElementById("no_pasien").focus();
		}
		function clearInputk() {
			document.getElementById("no_pasien").value = '';
			document.getElementById("no_pasien").focus();
		}

		// function setNumberInfo(clickedNumber){
		// 	document.getElementById("nik_pasien_rm").value += clickedNumber;
		// 	document.getElementById("nik_pasien_rm").focus();
		// }
		// function setDeleteInfo() {
		// 	document.getElementById("nik_pasien_rm").value = document.getElementById("nik_pasien_rm").value.slice(0, -1);
		// 	document.getElementById("nik_pasien_rm").focus();
		// }
		// function clearInputInfo() {
		// 	document.getElementById("nik_pasien_rm").value = '';
		// 	document.getElementById("nik_pasien_rm").focus();
		// }

		function info_booking_web(){
			var no_app = document.getElementById("no_booking_website").value;
			$.ajax({
				type: 'POST',
				data: { 
					no_mjkn: no_app
				},
				url: "<?php echo base_url(); ?>index.php/website_pro/info_booking_web",
				dataType : "JSON",
				success:function(data){ 
					console.log(data);
					if(data.data_book == '1'){
						$("#kode_booking").html("Kode Booking : " +data.book_code);
						$("#no_antrian").html("No RM : " +data.ps_nomor_rm);
						$("#nama_pasien").html("Nama Pasien : " +data.book_booker_fullname);
						$("#nik_pasien").html("NIK : " +data.ps_nik);
						$("#tgl_daftar").html("Tgl Booking : " +data.jad_tanggal);
						$("#nama_dokter").html("Nama Dokter : " +data.dr_label);
						$("#modalLompatInfoPasien").modal("show");
					} else {
						$("#MJKN_halaman").html("Mohon maaf, data booking tidak ditemukan, periksa kembali data booking pasien");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
						$("#modalLompatMenuRajal").modal("show");
					}
					
				},
				error:function(xhr, status, error){
					console.log(error);
					// alert(error);
					$("#MJKN_error_halaman").html("Mohon maaf terdapat kesalahan pada program MJKN, pasien harap diarahkan menuju Admisi Pendaftaran");
					$("#MJKN_error_msg").html(error);
					$("#MJKN_error_img").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
					$("#modalLompatError").modal("show");
				},
			});
		}

		function daftar_offline() {
			$("#modalLompatDaftarOffline").modal("show");
		}

		function info_pasien_lama() {
			$("#modalLompatMenuPasienLama").modal("show");
			document.getElementById("no_pasien").value = '';
			document.getElementById("kdpoli").value = '';
			document.getElementById("nmpoli").value = '';
			document.getElementById("kddokter").value = '';
			document.getElementById("nmdokter").value = '';
		}

		function info_pasien_baru() {
			$("#modalLompatMenuPasienBaru").modal("show");
		}

		function cari_poli(){
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
		
		function daftar_booking() {
			var no_app = document.getElementById("no_booking_website").value;
			warna = '-';

			$.ajax({
				type: 'POST',
				data: {
					no_mjkn: no_app
				},
				url: "<?php echo base_url(); ?>index.php/website_pro/cek_daftar",
				dataType: "JSON",
				success: function(data) {
					console.log(data);
					if (data.kunjungan == '1') {
						$("#MJKN_halaman").html("Mohon maaf pasien sudah terdaftar pada poli "+ data.nmpoli +" di tanggal "+ data.tgl_hari_ini +", harap konfirmasi ke Customer Information/Admisi");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
					} else if (data.kunjungan == '2') {
						$("#MJKN_halaman").html(data.code);
						$("#modalLompatMenuRajal").modal("show");
					} else {
						$("#MJKN_halaman").html(data.pesan);
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
						window.open("<?php echo base_url(); ?>index.php/website_pro/print_antrian_queue/" + data.no_rm + "/" + data.code + "/" + warna, "_blank").focus();
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

		function info_norm() {
			var no_rm = document.getElementById("no_pasien").value;

			$.ajax({
				type: 'POST',
				data: {
					no_rm: no_rm
				},
				url: "<?php echo base_url(); ?>index.php/website_pro/cek_info_no_rm",
				dataType: "JSON",
				success: function(data) {
					console.log(data);
					if (data.data_book == '1') {
						$("#nik_pasien_rm").val(data.nik);
						$("#no_rm").val(data.norm);
						$("#nama_pasien_rm").val(data.nama);
						$("#kdseks_rm").val(data.kdseks);
						$("#tmplahir_rm").val(data.tmplahir);
						$("#tgllahir_rm").val(data.tgllahir);
						$("#umurtahun_rm").val(data.umurtahun);
						$("#alamat_rm").val(data.jalan);
						$("#no_telepon_rm").val(data.notelepon);
						$("#modalLompatInfoNoRmPasien").modal("show");
					} else {
						$("#MJKN_halaman").html("Mohon maaf, data pasien tidak ditemukan, periksa kembali no RM pasien");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
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

		function info_nik() {
			var nik = document.getElementById("no_pasien").value;

			$.ajax({
				type: 'POST',
				data: {
					nik: nik
				},
				url: "<?php echo base_url(); ?>index.php/website_pro/cek_info_nik",
				dataType: "JSON",
				success: function(data) {
					console.log(data);
					if (data.data_book == '1') {
						$("#nik_pasien_rm").val(data.nik);
						$("#no_rm").val(data.norm);
						$("#nama_pasien_rm").val(data.nama);
						$("#kdseks_rm").val(data.kdseks);
						$("#tmplahir_rm").val(data.tmplahir);
						$("#tgllahir_rm").val(data.tgllahir);
						$("#umurtahun_rm").val(data.umurtahun);
						$("#alamat_rm").val(data.jalan);
						$("#no_telepon_rm").val(data.notelepon);
						$("#modalLompatInfoNoRmPasien").modal("show");
					} else {
						$("#MJKN_halaman").html("Mohon maaf, data pasien tidak ditemukan, periksa kembali NIK pasien");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/error.png'; ?>" style="width:150px">');
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

		function set_poli(kode_poli, poli, kode_dokter, dokter, service_id, doctor_id) {
			
			$("#kdpoli").val(kode_poli);
			$("#nmpoli").val(poli);
			$("#kddokter").val(kode_dokter);
			$("#nmdokter").val(dokter);
			$("#serviceid").val(service_id);
			$("#doctorid").val(doctor_id);
			$("#modalLompatCariPoli").modal("hide");
		}

		function daftar_pasien() {
			nik_pasien_rm = document.getElementById("nik_pasien_rm").value;
			no_rm = document.getElementById("no_rm").value;
			nama_pasien_rm = document.getElementById("nama_pasien_rm").value;
			kdseks_rm = document.getElementById("kdseks_rm").value;
			tmplahir_rm = document.getElementById("tmplahir_rm").value;
			umurtahun_rm = document.getElementById("umurtahun_rm").value;
			tgllahir_rm = document.getElementById("tgllahir_rm").value;
			no_telepon_rm = document.getElementById("no_telepon_rm").value;
			alamat_rm = document.getElementById("alamat_rm").value;
			kdpoli = document.getElementById("kdpoli").value;
			nmpoli = document.getElementById("nmpoli").value;
			kddokter = document.getElementById("kddokter").value;
			nmdokter = document.getElementById("nmdokter").value;
			serviceid = document.getElementById("serviceid").value;
			doctorid = document.getElementById("doctorid").value;

			warna = '-';

			$.ajax({
				type: 'POST',
				data: {
					nik_pasien_rm: nik_pasien_rm,
					no_rm: no_rm,
					nama_pasien_rm: nama_pasien_rm,
					kdseks_rm: kdseks_rm,
					tmplahir_rm: tmplahir_rm,
					umurtahun_rm: umurtahun_rm,
					tgllahir_rm: tgllahir_rm,
					no_telepon_rm: no_telepon_rm,
					alamat_rm: alamat_rm,
					kdpoli: kdpoli,
					nmpoli: nmpoli,
					kddokter: kddokter,
					nmdokter: nmdokter,
					serviceid: serviceid,
					doctorid: doctorid,
					penjamin: $("input[name ='penjamin']:checked").val()
				},
				url: "<?php echo base_url(); ?>index.php/website_pro/daftar_pasien",
				dataType: "JSON",
				success: function(data) {
					console.log(data);
					if (data.kunjungan == '1') {
						$("#MJKN_halaman").html("Mohon maaf pasien sudah terdaftar pada poli "+ data.nmpoli +" di tanggal "+ data.tgl_hari_ini +", harap konfirmasi ke Customer Information/Admisi");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
					} else {
						$("#MJKN_halaman").html("Pendaftaran berhasil, silahkan menunggu panggilan kasir untuk pembayaran");
						$("#EMR_img_warning").html('<img src="<?php echo base_url() . 'assets/img/check.gif'; ?>" style="width:480px">');
						$("#modalLompatMenuRajal").modal("show");
						window.open("<?php echo base_url(); ?>index.php/website_pro/print_antrian_queue/" + no_rm + "/" + data.code + "/" + warna, "_blank").focus();
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
		
	</script>
</body>

</html>