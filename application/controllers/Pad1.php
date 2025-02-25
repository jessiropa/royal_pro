<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pad1 extends CI_Controller {

	public function __costruct()
	{
		parent::__costruct();
		date_default_timezone_set('Asia/Jakarta');
		$dbsecond = $this->load->database('five',TRUE);
	}

	public function index()
	{
		$this->load->view('pad1');
	}

	public function get_mr()
	{
		$norm = $this->input->post('norm');
		$five_db = $this->load->database('five',TRUE);
		$three_db = $this->load->database('three',TRUE);

		// ambil data pasien
		$get_px = $three_db->query("SELECT nama, kdseks, umurtahun, jalan, CAST(tgllahir AS DATE) AS ttl FROM Pasien WHERE norm = '$norm'");
		if($get_px->num_rows() > 0){
			$nama_pasien = $get_px->row()->nama;
			$jk = $get_px->row()->kdseks;
			$umur = $get_px->row()->umurtahun;
			$alamat = $get_px->row()->jalan;
			$ttl = $get_px->row()->ttl;
		}else{
			$nama_pasien = '';
			$jk = '';
			$umur = '';
			$alamat = '';
			$ttl = '';
		}

		$jenis_kelamin = '';
		if($jk == 'L'){
			$jenis_kelamin = 'Laki-laki';
		}else if($jk == 'P'){
			$jenis_kelamin = 'Perempuan';
		}

		// ambil ttd
		$get_ttd = $five_db->query("SELECT * FROM EMR_TTD_PASIEN WHERE No_rm='$norm' AND status = 'BARU'");

		if($get_ttd->num_rows() > 0){
			$url_ttd = $get_ttd->row()->ttd;
		}else{
			$url_ttd = '';
			// echo 'tidak ada data';
		}

		// ambil noreg, nama dokter, dan poli
		$data_terakhir = $five_db->query("SELECT TOP(1)
		noreg,
		nama_dokter, 
		poli
		FROM EMR_GET_DATA_KUNJUNGAN
		WHERE norm = '$norm'
		AND batal = '0'
		ORDER BY tgl_reg DESC");

		if($data_terakhir->num_rows() > 0){
			$noreg = $data_terakhir->row()->noreg;
			$nama_dokter = $data_terakhir->row()->nama_dokter;
			$poli = $data_terakhir->row()->poli;
		}else{
			$noreg = '';
			$nama_dokter = '';
			$poli = '';
			// echo 'tidak ada data';
		}

		$get_konfirmasi = $five_db->query("SELECT * FROM EMR_KONFIRMASI_TTD WHERE NORM = '$norm' AND NOREG = '$noreg' AND STATUS2 = 'BARU'");
		
		if($get_konfirmasi->num_rows() > 0){
			$konfirmasi = '1';
			$tglkonfirmasi = $get_konfirmasi->row()->TGL_INSERT;
		}else{
			$konfirmasi = '0';
			$tglkonfirmasi = '';
		}

		echo json_encode([
			'nama' => $nama_pasien,
			'jenis_kelamin' => $jenis_kelamin,
			'umur' => $umur,
			'alamat' => $alamat,
			'ttl' => $ttl,
			'noreg' => $noreg,
			'dokter' => $nama_dokter,
			'poli' => $poli,
			'konfirmasi' => $konfirmasi,
			'tgl' => $tglkonfirmasi,
			'ttd' => $url_ttd
		]);
		
	}

	public function upload()
	{ 
		$norm = $this->input->post('norm');

		// block browser untuk tidak simpan
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$dbfive = $this->load->database('five', TRUE);
		date_default_timezone_set('Asia/Jakarta');
		$dateins = DATE("Y-m-d h:i:s");
		// $ip_server = '192.168.80.143';
		$ip_server = $_SERVER['SERVER_ADDR'];
		$ip_insert = $_SERVER['REMOTE_ADDR'];

		if (empty($norm)) {
			echo json_encode(["status" => "error", "message" => "NORM tidak ditemukan"]);
			return;
		}

		$filename = $_FILES['gambar']['name']; // Nama file
		$ttd_img = $_FILES['gambar']['tmp_name'];

		// path untuk di database
		$path = "assets/ttd_pasien/";
		$ImagePath = $path . $filename;
		$ttd_l = "http://" . $ip_server . "/royal_pro/" . $ImagePath;

		// path untuk simpan gambar (menggunakan file sharing)
		// $path2 = "D:/ttd_pasien/";
		// $path2 = "\\\\192.168.80.143\\ttd_pasien\\";
		// $ImagePath2 = $path2 . $filename;

		// cek folder ada atau tidak
		// if (!file_exists($path2)) {
		// 	mkdir($path2, 0777, true);
		// }

		// Hapus foto yang lama di dalam folder ttd_pasien dgn norm yg dicari
		// $files = glob("\\\\192.168.80.143\\ttd_pasien\\" . $norm . "*.png");
		// foreach ($files as $file) {
		// 	unlink($file);
		// }

		$files = glob("ttd_pasien/" . $norm . "*.png");
		foreach ($files as $file) {
			unlink($file);
		}

		// upload hasil gambar ke folder ttd pasien
		if (move_uploaded_file($ttd_img, $ImagePath)) {
			// Jika berhasil maka akan melakukan pengecekan ke database 
			$cek_data = $dbfive->query("SELECT * FROM EMR_TTD_PASIEN WHERE No_rm = '$norm'");
			if ($cek_data->num_rows() > 0) {
				// jika ada data lama maka data tersebut di update menjadi revisi
				$dbfive->query("UPDATE EMR_TTD_PASIEN SET tgl_update='$dateins', ip_insert='$ip_insert', status='REVISI' WHERE No_rm='$norm'");
				// kemudian akan di insert baru dengan data terbaru dan nama ttd terbaru
				$dbfive->query("INSERT INTO EMR_TTD_PASIEN (No_rm, ttd, tgl_insert, ip_insert, status, filename) VALUES ('$norm', '$ttd_l', '$dateins', '$ip_insert', 'BARU', '$filename')");
			} else {
				// jika tidak ada data sama sekali maka akan dilakukan insert data terbaru
				$dbfive->query("INSERT INTO EMR_TTD_PASIEN (No_rm, ttd, tgl_insert, ip_insert, status, filename) VALUES ('$norm', '$ttd_l', '$dateins', '$ip_insert', 'BARU', '$filename')");
				
			}

			echo json_encode(["status" => "success", "filename" => $filename]);
		
		} else {
			echo json_encode(["status" => "error", "message" => "Gagal menyimpan file"]);
		}
	}

	public function get_resep(){
		$noreg = $this->input->post('noreg');
		// emr
		$five_db = $this->load->database('five', TRUE);
		// medinfras
		$three_db = $this->load->database('three', TRUE);

		/*ambil 
		- no order obat (no jo)
		- nama pasien
		- noreg dan norm 
		- nama dokter 
		- alergi */ 
		$get_px = $five_db->query("SELECT
		nama_pasien,
		nama_dokter,
		norm,
		jalan,
		CONVERT(VARCHAR, tgl_lahir, 105) AS ttl
		FROM 
		EMR_GET_DATA_KUNJUNGAN
		WHERE 
		noreg = '$noreg'
		AND status = 0");

		if($get_px->num_rows() > 0){
			$namapx = $get_px->row()->nama_pasien;
			$dokter = $get_px->row()->nama_dokter;
			$norm = $get_px->row()->norm;
			$ttl = $get_px->row()->ttl;
			$alamat = $get_px->row()->jalan;
		}else{
			$namapx = "";
			$dokter = "";
			$norm = "";
			$ttl = "";
			$alamat = "";
		}

		$cek_id_worklist = $three_db->query("SELECT mdf.NoJO, mdf.UsrInsert FROM
		md_WorkListHD mdf
		WHERE mdf.NoReg = '$noreg'
		AND mdf.KdPMedis = 'FAR'");

		if($cek_id_worklist->num_rows() > 0){
			$noorder = $cek_id_worklist->row()->NoJO;
			// $dokter_order = $cek_id_worklist->row()->UsrInsert;
		}else{
			$noorder = "";
			// $dokter_order = "";
		}

		$get_alergi = $five_db->query("SELECT * FROM EMR_ALERGI WHERE NOREG = '$noreg'");

		if($get_alergi->num_rows() > 0){
			$alergi = $get_alergi->row()->ALERGI;
		}else{
			$alergi = "-";
		}

		$get_resep = $five_db->query("SELECT
		x.NAMA,
		x.tgl_insert
		FROM
		(
			SELECT
			CONCAT ( eos.NAMA_OBAT_SATUAN, ' ', eos.ATURAN1_OBAT_SATUAN, ' ', eos.JUMLAH_OBAT_SATUAN ) AS NAMA,
			eos.tgl_insert AS tgl_insert
			FROM
			EMR_OBAT_SATUAN eos
			LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eos.ID_PEMERIKSAAN 
			WHERE
			eup.NOREG = '$noreg' 
			AND eos.STATUS_OBAT_SATUAN = 'BARU'
			AND 1 = 1
			
			UNION ALL

			SELECT
			eot.ISI_RESEP AS NAMA,
			eot.tgl_insert AS tgl_insert
			FROM
			EMR_OBAT_SATUAN_TXT eot
			LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eot.ID_PEMERIKSAAN 
			WHERE
			eup.NOREG = '$noreg' 
			AND eot.STATUS_OBAT_SATUAN = 'BARU'
			AND 1 = 1 
			UNION ALL

			SELECT
			eor.OBAT_RACIK AS NAMA,
			eor.TANGGAL_INPUT AS tgl_insert
			FROM
			EMR_OBAT_RACIK eor
			LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eor.ID_PEMERIKSAAN 
			WHERE
			eup.NOREG = '$noreg' 
			AND eor.STATUS_OBAT_RACIK = 'BARU'
			AND 1 = 1
			)x
			ORDER BY x.tgl_insert")->result();

		$get_updater = $three_db->query("SELECT 
		usr.UserName
		FROM 
		fm_hdresep rsp
		LEFT JOIN [User] usr ON usr.UserID = rsp.updater
		WHERE 
		noreg = '$noreg'
		AND 
		batal = '0'

		UNION ALL

		SELECT 
		usr.UserName
		FROM 
		fm_hdresepri rsp
		LEFT JOIN [User] usr ON usr.UserID = rsp.updater
		WHERE 
		noreg = '$noreg'
		AND 
		batal = '0'

		UNION ALL

		SELECT 
		usr.UserName
		FROM 
		fm_hdresepRD rsp
		LEFT JOIN [User] usr ON usr.UserID = rsp.updater
		WHERE 
		noreg = '$noreg'
		AND 
		batal = '0'");

		if($get_updater->num_rows() > 0){
			$etiket = $get_updater->row()->UserName;
		}else{
			$etiket = "-";
		}

		if($noorder == ""){
			$dokter_order = "-";
		}else{
			$dokter_order = $dokter;
		}

		$get_lihat_resep = $five_db->query("SELECT * FROM EMR_TTD_LIHAT_RESEP WHERE NOREG = '$noreg' AND NORM = '$norm' AND STATUS = 'BARU'");

		if($get_lihat_resep->num_rows() > 0){
			$racikinput = $get_lihat_resep->row()->RACIKAN_INPUT;
			$penyerahan_user = $get_lihat_resep->row()->PENYERAHAN_INPUT;
			$reseplengkap = $get_lihat_resep->row()->RESEP_LENGKAP;
			$farmasetis = $get_lihat_resep->row()->FARMASETIS;
			$benerpasien = $get_lihat_resep->row()->BENAR_PASIEN;
			$benerobat = $get_lihat_resep->row()->BENAR_OBAT;
			$benerdosis = $get_lihat_resep->row()->BENAR_DOSIS;
			$benerrute = $get_lihat_resep->row()->BENAR_RUTE;
			$benerwaktu = $get_lihat_resep->row()->BENAR_WAKTU;
			$benerdokumentasi = $get_lihat_resep->row()->BENAR_DOKUMENTASI;
			$kontraindikasi = $get_lihat_resep->row()->KONTRA_INDIKASI;
			$potensialergi = $get_lihat_resep->row()->POTENSI_ALERGI;
			$duplikasi = $get_lihat_resep->row()->DUPLIKASI;
			$petugasfarmasi1 = $get_lihat_resep->row()->PETUGAS_FARMASI1;
			$telaahobat = $get_lihat_resep->row()->TELAAH_OBAT;
			$kesesuaianresep = $get_lihat_resep->row()->KESESUAIAN_RESEP;
			$namaobat = $get_lihat_resep->row()->NAMA_OBAT;
			$jumlahdosis = $get_lihat_resep->row()->JUMLAH_DOSIS;
			$rute = $get_lihat_resep->row()->RUTE;
			$waktufrekuensi = $get_lihat_resep->row()->WAKTU_FREKUENSI;
			$petugasfarmasi2 = $get_lihat_resep->row()->PETUGAS_FARMASI2;
			$konseling = $get_lihat_resep->row()->KONSELING;
			$petugaskonseling = $get_lihat_resep->row()->PETUGAS_KONSELING;
		}else{
			$racikinput = "";
			$penyerahan_user = "";
			$reseplengkap = "";
			$farmasetis = "";
			$benerpasien = "";
			$benerobat = "";
			$benerdosis = "";
			$benerrute = "";
			$benerwaktu = "";
			$benerdokumentasi = "";
			$kontraindikasi = "";
			$potensialergi = "";
			$duplikasi = "";
			$petugasfarmasi1 = $etiket;
			$telaahobat = "";
			$kesesuaianresep = "";
			$namaobat = "";
			$jumlahdosis = "";
			$rute = "";
			$waktufrekuensi = "";
			$petugasfarmasi2 = $etiket;
			$konseling = "";
			$petugaskonseling = $etiket;
		}

		
		$reseplengkapya = 'checked';
		$reseplengkaptidak = '';
		if($reseplengkap == 'ya'){
			$reseplengkapya = 'checked';
		}elseif($reseplengkap == 'tidak'){
			$reseplengkaptidak = 'checked';
		}

		$farmasetisya = 'checked';
		$farmasetistidak = '';
		if($farmasetis == 'ya'){
			$farmasetisya = 'checked';
		}elseif($farmasetis == 'tidak'){
			$farmasetistidak = 'checked';
		}
		
		$benerpasienya = 'checked';
		$benerpasientidak = ''; 
		if($benerpasien == 'ya'){
			$benerpasienya = 'checked';
		}elseif($benerpasien == 'tidak'){
			$benerpasientidak = 'checked';
		}


		$benerobatya = 'checked';
		$benerobattidak = '';
		if($benerobat == 'ya'){
			$benerobatya = 'checked';
		}elseif($benerobat == 'tidak'){
			$benerobattidak = 'checked';
		}
		
		$benerdosisya = 'checked';
		$benerdosistidak = '';
		if($benerdosis == 'ya'){
			$benerdosisya = 'checked';
		}elseif($benerdosis == 'tidak'){
			$benerdosistidak = 'checked';
		}

		$benerruteya = 'checked';
		$benerrutetidak = '';
		if($benerrute == 'ya'){
			$benerruteya = 'checked';
		}elseif($benerrute == 'tidak'){
			$benerrutetidak = 'checked';
		}

		$benerwaktuya = 'checked';
		$benerwaktutidak = '';
		if($benerwaktu == 'ya'){
			$farmasetisya = 'checked';
		}elseif($benerwaktu == 'tidak'){
			$farmasetistidak = 'checked';
		}


		$benerdokumentasiya = 'checked';
		$benerdokumentasitidak = '';
		if($benerdokumentasi == 'ya'){
			$benerdokumentasiya = 'checked';
		}elseif($benerdokumentasi == 'tidak'){
			$benerdokumentasitidak = 'checked';
		}
		
		$kontraindikasiya = 'checked';
		$kontraindikasitidak = '';
		if($kontraindikasi == 'ya'){
			$kontraindikasiya = 'checked';
		}elseif($kontraindikasi == 'tidak'){
			$kontraindikasitidak = 'checked';
		}

		$potensialergiya = 'checked';
		$potensialergitidak = '';
		if($potensialergi == 'ya'){
			$potensialergiya = 'checked';
		}elseif($potensialergi == 'tidak'){
			$potensialergitidak = 'checked';
		}

		$duplikasiya = 'checked';
		$duplikasitidak = '';
		if($duplikasi == 'ya'){
			$duplikasiya = 'checked';
		}elseif($duplikasi == 'tidak'){
			$duplikasitidak = 'checked';
		}

		$telaahobatya = 'checked';
		$telaahobattidak = '';
		if($telaahobat == 'ya'){
			$telaahobatya = 'checked';
		}elseif($telaahobat == 'tidak'){
			$telaahobattidak = 'checked';
		}

		
		$kesesuaianresepya = 'checked';
		$kesesuaianreseptidak = '';
		if($kesesuaianresep == 'ya'){
			$kesesuaianresepya = 'checked';
		}elseif($kesesuaianresep == 'tidak'){
			$kesesuaianreseptidak = 'checked';
		}
			

		$namaobatya = 'checked';
		$namaobattidak = '';
		if($namaobat == 'ya'){
			$namaobatya = 'checked';
		}elseif($namaobat == 'tidak'){
			$namaobattidak = 'checked';
		}

		$jumlahdosisya = 'checked';
		$jumlahdosistidak = '';
		if($jumlahdosis == 'ya'){
			$jumlahdosisya = 'checked';
		}elseif($jumlahdosis == 'tidak'){
			$jumlahdosistidak = 'checked';
		}

		$ruteya = 'checked';
		$rutetidak = '';
		if($rute == 'ya'){
			$ruteya = 'checked';
		}elseif($rute == 'tidak'){
			$rutetidak = 'checked';
		}else{
			
		}

		$waktufrekuensiya = 'checked';
		$waktufrekuensitidak = '';
		if($waktufrekuensi == 'ya'){
			$waktufrekuensiya = 'checked';
		}elseif($waktufrekuensi == 'tidak'){
			$waktufrekuensitidak = 'checked';
		}


		// get ttd
		$get_ttd = $five_db->query("SELECT * FROM EMR_TTD_PASIEN WHERE No_rm='$norm' AND status = 'BARU'");

		if($get_ttd->num_rows() > 0){
			$ttd = $get_ttd->row()->ttd;
		}else{
			$ttd = '';
			// echo 'tidak ada data';
		}


		$resep = "";
		$resep .= '<div class="container-fluid">
                No. Order : '.$noorder.' <br>
                Nama : '.$namapx.' <br>
                Noreg / Norm : '.$noreg.' / '.$norm.' <br>
                '.$dokter.'<br>';
				// barcode dokter farmasi 
				$this->load->library('ciqrcode');
				$config['cacheable'] = true;
				$config['cachedir'] = './qr/';
				$config['errorlog'] = './qr/';
				$config['imagedir'] = './qr/dokter_farmasi/';
				$config['quality'] = true;
				$config['size'] = '1024';
				$config['black'] = array(224, 255, 255);
				$config['white'] = array(70, 130, 180);
				$this->ciqrcode->initialize($config);

				$image_name = $dokter . '.png';
				$params['data'] = 'Dokter : ' . $dokter;
				$params['level'] = 'H';
				$params['size'] = 10;
				$params['savename'] = FCPATH . $config['imagedir'] . $image_name;
				$this->ciqrcode->generate($params);
				$resep .= '<img src="' . base_url() . '/qr/dokter_farmasi/' . $dokter . '.png" alt="" width="100" height="100"> <br>';

        $resep .='Alamat : '.$alamat.' <br>
                Tgl Lahir : '.$ttl.' <br>

                Riwayat Alergi : '.$alergi.' <br>

                <div class="dropdown-divider"></div>

                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputanfarmasi">Input</label>
                        <input type="text" class="form-control" id="inputanfarmasi" value="'.$dokter_order.'" readonly>
                      </div>
					  	<div class="form-group">
							<label for="racikanfarmasi">Racikan</label>
							<input type="text" class="form-control" id="racikanfarmasi" value="'.$racikinput.'">
							<input type="hidden" class="id_farmasi">
						</div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group">
							<label for="etiketfarmasi">Etiket</label>
							<input type="text" class="form-control" id="etiketfarmasi" value="'.$etiket.'">
							<input type="hidden" class="id_farmasi">
						</div>
						<div class="form-group">
							<label for="penyerahanfarmasi">Penyerahan</label>
							<input type="text" class="form-control" id="penyerahanfarmasi" value="'.$penyerahan_user.'">
							<input type="hidden" class="id_farmasi">
							<input type="hidden" class="form-control" id="noreg" value="'.$noreg.'" >
							<input type="hidden" class="form-control" id="norm" value="'.$norm.'" >
						</div>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <p>
                  <b>Resep Obat</b> <br>';
				foreach($get_resep as $list_resep){
					$resepi = $list_resep->NAMA;
					$resep .= $resepi.'<br>';
				}
                $resep .= '</p>

                <div class="dropdown-divider"></div>
                <b>Administrasi</b><br>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Resep Lengkap &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="resep_lengkapya" name="resep_lengkap" value="ya" '.$reseplengkapya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="resep_lengkaptidak" name="resep_lengkap" value="tidak" '.$reseplengkaptidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Farmasetis nama, bentuk, &ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;  </label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="farmasetisya" name="farmasetis" value="ya" '.$farmasetisya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="farmasetistidak" name="farmasetis" value="tidak" '.$farmasetistidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                        <br>kekuatan obat
                    </div>
                  </div>
                </div>
                <b>Farmasi Klinis</b><br>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Benar Pasien &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_pasienya" name="benar_pasien" value="ya" '.$benerpasienya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_pasientidak" name="benar_pasien" value="tidak" '.$benerpasientidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Benar Obat &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_obatya" name="benar_obat" value="ya" '.$benerobatya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_obattidak" name="benar_obat" value="tidak" '.$benerobattidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Benar Dosis &nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_dosisya" name="benar_dosis" value="ya" '.$benerdosisya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_dosistidak" name="benar_dosis" value="tidak" '.$benerdosistidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Benar Rute &nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_ruteya" name="benar_rute" value="ya" '.$benerruteya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_rutetidak" name="benar_rute" value="tidak" '.$benerrutetidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Benar Waktu &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_waktuya" name="benar_waktu" value="ya" '.$benerwaktuya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_waktutidak" name="benar_waktu" value="tidak" '.$benerwaktutidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Benar Dokumentasi &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_dokumentasiya" name="benar_dokumentasi" value="ya" '.$benerdokumentasiya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="benar_dokumentasitidak" name="benar_dokumentasi" value="tidak" '.$benerdokumentasitidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                  </div>
                </div>
                <b>Interaksi</b><br>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Kontra Indikasi &nbsp;&nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="kontra_indikasiya" name="kontra_indikasi" value="ya" '.$kontraindikasiya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="kontra_indikasitidak" name="kontra_indikasi" value="tidak" '.$kontraindikasitidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Potensi Alergi &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="potensi_alergiya" name="potensi_alergi" value="ya" '.$potensialergiya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="potensi_alergitidak" name="potensi_alergi" value="tidak" '.$potensialergitidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Duplikasi &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="duplikasiya" name="duplikasi" value="ya" '.$duplikasiya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="duplikasitidak" name="duplikasi" value="tidak" '.$duplikasitidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
					<label for="farmasiInput"><b>Petugas Farmasi</b></label>
					<input type="text" class="form-control" id="farmasiInput1" value="'.$petugasfarmasi1.'">
					<input type="hidden" class="id_farmasi">
				</div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Telaah Obat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="telaah_obatya" name="telaah_obat" value="ya" '.$telaahobatya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="telaah_obattidak" name="telaah_obat" value="tidak" '.$telaahobattidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Kesesuaian dengan resep &ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="kesesuaian_resepya" name="kesesuaian_resep" value="ya" '.$kesesuaianresepya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="kesesuaian_reseptidak" name="kesesuaian_resep" value="tidak" '.$kesesuaianreseptidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nama Obat &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="nama_obatya" name="nama_obat" value="ya" '.$namaobatya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="nama_obattidak" name="nama_obat" value="tidak" '.$namaobattidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Jumlah / Dosis &nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="jumlah_dosisya" name="jumlah_dosis" value="ya" '.$jumlahdosisya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="jumlah_dosistidak" name="jumlah_dosis" value="tidak" '.$jumlahdosistidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Rute &nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="ruteya" name="rute" value="ya" '.$ruteya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="rutetidak" name="rute" value="tidak" '.$rutetidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Waktu & Frekuensi &nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;:&ensp;&ensp;</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="waktu_frekuensiya" name="waktu_frekuensi" value="ya" '.$waktufrekuensiya.'>
                          <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="waktu_frekuensitidak" name="waktu_frekuensi" value="tidak" '.$waktufrekuensitidak.'>
                          <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                        </div>
                    </div>
                  </div>
                </div>

				<div class="form-group">
					<label for="farmasiInput"><b>Petugas Farmasi</b></label>
					<input type="text" class="form-control" id="farmasiInput2" value="'.$petugasfarmasi2.'">
					<input type="hidden" class="id_farmasi">
				</div>


				<div class="form-group">
					<label for="farmasiInput"><b>Petugas Konseling</b></label>
					<input type="text" class="form-control" id="petugaskonseling" value="'.$petugaskonseling.'">
					<input type="hidden" class="id_farmasi">
				</div>
				<div class="form-group">
					<label for="farmasiInput">Catatan Konseling</label>
					<input type="text" class="form-control" id="konseling" value="'.$konseling.'">
				</div>
				<div class="dropdown-divider"></div>
				<p>
                  <b>Nama & ttd</b> <br>
				  <label for="">'.$namapx.'</label>';
				if($ttd){
					$resep .= '<img src="'.$ttd.'" width="350" height="250" alt="Tanda Tangan Pasien">';
				}else{
					$resep .= '<p>Tanda tangan tidak tersedia</p>';
				}
				$resep .= '
				</p>
              </div>';

		echo $resep;
	}

	public function konfirmasi(){
		$norm = $this->input->post('norm');
		$noreg = $this->input->post('noreg');
		$status = $this->input->post('status');
		$ttd = $this->input->post('ttd');
		$five_db = $this->load->database('five', TRUE);

		$get_konfirmasi = $five_db->query("SELECT * FROM EMR_KONFIRMASI_TTD WHERE NORM = '$norm' AND NOREG = '$noreg' AND STATUS2 = 'BARU'");

		if($get_konfirmasi->num_rows() > 0){
			$five_db->query("UPDATE EMR_KONFIRMASI_TTD SET STATUS2 ='REVISI' WHERE NORM='$norm' AND NOREG = '$noreg'");
		}
		$tgl_insert = DATE("Y-m-d h:i:s");
		$five_db->query("INSERT INTO EMR_KONFIRMASI_TTD (NORM, NOREG, STATUS, TTD, TGL_INSERT, STATUS2) VALUES ('$norm', '$noreg', '$status', '$ttd', '$tgl_insert', 'BARU')");

		echo 'Berhasil';
	}

	public function get_farmasi(){
		$five_db = $this->load->database('five', TRUE);
		$q = $this->input->get('q');

		$data = $five_db->query("SELECT 
        id_m_pengguna as id,
        nama_pengguna as nama
		FROM m_pengguna
		WHERE id_m_grup_pengguna = 'GP017'
		AND nama_pengguna LIKE '%$q%'
		AND pengguna_aktif = '1'")->result_array();

		$arr = array();
		foreach ($data as $dt) {
			$arr[] = array(
				'label' => trim($dt['nama']), // Ini yang akan ditampilkan di dropdown
				'value' => trim($dt['nama']), // Ini yang akan muncul di input setelah dipilih
				'id'    => trim($dt['id'])    // ID yang bisa digunakan untuk keperluan lain
			);
		}

		echo json_encode($arr);
	}

	public function simpan_resep_farmasi(){
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set('Asia/Jakarta');
		$noreg = $this->input->post('noreg');
		$norm = $this->input->post('norm');
		$dokter_input = $this->input->post('dokter_input');
		$racikan_input = $this->input->post('racikan_input');
		$etiket_input = $this->input->post('etiket_input');
		$penyerahan_input = $this->input->post('penyerahan_input');
		$resep_lengkap = $this->input->post('resep_lengkap');
		$farmasetis = $this->input->post('farmasetis');
		$benar_pasien = $this->input->post('benar_pasien');
		$benar_obat = $this->input->post('benar_obat');
		$benar_dosis = $this->input->post('benar_dosis');
		$benar_rute = $this->input->post('benar_rute');
		$benar_waktu = $this->input->post('benar_waktu');
		$benar_dokuemntasi = $this->input->post('benar_dokuemntasi');
		$kontra_indikasi = $this->input->post('kontra_indikasi');
		$potensi_alergi = $this->input->post('potensi_alergi');
		$duplikasi = $this->input->post('duplikasi');
		$petugas_farmasi1 = $this->input->post('petugas_farmasi1');
		$telaah_obat = $this->input->post('telaah_obat');
		$kesesuaian_resep = $this->input->post('kesesuaian_resep');
		$nama_obat = $this->input->post('nama_obat');
		$jumlah_dosis = $this->input->post('jumlah_dosis');
		$rute = $this->input->post('rute');
		$waktu_frekuensi = $this->input->post('waktu_frekuensi');
		$petugas_farmasi2 = $this->input->post('petugas_farmasi2');
		$konseling = $this->input->post('konseling');
		$petugaskonseling = $this->input->post('petugaskonseling');
		$dateins = DATE("Y-m-d h:i:s");

		$cek_data = $five_db->query("SELECT * FROM EMR_TTD_LIHAT_RESEP WHERE NOREG = '$noreg' AND NORM = '$norm' AND STATUS = 'BARU'");

		if($cek_data->num_rows() > 0){
			$five_db->query("UPDATE EMR_TTD_LIHAT_RESEP SET STATUS ='REVISI' WHERE NORM='$norm' AND NOREG = '$noreg'");
		}

		$resep['NORM'] =$norm;
		$resep['NOREG'] =$noreg;
		$resep['DOKTER_INPUT'] =$dokter_input;
		$resep['ETIKET_INPUT'] =$etiket_input;
		$resep['RACIKAN_INPUT'] =$racikan_input;
		$resep['PENYERAHAN_INPUT'] =$penyerahan_input;
		$resep['RESEP_LENGKAP'] =$resep_lengkap;
		$resep['FARMASETIS'] =$farmasetis;
		$resep['BENAR_PASIEN'] =$benar_pasien;
		$resep['BENAR_OBAT'] =$benar_obat;
		$resep['BENAR_DOSIS'] =$benar_dosis;
		$resep['BENAR_RUTE'] =$benar_rute;
		$resep['BENAR_WAKTU'] =$benar_waktu;
		$resep['BENAR_DOKUMENTASI'] =$benar_dokuemntasi;
		$resep['KONTRA_INDIKASI'] =$kontra_indikasi;
		$resep['POTENSI_ALERGI'] =$potensi_alergi;
		$resep['DUPLIKASI'] =$duplikasi;
		$resep['PETUGAS_FARMASI1'] =$petugas_farmasi1;
		$resep['TELAAH_OBAT'] =$telaah_obat;
		$resep['KESESUAIAN_RESEP'] =$kesesuaian_resep;
		$resep['NAMA_OBAT'] =$nama_obat;
		$resep['JUMLAH_DOSIS'] =$jumlah_dosis;
		$resep['RUTE'] =$rute;
		$resep['WAKTU_FREKUENSI'] =$waktu_frekuensi;
		$resep['PETUGAS_FARMASI2'] =$petugas_farmasi2;
		$resep['KONSELING'] =$konseling;
		$resep['TGL_INSERT'] =$dateins;
		$resep['STATUS'] = 'BARU';
		$resep['PETUGAS_KONSELING'] = $petugaskonseling;

		$five_db->insert('EMR_TTD_LIHAT_RESEP', $resep);

		echo 'Berhasil';
	}
}
