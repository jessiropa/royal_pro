<?php 

class mjkn_pro extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('m_mjkn_pro');
	}

	function index(){
		$this->load->view('v_mjkn_pro');
	}

	public function cek_blacklist(){
		$three_db = $this->load->database('three', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');

		$cek_noantrian = $three_db->query("SELECT
			LTRIM(RTRIM(rja.norm)) AS norm
			FROM
			rj_appointment rja
			WHERE 1 = 1
			AND rja.noapp = '$no_mjkn'
			AND rja.updater = 'MobileJKN'");

		if($cek_noantrian->num_rows() > '0'){

			$cek_data_antrian_n = $cek_noantrian->row();

			$norm = $cek_data_antrian_n->norm;

			$get_pasien_blacklist = $three_db->query("SELECT blacklist FROM Pasien WHERE norm = '$norm'")->row();
			if ($get_pasien_blacklist->blacklist == '1') {
				$data['blacklist'] = 'ya';
			} else {
				$data['blacklist'] = 'tidak';
			}

			echo json_encode($data);

		} 
	}

	public function cek_data_booking(){

		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$cek_data_booking_n =  $three_db->query("SELECT baa.nokartu, LTRIM(RTRIM(baa.noapp)) AS noapp, raa.updater, baa.tgl, baa.nmpoli, baa.nmdokter, raa.nama, baa.noantrian,
			CAST(RIGHT(baa.noantrian, 4) AS int) AS no_antrian_int FROM BPJSAntrean baa 
			INNER JOIN rj_appointment raa ON LTRIM(RTRIM(raa.noapp)) = LTRIM(RTRIM(baa.noapp))
			WHERE
			1 = 1
			AND raa.noapp = '$no_mjkn'
			AND raa.batal = '0'
			AND baa.tgl = '$tgl_hari_ini'");
		if($cek_data_booking_n->num_rows() > '0' ){
			$cek_data_booking = $cek_data_booking_n->row();
			$data['nokartu'] = $cek_data_booking->nokartu;
			$data['noapp'] = $cek_data_booking->noapp;
			$data['updater'] = $cek_data_booking->updater;
			$data['tgl'] = $cek_data_booking->tgl;
			$data['nmpoli'] = $cek_data_booking->nmpoli;
			$data['nmdokter'] = $cek_data_booking->nmdokter;
			$data['nama'] = $cek_data_booking->nama;
			$data['noantrian'] = $cek_data_booking->noantrian;
			$data['data_book'] = '1';
		}else{
			$data['data_book'] = '0';
		}
		echo json_encode($data);

	}

	public function susah_finger(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$cek_data_booking_n =  $three_db->query("SELECT LTRIM(RTRIM(baa.nokartu)) AS nokartu, LTRIM(RTRIM(baa.noapp)) AS noapp, raa.updater, baa.tgl, baa.nmpoli, baa.nmdokter, raa.nama, baa.noantrian,
			CAST(RIGHT(baa.noantrian, 4) AS int) AS no_antrian_int FROM BPJSAntrean baa 
			INNER JOIN rj_appointment raa ON LTRIM(RTRIM(raa.noapp)) = LTRIM(RTRIM(baa.noapp))
			WHERE
			1 = 1
			AND raa.noapp = '$no_mjkn'
			AND raa.batal = '0'
			AND baa.tgl = '$tgl_hari_ini'");

		if($cek_data_booking_n->num_rows() > '0'){
			$cek_data_booking = $cek_data_booking_n->row();
			$data['nokartu'] = $cek_data_booking->nokartu;
			date_default_timezone_set("UTC");
			$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

			$cek_consid_v = $five_db->query("SELECT
				dbs.consid,
				dbs.SecretKey,
				dbs.userKey,
				dbs.kodePPK,
				dbs.url,
				dbs.keterangan,
				dbs.status
				FROM decrypt_bpjs dbs
				WHERE 
				1 = 1
				AND keterangan = 'vclaim'")->row();
			$consid_v = $cek_consid_v->consid;
			$SecretKey_v = $cek_consid_v->SecretKey;
			$userKey_v = $cek_consid_v->userKey;
			$kodePPK = $cek_consid_v->kodePPK;

			$key= $consid_v.$SecretKey_v.$tStamp;

			$signature = hash_hmac('sha256', $consid_v."&".$tStamp, $SecretKey_v, true);

			//base64 encode
			$encodedSignature = base64_encode($signature);
			$headers = array('X-cons-id: '. $consid_v,
				'X-timestamp: '.$tStamp,
				'X-signature: '.$encodedSignature,
				'user_key: '.$userKey_v,
				'Content-Type: Application/x-www-form-urlencoded'
			);
			$arr = array("request" => 
				["t_sep" =>
				[
					"noKartu" => "$cek_data_booking->nokartu",
					"tglSep" => "$tgl_hari_ini",
					"jnsPelayanan" => "2",
					"jnsPengajuan" => "2",
					"keterangan" => "Pasien susah melakukan sidik jari, 3x",
					"user" => "Mobile JKN Pro",
				]
			]);
			$json = json_encode($arr);
			// echo $json;
			$url_v = $cek_consid_v->url;
			$url = $url_v."Sep/pengajuanSEP";


			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

			$string_1 = curl_exec($ch);
			$err_1 = curl_error($ch);
			curl_close($ch);
			// echo $string_1;

			$arr_1 = json_decode($string_1, true);
			$string_1 = $arr_1['response'];
			$string_mess = $arr_1['metaData']['message'];
			$string_code = $arr_1['metaData']['code'];
			// echo $string_response;
			$string_dec_sep = $this->stringDecrypt($key, $string_1);

			if($string_code == '200'){
				$data['data_finger'] = '1';
				$data['string_code'] = $string_code;
				$data['string_mess'] = $string_mess;
				$IsSuccess = '1';
				$three_db->query("INSERT INTO bpjs_pengajuan ( NoKartu, TglSep, Nama, kdjenispelayanan, nmjenispelayanan, kdjenispengajuan, nmjenispengajuan, Keterangan, isApprove, Petugas )
					VALUES
					( '$cek_data_booking->nokartu', '$tgl_hari_ini', '$cek_data_booking->nama', '2', 'RJ', '2', 'Pengajuan Finger Print', 'Pasien susah melakukan sidik jari, 3x', '0', 'Mobile JKN Pro')");

				$seven_db->query("INSERT INTO APIMessageLog ( 
					MessageDateTime, 
					Sender, 
					Recipient, 
					ReferenceNo, 
					MessageText, 
					Response, 
					IsSuccess, 
					ErrorMessage)
					VALUES
					( 
					'$tglinsert', 
					'APM_JKN', 
					'BPJS_VCLAIM', 
					'$url', 
					'$json', 
					'$string_dec_sep', 
					'$IsSuccess', 
					'$string_mess')");

				// Approve
				$key= $consid_v.$SecretKey_v.$tStamp;

				$signature = hash_hmac('sha256', $consid_v."&".$tStamp, $SecretKey_v, true);

			//base64 encode
				$encodedSignature = base64_encode($signature);
				$headers = array('X-cons-id: '. $consid_v,
					'X-timestamp: '.$tStamp,
					'X-signature: '.$encodedSignature,
					'user_key: '.$userKey_v,
					'Content-Type: Application/x-www-form-urlencoded'
				);
				$arr = array("request" => 
					["t_sep" =>
					[
						"noKartu" => "$cek_data_booking->nokartu",
						"tglSep" => "$tgl_hari_ini",
						"jnsPelayanan" => "2",
						"jnsPengajuan" => "2",
						"keterangan" => "Pasien susah melakukan sidik jari, 3x",
						"user" => "Mobile JKN Pro",
					]
				]);

				$json = json_encode($arr);
			// echo $json;
				$url_v = $cek_consid_v->url;
				$url = $url_v."Sep/aprovalSEP";


				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 3);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

				$string_1_app = curl_exec($ch);
				$err_1 = curl_error($ch);
				curl_close($ch);
			// echo $string_1;

				$arr_1_app = json_decode($string_1_app, true);
				$string_1_app = $arr_1_app['response'];
				$string_mess_app = $arr_1_app['metaData']['message'];
				$string_code_app = $arr_1_app['metaData']['code'];
			// echo $string_response;
				$string_dec_sep = $this->stringDecrypt($key, $string_1);

			// End Approve

				if($string_code_app == '200'){
					$data['data_finger'] = '1';
					$data['string_code'] = $string_code_app;
					$data['string_mess'] = $string_mess_app;
					$IsSuccess = '1';
					$three_db->query("UPDATE bpjs_pengajuan SET isApprove = '1' WHERE NoKartu = '$cek_data_booking->nokartu'
						AND CAST(TglSep AS DATE) = '$tgl_hari_ini'");
				}else{
					$data['data_finger'] = '0';
					$data['string_code'] = $string_code_app;
					$data['string_mess'] = $string_mess_app;
					$IsSuccess = '0';
				}

				$seven_db->query("INSERT INTO APIMessageLog ( 
					MessageDateTime, 
					Sender, 
					Recipient, 
					ReferenceNo, 
					MessageText, 
					Response, 
					IsSuccess, 
					ErrorMessage)
					VALUES
					( 
					'$tglinsert', 
					'APM_JKN', 
					'BPJS_VCLAIM', 
					'$url', 
					'$json', 
					'$string_dec_sep', 
					'$IsSuccess', 
					'$string_mess')");


			}else{
				if(strpos($string_mess, 'Peserta Dalam Proses Pengajuan Aproval Penjaminan') !== false){
					$data['data_finger'] = '1';
					$data['string_code'] = $string_code;
					$data['string_mess'] = $string_mess;
					$IsSuccess = '1';

					// Approve
					$key= $consid_v.$SecretKey_v.$tStamp;

					$signature = hash_hmac('sha256', $consid_v."&".$tStamp, $SecretKey_v, true);

			//base64 encode
					$encodedSignature = base64_encode($signature);
					$headers = array('X-cons-id: '. $consid_v,
						'X-timestamp: '.$tStamp,
						'X-signature: '.$encodedSignature,
						'user_key: '.$userKey_v,
						'Content-Type: Application/x-www-form-urlencoded'
					);
					$arr = array("request" => 
						["t_sep" =>
						[
							"noKartu" => "$cek_data_booking->nokartu",
							"tglSep" => "$tgl_hari_ini",
							"jnsPelayanan" => "2",
							"jnsPengajuan" => "2",
							"keterangan" => "Pasien susah melakukan sidik jari, 3x",
							"user" => "Mobile JKN Pro",
						]
					]);

					$json = json_encode($arr);
			// echo $json;
					$url_v = $cek_consid_v->url;
					$url = $url_v."Sep/aprovalSEP";


					$ch = curl_init();

					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_TIMEOUT, 3);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

					$string_1_app = curl_exec($ch);
					$err_1 = curl_error($ch);
					curl_close($ch);
			// echo $string_1;

					$arr_1_app = json_decode($string_1_app, true);
					$string_1_app = $arr_1_app['response'];
					$string_mess_app = $arr_1_app['metaData']['message'];
					$string_code_app = $arr_1_app['metaData']['code'];
			// echo $string_response;
					$string_dec_sep = $this->stringDecrypt($key, $string_1);

			// End Approve

					if($string_code_app == '200'){
						$data['data_finger'] = '1';
						$data['string_code'] = $string_code_app;
						$data['string_mess'] = $string_mess_app;
						$IsSuccess = '1';
						$three_db->query("UPDATE bpjs_pengajuan SET isApprove = '1' WHERE NoKartu = '$cek_data_booking->nokartu'
							AND CAST(TglSep AS DATE) = '$tgl_hari_ini'");
					}else{
						if(strpos($string_mess_app, 'Peserta Sudah Aproval Penjaminan') !== false){
							$data['data_finger'] = '2';
							$data['string_code'] = $string_code_app;
							$data['string_mess'] = $string_mess_app;
							$IsSuccess = '1';
						}else{
							$data['data_finger'] = '0';
							$data['string_code'] = $string_code_app;
							$data['string_mess'] = $string_mess_app;
							$IsSuccess = '0';
						}
						
					}

					$seven_db->query("INSERT INTO APIMessageLog ( 
						MessageDateTime, 
						Sender, 
						Recipient, 
						ReferenceNo, 
						MessageText, 
						Response, 
						IsSuccess, 
						ErrorMessage)
						VALUES
						( 
						'$tglinsert', 
						'APM_JKN', 
						'BPJS_VCLAIM', 
						'$url', 
						'$json', 
						'$string_dec_sep', 
						'$IsSuccess', 
						'$string_mess_app')");

				}
				else if(strpos($string_mess, 'Peserta Sudah Aproval Penjaminan') !== false){
					$data['data_finger'] = '2';
					$data['string_code'] = $string_code;
					$data['string_mess'] = $string_mess;
					$IsSuccess = '1';
				}
				else{
					$data['data_finger'] = '0';
					$data['string_code'] = $string_code;
					$data['string_mess'] = $string_mess;
					$IsSuccess = '0';
				}

				$seven_db->query("INSERT INTO APIMessageLog ( 
					MessageDateTime, 
					Sender, 
					Recipient, 
					ReferenceNo, 
					MessageText, 
					Response, 
					IsSuccess, 
					ErrorMessage)
					VALUES
					( 
					'$tglinsert', 
					'APM_JKN', 
					'BPJS_VCLAIM', 
					'$url', 
					'$json', 
					'$string_dec_sep', 
					'$IsSuccess', 
					'$string_mess')");
				
			}
			


			// $data['data_finger'] = '1';
		}else{
			$data['data_finger'] = '0';
		}
		echo json_encode($data);


	}

	public function insert_noapp_rj(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$cek_noantrian = $three_db->query("SELECT
			rja.noapp,
			rja.tglapp,
			rja.jamapp,
			rja.shift,
			rja.kdtipevisit,
			LTRIM(RTRIM(rja.norm)) AS norm,
			rja.nama,
			rja.marga,
			rja.kdseks,
			rja.tgllahir,
			rja.umurtahun,
			rja.umurbulan,
			rja.umurhari,
			rja.notelepon,
			rja.nohp,
			rja.kddokter,
			rja.kdpoli,
			rja.batal,
			rja.kdjmbayar,
			rja.kdinstansi,
			i.nminstansi,
			rja.noref,
			rja.updater
			FROM
			rj_appointment rja
			LEFT JOIN instansi i ON i.kdinstansi = rja.kdinstansi
			WHERE 1 = 1
			AND rja.noapp = '$no_mjkn'
			AND rja.updater = 'MobileJKN'");

		if($cek_noantrian->num_rows() > '0'){

			$cek_data_antrian_n = $cek_noantrian->row();

			$norm = $cek_data_antrian_n->norm;
			$tglapp = $cek_data_antrian_n->tglapp;
			$jamapp = $cek_data_antrian_n->jamapp;
			$shift = $cek_data_antrian_n->shift;
			$kdtipevisit = $cek_data_antrian_n->kdtipevisit;
			$notelepon = $cek_data_antrian_n->notelepon;
			$nohp = $cek_data_antrian_n->nohp;
			$batal = $cek_data_antrian_n->batal;
			$kdjmbayar = $cek_data_antrian_n->kdjmbayar;
			$kdinstansi = $cek_data_antrian_n->kdinstansi;
			$nminstansi = $cek_data_antrian_n->nminstansi;
			$umurtahun = $cek_data_antrian_n->umurtahun;
			$umurbulan = $cek_data_antrian_n->umurbulan;
			$umurhari = $cek_data_antrian_n->umurhari;
			$noref = $cek_data_antrian_n->noref;
			$updater = $cek_data_antrian_n->updater;

			$noreg = 'OP';
			$date = str_replace("-", "", date('Y-m-d'));
			$noreg .= $date;
			$noreg .= '-';

			$get_norm = $three_db->query("SELECT MAX(right(noreg,4)) as maxid FROM rj_reg WHERE noreg LIKE '%$date%'")->row();
			if ($get_norm) {
				$maxid = $get_norm->maxid;
				$maxid = $maxid + 1;
			} else {
				$maxid = '0001';
			}

			$noreg .= sprintf("%04s", $maxid);

			$cek_antrian = $three_db->query("SELECT
				bja.nokartu,
				bja.nik,
				bja.tgl,
				rjp.kdpoli,
				rjp.nmpoli,
				bja.noantrian,
				m.kode,
				m.nama
				FROM
				BPJSAntrean bja
				LEFT JOIN rj_poliklinik rjp ON rjp.kdsubpolibpjs = bja.kdpoli 
				LEFT JOIN medis m ON m.kdDPJP = bja.kddokter
				WHERE 
				noapp = '$no_mjkn'")->row();

			$nokartu = $cek_antrian->nokartu;
			$nik = $cek_antrian->nik;
			$tgl = $cek_antrian->tgl;
			$kdpoli = $cek_antrian->kdpoli;
			$nmpoli = $cek_antrian->nmpoli;
			$noantrian = $cek_antrian->noantrian;
			$kode_dok = $cek_antrian->kode;
			$nama_dok = $cek_antrian->nama;

			$get_kunjungan_pasien = $three_db->query("SELECT noreg FROM rj_reg WHERE norm = '$norm'");
			if ($get_kunjungan_pasien->num_rows() > 1) {
				$pasienbaru = '0';
			} else {
				$pasienbaru = '1';
			}

			// $three_db->query("INSERT INTO rj_reg (
			// 	noreg,
			// 	norm,
			// 	noapp,
			// 	nomember,
			// 	tglregistrasi,
			// 	kdtipevisit,
			// 	kdpoli,
			// 	kddokter,
			// 	kdtipecharge,
			// 	kdjmbayar,
			// 	tutup,
			// 	tglupdate,
			// 	usrupdate,
			// 	kdinstansi,
			// 	ketinstansi,
			// 	jamreg,
			// 	jamapp,
			// 	kdtipetinggal,
			// 	kdpaketmember,
			// 	piutang,
			// 	kdtarifkonsul,
			// 	kdtipepasien,
			// 	kdplafon,
			// 	plafon,
			// 	sisaplafon,
			// 	fplafonperhari,
			// 	kontrakmcu,
			// 	batal,
			// 	kdpengirim,
			// 	nopengirim,
			// 	keterangan,
			// 	tglkeluar,
			// 	jamkeluar,
			// 	kdkdnkeluar,
			// 	kdcarakeluar,
			// 	kdtdklanjut,
			// 	umurtahun,
			// 	umurbulan,
			// 	umurhari,
			// 	pasienbaru,
			// 	usrinsert,
			// 	tglinsert,
			// 	shift,
			// 	kartu,
			// 	rujukan,
			// 	noSEPBPJS,
			// 	noreg_eksternal
			// 	)
			// 	VALUES
			// 	(
			// 	'$noreg',
			// 	'$norm',
			// 	'$no_mjkn',
			// 	'',
			// 	'$tgl_hari_ini',
			// 	'$kdtipevisit',
			// 	'$kdpoli',
			// 	'$kode_dok',
			// 	'01',
			// 	'$kdjmbayar',
			// 	'0',
			// 	'$tglinsert',
			// 	'$updater',
			// 	'$kdinstansi',
			// 	'$nminstansi',
			// 	'$jam_hari_ini',
			// 	'$jamapp',
			// 	'',
			// 	'',
			// 	'0',
			// 	'',
			// 	'08',
			// 	'',
			// 	'',
			// 	'',
			// 	'',
			// 	'',
			// 	'$batal',
			// 	'00',
			// 	'',
			// 	'',
			// 	'$tgl_hari_ini',
			// 	'',
			// 	'',
			// 	'',
			// 	'',
			// 	'$umurtahun',
			// 	'$umurbulan',
			// 	'$umurhari',
			// 	'$pasienbaru',
			// 	'$updater',
			// 	'$tglinsert',
			// 	'$shift',
			// 	'0',
			// 	'0',
			// 	'',
			// 	''
			// 	);");

			// $date = str_replace("-", "", date('Y-m-d'));
			// $nobukti = $date . '0';

			// $get_norm = $three_db->query("SELECT MAX(right(noreg,4)) as maxid FROM rj_reg WHERE noreg LIKE '%$date%'")->row();
			// if ($get_norm) {
			// 	$maxid = $get_norm->maxid;
			// } else {
			// 	$maxid = '0001';
			// }

			// $nobukti .= sprintf("%04s", $maxid);

			// $get_kunjungan = $three_db->query("SELECT noreg FROM rj_reg WHERE norm = '$norm'");
			// if ($get_kunjungan->num_rows() > 1) {
			// 	$kunjungan = '0';
			// } else {
			// 	$kunjungan = '1';
			// }

			// $three_db->query("INSERT INTO rj_transhd (
			// 	nobukti,
			// 	noreg,
			// 	noKunjungan,
			// 	tgltrans,
			// 	kdpoli,
			// 	posting,
			// 	nobill,
			// 	fReferal,
			// 	noresep,
			// 	kddokter,
			// 	nopaket,
			// 	otomatis,
			// 	kunjunganbaru,
			// 	kdcabang,
			// 	shift,
			// 	jamtrans,
			// 	jnsKasus,
			// 	Diagnosa,
			// 	kdterminal
			// 	)
			// 	VALUES
			// 	(
			// 	'$nobukti',
			// 	'$noreg',
			// 	'',
			// 	'$tgl_hari_ini',
			// 	'$kdpoli',
			// 	'',
			// 	'',
			// 	'',
			// 	'',
			// 	'$kode_dok',
			// 	'',
			// 	'1',
			// 	'$kunjungan',
			// 	'',
			// 	'$shift',
			// 	'$jam_hari_ini',
			// 	'',
			// 	'',
			// 	''
			// 	);");

			// $three_db->query("UPDATE rj_appointment 
			// 	SET registrasi = '1'
			// 	WHERE noapp = '$no_mjkn'");

			// echo "Sukses insert data untuk " . $no_mjkn;

			$noapp = $cek_data_antrian_n->noapp;

			$cek_antrian = $three_db->query("SELECT
				bja.nokartu,
				bja.kdpoli,
				bja.noantrian,
				bja.jnsref,
				bja.kddokter,
				bja.nmdokter,
				LTRIM(RTRIM(m.kode)) AS kode
				FROM
				BPJSAntrean bja
				LEFT JOIN rj_poliklinik rjp ON rjp.kdsubpolibpjs = bja.kdpoli 
				LEFT JOIN medis m ON m.kdDPJP = bja.kddokter
				WHERE 
				noapp = '$noapp'");

			$cek_antrian = $cek_antrian->row();
			$nokartu = $cek_antrian->nokartu;
			$noantrian = $cek_antrian->noantrian;
			$jnsref = $cek_antrian->jnsref;
			$kddokter = $cek_antrian->kddokter;
			$nmdokter = $cek_antrian->nmdokter;

			date_default_timezone_set("UTC");
			$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

			$cek_consid_v = $five_db->query("SELECT
				dbs.consid,
				dbs.SecretKey,
				dbs.userKey,
				dbs.kodePPK,
				dbs.url,
				dbs.keterangan,
				dbs.status
				FROM decrypt_bpjs dbs
				WHERE 
				1 = 1
				AND keterangan = 'vclaim'")->row();
			$consid_v = $cek_consid_v->consid;
			$SecretKey_v = $cek_consid_v->SecretKey;
			$userKey_v = $cek_consid_v->userKey;
			$kodePPK = $cek_consid_v->kodePPK;

			$key= $consid_v.$SecretKey_v.$tStamp;

			$signature = hash_hmac('sha256', $consid_v."&".$tStamp, $SecretKey_v, true);

			//base64 encode
			$encodedSignature = base64_encode($signature);

			$headers = array('X-cons-id: '. $consid_v,
				'X-timestamp: '.$tStamp,
				'X-signature: '.$encodedSignature,
				'user_key: '.$userKey_v,
				'Content-Type: application/json'
			);

			$url_v = $cek_consid_v->url;
			$url = $url_v . "Rujukan/Peserta/" . $nokartu;
			$ch = curl_init();

				// echo $url . "\n";

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$string = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);
				// echo $string . "\n";

			$arr = json_decode($string, true);

			$string = $arr['response'];
			// echo $string;
			$string_dec = $this->stringDecrypt($key, $string);
			// echo $string_dec . "\n";
			$arr_dec = json_decode($string_dec, true);

			// echo var_dump($arr_dec);

			$asalFaskes = $arr_dec['asalFaskes'];
			$tglRujukan = $arr_dec['rujukan']['tglKunjungan'];
			$provPerujukKode_n = $arr_dec['rujukan']['provPerujuk']['kode'];
			$namaDiagnosa = $arr_dec['rujukan']['diagnosa']['nama'];

			$cek_sep = $five_db->query("SELECT TOP(1)
				bgk.tglSep,
				bgk.noSep,
				bgk.diagnosa
				FROM
				bpjs_get_kunjungan_sep bgk
				WHERE 
				noKartu = '$nokartu' AND poli = '$cek_antrian->kdpoli'
				ORDER BY tglSep DESC");

			if($cek_sep->num_rows() > 0){
				$cek_sep = $cek_sep->row();
				$kodeDiagnosa = $cek_sep->diagnosa;
				$noSep = $cek_sep->noSep;
				$tglSep = $cek_sep->tglSep;
			} else {
				$kodeDiagnosa = $arr_dec['rujukan']['diagnosa']['kode'];
				$noSep = "";
				$tglSep = "";
			}

			$cek_rujukan = $five_db->query("SELECT TOP(1)
				brk.hakkelas_kode,
				brk.nama,
				brk.nik,
				brk.sex,
				brk.tgl_lahir,
				brk.hasil_json
				FROM
				bpjs_reg_kartu brk
				WHERE 
				no_kartu = '$nokartu'
				ORDER BY brk.tgl_insert DESC")->row();

			// $hakKelasKode = $cek_rujukan->hakkelas_kode;
			// $nama = $cek_rujukan->nama;
			// $nik = $cek_rujukan->nik;
			// $sex = $cek_rujukan->sex;
			// $tgl_lahir = $cek_rujukan->tgl_lahir;

			$hasil_json = json_decode($cek_rujukan->hasil_json, true);
			$nik = $hasil_json['peserta']['nik'];
			$nama_p = $hasil_json['peserta']['nama'];
			$nama = str_replace("'","`",$nama_p);
			$pisa = $hasil_json['peserta']['pisa'];
			$sex = $hasil_json['peserta']['sex'];
			// $norm = $hasil_json['peserta']['mr']['noMR'];
			$noTelepon = $hasil_json['peserta']['mr']['noTelepon'];
			$tgl_lahir = $hasil_json['peserta']['tglLahir'];
			$tglCetakKartu = $hasil_json['peserta']['tglCetakKartu'];
			$tglTAT = $hasil_json['peserta']['tglTAT'];
			$tglTMT = $hasil_json['peserta']['tglTMT'];
			$statusPesertakode = $hasil_json['peserta']['statusPeserta']['kode'];
			$statusPesertaketerangan = $hasil_json['peserta']['statusPeserta']['keterangan'];
			$kdProvider = $hasil_json['peserta']['provUmum']['kdProvider'];
			$nmProvider = $hasil_json['peserta']['provUmum']['nmProvider'];
			$jenisPesertakode = $hasil_json['peserta']['jenisPeserta']['kode'];
			$jenisPesertaketerangan = $hasil_json['peserta']['jenisPeserta']['keterangan'];
			$hakKelasKode = $hasil_json['peserta']['hakKelas']['kode'];
			$hakKelasKeterangan = $hasil_json['peserta']['hakKelas']['keterangan'];
			$umurSekarang = $hasil_json['peserta']['umur']['umurSekarang'];
			$umurSaatPelayanan = $hasil_json['peserta']['umur']['umurSaatPelayanan'];
			$dinsos = $hasil_json['peserta']['informasi']['dinsos'];
			$prolanisPRB = $hasil_json['peserta']['informasi']['prolanisPRB'];
			$noSKTM = $hasil_json['peserta']['informasi']['noSKTM'];
			$eSEP = $hasil_json['peserta']['informasi']['eSEP'];
			$noAsuransi = $hasil_json['peserta']['cob']['noAsuransi'];
			$nmAsuransi = $hasil_json['peserta']['cob']['nmAsuransi'];

			if($provPerujukKode_n != ''){
				$PPKTK1 = $provPerujukKode_n;
			} else {
				$PPKTK1 = $kdProvider;
			}

			if($jnsref == '1'){
				$tujuanKunj = "0";
				$flagProcedure = "";
				$kdPenunjang = "";
				$assestmenPel = "";
				$noSurat = "";
				$kdpoli = $cek_antrian->kdpoli;
				$kode_dok = $kddokter;
				$noRujukan = $noref;

			} else if($jnsref == '3'){
				$tujuanKunj = "2";
				$flagProcedure = "";
				$kdPenunjang = "";
				$assestmenPel = "5";
				$noRujukan_n = $arr_dec['rujukan']['noKunjungan'];

				if($noRujukan_n == ''){
					$noRujukan = "";
				} else {
					$noRujukan = $noRujukan_n;
				}

				$cek_dpjp = $three_db->query("SELECT
					bk.noKontrol,
					bk.KdPoli,
					bk.DPJP
					FROM
					BPJS_Kontrol bk
					WHERE 
					noKontrol = '$noref'");

				if($cek_dpjp->num_rows() > 0){
					$cek_dpjp = $cek_dpjp->row();
					$noSurat = $cek_dpjp->noKontrol;
					$kdpoli = $cek_dpjp->KdPoli;
					$kode_dok = $cek_dpjp->DPJP;
				} else {
					$noSurat = $noref;
					$kdpoli = $cek_antrian->kdpoli;
					$kode_dok = $kddokter;
				}

			}


			$headers = array('X-cons-id: '. $consid_v,
				'X-timestamp: '.$tStamp,
				'X-signature: '.$encodedSignature,
				'user_key: '.$userKey_v,
				'Content-Type: Application/x-www-form-urlencoded'
			);

			$arr = array("request" => 
				["t_sep" =>
				[
					"noKartu" => "$nokartu",
					"tglSep" => "$tgl_hari_ini",
					"ppkPelayanan" => "$kodePPK",
					"jnsPelayanan" => "2",
					"klsRawat" => [
						"klsRawatHak" => "$hakKelasKode",
						"klsRawatNaik" => "",
						"pembiayaan" => "",
						"penanggungJawab" => ""
					],
					"noMR" => "$norm",
					"rujukan" => [
						"asalRujukan" => "$asalFaskes",
						"tglRujukan" => "$tglRujukan",
						"noRujukan" => "$noRujukan",
						"ppkRujukan" => "$provPerujukKode_n"
					],
					"catatan" => "",
					"diagAwal" => "$kodeDiagnosa",
					"poli" => [
						"tujuan" => "$kdpoli",
						"eksekutif" => "0"
					],
					"cob" => [
						"cob" => "0"
					],
					"katarak" => [
						"katarak" => "0"
					],
					"jaminan" => [
						"lakaLantas" => "0",
						"noLP" => "",
						"penjamin" => [
							"tglKejadian" => "$tgl_hari_ini",
							"keterangan" => "",
							"suplesi" => [
								"suplesi" => "0",
								"noSepSuplesi" => "",
								"lokasiLaka" => [
									"kdPropinsi" => "",
									"kdKabupaten" => "",
									"kdKecamatan" => ""
								],
							],
						],
					],
					"tujuanKunj" => "$tujuanKunj",
					"flagProcedure" => "$flagProcedure",
					"kdPenunjang" => "$kdPenunjang",
					"assesmentPel" => "$assestmenPel",
					"skdp" => [
						"noSurat" => "$noSurat",
						"kodeDPJP" => "$kode_dok"
					],
					"dpjpLayan" => "$kddokter",
					"noTelp" => "$noTelepon",
					"user" => "MobileJKN",
				]
			]);

			$json = json_encode($arr);

			// echo $json;

			$url_v = $cek_consid_v->url;
			$url = $url_v."SEP/2.0/insert";

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

			$string_1 = curl_exec($ch);
			$err_1 = curl_error($ch);
			curl_close($ch);
			// echo $string_1;

			$arr_1 = json_decode($string_1, true);
			$string_1 = $arr_1['response'];
			$string_mess = $arr_1['metaData']['message'];
			$string_code = $arr_1['metaData']['code'];
			// echo $string_response;
			$string_dec_sep = $this->stringDecrypt($key, $string_1);
			// echo "11";
			// echo $string_response;

			if($string_code == '200'){
				$IsSuccess = '1';
				$data['string_code'] = "200";
				$data['string_mess'] = "Success";
			} else {
				$IsSuccess = '0';
				$data['string_code'] = $string_code;
				$data['string_mess'] = $string_mess;
			}

			$seven_db->query("INSERT INTO APIMessageLog ( 
				MessageDateTime, 
				Sender, 
				Recipient, 
				ReferenceNo, 
				MessageText, 
				Response, 
				IsSuccess, 
				ErrorMessage)
				VALUES
				( 
				'$tglinsert', 
				'APM_JKN', 
				'BPJS_VCLAIM', 
				'$url', 
				'$json', 
				'$string_dec_sep', 
				'$IsSuccess', 
				'$string_mess')");

			$arr_dec_sep = json_decode($string_dec_sep, true);

			$jnsPelayanan = $arr_dec_sep['sep']['jnsPelayanan'];
			$poliEksekutif = $arr_dec_sep['sep']['poliEksekutif'];
			$catatan = $arr_dec_sep['sep']['catatan'];
			$penjamin = $arr_dec_sep['sep']['penjamin'];
			$tujuanKunj = $arr_dec_sep['sep']['tujuanKunj'];
			$assestmenPel = $arr_dec_sep['sep']['assestmenPel'];
			$kdPenunjang = $arr_dec_sep['sep']['kdPenunjang'];
			$flagProcedure = $arr_dec_sep['sep']['flagProcedure'];

			// echo $arr_dec_sep;

			$SKDP = date("ym"."01");

			// echo $SKDP;

			if($string_code == '200'){
				$three_db->query("INSERT INTO BPJS_reg (
					Noreg,
					AsalPasien,
					norm,
					NoKartu,
					NIK,
					Nama,
					Sex,
					TglLahir,
					pisat,
					NoSEP,
					TglSEP,
					NoRujukan,
					TglRujukan,
					KdRujukan,
					NmRujukan,
					JnsPelayanan,
					JnsPeserta,
					KdKlsTanggungan,
					NmKlsTanggungan,
					KdPoliklinik,
					NmPoliklinik,
					KdDiagnosa,
					NmDiagnosa,
					Keluhan,
					Catatan,
					usrInsert,
					tglInsert,
					usrUpdate,
					tglUpdate,
					isDeleted,
					KlsBPJS,
					NmKlsBPJS,
					tglPulang,
					SKTM,
					KdInstansi,
					isCOB,
					isKasus,
					PPKTK1,
					tglCetakKartu,
					TMT,
					TAT,
					LokasiKasus,
					usrPrint,
					tglLastPrint,
					printcount,
					asalrujukan,
					penjamin,
					notlp,
					isEksekutif,
					tglKLL,
					KetKLL,
					Propinsi,
					Kabupaten,
					Kecamatan,
					SKDP,
					isSuplesi,
					NoSuplesi,
					isPRB,
					kdDPJP,
					nmDPJP,
					noKunjungan,
					isKatarak,
					dinsos,
					TujKunjungan,
					fProsedur,
					kdPenunjang,
					assesmentPel,
					carakeluar,
					noKematian,
					tglKematian,
					noLPManual,
					noSurat
					)
					VALUES
					(
					'$noreg',
					'RJ',
					'$norm',
					'$nokartu',
					'$nik',
					'$nama',
					'$sex',
					'$tgl_lahir',
					'$pisa',
					'$noSurat',
					'$tgl_hari_ini',
					'$noRujukan',
					'$tglRujukan',
					'$provPerujukKode',
					'$nmProvider',
					'$jnsPelayanan',
					'$jenisPesertaketerangan',
					'$hakKelasKode',
					'$hakKelasKeterangan',
					'$kdpoli',
					'$poli',
					'$kodeDiagnosa',
					'$namaDiagnosa',
					'',
					'',
					'MobileJKN,
					'$tglinsert',
					'MobileJKN',
					'$tglinsert',
					'0',
					'$hakKelasKode',
					'$hakKelasKeterangan',
					'$tglinsert',
					'',
					'',
					'0',
					'0',
					'$PPKTK1',
					'$tglCetakKartu',
					'$tglTMT',
					'$tglTAT',
					'',
					'MobileJKN',
					'$tgl_hari_ini',
					'1',
					'',
					'0',
					'$noTelepon',
					'$poliEksekutif',
					'$tgl_hari_ini',
					'',
					'',
					'',
					'',
					'$SKDP',
					'0',
					'',
					'0',
					'$kode_dok',
					'$nmdokter',
					'$noRujukan',
					'0',
					'',
					'$tujuanKunj',
					'$flagProcedure',
					'$kdPenunjang',
					'$assestmenPel',
					'1',
					'',
					'',
					'',
					'$noSurat' 
				)");
			}

			echo json_encode($data);

		} 
	}

	public function stringDecrypt($key, $string)
	{
		require_once 'vendor/autoload.php';
		// echo "2";

		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);

		date_default_timezone_set("UTC");
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

		$cek_consid_v = $five_db->query("SELECT
			dbs.consid,
			dbs.SecretKey,
			dbs.userKey,
			dbs.kodePPK,
			dbs.url,
			dbs.keterangan,
			dbs.status
			FROM decrypt_bpjs dbs
			WHERE 
			1 = 1
			AND keterangan = 'vclaim'")->row();
		$consid_v = $cek_consid_v->consid;
		$SecretKey_v = $cek_consid_v->SecretKey;

		// $key= $consid_v.$SecretKey_v.$tStamp;
		$encrypt_method = 'AES-256-CBC';
		// hash
		$key_hash = hex2bin(hash('sha256', $key));
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning        
		$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
		return \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
		// echo $output;
	}

	public function cetak_sep(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);

		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$cek_noantrian = $three_db->query("SELECT
			rja.noapp,
			rja.noref
			FROM
			rj_appointment rja
			LEFT JOIN instansi i ON i.kdinstansi = rja.kdinstansi
			WHERE 
			rja.noapp = '$no_mjkn'
			AND rja.updater = 'MobileJKN'");

		if($cek_noantrian->num_rows() > 0){

			// echo "1";

			$cek_data_antrian_n = $cek_noantrian->row();

			$noapp = $cek_data_antrian_n->noapp;
			$noref = $cek_data_antrian_n->noref;

			$cek_antrian = $three_db->query("SELECT
				bja.nokartu,
				bja.kdpoli,
				bja.noantrian,
				bja.jnsref,
				bja.kddokter,
				bja.nmdokter,
				LTRIM(RTRIM(m.kode)) AS kode
				FROM
				BPJSAntrean bja
				LEFT JOIN rj_poliklinik rjp ON rjp.kdsubpolibpjs = bja.kdpoli 
				LEFT JOIN medis m ON m.kdDPJP = bja.kddokter
				WHERE 
				noapp = '$noapp'");

			$cek_antrian = $cek_antrian->row();
			$nokartu = $cek_antrian->nokartu;
			$noantrian = $cek_antrian->noantrian;
			$jnsref = $cek_antrian->jnsref;
			$kddokter = $cek_antrian->kddokter;
			$nmdokter = $cek_antrian->nmdokter;

			date_default_timezone_set("UTC");
			$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

			$cek_consid_v = $five_db->query("SELECT
				dbs.consid,
				dbs.SecretKey,
				dbs.userKey,
				dbs.kodePPK,
				dbs.url,
				dbs.keterangan,
				dbs.status
				FROM decrypt_bpjs dbs
				WHERE 
				1 = 1
				AND keterangan = 'vclaim'")->row();
			$consid_v = $cek_consid_v->consid;
			$SecretKey_v = $cek_consid_v->SecretKey;
			$userKey_v = $cek_consid_v->userKey;
			$kodePPK = $cek_consid_v->kodePPK;

			$key= $consid_v.$SecretKey_v.$tStamp;

			$signature = hash_hmac('sha256', $consid_v."&".$tStamp, $SecretKey_v, true);

			//base64 encode
			$encodedSignature = base64_encode($signature);

			$headers = array('X-cons-id: '. $consid_v,
				'X-timestamp: '.$tStamp,
				'X-signature: '.$encodedSignature,
				'user_key: '.$userKey_v,
				'Content-Type: application/json'
			);

			$url_v = $cek_consid_v->url;
			$url = $url_v . "Rujukan/Peserta/" . $nokartu;
			$ch = curl_init();

				// echo $url . "\n";

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$string = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);
				// echo $string . "\n";

			$arr = json_decode($string, true);

			$string = $arr['response'];
			// echo $string;
			$string_dec = $this->stringDecrypt($key, $string);
			// echo $string_dec . "\n";
			$arr_dec = json_decode($string_dec, true);

			// echo var_dump($arr_dec);

			$asalFaskes = $arr_dec['asalFaskes'];
			$tglRujukan = $arr_dec['rujukan']['tglKunjungan'];
			$provPerujukKode_n = $arr_dec['rujukan']['provPerujuk']['kode'];
			$namaDiagnosa = $arr_dec['rujukan']['diagnosa']['nama'];

			$cek_sep = $five_db->query("SELECT TOP(1)
				bgk.tglSep,
				bgk.noSep,
				bgk.diagnosa
				FROM
				bpjs_get_kunjungan_sep bgk
				WHERE 
				noKartu = '$nokartu' AND poli = '$cek_antrian->kdpoli'
				ORDER BY tglSep DESC");

			if($cek_sep->num_rows() > 0){
				$cek_sep = $cek_sep->row();
				$kodeDiagnosa = $cek_sep->diagnosa;
				$noSep = $cek_sep->noSep;
				$tglSep = $cek_sep->tglSep;
			} else {
				$kodeDiagnosa = $arr_dec['rujukan']['diagnosa']['kode'];
				$noSep = "";
				$tglSep = "";
			}

			$cek_rujukan = $five_db->query("SELECT TOP(1)
				brk.hakkelas_kode,
				brk.nama,
				brk.nik,
				brk.sex,
				brk.tgl_lahir,
				brk.hasil_json
				FROM
				bpjs_reg_kartu brk
				WHERE 
				no_kartu = '$nokartu'
				ORDER BY brk.tgl_insert DESC")->row();

			// $hakKelasKode = $cek_rujukan->hakkelas_kode;
			// $nama = $cek_rujukan->nama;
			// $nik = $cek_rujukan->nik;
			// $sex = $cek_rujukan->sex;
			// $tgl_lahir = $cek_rujukan->tgl_lahir;

			$hasil_json = json_decode($cek_rujukan->hasil_json, true);
			$nik = $hasil_json['peserta']['nik'];
			$nama_p = $hasil_json['peserta']['nama'];
			$nama = str_replace("'","`",$nama_p);
			$pisa = $hasil_json['peserta']['pisa'];
			$sex = $hasil_json['peserta']['sex'];
			$norm = $hasil_json['peserta']['mr']['noMR'];
			$noTelepon = $hasil_json['peserta']['mr']['noTelepon'];
			$tgl_lahir = $hasil_json['peserta']['tglLahir'];
			$tglCetakKartu = $hasil_json['peserta']['tglCetakKartu'];
			$tglTAT = $hasil_json['peserta']['tglTAT'];
			$tglTMT = $hasil_json['peserta']['tglTMT'];
			$statusPesertakode = $hasil_json['peserta']['statusPeserta']['kode'];
			$statusPesertaketerangan = $hasil_json['peserta']['statusPeserta']['keterangan'];
			$kdProvider = $hasil_json['peserta']['provUmum']['kdProvider'];
			$nmProvider = $hasil_json['peserta']['provUmum']['nmProvider'];
			$jenisPesertakode = $hasil_json['peserta']['jenisPeserta']['kode'];
			$jenisPesertaketerangan = $hasil_json['peserta']['jenisPeserta']['keterangan'];
			$hakKelasKode = $hasil_json['peserta']['hakKelas']['kode'];
			$hakKelasKeterangan = $hasil_json['peserta']['hakKelas']['keterangan'];
			$umurSekarang = $hasil_json['peserta']['umur']['umurSekarang'];
			$umurSaatPelayanan = $hasil_json['peserta']['umur']['umurSaatPelayanan'];
			$dinsos = $hasil_json['peserta']['informasi']['dinsos'];
			$prolanisPRB = $hasil_json['peserta']['informasi']['prolanisPRB'];
			$noSKTM = $hasil_json['peserta']['informasi']['noSKTM'];
			$eSEP = $hasil_json['peserta']['informasi']['eSEP'];
			$noAsuransi = $hasil_json['peserta']['cob']['noAsuransi'];
			$nmAsuransi = $hasil_json['peserta']['cob']['nmAsuransi'];

			if($provPerujukKode_n != ''){
				$PPKTK1 = $provPerujukKode_n;
			} else {
				$PPKTK1 = $kdProvider;
			}

			if($jnsref == '1'){
				$tujuanKunj = "0";
				$flagProcedure = "";
				$kdPenunjang = "";
				$assestmenPel = "";
				$noSurat = "";
				$kdpoli = $cek_antrian->kdpoli;
				$kode_dok = $kddokter;
				$noRujukan = $noref;

			} else if($jnsref == '3'){
				$tujuanKunj = "2";
				$flagProcedure = "";
				$kdPenunjang = "";
				$assestmenPel = "5";
				$noRujukan_n = $arr_dec['rujukan']['noKunjungan'];

				if($noRujukan_n == ''){
					$noRujukan = "";
				} else {
					$noRujukan = $noRujukan_n;
				}

				$cek_dpjp = $three_db->query("SELECT
					bk.noKontrol,
					bk.KdPoli,
					bk.DPJP
					FROM
					BPJS_Kontrol bk
					WHERE 
					noKontrol = '$noref'");

				if($cek_dpjp->num_rows() > 0){
					$cek_dpjp = $cek_dpjp->row();
					$noSurat = $cek_dpjp->noKontrol;
					$kdpoli = $cek_dpjp->KdPoli;
					$kode_dok = $cek_dpjp->DPJP;
				} else {
					$noSurat = $noref;
					$kdpoli = $cek_antrian->kdpoli;
					$kode_dok = $kddokter;
				}
				
			}


			$headers = array('X-cons-id: '. $consid_v,
				'X-timestamp: '.$tStamp,
				'X-signature: '.$encodedSignature,
				'user_key: '.$userKey_v,
				'Content-Type: Application/x-www-form-urlencoded'
			);

			$arr = array("request" => 
				["t_sep" =>
				[
					"noKartu" => "$nokartu",
					"tglSep" => "$tgl_hari_ini",
					"ppkPelayanan" => "$kodePPK",
					"jnsPelayanan" => "2",
					"klsRawat" => [
						"klsRawatHak" => "$hakKelasKode",
						"klsRawatNaik" => "",
						"pembiayaan" => "",
						"penanggungJawab" => ""
					],
					"noMR" => "$norm",
					"rujukan" => [
						"asalRujukan" => "$asalFaskes",
						"tglRujukan" => "$tglRujukan",
						"noRujukan" => "$noRujukan",
						"ppkRujukan" => "$provPerujukKode_n"
					],
					"catatan" => "",
					"diagAwal" => "$kodeDiagnosa",
					"poli" => [
						"tujuan" => "$kdpoli",
						"eksekutif" => "0"
					],
					"cob" => [
						"cob" => "0"
					],
					"katarak" => [
						"katarak" => "0"
					],
					"jaminan" => [
						"lakaLantas" => "0",
						"noLP" => "",
						"penjamin" => [
							"tglKejadian" => "$tgl_hari_ini",
							"keterangan" => "",
							"suplesi" => [
								"suplesi" => "0",
								"noSepSuplesi" => "",
								"lokasiLaka" => [
									"kdPropinsi" => "",
									"kdKabupaten" => "",
									"kdKecamatan" => ""
								],
							],
						],
					],
					"tujuanKunj" => "$tujuanKunj",
					"flagProcedure" => "$flagProcedure",
					"kdPenunjang" => "$kdPenunjang",
					"assesmentPel" => "$assestmenPel",
					"skdp" => [
						"noSurat" => "$noSurat",
						"kodeDPJP" => "$kode_dok"
					],
					"dpjpLayan" => "$kddokter",
					"noTelp" => "$noTelepon",
					"user" => "MobileJKN",
				]
			]);

			$json = json_encode($arr);

			// echo $json;

			$url_v = $cek_consid_v->url;
			$url = $url_v."SEP/2.0/insert";

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

			$string_1 = curl_exec($ch);
			$err_1 = curl_error($ch);
			curl_close($ch);
			// echo $string_1;

			$arr_1 = json_decode($string_1, true);
			$string_1 = $arr_1['response'];
			$string_mess = $arr_1['metaData']['message'];
			$string_code = $arr_1['metaData']['code'];
			// echo $string_response;
			$string_dec_sep = $this->stringDecrypt($key, $string_1);
			// echo "11";
			// echo $string_response;

			if($string_code == '200'){
				$IsSuccess = '1';
				$data['string_code'] = "200";
				$data['string_mess'] = "Success";
			} else {
				$IsSuccess = '0';
				$data['string_code'] = $string_code;
				$data['string_mess'] = $string_mess;
			}

			$seven_db->query("INSERT INTO APIMessageLog ( 
				MessageDateTime, 
				Sender, 
				Recipient, 
				ReferenceNo, 
				MessageText, 
				Response, 
				IsSuccess, 
				ErrorMessage)
				VALUES
				( 
				'$tglinsert', 
				'APM_JKN', 
				'BPJS_VCLAIM', 
				'$url', 
				'$json', 
				'$string_dec_sep', 
				'$IsSuccess', 
				'$string_mess')");

			$arr_dec_sep = json_decode($string_dec_sep, true);

			$jnsPelayanan = $arr_dec_sep['sep']['jnsPelayanan'];
			$poliEksekutif = $arr_dec_sep['sep']['poliEksekutif'];
			$catatan = $arr_dec_sep['sep']['catatan'];
			$penjamin = $arr_dec_sep['sep']['penjamin'];
			$tujuanKunj = $arr_dec_sep['sep']['tujuanKunj'];
			$assestmenPel = $arr_dec_sep['sep']['assestmenPel'];
			$kdPenunjang = $arr_dec_sep['sep']['kdPenunjang'];
			$flagProcedure = $arr_dec_sep['sep']['flagProcedure'];

			// echo $arr_dec_sep;

			$SKDP = date("ym"."01");

			// echo $SKDP;

			if($string_code == '200'){
			// 	$three_db->query("INSERT INTO BPJS_reg (
			// 		Noreg,
			// 		AsalPasien,
			// 		norm,
			// 		NoKartu,
			// 		NIK,
			// 		Nama,
			// 		Sex,
			// 		TglLahir,
			// 		pisat,
			// 		NoSEP,
			// 		TglSEP,
			// 		NoRujukan,
			// 		TglRujukan,
			// 		KdRujukan,
			// 		NmRujukan,
			// 		JnsPelayanan,
			// 		JnsPeserta,
			// 		KdKlsTanggungan,
			// 		NmKlsTanggungan,
			// 		KdPoliklinik,
			// 		NmPoliklinik,
			// 		KdDiagnosa,
			// 		NmDiagnosa,
			// 		Keluhan,
			// 		Catatan,
			// 		usrInsert,
			// 		tglInsert,
			// 		usrUpdate,
			// 		tglUpdate,
			// 		isDeleted,
			// 		KlsBPJS,
			// 		NmKlsBPJS,
			// 		tglPulang,
			// 		SKTM,
			// 		KdInstansi,
			// 		isCOB,
			// 		isKasus,
			// 		PPKTK1,
			// 		tglCetakKartu,
			// 		TMT,
			// 		TAT,
			// 		LokasiKasus,
			// 		usrPrint,
			// 		tglLastPrint,
			// 		printcount,
			// 		asalrujukan,
			// 		penjamin,
			// 		notlp,
			// 		isEksekutif,
			// 		tglKLL,
			// 		KetKLL,
			// 		Propinsi,
			// 		Kabupaten,
			// 		Kecamatan,
			// 		SKDP,
			// 		isSuplesi,
			// 		NoSuplesi,
			// 		isPRB,
			// 		kdDPJP,
			// 		nmDPJP,
			// 		noKunjungan,
			// 		isKatarak,
			// 		dinsos,
			// 		TujKunjungan,
			// 		fProsedur,
			// 		kdPenunjang,
			// 		assesmentPel,
			// 		carakeluar,
			// 		noKematian,
			// 		tglKematian,
			// 		noLPManual,
			// 		noSurat
			// 		)
			// 		VALUES
			// 		(
			// 		'$noreg',
			// 		'RJ',
			// 		'$norm',
			// 		'$nokartu',
			// 		'$nik',
			// 		'$nama',
			// 		'$sex',
			// 		'$tgl_lahir',
			// 		'$pisa',
			// 		'$noSurat',
			// 		'$tgl_hari_ini',
			// 		'$noRujukan',
			// 		'$tglRujukan',
			// 		'$provPerujukKode',
			// 		'$nmProvider',
			// 		'$jnsPelayanan',
			// 		'$jenisPesertaketerangan',
			// 		'$hakKelasKode',
			// 		'$hakKelasKeterangan',
			// 		'$kdpoli',
			// 		'$poli',
			// 		'$kodeDiagnosa',
			// 		'$namaDiagnosa',
			// 		'',
			// 		'',
			// 		'MobileJKN,
			// 		'$tglinsert',
			// 		'MobileJKN',
			// 		'$tglinsert',
			// 		'0',
			// 		'$hakKelasKode',
			// 		'$hakKelasKeterangan',
			// 		'$tglinsert',
			// 		'',
			// 		'',
			// 		'0',
			// 		'0',
			// 		'$PPKTK1',
			// 		'$tglCetakKartu',
			// 		'$tglTMT',
			// 		'$tglTAT',
			// 		'',
			// 		'MobileJKN',
			// 		'$tgl_hari_ini',
			// 		'1',
			// 		'',
			// 		'0',
			// 		'$noTelepon',
			// 		'$poliEksekutif',
			// 		'$tgl_hari_ini',
			// 		'',
			// 		'',
			// 		'',
			// 		'',
			// 		'$SKDP',
			// 		'0',
			// 		'',
			// 		'0',
			// 		'$kode_dok',
			// 		'$nmdokter',
			// 		'$noRujukan',
			// 		'0',
			// 		'',
			// 		'$tujuanKunj',
			// 		'$flagProcedure',
			// 		'$kdPenunjang',
			// 		'$assestmenPel',
			// 		'1',
			// 		'',
			// 		'',
			// 		'',
			// 		'$noSurat' 
			// 		)");
			}

			// echo "Sukses insert data untuk " . $no_mjkn;

			echo json_encode($data);
		} else {
			echo "Data Not Found";
		}
	}
}
?>