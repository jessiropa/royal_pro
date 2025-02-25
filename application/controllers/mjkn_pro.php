<?php
// require_once 'vendor/autoload.php';

require_once FCPATH . 'vendor/autoload.php';

use LZCompressor\LZString;

class mjkn_pro extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('m_mjkn_pro');
	}

	function index()
	{
		$this->load->view('v_mjkn_pro');
	}

	public function stringDecrypt($key, $string)
	{
		// require_once 'vendor/autoload.php';
		// use LZCompressor\LZString;
		// require_once FCPATH . 'vendor/autoload.php';
		// echo "2";

		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);

		date_default_timezone_set("UTC");
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

		$cek_consid_v = $second_db->query("SELECT
			dbs.consid,
			dbs.SecretKey,
			dbs.userKey,
			dbs.kodePPK,
			dbs.url,
			dbs.keterangan,
			dbs.`status`
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
		// return \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
		return LZString::decompressFromEncodedURIComponent($output);
		// echo $output;
	}

	public function cek_blacklist()
	{
		$three_db = $this->load->database('three', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');

		// $three_db->query("UPDATE BPJSAntrean
		// 	SET jnsref = '3'
		// 	WHERE Noref LIKE '%0217R077%'
		// 	AND jnsref = '0'");

		// $three_db->query("UPDATE BPJSAntrean
		// 	SET jnsref = '1'
		// 	WHERE Noref NOT LIKE '%0217R077%'
		// 	AND jnsref = '0'");

		$cek_noantrian = $three_db->query("SELECT
			LTRIM(RTRIM(rja.norm)) AS norm
			FROM
			rj_appointment rja
			WHERE 1 = 1
			AND rja.noapp = '$no_mjkn'
			AND rja.updater = 'MobileJKN'");

		if ($cek_noantrian->num_rows() > '0') {

			$cek_data_antrian_n = $cek_noantrian->row();

			$norm = $cek_data_antrian_n->norm;

			$get_pasien_blacklist = $three_db->query("SELECT blacklist FROM Pasien WHERE norm = '$norm'")->row();
			if ($get_pasien_blacklist->blacklist == '1') {
				$data['blacklist'] = 'ya';
			} else {
				$data['blacklist'] = 'tidak';
			}

			echo json_encode($data);
		} else {
			$cek_noantrian_nn = $three_db->query("SELECT
				LTRIM(RTRIM(rja.norm)) AS norm
				FROM
				rj_appointment rja
				WHERE 1 = 1
				AND rja.noapp = '$no_mjkn'
				AND rja.updater = 'system'");
			if($cek_noantrian_nn->num_rows() > '0'){
				$cek_data_antrian_n = $cek_noantrian_nn->row();

				$norm = $cek_data_antrian_n->norm;

				$get_pasien_blacklist = $three_db->query("SELECT blacklist FROM Pasien WHERE norm = '$norm'")->row();
				if ($get_pasien_blacklist->blacklist == '1') {
					$data['blacklist'] = 'ya';
				} else {
					$data['blacklist'] = 'tidak';
				}

				echo json_encode($data);
			}else{
				$data['blacklist'] = 'bukan';
				echo json_encode($data);	
			}
			
			
		}
	}

	public function cek_data_booking()
	{

		// $second_db = $this->load->database('second', TRUE);
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
		if ($cek_data_booking_n->num_rows() > '0') {
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
		} else {
			$data['data_book'] = '0';
		}
		echo json_encode($data);
	}

	public function susah_finger()
	{
		// $second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$value_alasan_finger = $this->input->post('value_alasan_finger');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');
		$ip_address = $this->input->ip_address();

		$cek_data_booking_n =  $three_db->query("SELECT LTRIM(RTRIM(baa.nokartu)) AS nokartu, LTRIM(RTRIM(baa.noapp)) AS noapp, raa.updater, baa.tgl, baa.nmpoli, baa.nmdokter, raa.nama, baa.noantrian,
			CAST(RIGHT(baa.noantrian, 4) AS int) AS no_antrian_int FROM BPJSAntrean baa 
			INNER JOIN rj_appointment raa ON LTRIM(RTRIM(raa.noapp)) = LTRIM(RTRIM(baa.noapp))
			WHERE
			1 = 1
			AND raa.noapp = '$no_mjkn'
			AND raa.batal = '0'
			AND baa.tgl = '$tgl_hari_ini'");

		if ($cek_data_booking_n->num_rows() > '0') {
			$cek_data_booking = $cek_data_booking_n->row();
			$data['nokartu'] = $cek_data_booking->nokartu;
			date_default_timezone_set("UTC");
			$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

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

			$key = $consid_v . $SecretKey_v . $tStamp;

			$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

			//base64 encode
			$encodedSignature = base64_encode($signature);
			$headers = array(
				'X-cons-id: ' . $consid_v,
				'X-timestamp: ' . $tStamp,
				'X-signature: ' . $encodedSignature,
				'user_key: ' . $userKey_v,
				'Content-Type: Application/x-www-form-urlencoded'
			);
			$arr = array("request" =>
				[
					"t_sep" =>
					[
						"noKartu" => "$cek_data_booking->nokartu",
						"tglSep" => "$tgl_hari_ini",
						"jnsPelayanan" => "2",
						"jnsPengajuan" => "2",
						"keterangan" => "$value_alasan_finger",
						"user" => "Mobile JKN Pro",
					]
				]);
			$json = json_encode($arr);
			// echo $json;
			$url_v = $cek_consid_v->url;
			$url = $url_v . "Sep/pengajuanSEP";


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

			if ($string_code == '200') {
				$data['data_finger'] = '1';
				$data['string_code'] = $string_code;
				$data['string_mess'] = $string_mess;
				$IsSuccess = '1';
				$three_db->query("INSERT INTO bpjs_pengajuan ( NoKartu, TglSep, Nama, kdjenispelayanan, nmjenispelayanan, kdjenispengajuan, nmjenispengajuan, Keterangan, isApprove, Petugas, ip_address, tgl_insert )
					VALUES
					( '$cek_data_booking->nokartu', '$tgl_hari_ini', '$cek_data_booking->nama', '2', 'RJ', '2', 'Pengajuan Finger Print', '$value_alasan_finger', '0', 'Mobile JKN Pro', '$ip_address', '$tglinsert')");

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
				$key = $consid_v . $SecretKey_v . $tStamp;

				$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

				//base64 encode
				$encodedSignature = base64_encode($signature);
				$headers = array(
					'X-cons-id: ' . $consid_v,
					'X-timestamp: ' . $tStamp,
					'X-signature: ' . $encodedSignature,
					'user_key: ' . $userKey_v,
					'Content-Type: Application/x-www-form-urlencoded'
				);
				$arr = array("request" =>
					[
						"t_sep" =>
						[
							"noKartu" => "$cek_data_booking->nokartu",
							"tglSep" => "$tgl_hari_ini",
							"jnsPelayanan" => "2",
							"jnsPengajuan" => "2",
							"keterangan" => "$value_alasan_finger",
							"user" => "Mobile JKN Pro",
						]
					]);

				$json = json_encode($arr);
				// echo $json;
				$url_v = $cek_consid_v->url;
				$url = $url_v . "Sep/aprovalSEP";


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

				if ($string_code_app == '200') {
					$data['data_finger'] = '1';
					$data['string_code'] = $string_code_app;
					$data['string_mess'] = $string_mess_app;
					$IsSuccess = '1';
					$three_db->query("UPDATE bpjs_pengajuan SET isApprove = '1' WHERE NoKartu = '$cek_data_booking->nokartu'
						AND CAST(TglSep AS DATE) = '$tgl_hari_ini'");
				} else {
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
			} else {
				if (strpos($string_mess, 'Peserta Dalam Proses Pengajuan Aproval Penjaminan') !== false) {
					$data['data_finger'] = '1';
					$data['string_code'] = $string_code;
					$data['string_mess'] = $string_mess;
					$IsSuccess = '1';

					// Approve
					$key = $consid_v . $SecretKey_v . $tStamp;

					$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

					//base64 encode
					$encodedSignature = base64_encode($signature);
					$headers = array(
						'X-cons-id: ' . $consid_v,
						'X-timestamp: ' . $tStamp,
						'X-signature: ' . $encodedSignature,
						'user_key: ' . $userKey_v,
						'Content-Type: Application/x-www-form-urlencoded'
					);
					$arr = array("request" =>
						[
							"t_sep" =>
							[
								"noKartu" => "$cek_data_booking->nokartu",
								"tglSep" => "$tgl_hari_ini",
								"jnsPelayanan" => "2",
								"jnsPengajuan" => "2",
								"keterangan" => "$value_alasan_finger",
								"user" => "Mobile JKN Pro",
							]
						]);

					$json = json_encode($arr);
					// echo $json;
					$url_v = $cek_consid_v->url;
					$url = $url_v . "Sep/aprovalSEP";


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

					if ($string_code_app == '200') {
						$data['data_finger'] = '1';
						$data['string_code'] = $string_code_app;
						$data['string_mess'] = $string_mess_app;
						$IsSuccess = '1';
						$three_db->query("UPDATE bpjs_pengajuan SET isApprove = '1' WHERE NoKartu = '$cek_data_booking->nokartu'
							AND CAST(TglSep AS DATE) = '$tgl_hari_ini'");
					} else {
						if (strpos($string_mess_app, 'Peserta Sudah Aproval Penjaminan') !== false) {
							$data['data_finger'] = '2';
							$data['string_code'] = $string_code_app;
							$data['string_mess'] = $string_mess_app;
							$IsSuccess = '1';
						} else {
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
				} else if (strpos($string_mess, 'Peserta Sudah Aproval Penjaminan') !== false) {
					$data['data_finger'] = '2';
					$data['string_code'] = $string_code;
					$data['string_mess'] = $string_mess;
					$IsSuccess = '1';
				} else {
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
		} else {
			$data['data_finger'] = '0';
		}
		echo json_encode($data);
	}

	public function cek_finger()
	{
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$pass = $this->input->post('pass');
		$tgl_hari_ini = date('Y-m-d');

		$cek_pass_jkn_pro = $five_db->query("SELECT
			setting
			FROM
			EMR_setting rja
			WHERE 1 = 1
			AND app = 'APM JKN PRO'")->row();

		if ($pass == $cek_pass_jkn_pro->setting) {

			$data['data_pass'] = '1';
		} else {
			$data['data_pass'] = '0';
		}

		echo json_encode($data);
	}

	public function cek_booking_mjkn()
	{
		$three_db = $this->load->database('three', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');

		$cek_data_booking_n =  $three_db->query("SELECT baa.nokartu
			FROM BPJSAntrean baa 
			INNER JOIN rj_appointment raa ON LTRIM(RTRIM(raa.noapp)) = LTRIM(RTRIM(baa.noapp))
			WHERE
			1 = 1
			AND raa.noapp = '$no_mjkn'
			AND raa.batal = '0'
			AND baa.tgl = '$tgl_hari_ini'
			");
		if ($cek_data_booking_n->num_rows() > '0') {
			$data['data_book'] = '1';
		} else {
			$data['data_book'] = '0';
		}


		echo json_encode($data);
	}

	public function cek_info_kartu()
	{
		$three_db = $this->load->database('three', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$kartu_pasien_f = $this->input->post('kartu_pasien_f');
		$tgl_hari_ini = date('Y-m-d');
		$cek_data_booking_n =  $three_db->query("SELECT LTRIM(RTRIM(baa.noapp)) AS noapp FROM BPJSAntrean baa 
			INNER JOIN rj_appointment rja ON LTRIM(RTRIM(baa.noapp)) = LTRIM(RTRIM(rja.noapp))
			WHERE 1 = 1
			AND rja.updater = 'MobileJKN'
			AND baa.tgl = '$tgl_hari_ini'
			AND baa.nokartu = '$kartu_pasien_f';
			");
		if ($cek_data_booking_n->num_rows() > '0') {
			$cek_data_booking_nn = $cek_data_booking_n->row();
			$data['data_book'] = '1';
			$data['noapp'] = $cek_data_booking_nn->noapp;
		} else {
			$data['data_book'] = '0';
			$data['noapp'] = '';
		}

		echo json_encode($data);
	}

	public function cek_sukses_mjkn()
	{
		$three_db = $this->load->database('three', TRUE);
		$seven_db = $this->load->database('seven', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');

		$cek_booking_log = $three_db->query("SELECT TOP(1)
			LTRIM(RTRIM(noapp)) as noapp,
			IsSuccess
			FROM
			MJKN_BookingLog
			WHERE 1 = 1
			AND noapp = '$no_mjkn'
			ORDER BY tglinsert DESC");

		if ($cek_booking_log->num_rows() > '0') {
			$data['booking'] = '1';

			$noapp = $cek_booking_log->row()->noapp;
			$IsSuccess = $cek_booking_log->row()->IsSuccess;

			if ($IsSuccess == '1') {
				$cek_data_booking_n =  $three_db->query("SELECT noreg, noSEPBPJS
					FROM rj_reg rr
					WHERE
					1 = 1
					AND rr.noapp = '$no_mjkn'
					AND rr.batal = '0'");

				if ($cek_data_booking_n->num_rows() > '0') {
					$data['noSep'] = $cek_data_booking_n->row()->noSEPBPJS;
				} else {
					$data['noSep'] = "";
				}

				$data['string_mess'] = "Sukses";
				$data['sukses'] = '1';
			} else {
				$cek_pesan =  $seven_db->query("SELECT pesan
					FROM pesan_apm_jkn
					WHERE noapp = '$noapp'");

				if ($cek_pesan->num_rows() > '0') {
					$data['string_mess'] = $cek_pesan->row()->pesan;
				} else {
					$data['string_mess'] = "";
				}

				$data['sukses'] = '0';
				$data['noSep'] = "";
			}
		} else {
			$data['booking'] = '0';
		}


		echo json_encode($data);
	}

	public function insert_noapp_rj()
	{
		// $second_db = $this->load->database('second', TRUE);
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
			AND rja.noapp = '$no_mjkn'");

		if ($cek_noantrian->num_rows() > '0') {

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
			$kdDokterMed = $cek_data_antrian_n->kddokter;
			$kdPoliM = $cek_data_antrian_n->kdpoli;

			$cek_antrian = $three_db->query("SELECT
				bja.nokartu,
				bja.jnsref,
				bja.nik,
				bja.tgl,
				bja.kdpoli,
				rjp.nmpoli,
				bja.kddokter,
				bja.nmdokter,
				bja.noantrian,
				LTRIM(RTRIM(m.kode)) AS kode
				FROM
				BPJSAntrean bja
				LEFT JOIN rj_poliklinik rjp ON rjp.kdsubpolibpjs = bja.kdpoli 
				LEFT JOIN medis m ON m.kdDPJP = bja.kddokter
				WHERE 
				noapp = '$no_mjkn'")->row();

			$nokartu = $cek_antrian->nokartu;
			$jnsref = $cek_antrian->jnsref;
			$nik = $cek_antrian->nik;
			$tgl = $cek_antrian->tgl;
			$kdpoli = $cek_antrian->kdpoli;
			$nmpoli = $cek_antrian->nmpoli;
			$noantrian = $cek_antrian->noantrian;
			$kddokter = $cek_antrian->kddokter;
			$nmdokter = $cek_antrian->nmdokter;

			$noreg = 'OP';
			$date = str_replace("-", "", date('Y-m-d'));
			$noreg .= $date;
			$noreg .= '-';

			$get_norm = $three_db->query("SELECT MAX(right(noreg,4)) as maxid FROM rj_reg WHERE noreg LIKE '%$date%'")->row();
			if ($get_norm) {
				$maxid = $get_norm->maxid;
				$maxid = $maxid + 1;

				$maxid_bukti = $get_norm->maxid;
			} else {
				$maxid = '0001';

				$maxid_bukti = '0001';
			}

			$noreg .= sprintf("%04s", $maxid);

			$nobukti = $date . '0';

			$nobukti .= sprintf("%04s", $maxid_bukti);

			$get_kunjungan_pasien = $three_db->query("SELECT TOP(2) noreg FROM rj_reg WHERE norm = '$norm'");
			if ($get_kunjungan_pasien->num_rows() > 1) {
				$pasienbaru = '0';
				$kunjungan = '0';
			} else {
				$pasienbaru = '1';
				$kunjungan = '1';
			}

			$get_kunjungan_pasien = $three_db->query("SELECT noreg FROM rj_reg WHERE noapp = '$no_mjkn'");
			if ($get_kunjungan_pasien->num_rows() <= '0') {
				$three_db->query("INSERT INTO rj_reg (
					noreg,
					norm,
					noapp,
					nomember,
					tglregistrasi,
					kdtipevisit,
					kdpoli,
					kddokter,
					kdtipecharge,
					kdjmbayar,
					tutup,
					tglupdate,
					usrupdate,
					kdinstansi,
					ketinstansi,
					jamreg,
					jamapp,
					kdtipetinggal,
					kdpaketmember,
					piutang,
					kdtarifkonsul,
					kdtipepasien,
					kdplafon,
					plafon,
					sisaplafon,
					fplafonperhari,
					kontrakmcu,
					batal,
					kdpengirim,
					nopengirim,
					keterangan,
					tglkeluar,
					jamkeluar,
					kdkdnkeluar,
					kdcarakeluar,
					kdtdklanjut,
					umurtahun,
					umurbulan,
					umurhari,
					pasienbaru,
					usrinsert,
					tglinsert,
					shift,
					kartu,
					rujukan,
					noSEPBPJS
					)
					VALUES
					(
					'$noreg',
					'$norm',
					'$no_mjkn',
					'',
					'$tgl_hari_ini',
					'$kdtipevisit',
					'$kdPoliM',
					'$kdDokterMed',
					'01',
					'$kdjmbayar',
					'0',
					'$tglinsert',
					'$updater',
					'$kdinstansi',
					'$nminstansi',
					'$jam_hari_ini',
					'$jamapp',
					'',
					'',
					'0',
					'',
					'08',
					'',
					'',
					'',
					'',
					'',
					'$batal',
					'00',
					'',
					'',
					'$tgl_hari_ini',
					'',
					'',
					'',
					'',
					'$umurtahun',
					'$umurbulan',
					'$umurhari',
					'$pasienbaru',
					'$updater',
					'$tglinsert',
					'$shift',
					'0',
					'0',
					''
				);");

				$three_db->query("INSERT INTO rj_transhd (
					nobukti,
					noreg,
					noKunjungan,
					tgltrans,
					kdpoli,
					posting,
					nobill,
					fReferal,
					noresep,
					kddokter,
					nopaket,
					otomatis,
					kunjunganbaru,
					kdcabang,
					shift,
					jamtrans,
					jnsKasus,
					Diagnosa,
					kdterminal
					)
					VALUES
					(
					'$nobukti',
					'$noreg',
					'',
					'$tgl_hari_ini',
					'$kdPoliM',
					'',
					'',
					'',
					'',
					'$kdDokterMed',
					'',
					'1',
					'$kunjungan',
					'',
					'$shift',
					'$jam_hari_ini',
					'',
					'',
					''
				);");

				$three_db->query("INSERT INTO rj_antrian (
					tanggal,
					shift,
					kdpoli,
					kddokter,
					noreg,
					noapp,
					nobukti,
					antrian,
					sisipan,
					statusreg,
					tglinsert,
					usrinsert
					)
					VALUES
					(
					'$tgl_hari_ini',
					'$shift',
					'$kdPoliM',
					'$kdDokterMed',
					'$noreg',
					'$no_mjkn',
					'',
					'$noantrian',
					'0',
					'0',
					'$tglinsert',
					'$updater'
				);");

				$three_db->query("UPDATE rj_appointment 
					SET registrasi = '1'
					WHERE noapp = '$no_mjkn'");
			}

			date_default_timezone_set("UTC");
			$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

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

			$key = $consid_v . $SecretKey_v . $tStamp;

			$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

			//base64 encode
			$encodedSignature = base64_encode($signature);

			$headers = array(
				'X-cons-id: ' . $consid_v,
				'X-timestamp: ' . $tStamp,
				'X-signature: ' . $encodedSignature,
				'user_key: ' . $userKey_v,
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
			$noRujukan_n = $arr_dec['rujukan']['noKunjungan'];

			if ($noRujukan_n == '') {
				date_default_timezone_set("UTC");
				$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

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

				$key = $consid_v . $SecretKey_v . $tStamp;

				$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

				//base64 encode
				$encodedSignature = base64_encode($signature);

				$headers = array(
					'X-cons-id: ' . $consid_v,
					'X-timestamp: ' . $tStamp,
					'X-signature: ' . $encodedSignature,
					'user_key: ' . $userKey_v,
					'Content-Type: application/json'
				);

				$url_v = $cek_consid_v->url;
				$url = $url_v . "Rujukan/RS/Peserta/" . $nokartu;
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
				$noRujukan_nn = $arr_dec['rujukan']['noKunjungan'];
				$noRujukan_xx = $noRujukan_nn;
			} else {
				$noRujukan_xx = $noRujukan_n;
			}


			$cek_sep = $five_db->query("SELECT TOP(1)
				bgk.tglSep,
				bgk.noSep,
				bgk.diagnosa
				FROM
				bpjs_get_kunjungan_sep bgk
				WHERE 
				noKartu = '$nokartu' AND poli = '$cek_antrian->kdpoli'
				ORDER BY tglSep DESC");

			if ($cek_sep->num_rows() > 0) {
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
				ORDER BY brk.tgl_insert DESC");

			if ($cek_rujukan->num_rows() > 0) {
				$hasil_json = json_decode($cek_rujukan->row()->hasil_json, true);
				$nik = $hasil_json['peserta']['nik'];
				$nama_p = $hasil_json['peserta']['nama'];
				$nama = str_replace("'", "`", $nama_p);
				$pisa = $hasil_json['peserta']['pisa'];
				$sex = $hasil_json['peserta']['sex'];
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
			} else {

				$headers = array(
					'X-cons-id: ' . $consid_v,
					'X-timestamp: ' . $tStamp,
					'X-signature: ' . $encodedSignature,
					'user_key: ' . $userKey_v,
					'Content-Type: application/json'
				);

				$url_v = $cek_consid_v->url;
				$url = $url_v . "Peserta/nokartu/" . $nokartu . "/tglSEP/" . $tgl_hari_ini;
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
				// echo $string_dec;
				$data['string_dec'] = $string_dec;
				$arr_dec = json_decode($string_dec, true);
				$nik = $arr_dec['peserta']['nik'];
				$nama_p = $arr_dec['peserta']['nama'];
				$nama = str_replace("'", "`", $nama_p);
				$pisa = $arr_dec['peserta']['pisa'];
				$sex = $arr_dec['peserta']['sex'];
				$noTelepon = $arr_dec['peserta']['mr']['noTelepon'];
				$tgl_lahir = $arr_dec['peserta']['tglLahir'];
				$tglCetakKartu = $arr_dec['peserta']['tglCetakKartu'];
				$tglTAT = $arr_dec['peserta']['tglTAT'];
				$tglTMT = $arr_dec['peserta']['tglTMT'];
				$statusPesertakode = $arr_dec['peserta']['statusPeserta']['kode'];
				$statusPesertaketerangan = $arr_dec['peserta']['statusPeserta']['keterangan'];
				$kdProvider = $arr_dec['peserta']['provUmum']['kdProvider'];
				$nmProvider = $arr_dec['peserta']['provUmum']['nmProvider'];
				$jenisPesertakode = $arr_dec['peserta']['jenisPeserta']['kode'];
				$jenisPesertaketerangan = $arr_dec['peserta']['jenisPeserta']['keterangan'];
				$hakKelasKode = $arr_dec['peserta']['hakKelas']['kode'];
				$hakKelasKeterangan = $arr_dec['peserta']['hakKelas']['keterangan'];
				$umurSekarang = $arr_dec['peserta']['umur']['umurSekarang'];
				$umurSaatPelayanan = $arr_dec['peserta']['umur']['umurSaatPelayanan'];
				$dinsos = $arr_dec['peserta']['informasi']['dinsos'];
				$prolanisPRB = $arr_dec['peserta']['informasi']['prolanisPRB'];
				$noSKTM = $arr_dec['peserta']['informasi']['noSKTM'];
				$eSEP = $arr_dec['peserta']['informasi']['eSEP'];
				$noAsuransi = $arr_dec['peserta']['cob']['noAsuransi'];
				$nmAsuransi = $arr_dec['peserta']['cob']['nmAsuransi'];
			}

			// $hakKelasKode = $cek_rujukan->hakkelas_kode;
			// $nama = $cek_rujukan->nama;
			// $nik = $cek_rujukan->nik;
			// $sex = $cek_rujukan->sex;
			// $tgl_lahir = $cek_rujukan->tgl_lahir;



			if ($provPerujukKode_n != '') {
				$PPKTK1 = $provPerujukKode_n;
			} else {
				$PPKTK1 = $kdProvider;
			}

			if ($jnsref == '1') {
				$kdpoli = $cek_antrian->kdpoli;
				$kode_dok = $kddokter;
				$noRujukan = $noref;
				if ($kdpoli == 'ORT') {
					$tujuanKunj = "0";
					$flagProcedure = "0";
					$kdPenunjang = "10";
					$assestmenPel = "";
					$noSurat = "";
				} else {
					$tujuanKunj = "0";
					$flagProcedure = "";
					$kdPenunjang = "";
					$assestmenPel = "";
					$noSurat = "";
				}
			} else if ($jnsref == '3') {
				$tujuanKunj = "2";
				$flagProcedure = "";
				$kdPenunjang = "";
				$assestmenPel = "5";

				$noRujukan = $noRujukan_xx;

				$cek_dpjp = $three_db->query("SELECT
					bk.noKontrol,
					bk.KdPoli,
					bk.DPJP
					FROM
					BPJS_Kontrol bk
					WHERE 
					noKontrol = '$noref'");

				if ($cek_dpjp->num_rows() > 0) {
					$cek_dpjp = $cek_dpjp->row();
					$noSurat = $cek_dpjp->noKontrol;
					$kdpoli = $cek_dpjp->KdPoli;
					$kode_dok = $cek_dpjp->DPJP;
				} else {
					$noSurat = $noref;
					$kdpoli = $cek_antrian->kdpoli;
					$kode_dok = $kddokter;
				}
			} else {
				$kdpoli = $cek_antrian->kdpoli;
				$kode_dok = $kddokter;
				$noRujukan = $noref;

				if ($kdpoli == 'ORT') {
					$tujuanKunj = "0";
					$flagProcedure = "0";
					$kdPenunjang = "10";
					$assestmenPel = "";
					$noSurat = "";
				} else {
					$tujuanKunj = "0";
					$flagProcedure = "";
					$kdPenunjang = "";
					$assestmenPel = "";
					$noSurat = "";
				}
			}

			// $cek_nm_poli = 

			$headers = array(
				'X-cons-id: ' . $consid_v,
				'X-timestamp: ' . $tStamp,
				'X-signature: ' . $encodedSignature,
				'user_key: ' . $userKey_v,
				'Content-Type: Application/x-www-form-urlencoded'
			);

			$arr = array("request" =>
				[
					"t_sep" =>
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
			$url = $url_v . "SEP/2.0/insert";

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

			if ($string_code == '200') {
				$IsSuccess = '1';
				$data['string_code'] = "200";
				$data['string_mess'] = "Success";

				$status = '1';
			} else {
				$IsSuccess = '0';

				if (strpos($string_mess, 'Peserta Belum Melakukan Enrollment') !== false) {
					$data['string_code'] = '888';
					$data['string_mess'] = $string_mess;
				} else if (strpos($string_mess, 'telah mendapat Pelayanan') !== false) {
					$data['string_code'] = '889';
					$data['string_mess'] = $string_mess;
				} else {
					$data['string_code'] = $string_code;
					$data['string_mess'] = $string_mess;
				}

				$status = '2';
			}

			$three_db->query("INSERT INTO MJKN_BookingLog (
				noapp,
				IsSuccess,
				tglinsert
				)
				VALUES
				(
				'$no_mjkn',
				'$IsSuccess',
				'$tglinsert'
			);");

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

			// $data['string_dec_sep'] = $string_dec_sep;
			// $data['json'] = $json;

			$arr_dec_sep = json_decode($string_dec_sep, true);

			$no_Sep = $arr_dec_sep['sep']['noSep'];
			$jnsPelayanan = $arr_dec_sep['sep']['jnsPelayanan'];
			$poliEksekutif = $arr_dec_sep['sep']['poliEksekutif'];
			$catatan = $arr_dec_sep['sep']['catatan'];
			$penjamin = $arr_dec_sep['sep']['penjamin'];
			$tujuanKunj = $arr_dec_sep['sep']['tujuanKunj'];
			$poli_n = $arr_dec_sep['sep']['poli'];
			$assestmenPel = $arr_dec_sep['sep']['assestmenPel'];
			$kdPenunjang = $arr_dec_sep['sep']['kdPenunjang'];
			$flagProcedure = $arr_dec_sep['sep']['flagProcedure'];
			if ($poliEksekutif == 'Tidak') {
				$poliEksekutif_n = '0';
			} else {
				$poliEksekutif_n = '1';
			}
			$jnsPelayanan_n = 'Rawat Jalan';


			// echo $arr_dec_sep;

			$data['noSep'] = $no_Sep;

			$SKDP = date("ym" . "01");

			// echo $SKDP;

			$get_no_reg_pasien = $three_db->query("SELECT noreg FROM rj_reg WHERE noapp = '$no_mjkn'")->row();

			$noreg_rj = $get_no_reg_pasien->noreg;

			if ($string_code == '200') {
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
					'$noreg_rj',
					'RJ',
					'$norm',
					'$nokartu',
					'$nik',
					'$nama',
					'$sex',
					'$tgl_lahir',
					'$pisa',
					'$no_Sep',
					'$tgl_hari_ini',
					'$noRujukan',
					'$tglRujukan',
					'$provPerujukKode_n',
					'$nmProvider',
					'$jnsPelayanan_n',
					'$jenisPesertaketerangan',
					'$hakKelasKode',
					'$hakKelasKeterangan',
					'$kdpoli',
					'$poli_n',
					'$kodeDiagnosa',
					'$namaDiagnosa',
					'',
					'',
					'MobileJKN',
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
					'$asalFaskes',
					'0',
					'$noTelepon',
					'$poliEksekutif_n',
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


				$three_db->query("UPDATE rj_reg SET noSEPBPJS = '$no_Sep' WHERE noreg = '$noreg_rj'");
			}

			echo json_encode($data);
		}
	}

	public function cek_queue()
	{
		// $second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$four_db = $this->load->database('four', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);

		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$string_mess = $this->input->post('string_mess');
		$no_sep = $this->input->post('no_sep');
		$status = $this->input->post('status');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$seven_db->query("INSERT INTO pesan_apm_jkn (
			noapp,
			pesan, 
			waktu, 
			status, 
			response, 
			json
			)
			VALUES
			( 
			'$no_mjkn',
			'$string_mess',
			'$tglinsert', 
			'$status',
			'',
			''
		)");

		$cek_data_queue = $four_db->query("SELECT id, code, mrn FROM queue
			WHERE
			1 = 1
			AND appointment_number = '$no_mjkn'");

		if ($cek_data_queue->num_rows() > '0') {

			$data['data_queue'] = '1';
			$data['code'] = $cek_data_queue->row()->code;
			$data['nokartu'] = $cek_data_queue->row()->mrn;

			$cek_data_booking_n = $three_db->query("SELECT baa.nmpoli, baa.nmdokter, LTRIM(RTRIM(baa.noapp)) AS noapp FROM BPJSAntrean baa
				WHERE
				1 = 1
				AND baa.noapp = '$no_mjkn'
				AND baa.tgl = '$tgl_hari_ini'");

			// $nmpoli = $cek_data_booking_n->row()->nmpoli;
			// $nmdokter = $cek_data_booking_n->row()->nmdokter;
			$noapp = $cek_data_booking_n->row()->noapp;
			$data['nmpoli'] = $cek_data_booking_n->row()->nmpoli;
			$data['nmdokter'] = $cek_data_booking_n->row()->nmdokter;

			$four_db->query("UPDATE queue 
				SET is_processed = 'f',
				is_call_skipped = 'f',
				last_called_at = NULL
				WHERE appointment_number = '$no_mjkn'");
		} else {
			$data['data_queue'] = '0';

			$cek_data_appointment = $three_db->query("SELECT LTRIM(RTRIM(raa.kddokter)) AS kddokter, LTRIM(RTRIM(raa.kdpoli)) AS kdpoli, CAST(raa.tglapp AS DATE) AS tglapp, raa.nama FROM rj_appointment raa 
				WHERE
				1 = 1
				AND raa.noapp = '$no_mjkn'
				AND raa.batal = '0'")->row();

			$kdpoli = $cek_data_appointment->kdpoli;
			// echo $kdpoli;
			$kddokter = $cek_data_appointment->kddokter;
			$tglapp = $cek_data_appointment->tglapp;
			$patient_name = $cek_data_appointment->nama;

			$cek_data_service =  $four_db->query("SELECT id AS service_id FROM master_service
				WHERE
				1 = 1
				AND code = '$kdpoli'");

			$service_id = $cek_data_service->row()->service_id;

			$cek_data_doctor =  $four_db->query("SELECT id AS doctor_id FROM master_doctor
				WHERE
				1 = 1
				AND code = '$kddokter'")->row();

			$doctor_id = $cek_data_doctor->doctor_id;

			$cek_data_schedule =  $four_db->query("SELECT MAX(id) AS schedule_id FROM master_schedule
				WHERE
				1 = 1
				AND doctor_id = '$doctor_id'
				AND date = '$tgl_hari_ini'")->row();

			$schedule_id = $cek_data_schedule->schedule_id;

			$cek_data_booking_n = $three_db->query("SELECT LTRIM(RTRIM(baa.nokartu)) AS nokartu, LTRIM(RTRIM(baa.noapp)) AS noapp, baa.tgl, baa.nmpoli, baa.nmdokter, baa.noantrian,
				CAST(RIGHT(baa.noantrian, 4) AS int) AS no_antrian_int FROM BPJSAntrean baa
				WHERE
				1 = 1
				AND baa.noapp = '$no_mjkn'
				AND baa.tgl = '$tgl_hari_ini'");

			$nokartu = $cek_data_booking_n->row()->nokartu;
			$noapp = $cek_data_booking_n->row()->noapp;
			$no_antrian_int = $cek_data_booking_n->row()->no_antrian_int;
			// $nmpoli = $cek_data_booking_n->row()->nmpoli;
			// $nmdokter = $cek_data_booking_n->row()->nmdokter;
			$data['nmpoli'] = $cek_data_booking_n->row()->nmpoli;
			$data['nmdokter'] = $cek_data_booking_n->row()->nmdokter;

			$code = "B" . $no_antrian_int . "-" . $kdpoli;

			$data['code'] = $code;
			$data['nokartu'] = $nokartu;

			$four_db->query("INSERT INTO queue (
				service_id,
				doctor_id,
				insurer_id,
				online_reg_number,
				online_verified,
				is_new_patient,
				patient_name,
				patient_info,
				created_at,
				mrn,
				code,
				is_processed,
				date,
				is_call_skipped,
				last_called_at,
				appointment_number,
				schedule_id,
				active,
				rm_found,
				rm_found_by,
				rm_found_at,
				rm_cannot_found,
				rm_cannot_found_reason,
				code_number,
				status_tambah_antrian_bpjs
				)
				VALUES
				(
				'$service_id',
				'$doctor_id',
				'3',
				NULL,
				'f',
				'f',
				'$patient_name',
				NULL,
				'$tglinsert',
				'$nokartu',
				'$code',
				'f',
				'$tglapp',
				'f',
				NULL,
				'$noapp',
				'$schedule_id',
				't',
				'f',
				NULL,
				NULL,
				'f',
				NULL,
				'$no_antrian_int',
				'0' 
			)");
		}

		$data['noapp'] = $noapp;
		$data['tglinsert'] = $tglinsert;
		$data['tgl_hari_ini'] = $tgl_hari_ini;
		if ($no_sep == "") {
			$data['noSep'] = "-";
		} else {
			$data['noSep'] = $no_sep;
		}
		$data['string_mess'] = str_replace(',', '', $string_mess);
		$data['status'] = $status;

		// $this->load->view('v_print_antrian', $data);

		echo json_encode($data);
	}

	function print_ubah_surkon($no_surkon)
	{
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$four_db = $this->load->database('four', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);
		date_default_timezone_set("Asia/Bangkok");
		$no_surkon = $this->uri->segment(3);
		$cek_data_kontrol = $three_db->query("SELECT
			bpjk.NoKontrol, 
			CAST(bpjk.TglKontrol AS DATE) AS TglKontrol, 
			bpjk.NoKartu, 
			bpjk.Nama, 
			bpjk.NmDPJP, 
			bpjk.NmPoli
			FROM
			dbo.BPJS_Kontrol AS bpjk
			WHERE
			bpjk.NoKontrol = '$no_surkon'")->row();
		$data['noSuratKontrol'] = $cek_data_kontrol->NoKontrol;
		$data['Nama'] = $cek_data_kontrol->Nama;
		$data['NoKartu'] = $cek_data_kontrol->NoKartu;
		$data['TglKontrol'] = $cek_data_kontrol->TglKontrol;
		$data['NmDPJP'] = $cek_data_kontrol->NmDPJP;

		$cek_warning = $five_db->query("SELECT TOP(1)
			eck.WarningMsg
			FROM
			dbo.EMR_CATATAN_KONTROL AS eck
			WHERE
			eck.NoKontrol = '$no_surkon'
			ORDER BY eck.TglInsert DESC")->row();
		$data['WarningMsg'] = $cek_warning->WarningMsg;	


		$this->load->view('v_print_surkon', $data);


	}


	function print_antrian_queue($no_mjkn, $no_sep, $status, $warna)
	{
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$four_db = $this->load->database('four', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);

		$no_mjkn = $this->uri->segment(3);
		// $string_mess = $this->uri->segment(4);
		$no_sep = $this->uri->segment(4);
		$status = $this->uri->segment(5);
		$warna = $this->uri->segment(6);

		date_default_timezone_set("Asia/Bangkok");

		// $no_mjkn = $this->input->post('no_mjkn');
		// $string_mess = $this->input->post('string_mess');
		// $no_sep = $this->input->post('no_sep');
		// $status = $this->input->post('status');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$cek_data_pesan = $seven_db->query("SELECT TOP(1) pesan FROM pesan_apm_jkn
			WHERE
			1 = 1
			AND noapp = '$no_mjkn'
			ORDER BY waktu DESC")->row();

		$data['string_mess'] = $cek_data_pesan->pesan;

		$cek_data_booking_n = $three_db->query("SELECT LTRIM(RTRIM(baa.nokartu)) AS nokartu, LTRIM(RTRIM(baa.noapp)) AS noapp, baa.tgl, baa.nmpoli, baa.nmdokter, baa.noantrian,
			CAST(RIGHT(baa.noantrian, 4) AS int) AS no_antrian_int FROM BPJSAntrean baa
			WHERE
			1 = 1
			AND baa.noapp = '$no_mjkn'
			");

		$cek_data_appointment = $three_db->query("SELECT LTRIM(RTRIM(raa.kddokter)) AS kddokter, LTRIM(RTRIM(raa.kdpoli)) AS kdpoli, CAST(raa.tglapp AS DATE) AS tglapp, raa.nama AS nama_pasien, norm, raa.updater FROM rj_appointment raa 
			WHERE
			1 = 1
			AND raa.noapp = '$no_mjkn'
			AND raa.batal = '0'")->row();
		$nama_pasien = $cek_data_appointment->nama_pasien;
		$norm = $cek_data_appointment->norm;

		if ($cek_data_booking_n->num_rows() > '0') {
			$kdpoli = $cek_data_appointment->kdpoli;
			$nama_pasien = $cek_data_appointment->nama_pasien;
			$updater = $cek_data_appointment->updater;
			// echo $kdpoli;


			$nokartu = $cek_data_booking_n->row()->nokartu;
			$noapp = $cek_data_booking_n->row()->noapp;
			$no_antrian_int = $cek_data_booking_n->row()->no_antrian_int;
			$code = "B" . $no_antrian_int . "-" . $kdpoli;

			$data['code'] = $code;
			$data['nokartu'] = $nokartu;

			$data['nmpoli'] = $cek_data_booking_n->row()->nmpoli;
			$data['nmdokter'] = $cek_data_booking_n->row()->nmdokter;
		} else {
			$kdpoli = $cek_data_appointment->kdpoli;
			$nama_pasien = $cek_data_appointment->nama_pasien;
			$updater = $cek_data_appointment->updater;
			$cek_data_queue = $four_db->query("SELECT id, code, mrn FROM queue
				WHERE
				1 = 1
				AND appointment_number = '$no_mjkn'");
			$cek_data_booking_n = $three_db->query("SELECT baa.nmpoli, baa.nmdokter, LTRIM(RTRIM(baa.noapp)) AS noapp FROM BPJSAntrean baa
				WHERE
				1 = 1
				AND baa.noapp = '$no_mjkn'
				-- AND baa.tgl = '$tgl_hari_ini'
				");

			$nokartu = $cek_data_queue->row()->mrn;
			$noapp = $cek_data_booking_n->row()->noapp;
			$data['code'] = $cek_data_queue->row()->code;
			$data['nokartu'] = $cek_data_queue->row()->mrn;

			$data['nmpoli'] = $cek_data_booking_n->row()->nmpoli;
			$data['nmdokter'] = $cek_data_booking_n->row()->nmdokter;

		}

		$cek_data_pasien_kartu = $three_db->query("SELECT TOP (1) NmKlsTanggungan FROM BPJS_reg
			WHERE NoKartu = '$nokartu'
			ORDER BY tglInsert DESC");
		if ($cek_data_pasien_kartu->num_rows() > '0') {
			$data['NmKlsTanggungan'] = $cek_data_pasien_kartu->row()->NmKlsTanggungan;
		} else {
			$data['NmKlsTanggungan'] = '';
		}

		$data['nama_pasien'] = $nama_pasien;
		$data['norm'] = $norm;
		$data['noapp'] = $noapp;
		$data['tglinsert'] = $tglinsert;
		$data['tgl_hari_ini'] = $tgl_hari_ini;
		$data['noSep'] = $no_sep;
		$data['status'] = $status;
		$data['warna'] = $warna;
		if($updater == 'MobileJKN'){
			$updater = 'Mobile JKN';
		}else{
			$updater = 'Website';
		}
		$data['updater'] = $updater;



		$this->load->view('v_print_antrian', $data);

		// for($i=1;$i<4;$i++){
		// 	
		// }

	}

	function tinjau_no_surat()
	{
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$four_db = $this->load->database('four', TRUE);
		$five_db = $this->load->database('five', TRUE);
		$seven_db = $this->load->database('seven', TRUE);
		$no_surkon = $this->input->post('no_surkon');

		$rencana_kontrol = $three_db->query("SELECT
			bpk.TglKontrol,
			bpk.Catatan,
			bpk.Noreg,
			bpk.NoKontrol,
			bpk.Nosep,
			LTRIM(RTRIM(rjb.kdpoli)) AS KdPoli,
			LTRIM(RTRIM(rjb.nmpoli)) AS NmPoli,
			LTRIM( RTRIM( rjb.kdpoliBPJS ) ) AS kdpoliBPJS,
			LTRIM( RTRIM( rjb.nmpolibpjs ) ) AS nmpolibpjs,
			LTRIM(RTRIM(mb.kode)) AS kdDPJP,
			LTRIM(RTRIM(mb.nama)) AS nmDPJP,
			LTRIM( RTRIM( mb.kdDPJP ) ) AS kddpjpBPJS,
			LTRIM( RTRIM( mb.nmDPJP ) ) AS nmdpjpBPJS,
			LTRIM( RTRIM( rjb.kdsubpolibpjs ) ) AS kdsubpolibpjs,
			LTRIM( RTRIM( rjb.nmsubpolibpjs ) ) AS nmsubpolibpjs
			FROM
			BPJS_Kontrol AS bpk
			LEFT JOIN rj_poliklinik rjb ON bpk.KdPoli = rjb.kdsubpolibpjs
			LEFT JOIN medis mb ON bpk.DPJP = mb.kdDPJP 
			WHERE
			1 = 1 
			AND bpk.NoKontrol = '$no_surkon' 
			AND bpk.IsDelete = '0'");
		if ($rencana_kontrol->num_rows() > '0') {
			$rencana_kontrol_ada = $rencana_kontrol->row();
			$output_soap = "";
			$output_soap .= '
			<input type="date" name="edit_tanggal_surkon" id="edit_tanggal_surkon" type="text" class="form-control" value="' . date('Y-m-d') . '">
			<input type="hidden" name="noreg_kontrol" id="noreg_kontrol" value="' . $rencana_kontrol_ada->Noreg . '">
			<input type="hidden" name="noSEP_kontrol" id="noSEP_kontrol" value="' . $rencana_kontrol_ada->Nosep . '">
			<input type="hidden" name="no_kontrol" id="no_kontrol" value="' . $rencana_kontrol_ada->NoKontrol . '">
			<input type="text" name="no_kontrol_xx" id="no_kontrol_xx" value="' . $rencana_kontrol_ada->NoKontrol . '" disabled>			
			<input type="hidden" name="kd_dpjp_bpjs" id="kd_dpjp_bpjs" value="' . $rencana_kontrol_ada->kddpjpBPJS . '">
			<input type="text" name="nama_dokter_bpjs" id="nama_dokter_bpjs" value="' . $rencana_kontrol_ada->nmdpjpBPJS . '" disabled>
			<input type="hidden" name="kd_poli_bpjs" id="kd_poli_bpjs" value="' . $rencana_kontrol_ada->kdsubpolibpjs . '">
			<input type="text" name="nama_poli_bpjs" id="nama_poli_bpjs" value="' . $rencana_kontrol_ada->nmpolibpjs . '" disabled>
			<input type="text" name="no_sep_bpjs" id="no_sep_bpjs" value="' . $rencana_kontrol_ada->Nosep . '" disabled>
			<select class="bs-select form-control" id="EMR_nama_dokter_kontrol_bpjs" data-live-search="true" data-size="15">
			<option value="' . $rencana_kontrol_ada->kddpjpBPJS . '" selected disabled>' . $rencana_kontrol_ada->nmdpjpBPJS . '</option>';
			$dataPoli  = $three_db->query("SELECT bpj.DPJP, med.nama FROM BPJS_Kontrol bpj 
				INNER JOIN medis med ON med.kdDPJP = bpj.DPJP
				WHERE bpj.KdPoli = '$rencana_kontrol_ada->kdsubpolibpjs'
				GROUP BY bpj.DPJP, med.nama
				");
			foreach ($dataPoli->result() as $row) {
				$output_soap .= '<option value="' . trim($row->DPJP) . '">' . $row->nama . '</option>';
			}
			$output_soap .= '
			</select>
			';

			echo $output_soap;
		}
	}

	function get_surat_kontrol_kartu()
	{
		$no_surkon = $this->input->post('no_surkon');
		$tahun_surkon = $this->input->post('tahun_surkon');
		$bulan_surkon = $this->input->post('bulan_surkon');
		$jenis_tgl = 2;

		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);

		date_default_timezone_set("UTC");
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));



		$cek_consid_v = $second_db->query("SELECT
			dbs.consid,
			dbs.SecretKey,
			dbs.userKey,
			dbs.kodePPK,
			dbs.url,
			dbs.keterangan,
			dbs.`status`
			FROM decrypt_bpjs dbs
			WHERE 
			1 = 1
			AND keterangan = 'vclaim'")->row();
		$consid_v = $cek_consid_v->consid;
		$SecretKey_v = $cek_consid_v->SecretKey;
		$userKey_v = $cek_consid_v->userKey;

		$key = $consid_v . $SecretKey_v . $tStamp;

		$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

		//base64 encode
		$encodedSignature = base64_encode($signature);

		$headers = array(
			'X-cons-id: ' . $consid_v,
			'X-timestamp: ' . $tStamp,
			'X-signature: ' . $encodedSignature,
			'user_key: ' . $userKey_v,
			'Content-Type: Application/x-www-form-urlencoded'
			// 'Content-Type: application/json'
		);

		// $bulan = $this->input->post('bulan');
		// $tahun = $this->input->post('tahun');
		// $no_kartu = $this->input->post('no_kartu');
		// $jenis_tgl = $this->input->post('jenis_tgl');


		$url_v = $cek_consid_v->url;
		$url = $url_v . "/RencanaKontrol/ListRencanaKontrol/Bulan/" . $bulan_surkon . "/Tahun/" . $tahun_surkon . "/Nokartu/" . $no_surkon . "/filter/" . $jenis_tgl;

		// echo $url;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$string = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		$arr = json_decode($string, true);
		$string = $arr['response'];
		// echo $string;
		$string_dec = $this->stringDecrypt($key, $string);

		$data = json_decode($string_dec, true);
		/* insert data rencana kontrol ke dalam bpjs get surat kontrol */
		$jum_ruj = count($data['list']);

		$noSuratKontrol = $data['list'][0]['noSuratKontrol'];

		if ($noSuratKontrol != NULL || $noSuratKontrol != '') {
			for ($i = 0; $i < $jum_ruj; $i++) {
				$noSuratKontrol = $data['list'][$i]['noSuratKontrol'];
				$jnsKontrol = $data['list'][$i]['jnsKontrol'];
				$jnsPelayanan = $data['list'][$i]['jnsPelayanan'];
				$kodeDokter = $data['list'][$i]['kodeDokter'];
				$nama = $data['list'][$i]['nama'];
				$nama = str_replace("'", "`", $nama);
				$namaDokter = $data['list'][$i]['namaDokter'];
				$namaDokter = str_replace("'", "`", $namaDokter);
				$namaJnsKontrol = $data['list'][$i]['namaJnsKontrol'];
				$namaPoliAsal = $data['list'][$i]['namaPoliAsal'];
				$namaPoliAsal = str_replace("'", "`", $namaPoliAsal);
				$namaPoliTujuan = $data['list'][$i]['namaPoliTujuan'];
				$namaPoliTujuan = str_replace("'", "`", $namaPoliTujuan);
				$noKartu = $data['list'][$i]['noKartu'];
				$noSepAsalKontrol = $data['list'][$i]['noSepAsalKontrol'];

				$poliAsal = $data['list'][$i]['poliAsal'];
				$poliTujuan = $data['list'][$i]['poliTujuan'];
				$terbitSEP = $data['list'][$i]['terbitSEP'];
				$tglRencanaKontrol = $data['list'][$i]['tglRencanaKontrol'];
				$tglSEP = $data['list'][$i]['tglSEP'];
				$tglTerbitKontrol = $data['list'][$i]['tglTerbitKontrol'];
				date_default_timezone_set("Asia/Bangkok");
				$tgl_insert = date('Y-m-d H:i:s');

				$cek_surat_kontrol = $second_db->query("SELECT * FROM bpjs_get_surat_kontrol 
					WHERE noSuratKontrol = '$noSuratKontrol' AND 
					noKartu = '$noKartu' AND 
					namaJnsKontrol = '$namaJnsKontrol'");

				if ($cek_surat_kontrol->num_rows() == 0) {
					$tambahsurkon['jnsKontrol'] = $jnsKontrol;
					$tambahsurkon['jnsPelayanan'] = $jnsPelayanan;
					$tambahsurkon['kodeDokter'] = $kodeDokter;
					$tambahsurkon['nama'] = $nama;
					$tambahsurkon['namaDokter'] = $namaDokter;
					$tambahsurkon['namaJnsKontrol'] = $namaJnsKontrol;
					$tambahsurkon['namaPoliAsal'] = $namaPoliAsal;
					$tambahsurkon['namaPoliTujuan'] = $namaPoliTujuan;
					$tambahsurkon['noKartu'] = $noKartu;
					$tambahsurkon['noSepAsalKontrol'] = $noSepAsalKontrol;
					$tambahsurkon['noSuratKontrol'] = $noSuratKontrol;
					$tambahsurkon['poliAsal'] = $poliAsal;
					$tambahsurkon['poliTujuan'] = $poliTujuan;
					$tambahsurkon['terbitSEP'] = $terbitSEP;
					$tambahsurkon['tglRencanaKontrol'] = $tglRencanaKontrol;
					$tambahsurkon['tglSEP'] = $tglSEP;
					$tambahsurkon['tglTerbitKontrol'] = $tglTerbitKontrol;
					$tambahsurkon['tgl_insert'] = $tgl_insert;
					$tambahsurkon['status_detail'] = '0';

					$second_db->insert('bpjs_get_surat_kontrol', $tambahsurkon);
				}
			}
		}


		// menampilkan hasil pencarian dalam bentuk tabel
		if (isset($data['list'])) {
			// Ambil data list
			$list = $data['list'];
		} else {
			echo "Data tidak ditemukan.";
			exit;
		}
		$surkon = "";
		$surkon .= '
		<table class="table table-striped table-bordered order-column" id="sample_3">
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
		<th>
		<center>Aksi</center>
		</th>
		</tr>
		</thead>
		<tbody>';
		foreach ($list as $item) {
			$surkon .= '<tr class="dd gradeX">
			<td>' . $item['noSuratKontrol'] . '</td>
			<td>' . $item['tglRencanaKontrol'] . '</td>
			<td>' . $item['nama'] . '</td>
			<td>' . $item['namaPoliTujuan'] . '</td>
			<td>' . $item['namaDokter'] . '</td>
			<td><a class="btn btn-primary" onclick="get_via_nosurkon(\'' . $item['noSuratKontrol'] . '\')" >Via no Surat Kontrol</a> <br/><br/>
			<a class="btn btn-danger" onclick="cetak_surkon_list(\'' . $item['noSuratKontrol'] . '\')" >Cetak Surat Kontrol</a></td>
			</tr>';
		}
		$surkon .= '
		</tbody>
		</table>';

		echo $surkon;
			// ending tampilkan dalam tabel
	}

		// tarik data surat kontrol berdasarkan nosuratkontrol
	function get_nosurkon()
	{
		$nosuratkontol = $this->input->post('nosuratkontol');

		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);

		date_default_timezone_set("UTC");
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));



		$cek_consid_v = $second_db->query("SELECT
			dbs.consid,
			dbs.SecretKey,
			dbs.userKey,
			dbs.kodePPK,
			dbs.url,
			dbs.keterangan,
			dbs.`status`
			FROM decrypt_bpjs dbs
			WHERE 
			1 = 1
			AND keterangan = 'vclaim'")->row();
		$consid_v = $cek_consid_v->consid;
		$SecretKey_v = $cek_consid_v->SecretKey;
		$userKey_v = $cek_consid_v->userKey;

		$key = $consid_v . $SecretKey_v . $tStamp;

		$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

		//base64 encode
		$encodedSignature = base64_encode($signature);

		$headers = array(
			'X-cons-id: ' . $consid_v,
			'X-timestamp: ' . $tStamp,
			'X-signature: ' . $encodedSignature,
			'user_key: ' . $userKey_v,
			'Content-Type: Application/x-www-form-urlencoded'
			// 'Content-Type: application/json'
		);

		$url_v = $cek_consid_v->url;
		$url = $url_v . "/RencanaKontrol/noSuratKontrol/" . $nosuratkontol;

		// echo $url;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$string = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		$arr = json_decode($string, true);
		$string = $arr['response'];
		// echo $string;
		$string_dec = $this->stringDecrypt($key, $string);

		// var_dump($string_dec);
		$hasil = json_decode($string_dec, true);

		$noSuratKontrol = $hasil['noSuratKontrol'];
		$kodeDokter = $hasil['kodeDokter'];
		$namaDokter = $hasil['namaDokter'];
		$poliTujuan = $hasil['poliTujuan'];
		$namaPoliTujuan = $hasil['namaPoliTujuan'];
		$tglRencanaKontrol = $hasil['tglRencanaKontrol'];
		$nosep = $hasil['sep']['noSep'];

		$three_db = $this->load->database('three', TRUE);
		// <input type="date" name="edit_tanggal_surkon" id="edit_tanggal_surkon" type="text" class="form-control" value="' . date('Y-m-d') . '">

		$output_soap = "";
		$output_soap .= '
		<input type="date" name="edit_tanggal_surkon" id="edit_tanggal_surkon" type="text" class="form-control" value="' . date('Y-m-d') . '">
		<input type="hidden" name="noSEP_kontrol" id="noSEP_kontrol" value="' . $nosep . '">
		<input type="hidden" name="no_kontrol" id="no_kontrol" value="' . $noSuratKontrol . '">
		<input type="text" name="no_kontrol_xx" id="no_kontrol_xx" value="' . $noSuratKontrol . '" disabled>			
		<input type="hidden" name="kd_dpjp_bpjs" id="kd_dpjp_bpjs" value="' . $kodeDokter . '">
		<input type="text" name="nama_dokter_bpjs" id="nama_dokter_bpjs" value="' . $namaDokter . '" disabled>
		<input type="hidden" name="kd_poli_bpjs" id="kd_poli_bpjs" value="' . $poliTujuan . '">
		<input type="text" name="nama_poli_bpjs" id="nama_poli_bpjs" value="' . $namaPoliTujuan . '" disabled>
		<input type="text" name="no_sep_bpjs" id="no_sep_bpjs" value="' . $nosep . '" disabled>
		<select class="bs-select form-control" id="EMR_nama_dokter_kontrol_bpjs" data-live-search="true" data-size="15">
		<option value="' . $kodeDokter . '" selected disabled>' . $namaDokter . '</option>';
		$dataPoli  = $three_db->query("SELECT bpj.DPJP, med.nama FROM BPJS_Kontrol bpj 
			INNER JOIN medis med ON med.kdDPJP = bpj.DPJP
			WHERE bpj.KdPoli = '$poliTujuan'
			GROUP BY bpj.DPJP, med.nama
			");
		foreach ($dataPoli->result() as $row) {
			$output_soap .= '<option value="' . trim($row->DPJP) . '">' . $row->nama . '</option>';
		}
		$output_soap .= '
		</select>
		';
		echo $output_soap;
	}

	public function cek_kuota_surkon(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$tanggal_surkon = $this->input->post('tanggal_surkon');
		$no_surkon = $this->input->post('no_surkon');
		$nosep = $this->input->post('nosep');
		$kodedokter = $this->input->post('kodedokter');
		$kodepoli = $this->input->post('kodepoli');
		$daftar_hari = array(
			'Sunday' => '7',
			'Monday' => '1',
			'Tuesday' => '2',
			'Wednesday' => '3',
			'Thursday' => '4',
			'Friday' => '5',
			'Saturday' => '6'
		);
		$namahari = date('l', strtotime($tanggal_surkon));
		$nama_hari_n = $daftar_hari[$namahari];
		$cek_jumlah_surkon = $three_db->query("SELECT COUNT(x.NoKontrol) AS jumlah FROM (
			SELECT bk.NoKontrol FROM BPJS_Kontrol bk 
			WHERE bk.IsDelete = '0' 
			AND bk.DPJP = '$kodedokter'
			AND CAST(bk.TglKontrol AS DATE) = '$tanggal_surkon'

			UNION ALL

			SELECT bk.NoKontrol FROM Umum_kontrol bk 
			WHERE bk.IsDelete = '0' 
			AND bk.DPJP = '$kodedokter'
			AND bk.JenisKontrol = 'BPJS'
			AND CAST(bk.TglKontrol AS DATE) = '$tanggal_surkon'
		)x")->row();
		$cek_kuota_dokter = $three_db->query("SELECT TOP(1) bjd.kapasitaspasien from bpjs_jadwal_dokter bjd 
			WHERE bjd.kodedokter = '$kodedokter'
			AND bjd.Hari = '$nama_hari_n'
			ORDER BY bjd.tgl_insert DESC;");
		if($cek_kuota_dokter->num_rows() == 0){
			$data_kuota = '0';
		}else{
			$cek_kuota_dokter_n = $cek_kuota_dokter->row();
			$data_kuota = $cek_kuota_dokter_n->kapasitaspasien;
		}
		$jumlah_surkon = $cek_jumlah_surkon->jumlah;
		if($jumlah_surkon < $data_kuota){
			$data['code_sukses'] = '200';
		}else{
			$data['code_sukses'] = '201';
		}
		$data['tanggal_kontrol'] = date('d-M-Y', strtotime($tanggal_surkon));;
		echo json_encode($data);

	}

	// update perubahan surat kontrol (tanggal kontrol dan dokter)
	public function update_surkon_bpjs()
	{
		$tanggal_surkon = $this->input->post('tanggal_surkon');
		$no_surkon = $this->input->post('no_surkon');
		$nosep = $this->input->post('nosep');
		$kodedokter = $this->input->post('kodedokter');
		$kodepoli = $this->input->post('kodepoli');
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);


		date_default_timezone_set("UTC");
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

		$cek_consid_v = $second_db->query("SELECT
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
		// echo $consid_v;

		$key = $consid_v . $SecretKey_v . $tStamp;
		// echo $key;

		$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

		//base64 encode
		$encodedSignature = base64_encode($signature);

		$headers = array(
			'X-cons-id: ' . $consid_v,
			'X-timestamp: ' . $tStamp,
			'X-signature: ' . $encodedSignature,
			'user_key: ' . $userKey_v,
			'Content-Type: Application/x-www-form-urlencoded'
		);

		$arr = [
			"request" => [
				"noSuratKontrol" => "$no_surkon",
				"noSEP" => "$nosep",
				"kodeDokter" => "$kodedokter",
				"poliKontrol" => "$kodepoli",
				"tglRencanaKontrol" => "$tanggal_surkon",
				"user" => "APM_JKN_ROYALSB"
			]
		];

		$json = json_encode($arr);

		$url_v = $cek_consid_v->url;
		// echo $url_v;

		$url = $url_v . "RencanaKontrol/Update";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		$string_1 = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		$arr = json_decode($string_1, true);
		$string = $arr['response'];
		$string_dec = $this->stringDecrypt($key, $string);

		// decode hasil string decript 
		$arr_dec = json_decode($string_dec, true);

		// ambil data dari object json
		$noSuratKontrol = $arr_dec['noSuratKontrol'];
		$tglRencanaKontrol = $arr_dec['tglRencanaKontrol'];
		$namaDokter = $arr_dec['namaDokter'];

		// ambil kode dan pesan dari hasil lemparan api
		$string_code = $arr['metaData']['code'];
		$string_msg = $arr['metaData']['message'];

		// $tglinsert = date('Y-m-d H:i:s');
		// date_default_timezone_set('Asia/Jakarta');


		// kondisi kode = 200
		if ($string_code == '200') {
			/* Ambil datanya dan update tgl kontrol dan dokter di BPJS kontrol berdasarkan nosuratkontrol*/
			date_default_timezone_set('Asia/Jakarta');
			$Tglupdate = date('Y-m-d H:i:s');
			$UsrUpdate = "APM_JKN";
			$three_db->query("UPDATE BPJS_Kontrol SET status_cetak = '1', TglKontrol = '$tglRencanaKontrol', DPJP = '$kodedokter', NmDPJP = '$namaDokter', TglUpdate = '$Tglupdate', UsrUpdate = '$UsrUpdate' WHERE NoKontrol = '$noSuratKontrol'");
			$second_db->query("UPDATE bpjs_get_surat_kontrol SET kodeDokter = '$kodedokter', namaDokter = '$namaDokter', tglRencanaKontrol = '$tglRencanaKontrol' WHERE NoSuratKontrol = '$noSuratKontrol'");
			// echo 'ini kode 200 : ' . $string_code . ' dan msg : ' . $string_msg;
			$data['Keterangan'] = 'Surat Kontrol Berhasil di Update';
			$data['pesan'] = $string_msg;
			$data['kode'] = $string_code;
			$data['tglRencanaKontrol'] = $tglRencanaKontrol;
		}
		//  kondisi msg = ''
		elseif ($string_msg == null || $string_msg == '') {
			/* melakukan perulangan while */
			$i = 1;
			while ($i < 7) {
				date_default_timezone_set("UTC");
				$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));

				$cek_consid_v = $second_db->query("SELECT
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
				// echo $consid_v;

				$key = $consid_v . $SecretKey_v . $tStamp;
				// echo $key;

				$signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

				//base64 encode
				$encodedSignature = base64_encode($signature);

				$headers = array(
					'X-cons-id: ' . $consid_v,
					'X-timestamp: ' . $tStamp,
					'X-signature: ' . $encodedSignature,
					'user_key: ' . $userKey_v,
					'Content-Type: Application/x-www-form-urlencoded'
				);

				$arr = [
					"request" => [
						"noSuratKontrol" => "$no_surkon",
						"noSEP" => "$nosep",
						"kodeDokter" => "$kodedokter",
						"poliKontrol" => "$kodepoli",
						"tglRencanaKontrol" => "$tanggal_surkon",
						"user" => "APM_JKN_ROYALSB"
					]
				];

				$json = json_encode($arr);

				$url_v = $cek_consid_v->url;
				// echo $url_v;

				$url = $url_v . "RencanaKontrol/Update";

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 3);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

				$string_1 = curl_exec($ch);
				$err = curl_error($ch);
				curl_close($ch);

				$arr = json_decode($string_1, true);
				$string = $arr['response'];
				$string_dec = $this->stringDecrypt($key, $string);

				// decode hasil string decript 
				$arr_dec = json_decode($string_dec, true);
				// ambil data dari object json
				$noSuratKontrol = $arr_dec['noSuratKontrol'];
				$tglRencanaKontrol = $arr_dec['tglRencanaKontrol'];
				$namaDokter = $arr_dec['namaDokter'];

				// ambil kode dan pesan dari hasil lemparan api
				$string_code = $arr['metaData']['code'];
				$string_msg = $arr['metaData']['message'];

				// date_default_timezone_set('Asia/Jakarta');

				/* ketika string kode = 200 */
				if ($string_code == '200') {
					date_default_timezone_set('Asia/Jakarta');
					$Tglupdate = date('Y-m-d H:i:s');
					$UsrUpdate = "APM_JKN";
					$three_db->query("UPDATE BPJS_Kontrol SET status_cetak = '1', TglKontrol = '$tglRencanaKontrol', DPJP = '$kodedokter', NmDPJP = '$namaDokter', TglUpdate = '$Tglupdate', UsrUpdate = '$UsrUpdate' WHERE NoKontrol = '$noSuratKontrol'");
					$second_db->query("UPDATE bpjs_get_surat_kontrol SET kodeDokter = '$kodedokter', namaDokter = '$namaDokter', tglRencanaKontrol = '$tglRencanaKontrol' WHERE NoSuratKontrol = '$noSuratKontrol'");
					$i = 11;
					$data['Keterangan'] = 'Surat Kontrol Berhasil di Update';
					$data['pesan'] = $string_msg;
					$data['kode'] = $string_code;
					$data['tglRencanaKontrol'] = $tglRencanaKontrol;
				} else {
					// menampilkan pesan selain 200
					$i++;
					$data['pesan'] = $string_msg;
					$data['kode'] = $string_code;
				}
			}
		}
		//  kondisi kode != 200 | menampilkan errornya dimodal
		elseif ($string_code != 200) {
			$data['pesan'] = $string_msg;
			$data['kode'] = $string_code;
			// echo 'selain kode null dan 200 : ' . $string_code . ' dan msg : ' . $string_msg;
		}

		echo json_encode($data);
	}

	function tinjau_no_rm()
	{
		date_default_timezone_set('Asia/Jakarta');
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);

		$tanggal_hari_ini = date("Y-m-d");
		$no_rm = $this->input->post('no_rm');

		$list_data_sep_xx = $five_db->query("SELECT
			egdk.noreg,
			egdk.norm,
			egdk.nama_pasien, 
			egdk.nama_dokter, 
			egdk.nminstansi
			FROM
			EMR_GET_DATA_KUNJUNGAN AS egdk
			WHERE
			1 = 1
			AND CAST(egdk.tgl_reg AS DATE) = '$tanggal_hari_ini'
			AND egdk.norm = '$no_rm'
			AND egdk.noreg LIKE '%OP%'
			AND egdk.batal = '0'
			AND egdk.nminstansi = 'BPJS KESEHATAN'
			ORDER BY
			egdk.tgl_reg DESC		
			");

		// menampilkan hasil pencarian dalam bentuk tabel
		if ($list_data_sep_xx->num_rows() > 0) {

			$list_data_sep_xx = $list_data_sep_xx->row();

			$output_soap = "";
			$output_soap .= '
			<p>No Reg : <input type="text" name="noreg" id="noreg" value="' . $list_data_sep_xx->noreg . '" disabled></p>
			<p>No RM : <input type="text" name="norm" id="norm" value="' . $list_data_sep_xx->norm . '" disabled></p>
			<p>Nama Pasien : <input type="text" name="nama_pasien" id="nama_pasien" value="' . $list_data_sep_xx->nama_pasien . '" disabled></p>
			<p>Nama Dokter : <input type="text" name="nama_dokter" id="nama_dokter" value="' . $list_data_sep_xx->nama_dokter . '" disabled style="width: 50%;"></p>
			<p>Nama Instansi : <input type="text" name="nminstansi" id="nminstansi" value="' . $list_data_sep_xx->nminstansi . '" disabled></p>
			<br>
			<a class="btn btn-danger" onclick="ambil_hari_ini(\'' . $list_data_sep_xx->noreg . '\', \'' . $list_data_sep_xx->norm . '\')">Ambil Hari Ini</a>
			<a class="btn btn-warning" onclick="ambil_besok(\'' . $list_data_sep_xx->noreg . '\', \'' . $list_data_sep_xx->norm . '\')" style="margin-left: 1%;">Ambil Besok</a>
			<a class="btn btn-primary" onclick="kirim_beetu(\'' . $list_data_sep_xx->noreg . '\', \'' . $list_data_sep_xx->norm . '\')" style="margin-left: 1%;">Kirim Obat</a>
			<a class="btn btn-success" data-dismiss="modal" style="margin-left: 1%;">Tutup</a>';

			$data['daftar'] = '1';
			$data['list'] = $output_soap;

		} else {
			$data['daftar'] = '0';
			$data['list'] = '';
		}

		echo json_encode($data);
	}

	function check_no_kartu()
	{
		date_default_timezone_set('Asia/Jakarta');
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);

		$tanggal_hari_ini = date("Y-m-d");
		// $tanggal_hari_ini = "2025-02-08";
		$no_kartu_bpjs = $this->input->post('no_kartu_bpjs');

		$list_data_sep_xx = $five_db->query("SELECT
			egdk.noreg,
			egdk.norm,
			egdk.nama_pasien, 
			egdk.nama_dokter, 
			egdk.nminstansi
			FROM
			EMR_KUNJUNGAN_PASIEN_BPJS AS ekpb
			LEFT JOIN EMR_GET_DATA_KUNJUNGAN egdk ON egdk.noreg = ekpb.NOREG
			WHERE
			1 = 1
			AND CAST(egdk.tgl_reg AS DATE) = '$tanggal_hari_ini'
			AND ekpb.noKartuPasien = '$no_kartu_bpjs'
			AND egdk.noreg LIKE '%OP%'
			AND egdk.batal = '0'
			AND egdk.nminstansi = 'BPJS KESEHATAN'
			ORDER BY
			ekpb.tglInsert DESC		
			");

		// menampilkan hasil pencarian dalam bentuk tabel
		if ($list_data_sep_xx->num_rows() > 0) {

			$list_data_sep_xx = $list_data_sep_xx->row();

			$output_soap = "";
			$output_soap .= '
			<p>No Reg : <input type="text" name="noreg" id="noreg" value="' . $list_data_sep_xx->noreg . '" disabled></p>
			<p>No RM : <input type="text" name="norm" id="norm" value="' . $list_data_sep_xx->norm . '" disabled></p>
			<p>Nama Pasien : <input type="text" name="nama_pasien" id="nama_pasien" value="' . $list_data_sep_xx->nama_pasien . '" disabled style="width: 30%;"></p>
			<p>Nama Dokter : <input type="text" name="nama_dokter" id="nama_dokter" value="' . $list_data_sep_xx->nama_dokter . '" disabled style="width: 30%;"></p>
			<p>Nama Instansi : <input type="text" name="nminstansi" id="nminstansi" value="' . $list_data_sep_xx->nminstansi . '" disabled></p>
			<br>
			<a class="btn btn-danger" onclick="ambil_hari_ini(\'' . $list_data_sep_xx->noreg . '\', \'' . $list_data_sep_xx->norm . '\', \'' . $no_kartu_bpjs . '\')">Ambil Hari Ini</a>
			<a class="btn btn-warning" onclick="ambil_besok(\'' . $list_data_sep_xx->noreg . '\', \'' . $list_data_sep_xx->norm . '\', \'' . $no_kartu_bpjs . '\')" style="margin-left: 1%;">Ambil Besok</a>
			<a class="btn btn-primary" onclick="kirim_beetu(\'' . $list_data_sep_xx->noreg . '\', \'' . $list_data_sep_xx->norm . '\', \'' . $no_kartu_bpjs . '\')" style="margin-left: 1%;">Kirim Beetu</a>
			<a class="btn btn-success" data-dismiss="modal" style="margin-left: 1%;">Tutup</a>';

			$data['daftar'] = '1';
			$data['list'] = $output_soap;

		} else {
			$data['daftar'] = '0';
			$data['list'] = '';
		}

		echo json_encode($data);
	}

	function check_nama()
	{
		date_default_timezone_set('Asia/Jakarta');
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);

		$tanggal_hari_ini = date('Y-m-d');
		$nama_bpjs = $this->input->post('nama_bpjs');

		$cek_data_booking_n =  $three_db->query("SELECT
			LTRIM( RTRIM(a.noreg) ) AS noreg,
			LTRIM( RTRIM(a.norm) ) AS norm,
			LTRIM( RTRIM(a.kdpoli) ) AS kdpoli,
			LTRIM( RTRIM(c.nmpoli) ) AS nmpoli,
			LTRIM( RTRIM(b.nama) ) AS namapasien,
			b.kdseks,
			CONVERT ( VARCHAR ( 11 ), b.tgllahir, 106 ) AS tgllahir,
			LTRIM( RTRIM(b.jalan) ) AS alamat,
			LTRIM( RTRIM(d.nama) ) AS namadokter,
			e.nminstansi, 
			f.nokartu 
			FROM
			rj_reg a
			LEFT JOIN Pasien b ON a.norm= b.norm
			LEFT JOIN rj_poliklinik c ON a.kdpoli= c.kdpoli
			LEFT JOIN medis d ON a.kddokter= d.kode
			LEFT JOIN instansi e ON a.kdinstansi= e.kdinstansi
			LEFT JOIN BPJS_reg f ON f.Noreg = a.noreg
			WHERE
			1 = 1
			AND b.nama LIKE '%$nama_bpjs%'
			AND CAST(a.tglregistrasi AS DATE) = '$tanggal_hari_ini'
			AND a.batal = '0'");

		// menampilkan hasil pencarian dalam bentuk tabel
		if ($cek_data_booking_n->num_rows() > 0) {

			$output_soap = "";
			foreach ($cek_data_booking_n->result_array() as $item) {
				$output_soap .= '
				<tr class="dd gradeX">
				<td>' . $item['noreg'] . '</td>
				<td>' . $item['norm'] . '</td>
				<td>' . $item['namapasien'] . '</td>
				<td>' . $item['nmpoli'] . '</td>
				<td>' . $item['namadokter'] . '</td>
				<td>' . $item['alamat'] . '</td>
				<td>' . $item['tgllahir'] . '</td>
				<td><a class="btn btn-danger" onclick="ambil_hari_ini(\'' . $item['noreg'] . '\', \'' . $item['norm'] . '\', \'' . $item['nokartu'] . '\')">Ambil Hari Ini</a><br>
				<a class="btn btn-warning" onclick="ambil_besok(\'' . $item['noreg'] . '\', \'' . $item['norm'] . '\', \'' . $item['nokartu'] . '\')" style="margin-left: 1%;margin-top: 5%;">Ambil Besok</a>
				<a class="btn btn-primary" onclick="kirim_beetu(\'' . $item['noreg'] . '\', \'' . $item['norm'] . '\', \'' . $item['nokartu'] . '\')" style="margin-left: 1%;margin-top: 5%;">Kirim Beetu</a>
				</td>
				</tr>';
			}
			$output_soap .= '
			<a class="btn btn-success" data-dismiss="modal" style="margin-left: 1%;">Tutup</a>';

			$data['daftar'] = '1';
			$data['list'] = $output_soap;

		} else {
			$data['daftar'] = '0';
			$data['list'] = '';
		}

		echo json_encode($data);
	}

	function insert_antrian_farmasi()
	{
		date_default_timezone_set('Asia/Jakarta');
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);

		$tanggal_hari_ini = date('Y-m-d');
		$tglinsert = date('Y-m-d H:i:s');
		$noreg = $this->input->post('noreg');
		$norm = $this->input->post('norm');
		$nokartu = $this->input->post('nokartu');
		$keterangan = $this->input->post('keterangan');
		$kode_form = $this->input->post('kode_form');

		$list_data_appoint = $three_db->query("SELECT
			baf.noapp
			FROM
			BPJSAntrean AS baf
			LEFT JOIN rj_appointment rja ON rja.noapp = baf.noapp
			WHERE
			1 = 1
			AND baf.tgl = '$tanggal_hari_ini'
			AND baf.nokartu = '$nokartu'
			AND rja.batal = '0'	
			");

		$list_data_antrian = $three_db->query("SELECT
			baf.noreg,
			baf.no_antrian,
			baf.keterangan,
			baf.form_no,
			baf.form_ket
			FROM
			bpjs_antrian_farmasi AS baf
			WHERE
			1 = 1
			AND CAST(baf.tgl_insert AS DATE) = '$tanggal_hari_ini'
			AND baf.noreg = '$noreg'	
			");

		if ($list_data_appoint->num_rows() > 0) {
			$kodebooking = $list_data_appoint->row()->noapp;

			// $cek_obat_racik = $five_db->query("SELECT 
			// 	STRING_AGG(CONVERT(NVARCHAR(max), CONCAT('R/ ', eor.OBAT_RACIK, CHAR(13))), CHAR(13)) AS nama_obat_racik 
			// 	FROM EMR_OBAT_RACIK eor
			// 	LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eor.ID_PEMERIKSAAN
			// 	WHERE
			// 	eup.NOREG = '$noreg' 
			// 	AND eor.STATUS_OBAT_RACIK = 'BARU'
			// 	AND 1 = 1");

			// if($cek_obat_racik->num_rows() > 0){
			// 	$jenisresep = 'racikan';
			// } else {
			// 	$cek_resep = $five_db->query("SELECT
			// 		x.NAMA_OBAT_SATUAN
			// 		FROM
			// 		(
			// 		SELECT
			// 		eos.NAMA_OBAT_SATUAN
			// 		FROM
			// 		EMR_OBAT_SATUAN eos
			// 		LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eos.ID_PEMERIKSAAN 
			// 		WHERE
			// 		eup.NOREG = '$noreg' 
			// 		AND eos.STATUS_OBAT_SATUAN = 'BARU'
			// 		AND 1 = 1 
			// 		UNION ALL

			// 		SELECT
			// 		eor.OBAT_RACIK AS NAMA_OBAT_SATUAN
			// 		FROM
			// 		EMR_OBAT_RACIK eor
			// 		LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eor.ID_PEMERIKSAAN
			// 		WHERE
			// 		eup.NOREG = '$noreg' 
			// 		AND eor.STATUS_OBAT_RACIK = 'BARU'
			// 		AND 1 = 1
			// 		)x");

			// 	if($cek_resep->num_rows() > 0){
			// 		$jenisresep = 'racikan';
			// 	} else {
			// 		$jenisresep = 'non racikan';
			// 	}
			// }

			$cek_no_antrian = $three_db->query("SELECT
				MAX(baf.no_antrian) AS no_antrian
				FROM
				bpjs_antrian_farmasi AS baf
				WHERE
				1 = 1
				AND CAST(baf.tgl_insert AS DATE) = '$tanggal_hari_ini'
				AND form_ket = '$kode_form'	
				");

			if ($cek_no_antrian->num_rows() > 0) {

				$no_antrian = $cek_no_antrian->row()->no_antrian + 1;

			} else {

				$no_antrian = '1';

			}

			$form_no = ''.$kode_form.''. $no_antrian;

			if ($list_data_antrian->num_rows() > 0) {

				$nomorantrean = $list_data_antrian->row()->no_antrian;

			} else {
				$nomorantrean = $no_antrian;
			}

			// date_default_timezone_set("UTC");
			// $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
			// $cek_consid_v = $five_db->query("SELECT
			// 	dbs.consid,
			// 	dbs.SecretKey,
			// 	dbs.userKey,
			// 	dbs.kodePPK,
			// 	dbs.url,
			// 	dbs.keterangan,
			// 	dbs.status
			// 	FROM decrypt_bpjs dbs
			// 	WHERE 
			// 	1 = 1
			// 	AND keterangan = 'antrian'")->row();
			// $consid_v = $cek_consid_v->consid;
			// $SecretKey_v = $cek_consid_v->SecretKey;
			// $userKey_v = $cek_consid_v->userKey;

			// $key = $consid_v . $SecretKey_v . $tStamp;

			// $signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

			// //base64 encode
			// $encodedSignature = base64_encode($signature);

			// $headers = array(
			// 	'X-cons-id: ' . $consid_v,
			// 	'X-timestamp: ' . $tStamp,
			// 	'X-signature: ' . $encodedSignature,
			// 	'user_key: ' . $userKey_v,
			// 	'Content-Type: application/json',
			// 	);

			// $url_v = $cek_consid_v->url;

			// $arr = array(
			// 	"kodebooking" => "$kodebooking",
			// 	"jenisresep" => "$jenisresep",
			// 	"nomorantrean" => "$nomorantrean",
			// 	"keterangan" => "Antrian Farmasi $kodebooking RS Royal",
			// 	);

			// $json = json_encode($arr);
			// // echo $json;

			// $url_v = $cek_consid_v->url;

			// $url = $url_v."antrean/farmasi/add";

			// $ch = curl_init();

			// curl_setopt($ch, CURLOPT_URL, $url);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			// curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

			// $string_1 = curl_exec($ch);
			// $err = curl_error($ch);
			// curl_close($ch);

			// $arr = json_decode($string_1, true);
			// $string = $arr['metadata'];
			// $string_mess = $arr['metadata']['message'];
			// $string_code = $arr['metadata']['code'];

			// $five_db->query("INSERT INTO bpjs_histori_antrian_farmasi (
			// 	noreg,
			// 	norm,
			// 	nobooking,
			// 	noantrian,
			// 	code,
			// 	message,
			// 	tglinsert,
			// 	usrinsert
			// 	)
			// 	VALUES
			// 	(
			// 	'$noreg',
			// 	'$norm',
			// 	'$kodebooking',
			// 	'$nomorantrean',
			// 	'$string_code',
			// 	'$string_mess',
			// 	'$tglinsert',
			// 	'APM'
			// 	);");

			if ($list_data_antrian->num_rows() > 0) {
				$form_ket = $list_data_antrian->row()->form_ket;

				if($form_ket == $kode_form){
					$three_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert' WHERE noreg = '$noreg'");
					$five_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert' WHERE noreg = '$noreg'");
				}else{
					$cek_no_antrian = $three_db->query("SELECT
						MAX(baf.no_antrian) AS no_antrian
						FROM
						bpjs_antrian_farmasi AS baf
						WHERE
						1 = 1
						AND CAST(baf.tgl_insert AS DATE) = '$tanggal_hari_ini'
						AND form_ket = '$kode_form'	
						");

					if ($cek_no_antrian->num_rows() > 0) {

						$no_antrian = $cek_no_antrian->row()->no_antrian + 1;

					} else {

						$no_antrian = '1';

					}

					$form_no = ''.$kode_form.''. $no_antrian;
					$three_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert', form_ket = '$kode_form', no_antrian = '$no_antrian', form_no = '$form_no' WHERE noreg = '$noreg'");
					$five_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert', form_ket = '$kode_form', no_antrian = '$no_antrian', form_no = '$form_no' WHERE noreg = '$noreg'");

					$five_db->query("INSERT INTO bpjs_antrian_farmasi_history (
						noreg,
						norm,
						tgl_insert,
						no_antrian,
						form_no,
						NoJo,
						keterangan,
						form_ket
						)
						VALUES
						(
						'$noreg',
						'$norm',
						'$tglinsert',
						'$no_antrian',
						'$form_no',
						'',
						'$keterangan',
						'$kode_form'
					)");

				}			

				$data['daftar'] = '1';

			} else {
				$cek_id_worklist = $three_db->query("SELECT TOP(1) 
					mdf.NoJO FROM
					md_WorkListHD mdf
					WHERE mdf.NoReg = '$noreg'
					AND mdf.KdPMedis = 'FAR'
					ORDER BY mdf.TglInsert DESC");
				if($cek_id_worklist->num_rows() > '0'){
					$cek_id_worklist = $cek_id_worklist->row();
					$id_workList = $cek_id_worklist->NoJO;

				}else {
					$id_workList = '';

				}

				$three_db->query("INSERT INTO bpjs_antrian_farmasi (
					noreg,
					norm,
					tgl_insert,
					no_antrian,
					form_no,
					NoJo,
					keterangan,
					form_ket
					)
					VALUES
					(
					'$noreg',
					'$norm',
					'$tglinsert',
					'$no_antrian',
					'$form_no',
					'$id_workList',
					'$keterangan',
					'$kode_form'
				)");

				$five_db->query("INSERT INTO bpjs_antrian_farmasi (
					noreg,
					norm,
					tgl_insert,
					no_antrian,
					form_no,
					NoJo,
					keterangan,
					form_ket
					)
					VALUES
					(
					'$noreg',
					'$norm',
					'$tglinsert',
					'$no_antrian',
					'$form_no',
					'$id_workList',
					'$keterangan',
					'$kode_form'
				)");

				$five_db->query("INSERT INTO bpjs_antrian_farmasi_history (
					noreg,
					norm,
					tgl_insert,
					no_antrian,
					form_no,
					NoJo,
					keterangan,
					form_ket
					)
					VALUES
					(
					'$noreg',
					'$norm',
					'$tglinsert',
					'$no_antrian',
					'$form_no',
					'$id_workList',
					'$keterangan',
					'$kode_form'
				)");

				$data['daftar'] = '0';
			}


		} else {
			$list_data_appoint = $second_db->query("SELECT
				bta.kode_booking AS noapp
				FROM
				bpjs_tambah_antrian AS bta
				WHERE
				1 = 1
				AND CAST(bta.tgl_insert AS DATE) = '$tanggal_hari_ini'
				AND bta.no_kartu = '$nokartu'
				AND bta.code_bpjs IN ('200', '208')
				LIMIT 1");

			if($list_data_appoint->num_rows() > 0){
				$kodebooking = $list_data_appoint->row()->noapp;

				// $cek_obat_racik = $five_db->query("SELECT 
				// 	STRING_AGG(CONVERT(NVARCHAR(max), CONCAT('R/ ', eor.OBAT_RACIK, CHAR(13))), CHAR(13)) AS nama_obat_racik 
				// 	FROM EMR_OBAT_RACIK eor
				// 	LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eor.ID_PEMERIKSAAN
				// 	WHERE
				// 	eup.NOREG = '$noreg' 
				// 	AND eor.STATUS_OBAT_RACIK = 'BARU'
				// 	AND 1 = 1");

				// if($cek_obat_racik->num_rows() > 0){
				// 	$jenisresep = 'racikan';
				// } else {
				// 	$cek_resep = $five_db->query("SELECT
				// 		x.NAMA_OBAT_SATUAN
				// 		FROM
				// 		(
				// 		SELECT
				// 		eos.NAMA_OBAT_SATUAN
				// 		FROM
				// 		EMR_OBAT_SATUAN eos
				// 		LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eos.ID_PEMERIKSAAN 
				// 		WHERE
				// 		eup.NOREG = '$noreg' 
				// 		AND eos.STATUS_OBAT_SATUAN = 'BARU'
				// 		AND 1 = 1 
				// 		UNION ALL

				// 		SELECT
				// 		eor.OBAT_RACIK AS NAMA_OBAT_SATUAN
				// 		FROM
				// 		EMR_OBAT_RACIK eor
				// 		LEFT JOIN EMR_UTAMA_PERIKSA eup ON eup.ID_PEMERIKSAAN = eor.ID_PEMERIKSAAN
				// 		WHERE
				// 		eup.NOREG = '$noreg' 
				// 		AND eor.STATUS_OBAT_RACIK = 'BARU'
				// 		AND 1 = 1
				// 		)x");

				// 	if($cek_resep->num_rows() > 0){
				// 		$jenisresep = 'racikan';
				// 	} else {
				// 		$jenisresep = 'non racikan';
				// 	}
				// }
				$cek_no_antrian = $three_db->query("SELECT
					MAX(baf.no_antrian) AS no_antrian
					FROM
					bpjs_antrian_farmasi AS baf
					WHERE
					1 = 1
					AND CAST(baf.tgl_insert AS DATE) = '$tanggal_hari_ini'
					AND form_ket = '$kode_form'	
					");

				if ($cek_no_antrian->num_rows() > 0) {

					$no_antrian = $cek_no_antrian->row()->no_antrian + 1;

				} else {

					$no_antrian = '1';

				}

				$form_no = ''.$kode_form.''. $no_antrian;

				if ($list_data_antrian->num_rows() > 0) {

					$nomorantrean = $list_data_antrian->row()->no_antrian;

				} else {
					$nomorantrean = $no_antrian;
				}

				// date_default_timezone_set("UTC");
				// $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
				// $cek_consid_v = $five_db->query("SELECT
				// 	dbs.consid,
				// 	dbs.SecretKey,
				// 	dbs.userKey,
				// 	dbs.kodePPK,
				// 	dbs.url,
				// 	dbs.keterangan,
				// 	dbs.status
				// 	FROM decrypt_bpjs dbs
				// 	WHERE 
				// 	1 = 1
				// 	AND keterangan = 'antrian'")->row();
				// $consid_v = $cek_consid_v->consid;
				// $SecretKey_v = $cek_consid_v->SecretKey;
				// $userKey_v = $cek_consid_v->userKey;

				// $key = $consid_v . $SecretKey_v . $tStamp;

				// $signature = hash_hmac('sha256', $consid_v . "&" . $tStamp, $SecretKey_v, true);

				// //base64 encode
				// $encodedSignature = base64_encode($signature);

				// $headers = array(
				// 	'X-cons-id: ' . $consid_v,
				// 	'X-timestamp: ' . $tStamp,
				// 	'X-signature: ' . $encodedSignature,
				// 	'user_key: ' . $userKey_v,
				// 	'Content-Type: application/json',
				// 	);

				// $url_v = $cek_consid_v->url;

				// $arr = array(
				// 	"kodebooking" => "$kodebooking",
				// 	"jenisresep" => "$jenisresep",
				// 	"nomorantrean" => "$nomorantrean",
				// 	"keterangan" => "Antrian Farmasi $kodebooking RS Royal",
				// 	);

				// $json = json_encode($arr);
				// // echo $json;

				// $url_v = $cek_consid_v->url;

				// $url = $url_v."antrean/farmasi/add";

				// $ch = curl_init();

				// curl_setopt($ch, CURLOPT_URL, $url);
				// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// curl_setopt($ch, CURLOPT_TIMEOUT, 3);
				// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				// curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

				// $string_1 = curl_exec($ch);
				// $err = curl_error($ch);
				// curl_close($ch);

				// $arr = json_decode($string_1, true);
				// $string = $arr['metadata'];
				// $string_mess = $arr['metadata']['message'];
				// $string_code = $arr['metadata']['code'];

				// $five_db->query("INSERT INTO bpjs_histori_antrian_farmasi (
				// 	noreg,
				// 	norm,
				// 	nobooking,
				// 	noantrian,
				// 	code,
				// 	message,
				// 	tglinsert,
				// 	usrinsert
				// 	)
				// 	VALUES
				// 	(
				// 	'$noreg',
				// 	'$norm',
				// 	'$kodebooking',
				// 	'$nomorantrean',
				// 	'$string_code',
				// 	'$string_mess',
				// 	'$tglinsert',
				// 	'APM'
				// 	);");

				if ($list_data_antrian->num_rows() > 0) {

					$form_ket = $list_data_antrian->row()->form_ket;

					if($form_ket == $kode_form){
						$three_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert' WHERE noreg = '$noreg'");
						$five_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert' WHERE noreg = '$noreg'");
					}else{
						$cek_no_antrian = $three_db->query("SELECT
							MAX(baf.no_antrian) AS no_antrian
							FROM
							bpjs_antrian_farmasi AS baf
							WHERE
							1 = 1
							AND CAST(baf.tgl_insert AS DATE) = '$tanggal_hari_ini'
							AND form_ket = '$kode_form'	
							");

						if ($cek_no_antrian->num_rows() > 0) {

							$no_antrian = $cek_no_antrian->row()->no_antrian + 1;

						} else {

							$no_antrian = '1';

						}

						$form_no = ''.$kode_form.''. $no_antrian;
						$three_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert', form_ket = '$kode_form', no_antrian = '$no_antrian', form_no = '$form_no' WHERE noreg = '$noreg'");
						$five_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert', form_ket = '$kode_form', no_antrian = '$no_antrian', form_no = '$form_no' WHERE noreg = '$noreg'");

						$five_db->query("INSERT INTO bpjs_antrian_farmasi_history (
							noreg,
							norm,
							tgl_insert,
							no_antrian,
							form_no,
							NoJo,
							keterangan,
							form_ket
							)
							VALUES
							(
							'$noreg',
							'$norm',
							'$tglinsert',
							'$no_antrian',
							'$form_no',
							'',
							'$keterangan',
							'$kode_form'
						)");

					}	

					$data['daftar'] = '1';

				} else {
					$cek_id_worklist = $three_db->query("SELECT TOP(1) 
						mdf.NoJO FROM
						md_WorkListHD mdf
						WHERE mdf.NoReg = '$noreg'
						AND mdf.KdPMedis = 'FAR'
						ORDER BY mdf.TglInsert DESC");
					if($cek_id_worklist->num_rows() > '0'){
						$cek_id_worklist = $cek_id_worklist->row();
						$id_workList = $cek_id_worklist->NoJO;

					}else {
						$id_workList = '';

					}

					$three_db->query("INSERT INTO bpjs_antrian_farmasi (
						noreg,
						norm,
						tgl_insert,
						no_antrian,
						form_no,
						NoJo,
						keterangan,
						form_ket
						)
						VALUES
						(
						'$noreg',
						'$norm',
						'$tglinsert',
						'$no_antrian',
						'$form_no',
						'$id_workList',
						'$keterangan',
						'$kode_form'
					)");

					$five_db->query("INSERT INTO bpjs_antrian_farmasi (
						noreg,
						norm,
						tgl_insert,
						no_antrian,
						form_no,
						NoJo,
						keterangan,
						form_ket
						)
						VALUES
						(
						'$noreg',
						'$norm',
						'$tglinsert',
						'$no_antrian',
						'$form_no',
						'$id_workList',
						'$keterangan',
						'$kode_form'
					)");

					$five_db->query("INSERT INTO bpjs_antrian_farmasi_history (
						noreg,
						norm,
						tgl_insert,
						no_antrian,
						form_no,
						NoJo,
						keterangan,
						form_ket
						)
						VALUES
						(
						'$noreg',
						'$norm',
						'$tglinsert',
						'$no_antrian',
						'$form_no',
						'$id_workList',
						'$keterangan',
						'$kode_form'
					)");

					$data['daftar'] = '0';
				}
			} else {
				if ($list_data_antrian->num_rows() > 0) {
					
					$form_ket = $list_data_antrian->row()->form_ket;

					if($form_ket == $kode_form){
						$three_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert' WHERE noreg = '$noreg'");
						$five_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert' WHERE noreg = '$noreg'");
					}else{
						$cek_no_antrian = $three_db->query("SELECT
							MAX(baf.no_antrian) AS no_antrian
							FROM
							bpjs_antrian_farmasi AS baf
							WHERE
							1 = 1
							AND CAST(baf.tgl_insert AS DATE) = '$tanggal_hari_ini'
							AND form_ket = '$kode_form'	
							");

						if ($cek_no_antrian->num_rows() > 0) {

							$no_antrian = $cek_no_antrian->row()->no_antrian + 1;

						} else {

							$no_antrian = '1';

						}

						$form_no = ''.$kode_form.''. $no_antrian;
						$three_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert', form_ket = '$kode_form', no_antrian = '$no_antrian', form_no = '$form_no' WHERE noreg = '$noreg'");
						$five_db->query("UPDATE bpjs_antrian_farmasi SET keterangan = '$keterangan', tgl_update = '$tglinsert', form_ket = '$kode_form', no_antrian = '$no_antrian', form_no = '$form_no' WHERE noreg = '$noreg'");

						$five_db->query("INSERT INTO bpjs_antrian_farmasi_history (
							noreg,
							norm,
							tgl_insert,
							no_antrian,
							form_no,
							NoJo,
							keterangan,
							form_ket
							)
							VALUES
							(
							'$noreg',
							'$norm',
							'$tglinsert',
							'$no_antrian',
							'$form_no',
							'',
							'$keterangan',
							'$kode_form'
						)");

					}	

					$data['daftar'] = '1';

				} else {
					$cek_id_worklist = $three_db->query("SELECT TOP(1) 
						mdf.NoJO FROM
						md_WorkListHD mdf
						WHERE mdf.NoReg = '$noreg'
						AND mdf.KdPMedis = 'FAR'
						ORDER BY mdf.TglInsert DESC");
					if($cek_id_worklist->num_rows() > '0'){
						$cek_id_worklist = $cek_id_worklist->row();
						$id_workList = $cek_id_worklist->NoJO;

					}else {
						$id_workList = '';

					}

					$cek_no_antrian = $three_db->query("SELECT
						MAX(baf.no_antrian) AS no_antrian
						FROM
						bpjs_antrian_farmasi AS baf
						WHERE
						1 = 1
						AND CAST(baf.tgl_insert AS DATE) = '$tanggal_hari_ini'
						AND form_ket = '$kode_form'	
						");

					if ($cek_no_antrian->num_rows() > 0) {

						$no_antrian = $cek_no_antrian->row()->no_antrian + 1;

					} else {

						$no_antrian = '1';

					}

					$form_no = ''.$kode_form.''. $no_antrian;

					$three_db->query("INSERT INTO bpjs_antrian_farmasi (
						noreg,
						norm,
						tgl_insert,
						no_antrian,
						form_no,
						NoJo,
						keterangan,
						form_ket
						)
						VALUES
						(
						'$noreg',
						'$norm',
						'$tglinsert',
						'$no_antrian',
						'$form_no',
						'$id_workList',
						'$keterangan',
						'$kode_form'
					)");

					$five_db->query("INSERT INTO bpjs_antrian_farmasi (
						noreg,
						norm,
						tgl_insert,
						no_antrian,
						form_no,
						NoJo,
						keterangan,
						form_ket
						)
						VALUES
						(
						'$noreg',
						'$norm',
						'$tglinsert',
						'$no_antrian',
						'$form_no',
						'$id_workList',
						'$keterangan',
						'$kode_form'
					)");

					$five_db->query("INSERT INTO bpjs_antrian_farmasi_history (
						noreg,
						norm,
						tgl_insert,
						no_antrian,
						form_no,
						NoJo,
						keterangan,
						form_ket
						)
						VALUES
						(
						'$noreg',
						'$norm',
						'$tglinsert',
						'$no_antrian',
						'$form_no',
						'$id_workList',
						'$keterangan',
						'$kode_form'
					)");

					$data['daftar'] = '0';
				}
			}

		}

		echo json_encode($data);
	}

	function print_antrian_farmasi($noreg, $norm)
	{
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);

		$noreg = $this->uri->segment(3);
		$norm = $this->uri->segment(4);

		date_default_timezone_set("Asia/Bangkok");

		// $no_mjkn = $this->input->post('no_mjkn');
		// $string_mess = $this->input->post('string_mess');
		// $no_sep = $this->input->post('no_sep');
		// $status = $this->input->post('status');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('d-m-Y H:i:s');

		$list_data_antrian = $three_db->query("SELECT
			baf.noreg,
			baf.no_antrian,
			baf.keterangan,
			baf.tgl_insert,
			baf.form_no
			FROM
			bpjs_antrian_farmasi AS baf
			WHERE
			1 = 1
			AND CAST(baf.tgl_insert AS DATE) = '$tgl_hari_ini'
			AND baf.noreg = '$noreg'	
			")->row();

		$list_data_pasien = $five_db->query("SELECT
			egdk.noreg,
			egdk.nama_pasien, 
			egdk.nama_dokter, 
			egdk.poli
			FROM
			EMR_GET_DATA_KUNJUNGAN AS egdk
			WHERE
			1 = 1
			AND egdk.noreg = '$noreg'
			AND egdk.batal = '0'		
			")->row();

		$data['nama_pasien'] = $list_data_pasien->nama_pasien;
		$data['nmdokter'] = $list_data_pasien->nama_dokter;
		$data['nmpoli'] = $list_data_pasien->poli;
		$data['norm'] = $norm;
		$data['no_antrian'] = $list_data_antrian->no_antrian;
		$data['tglinsert'] = date('d-m-Y H:i:s', strtotime($list_data_antrian->tgl_insert));
		$data['tgl_hari_ini'] = $tgl_hari_ini;
		$data['tgl_cetak'] = $tglinsert;
		$data['noreg'] = $noreg;
		$data['form_no'] = $list_data_antrian->form_no;
		$data['keterangan'] = $list_data_antrian->keterangan;

		$this->load->view('v_print_antrian_farmasi', $data);


	}

	public function get_dokter()
	{
		$id = $this->input->get('id');
		// $id = $_GET['id'];
		$three_db = $this->load->database('three', TRUE);
		$get  = $three_db->query("SELECT
			LTRIM(RTRIM(m.kode)) AS kode, 
			LTRIM(RTRIM(m.nama)) AS nama,
			m.kdDPJP AS kdDPJP, 
			LTRIM(RTRIM(rmp.kdPoli)) AS kdPoli,
			LTRIM(RTRIM(rp.nmPoli)) AS nmPoli,
			rp.kdsubpolibpjs AS kdpoliBPJS
			FROM
			dbo.medis AS m
			LEFT JOIN
			dbo.rj_matrikspoliklinik AS rmp
			ON 
			rmp.kdDokter = m.kode
			LEFT JOIN
			dbo.rj_poliklinik AS rp
			ON 
			rp.kdpoli = rmp.kdPoli
			WHERE
			m.kdDPJP <> '' 
			AND 
			rp.kdpoliBPJS <> ''
			AND 
			m.aktif = '1'
			AND
			rmp.kdDokter = '$id'
			ORDER BY rp.nmPoli")->result();
		// return $get->result();
		print json_encode($get);
	}

	public function check_antrean()
	{
		$nosurkon = $this->input->post('no_surkon');
		$three_db = $this->load->database('three', TRUE);

		$get_noref = $three_db->query("SELECT 
			tgl, 
			Noref 
			FROM BPJSAntrean WHERE Noref = '$nosurkon'");

		if ($get_noref->num_rows() > 0) {
			$tgl = $get_noref->row()->tgl;
			$noref = $get_noref->row()->Noref;
			$data['noref'] = $noref;
			$data['tgl'] = $tgl;
			$data['kode'] = '200';
		} else {
			$data['kode'] = '404';
		}
		echo json_encode($data);
	}
}
