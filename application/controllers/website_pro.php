<?php 

class website_pro extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('m_website_pro');
	}

	function index(){
		$this->load->view('v_website_pro');
	}

	public function info_booking_web(){
		$three_db = $this->load->database('three', TRUE);
		$eight_db = $this->load->database('eight', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');
		$cek_data_booking_n = $eight_db->query("SELECT boo.book_appointment, boo.book_booker_fullname, book_nohp, pas.ps_nomor_rm, pas.ps_namalengkap, boo.book_royalpaymentconfirm_is, boo.book_royalpaymentconfirm_at, boo.book_id, dj.jad_tanggal, dok.dr_label, boo.book_code, pas.ps_id, pas.ps_nik, pas.ps_tgllahir FROM booking boo 
			LEFT JOIN pasien pas ON pas.ps_id = boo.book_id_pasien
			LEFT JOIN dokter_jadwal dj ON dj.jad_id = boo.book_id_jadwal
			LEFT JOIN dokter dok ON dok.dr_id = dj.jad_id_dokter
			WHERE 1 = 1
			AND boo.book_code = '$no_mjkn'
			-- AND dj.jad_tanggal = '$tgl_hari_ini'
			AND boo.book_id_penanggung IN ('1','3')
			ORDER BY boo.book_id DESC");
		if($cek_data_booking_n->num_rows() > '0'){
			$cek_data_booking = $cek_data_booking_n->row();
			$data['data_book'] = '1';
			$data['book_appointment'] = $cek_data_booking->book_appointment;
			$data['book_booker_fullname'] = $cek_data_booking->book_booker_fullname;
			$data['book_nohp'] = $cek_data_booking->book_nohp;
			if($cek_data_booking->ps_nomor_rm != ''){
				$data['ps_nomor_rm'] = $cek_data_booking->ps_nomor_rm;
			} else {
				$data['ps_nomor_rm'] = '';
			}
			$data['ps_namalengkap'] = $cek_data_booking->ps_namalengkap;
			$data['book_royalpaymentconfirm_is'] = $cek_data_booking->book_royalpaymentconfirm_is;
			$data['book_royalpaymentconfirm_at'] = $cek_data_booking->book_royalpaymentconfirm_at;
			$data['book_id'] = $cek_data_booking->book_id;
			$data['jad_tanggal'] = $cek_data_booking->jad_tanggal;
			$data['dr_label'] = $cek_data_booking->dr_label;
			$data['book_code'] = $cek_data_booking->book_code;
			$data['ps_id'] = $cek_data_booking->ps_id;
			if($cek_data_booking->ps_nik != ''){
				$data['ps_nik'] = $cek_data_booking->ps_nik;
			} else {
				$data['ps_nik'] = '';
			}
			$data['ps_tgllahir'] = $cek_data_booking->ps_tgllahir;

		}else{
			$data['data_book'] = '0';

		}
		echo json_encode($data);


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

	public function cek_info_no_rm(){

		$three_db = $this->load->database('three', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$norm = $this->input->post('no_rm');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$cek_data_booking_n =  $three_db->query("SELECT LTRIM(RTRIM(noktpsim)) AS noktpsim, LTRIM(RTRIM(norm)) AS norm, LTRIM(RTRIM(nama)) AS nama, kdseks, LTRIM(RTRIM(tmplahir)) AS tmplahir, tgllahir, umurtahun, LTRIM(RTRIM(jalan)) AS jalan, notelepon, nohp FROM Pasien WHERE norm = '$norm'");
		if($cek_data_booking_n->num_rows() > '0' ){
			$cek_data_booking = $cek_data_booking_n->row();
			$data['nik'] = $cek_data_booking->noktpsim;
			$data['norm'] = $cek_data_booking->norm;
			$data['nama'] = $cek_data_booking->nama;
			$data['kdseks'] = $cek_data_booking->kdseks;
			$data['tmplahir'] = $cek_data_booking->tmplahir;
			$data['tgllahir'] = date('d-m-Y', strtotime($cek_data_booking->tgllahir));
			$data['umurtahun'] = $cek_data_booking->umurtahun;
			$data['jalan'] = $cek_data_booking->jalan;
			$data['notelepon'] = $cek_data_booking->notelepon;
			$data['data_book'] = '1';
		}else{
			$data['data_book'] = '0';
		}
		echo json_encode($data);

	}

	public function cek_info_nik(){

		$three_db = $this->load->database('three', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$nik = $this->input->post('nik');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		if($nik != ''){
			$cek_data_booking_n =  $three_db->query("SELECT LTRIM(RTRIM(noktpsim)) AS noktpsim, LTRIM(RTRIM(norm)) AS norm, LTRIM(RTRIM(nama)) AS nama, kdseks, LTRIM(RTRIM(tmplahir)) AS tmplahir, tgllahir, umurtahun, LTRIM(RTRIM(jalan)) AS jalan, notelepon, nohp FROM Pasien WHERE noktpsim = '$nik'");
			if($cek_data_booking_n->num_rows() > '0' ){
				$cek_data_booking = $cek_data_booking_n->row();
				$data['nik'] = $cek_data_booking->noktpsim;
				$data['norm'] = $cek_data_booking->norm;
				$data['nama'] = $cek_data_booking->nama;
				$data['kdseks'] = $cek_data_booking->kdseks;
				$data['tmplahir'] = $cek_data_booking->tmplahir;
				$data['tgllahir'] = date('d-m-Y', strtotime($cek_data_booking->tgllahir));
				$data['umurtahun'] = $cek_data_booking->umurtahun;
				$data['jalan'] = $cek_data_booking->jalan;
				$data['notelepon'] = $cek_data_booking->notelepon;
				$data['data_book'] = '1';
			}else{
				$data['data_book'] = '0';
			}
		} else {
			$data['data_book'] = '2';
		}

		echo json_encode($data);

	}

	public function cek_daftar(){
		$three_db = $this->load->database('three', TRUE);
		$four_db = $this->load->database('four', TRUE);
		$nine_db = $this->load->database('nine', TRUE);
		$eight_db = $this->load->database('eight', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$no_mjkn = $this->input->post('no_mjkn');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');
		$cek_data_booking_n = $eight_db->query("SELECT boo.book_appointment, boo.book_booker_fullname, book_nohp, pas.ps_nomor_rm, pas.ps_namalengkap, boo.book_royalpaymentconfirm_is, boo.book_royalpaymentconfirm_at, boo.book_id, dj.jad_tanggal, dok.dr_code, dok.dr_label, boo.book_code, pas.ps_id, pas.ps_nik, pas.ps_tgllahir, rjk.jk_label, pas.ps_kotalahir, pas.ps_alamat_ktp, pas.ps_kota, pas.ps_pendidikan, pas.ps_agama, rl.lay_label, rl.lay_code FROM booking boo 
			LEFT JOIN pasien pas ON pas.ps_id = boo.book_id_pasien
			LEFT JOIN dokter_jadwal dj ON dj.jad_id = boo.book_id_jadwal
			LEFT JOIN dokter dok ON dok.dr_id = dj.jad_id_dokter
			LEFT JOIN ref_jenis_kelamin rjk ON rjk.jk_id = pas.ps_jeniskelamin
			LEFT JOIN ref_layanan rl ON rl.lay_id = dj.jad_id_layanan
			WHERE 1 = 1
			AND boo.book_code = '$no_mjkn'
			-- AND dj.jad_tanggal = '$tgl_hari_ini'
			AND boo.book_id_penanggung IN ('1','3')
			ORDER BY boo.book_id DESC");
		if($cek_data_booking_n->num_rows() > '0'){
			$cek_data_booking = $cek_data_booking_n->row();

			$nik_pasien_rm = $cek_data_booking->ps_nik;
			$no_rm = $cek_data_booking->ps_nomor_rm;
			$nama_pasien_rm = $cek_data_booking->book_booker_fullname;
			$kdseks_rm = $cek_data_booking->jk_label;
			$tmplahir_rm = $cek_data_booking->ps_kotalahir;
			$tgllahir_rm = $cek_data_booking->ps_tgllahir;
			$lahir = new DateTime($tgllahir_rm);
			$today = new DateTime($tgl_hari_ini);
			if ($lahir > $today) { 
				$umurtahun_rm = "0";
			} else {
				$umurtahun_rm = $today->diff($lahir)->y;
			}
			$no_telepon_rm = $cek_data_booking->book_nohp;
			$alamat_rm = $cek_data_booking->ps_alamat_ktp;
			$kota = $cek_data_booking->ps_kota;
			$pendidikan = $cek_data_booking->ps_pendidikan;
			$agama = $cek_data_booking->ps_agama;
			$kdpoli = $cek_data_booking->lay_code;
			$nmpoli = $cek_data_booking->lay_label;
			$kddokter = $cek_data_booking->dr_code;
			$nmdokter = $cek_data_booking->dr_label;
			$book_royalpaymentconfirm_is = $cek_data_booking->book_royalpaymentconfirm_is;

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

			if($no_rm != ''){
				$noreg = 'OP';
				$date = str_replace("-", "", date('Y-m-d'));
				$noreg .= $date;
				$noreg .= '-';

				$get_norm = $nine_db->query("SELECT MAX(right(noreg,4)) as maxid FROM rj_reg WHERE noreg LIKE '%$date%'")->row();
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

				$get_kunjungan_pasien = $nine_db->query("SELECT TOP(2) noreg FROM rj_reg WHERE norm = '$no_rm'");
				if ($get_kunjungan_pasien->num_rows() > 1) {
					$pasienbaru = '0';
					$kunjungan = '0';
				} else {
					$pasienbaru = '1';
					$kunjungan = '1';
				}

				if ($lahir > $today) { 
					$umurtahun_rm = "0";
					$umurbulan = "0";
					$umurhari = "0";
				}
				$umurbulan = $today->diff($lahir)->m;
				$umurhari = $today->diff($lahir)->d;

				$get_kunjungan = $nine_db->query("SELECT
					rr.noreg AS noreg,
					LTRIM(RTRIM(rp.nmpoli)) AS nmpoli,
					LTRIM(RTRIM(med.nama)) AS nmdokter
					FROM rj_reg rr
					INNER JOIN rj_poliklinik AS rp ON rp.kdpoli = rr.kdpoli
					INNER JOIN medis AS med ON med.kode = rr.kddokter
					WHERE rr.norm = '$no_rm'
					AND rr.kdpoli = '$kdpoli'
					AND rr.kddokter = '$kddokter'
					AND CAST(rr.tglregistrasi AS DATE) = '$tgl_hari_ini'
					AND rr.batal = '0'");
				if ($get_kunjungan->num_rows() < 1) {
					$nine_db->query("INSERT INTO rj_reg (
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
						'$no_rm',
						'',
						'',
						'$tgl_hari_ini',
						'01',
						'$kdpoli',
						'$kddokter',
						'01',
						'01',
						'0',
						'$tglinsert',
						'Website Pro',
						' ',
						' ',
						'$jam_hari_ini',
						'',
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
						'0',
						'00',
						'',
						'',
						'$tgl_hari_ini',
						'',
						'',
						'',
						'',
						'$umurtahun_rm',
						'$umurbulan',
						'$umurhari',
						'$pasienbaru',
						'Website Pro',
						'$tglinsert',
						'1',
						'0',
						'0',
						''
						);");

					$nine_db->query("INSERT INTO rj_transhd (
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
						'$kdpoli',
						'',
						'',
						'',
						'',
						'$kddokter',
						'',
						'1',
						'$kunjungan',
						'',
						'1',
						'$jam_hari_ini',
						'',
						'',
						''
						);");

					$nine_db->query("INSERT INTO rj_antrian (
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
						'1',
						'$kdpoli',
						'$kddokter',
						'$noreg',
						'',
						'$nobukti',
						'',
						'0',
						'0',
						'$tglinsert',
						'Website Pro'
						);");

					$nine_db->query("UPDATE Pasien 
						SET noktpsim = '$nik_pasien_rm',
						notelepon = '$no_telepon_rm'
						WHERE norm = '$no_rm'");

					$cek_data_schedule =  $four_db->query("SELECT MAX(id) AS schedule_id FROM master_schedule
						WHERE
						1 = 1
						AND doctor_id = '$doctor_id'
						AND date = '$tgl_hari_ini'")->row();

					$schedule_id = $cek_data_schedule->schedule_id;

					$cek_data_booking_n = $four_db->query("SELECT
						code 
						FROM queue
						WHERE
						1 = 1
						AND RIGHT(code, 3) = '$kdpoli'
						AND (LEFT(code, 1) != 'B' AND LEFT(code, 1) != 'C')
						AND date = '$tgl_hari_ini'
						ORDER BY created_at DESC
						LIMIT 1");

					if($cek_data_booking_n->num_rows() > 0){
						$code_n = $cek_data_booking_n->row()->code;

						$no_antrian_int = preg_replace("/[^0-9]/", '', $code_n) + 1;

						$code = "A" . $no_antrian_int . "-" . $kdpoli;
					} else {
						$no_antrian_int = "1";
						$code = "A1-" . $kdpoli;
					}

					// echo $code;

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
						'$nama_pasien_rm',
						NULL,
						'$tglinsert',
						'$no_rm',
						'$code',
						'f',
						'$tgl_hari_ini',
						'f',
						NULL,
						'',
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

					if($book_royalpaymentconfirm_is == '1'){
						$data['kunjungan'] = '0';
						$data['nmpoli'] = '';
						$data['tgl_hari_ini'] = '';
						$data['code'] = $code;
						$data['no_rm'] = $no_rm;
						$data['payment_confirm'] = '1';
						$data['pesan'] = 'Pendaftaran berhasil, silahkan langsung menuju poli pemeriksaan';
					} else {
						$data['kunjungan'] = '0';
						$data['nmpoli'] = '';
						$data['tgl_hari_ini'] = '';
						$data['code'] = $code;
						$data['no_rm'] = $no_rm;
						$data['payment_confirm'] = '0';
						$data['pesan'] = 'Pendaftaran berhasil, silahkan menunggu panggilan kasir untuk pembayaran';
					}

				} else {
					$data['kunjungan'] = '1';
					$data['nmpoli'] = $nmpoli;
					$data['tgl_hari_ini'] = date('d-m-Y');
					$data['code'] = '';
					$data['no_rm'] = $no_rm;
					$data['payment_confirm'] = '';
					$data['pesan'] = '';
				}

			} else {
				$get_rs_norm = $nine_db->query("SELECT TOP(1) 
					rs_nomor_set 
					FROM rs_nomor_value
					WHERE rs_nomor_set > (SELECT nomor FROM rs_nomor)
					ORDER BY rs_nomor_set ASC");

				if ($get_rs_norm->num_rows() > 0) {
					$new_norm = $get_rs_norm->row()->rs_nomor_set;

					// UPDATE NORM DI RS_NOMOR
					$nine_db->query("UPDATE rs_nomor SET nomor = '$new_norm'");

					$nine_db->query("UPDATE rs_nomor_value SET status = '1' WHERE rs_nomor_set = '$new_norm'");

					// insert data pasien 
					$px['norm'] = $new_norm;
					$px['nama'] = $nama_pasien_rm;
					if($kdseks_rm == 'PRIA'){
						$kdseks = 'L';
					} else if ($kdseks_rm == 'WANITA'){
						$kdseks = 'P';
					} else {
						$kdseks = '';
					}

					$px['kdseks'] = $kdseks;
					$px['tmplahir'] = $tmplahir_rm;
					$px['tgllahir'] = $tgllahir_rm;
					$px['jalan'] = $alamat_rm;
					$px['kota'] = $kota;
					$px['kdpropinsi'] = '';
					if($agama == NULL){
						$kdagama = '';
					} else {
						$kdagama = $agama;
					}
					if($pendidikan == NULL){
						$kdpendidikan = '';
					} else {
						$kdpendidikan = $pendidikan;
					}
					if($nik_pasien_rm == NULL){
						$noktpsim = '';
					} else {
						$noktpsim = $nik_pasien_rm;
					}
					$px['kdagama'] = $kdagama;
					$px['kdstkawin'] = '';
					$px['kdpendidikan'] = $kdpendidikan;
					$px['kdpekerjaan'] = '';
					$px['notelepon'] = $no_telepon_rm;
					$px['noktpsim'] = $noktpsim;
					$px['updater'] = 'Website Pro';

					$nine_db->insert('Pasien', $px);

					// ambil noreg 
					$noreg = 'OP';
					$date = str_replace("-", "", date('Y-m-d'));
					$noreg .= $date;
					$noreg .= '-';

					$get_noreg = $nine_db->query("SELECT MAX(right(noreg,4)) as maxid FROM rj_reg WHERE noreg LIKE '%$date%'")->row();

					if ($get_noreg) {
						$maxid = $get_noreg->maxid;
						$maxid = $maxid + 1;

						$maxid_bukti = $get_noreg->maxid;
					} else {
						$maxid = '0001';

						$maxid_bukti = '0001';
					}

					$noreg .= sprintf("%04s", $maxid);

					$nobukti = $date . '0';

					$nobukti .= sprintf("%04s", $maxid_bukti);
					$get_kunjungan_pasien = $nine_db->query("SELECT TOP(2) noreg FROM rj_reg WHERE norm = '$new_norm'");

					if ($get_kunjungan_pasien->num_rows() > 1) {
						$pasienbaru = '0';
						$kunjungan = '0';
					} else {
						$pasienbaru = '1';
						$kunjungan = '1';
					}

					$lahir = new DateTime($tgllahir_rm);
					$today = new DateTime($tgl_hari_ini);

					if ($lahir > $today) {
						$umurtahun_rm = "0";
						$umurbulan = "0";
						$umurhari = "0";
					}

					$umurtahun_rm = $today->diff($lahir)->y;
					$umurbulan = $today->diff($lahir)->m;
					$umurhari = $today->diff($lahir)->d;

			// insert rj_reg
					$rj_reg['noreg'] = $noreg;
					$rj_reg['norm'] = $new_norm;
					$rj_reg['tglregistrasi'] = $tgl_hari_ini;
					$rj_reg['kdtipevisit'] = '01';
					$rj_reg['kdpoli'] = $kdpoli;
					$rj_reg['kddokter'] = $kddokter;
					$rj_reg['kdtipecharge'] = '01';
					$rj_reg['kdjmbayar'] = '01';
					$rj_reg['tutup'] = '0';
					$rj_reg['tglupdate'] = $tglinsert;
					$rj_reg['usrupdate'] = 'Website Pro';
					$rj_reg['jamreg'] = $jam_hari_ini;
					$rj_reg['piutang'] = '0';
					$rj_reg['kdtipepasien'] = '08';
					$rj_reg['batal'] = '0';
					$rj_reg['kdpengirim'] = '00';
					$rj_reg['tglkeluar'] = $tgl_hari_ini;
					$rj_reg['umurtahun'] = $umurtahun_rm;
					$rj_reg['umurbulan'] = $umurbulan;
					$rj_reg['umurhari'] = $umurhari;
					$rj_reg['pasienbaru'] = $pasienbaru;
					$rj_reg['usrinsert'] = 'Website Pro';
					$rj_reg['tglinsert'] = $tglinsert;
					$rj_reg['shift'] = '1';
					$rj_reg['kartu'] = '0';
					$rj_reg['rujukan'] = '0';

					$nine_db->insert('rj_reg', $rj_reg);

			// insert rj_transhd
					$rj_transhd['nobukti'] = $nobukti;
					$rj_transhd['noreg'] = $noreg;
					$rj_transhd['noKunjungan'] = '';
					$rj_transhd['tgltrans'] = $tgl_hari_ini;
					$rj_transhd['kdpoli'] = $kdpoli;
					$rj_transhd['posting'] = '';
					$rj_transhd['nobill'] = '';
					$rj_transhd['fReferal'] = '';
					$rj_transhd['noresep'] = '';
					$rj_transhd['kddokter'] = $kddokter;
					$rj_transhd['nopaket'] = '';
					$rj_transhd['otomatis'] = '1';
					$rj_transhd['kunjunganbaru'] = $kunjungan;
					$rj_transhd['kdcabang'] = '';
					$rj_transhd['shift'] = '1';
					$rj_transhd['jamtrans'] = $jam_hari_ini;
					$rj_transhd['jnsKasus'] = '';
					$rj_transhd['Diagnosa'] = '';
					$rj_transhd['kdterminal'] = '';

					$nine_db->insert('rj_transhd', $rj_transhd);

			// insert rj_antrian
					$rj_antrian['tanggal'] = $tgl_hari_ini;
					$rj_antrian['shift'] = '1';
					$rj_antrian['kdpoli'] = $kdpoli;
					$rj_antrian['kddokter'] = $kddokter;
					$rj_antrian['noreg'] = $noreg;
					$rj_antrian['noapp'] = '';
					$rj_antrian['nobukti'] = $nobukti;
					$rj_antrian['antrian'] = '';
					$rj_antrian['sisipan'] = '0';
					$rj_antrian['statusreg'] = '0';
					$rj_antrian['tglinsert'] = $tglinsert;
					$rj_antrian['usrinsert'] = 'Website Pro';

					$nine_db->insert('rj_antrian', $rj_antrian);

					$cek_data_schedule =  $four_db->query("SELECT MAX(id) AS schedule_id FROM master_schedule
						WHERE
						1 = 1
						AND doctor_id = '$doctor_id'
						AND date = '$tgl_hari_ini'")->row();

					$schedule_id = $cek_data_schedule->schedule_id;

					$cek_data_booking_n = $four_db->query("SELECT
						code 
						FROM queue
						WHERE
						1 = 1
						AND RIGHT(code, 3) = '$kdpoli'
						AND (LEFT(code, 1) != 'B' AND LEFT(code, 1) != 'C')
						AND date = '$tgl_hari_ini'
						ORDER BY created_at DESC
						LIMIT 1");

					if($cek_data_booking_n->num_rows() > 0){
						$code_n = $cek_data_booking_n->row()->code;

						$no_antrian_int = (preg_replace("/[^0-9]/", '', $code_n)) + 1;

						$code = "A" . $no_antrian_int . "-" . $kdpoli;
					} else {
						$no_antrian_int = "1";
						$code = "A1-" . $kdpoli;
					}

					// insert queue
					$queue['service_id'] = $service_id;
					$queue['doctor_id'] = $doctor_id;
					$queue['insurer_id'] = '3';
					$queue['online_reg_number'] = NULL;
					$queue['online_verified'] = 'f';
					$queue['is_new_patient'] = 't';
					$queue['patient_name'] = $nama_pasien_rm;
					$queue['patient_info'] = NULL;
					$queue['created_at'] = $tglinsert;
					$queue['mrn'] = $new_norm;
					$queue['code'] = $code;
					$queue['is_processed'] = 'f';
					$queue['date'] = $tgl_hari_ini;
					$queue['is_call_skipped'] = 'f';
					$queue['last_called_at'] = NULL;
					$queue['appointment_number'] = '';
					$queue['schedule_id'] = $schedule_id;
					$queue['active'] = 't';
					$queue['rm_found'] = 'f';
					$queue['rm_found_by'] = NULL;
					$queue['rm_found_at'] = NULL;
					$queue['rm_cannot_found'] = 'f';
					$queue['rm_cannot_found_reason'] = NULL;
					$queue['code_number'] = $no_antrian_int;
					$queue['status_tambah_antrian_bpjs'] = '0';

					$four_db->insert('queue', $queue);
				} else {
					echo 'data tidak berhasil';
				}


				$output_soap = "";


				$output_soap .= '
				<div id="printing_table" style="width:100%">
					<div id="content">
						<center>
							<img src="';
							$output_soap .= base_url() . 'assets/img/royal.png';
							$output_soap .= '" height="80" width="140" />
							<div style="width:250px; font-size: 15px; margin-top: 1%"> Pendaftaran Umum Mandiri
							</div>
							<div style="width:250px; font-size: 12px; margin-top: 1%">';
								$output_soap .= $tglinsert;
								$output_soap .= '
							</div>
							<div style="width:250px; font-size: 20px; margin-top: 1%">';
								$output_soap .= $nmpoli;
								$output_soap .= '
							</div>
							<div style="width:250px; font-size: 20px; margin-top: 1%">';
								$output_soap .= $tgl_hari_ini;
								$output_soap .= '
							</div>
							<div style="width:250px; font-size: 20px; margin-top: 1%">';
								$output_soap .= $nmdokter;
								$output_soap .= '
							</div>
							<div style="width:250px; font-size: 35px; margin-top: 1%"><b>';
								$output_soap .= $code;
								$output_soap .= '</b>
							</div>
							<div style="width:250px; font-size: 15px; margin-top: 1%"> No Reg : ';
								$output_soap .= $noreg;
								$output_soap .= '
							</div>
							<div style="width:250px; font-size: 15px; margin-top: 1%"> Nama pasien : ';
								$output_soap .= $nama_pasien_rm;
								$output_soap .= ' / ( ';
								$output_soap .= $new_norm;
								$output_soap .= ')
							</div>
						</center>
						<div style="width:100%; font-size: 15px; margin-top: 1%"><br>
							<b> *Pendaftaran berhasil, simpan/capture sebagai bukti pendaftaran<br>';
								if($book_royalpaymentconfirm_is == '1'){
									$output_soap .= '*Silahkan langsung menuju poli pemeriksaan';
								} else {
									$output_soap .= '*Silahkan menunggu panggilan kasir untuk pembayaran';
								}
								$output_soap .= '
							</b>
						</div>

					</div>
				</div>';

				$data['kunjungan'] = '2';
				$data['nmpoli'] = '';
				$data['tgl_hari_ini'] = date('d-m-Y');
				$data['code'] = $output_soap;
			}

		}
		

		echo json_encode($data);
	}

	function view_cari_poli()
	{
		$four_db = $this->load->database('four', TRUE);
		$kdpoli = $this->input->post('kdpoli');
		date_default_timezone_set("Asia/Bangkok");
		$tgl_hari_ini = $this->input->post('tgl_hari_ini');
		$jam_hari_ini = date('H:i:s');

		$get_data_poli = $four_db->query("SELECT mse.service_id, mse.doctor_id, msc.name AS poli, md.name AS dokter, msc.code AS kode_poli, md.code AS kode_dokter, mse.start, mse.end FROM master_schedule mse
			LEFT JOIN master_service msc ON msc.id = mse.service_id
			LEFT JOIN master_doctor md ON md.id = mse.doctor_id 
			WHERE 1 = 1
			AND mse.room_id = '2'
			AND CAST(mse.date AS DATE) = '$tgl_hari_ini'
			AND CAST(mse.start AS TIME) > '$jam_hari_ini'
			ORDER BY msc.name ASC, mse.start ASC");

		$output_soap = "";


		if ($get_data_poli->num_rows() > 0) {
			$output_soap .= "<div class='row col-md-12' style='margin-top: 1%;position: relative;'>";
			foreach($get_data_poli->result() as $row)
			{
				$poli = $row->poli;
				$dokter = $row->dokter;
				$kode_poli = $row->kode_poli;
				$kode_dokter = $row->kode_dokter;
				$start = date('H:i', strtotime($row->start));
				$end = date('H:i', strtotime($row->end));
				$service_id = $row->service_id;
				$doctor_id = $row->doctor_id;

				$output_soap .= "
				<div class='col-md-3' style='margin-top: 2%;'>
					<a class='btn green' id='dokter' style='height:150px;width: 90%;background-color: #3498db;font-size:18px' onclick='set_poli(".'"'.$kode_poli.'"'.", ".'"'.$poli.'"'.", ".'"'.$kode_dokter.'"'.", ".'"'.$dokter.'"'.", ".'"'.$service_id.'"'.", ".'"'.$doctor_id.'"'.")'>";
						$output_soap .= '<b>' . $poli . '</b><br><b>' . wordwrap($dokter,10," \n") . '</b><br>' . $start . ' s/d ' . $end;
						$output_soap .= ' 
					</a>
				</div>';
			}
			$output_soap .= "</div>";
		}

		echo $output_soap;

		
	}

	public function daftar_pasien(){
		$three_db = $this->load->database('three', TRUE);
		$four_db = $this->load->database('four', TRUE);
		$nine_db = $this->load->database('nine', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$nik_pasien_rm = $this->input->post('nik_pasien_rm');
		$no_rm = $this->input->post('no_rm');
		$nama_pasien_rm = $this->input->post('nama_pasien_rm');
		$kdseks_rm = $this->input->post('kdseks_rm');
		$tmplahir_rm = $this->input->post('tmplahir_rm');
		$umurtahun_rm = $this->input->post('umurtahun_rm');
		$tgllahir_rm = $this->input->post('tgllahir_rm');
		$no_telepon_rm = $this->input->post('no_telepon_rm');
		$alamat_rm = $this->input->post('alamat_rm');
		$kdpoli = $this->input->post('kdpoli');
		$nmpoli = $this->input->post('nmpoli');
		$kddokter = $this->input->post('kddokter');
		$nmdokter = $this->input->post('nmdokter');
		$penjamin = $this->input->post('penjamin');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$noreg = 'OP';
		$date = str_replace("-", "", date('Y-m-d'));
		$noreg .= $date;
		$noreg .= '-';

		$get_norm = $nine_db->query("SELECT MAX(right(noreg,4)) as maxid FROM rj_reg WHERE noreg LIKE '%$date%'")->row();
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

		$get_kunjungan_pasien = $nine_db->query("SELECT TOP(2) noreg FROM rj_reg WHERE norm = '$no_rm'");
		if ($get_kunjungan_pasien->num_rows() > 1) {
			$pasienbaru = '0';
			$kunjungan = '0';
		} else {
			$pasienbaru = '1';
			$kunjungan = '1';
		}

		$lahir = new DateTime($tgllahir_rm);
		$today = new DateTime($tgl_hari_ini);

		if ($lahir > $today) { 
			$umurtahun_rm = "0";
			$umurbulan = "0";
			$umurhari = "0";
		}
		$umurbulan = $today->diff($lahir)->m;
		$umurhari = $today->diff($lahir)->d;

		$get_kunjungan = $nine_db->query("SELECT
			rr.noreg AS noreg,
			LTRIM(RTRIM(rp.nmpoli)) AS nmpoli,
			LTRIM(RTRIM(med.nama)) AS nmdokter
			FROM rj_reg rr
			INNER JOIN rj_poliklinik AS rp ON rp.kdpoli = rr.kdpoli
			INNER JOIN medis AS med ON med.kode = rr.kddokter
			WHERE rr.norm = '$no_rm'
			AND rr.kdpoli = '$kdpoli'
			AND rr.kddokter = '$kddokter'
			AND CAST(rr.tglregistrasi AS DATE) = '$tgl_hari_ini'
			AND rr.batal = '0'");
		if ($get_kunjungan->num_rows() < 1) {
			$nine_db->query("INSERT INTO rj_reg (
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
				'$no_rm',
				'',
				'',
				'$tgl_hari_ini',
				'01',
				'$kdpoli',
				'$kddokter',
				'01',
				'01',
				'0',
				'$tglinsert',
				'Website Pro',
				'$penjamin',
				' ',
				'$jam_hari_ini',
				'',
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
				'0',
				'00',
				'',
				'',
				'$tgl_hari_ini',
				'',
				'',
				'',
				'',
				'$umurtahun_rm',
				'$umurbulan',
				'$umurhari',
				'$pasienbaru',
				'Website Pro',
				'$tglinsert',
				'1',
				'0',
				'0',
				''
				);");

			$nine_db->query("INSERT INTO rj_transhd (
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
				'$kdpoli',
				'',
				'',
				'',
				'',
				'$kddokter',
				'',
				'1',
				'$kunjungan',
				'',
				'1',
				'$jam_hari_ini',
				'',
				'',
				''
				);");

			$nine_db->query("INSERT INTO rj_antrian (
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
				'1',
				'$kdpoli',
				'$kddokter',
				'$noreg',
				'',
				'$nobukti',
				'',
				'0',
				'0',
				'$tglinsert',
				'Website Pro'
				);");

			$nine_db->query("UPDATE Pasien 
				SET noktpsim = '$nik_pasien_rm',
				notelepon = '$no_telepon_rm'
				WHERE norm = '$no_rm'");

			$service_id = $this->input->post('serviceid');
			$doctor_id = $this->input->post('doctorid');

			$cek_data_schedule =  $four_db->query("SELECT MAX(id) AS schedule_id FROM master_schedule
				WHERE
				1 = 1
				AND doctor_id = '$doctor_id'
				AND date = '$tgl_hari_ini'")->row();

			$schedule_id = $cek_data_schedule->schedule_id;

			$cek_data_booking_n = $four_db->query("SELECT
				code 
				FROM queue
				WHERE
				1 = 1
				AND RIGHT(code, 3) = '$kdpoli'
				AND (LEFT(code, 1) != 'B' AND LEFT(code, 1) != 'C')
				AND date = '$tgl_hari_ini'
				ORDER BY created_at DESC
				LIMIT 1");

			if($cek_data_booking_n->num_rows() > 0){
				$code_n = $cek_data_booking_n->row()->code;

				$no_antrian_int = preg_replace("/[^0-9]/", '', $code_n) + 1;

				$code = "A" . $no_antrian_int . "-" . $kdpoli;
			} else {
				$no_antrian_int = "1";
				$code = "A1-" . $kdpoli;
			}

			// echo $code;

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
				'$nama_pasien_rm',
				NULL,
				'$tglinsert',
				'$no_rm',
				'$code',
				'f',
				'$tgl_hari_ini',
				'f',
				NULL,
				'',
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

			$data['kunjungan'] = '0';
			$data['nmpoli'] = '';
			$data['tgl_hari_ini'] = '';
			$data['code'] = $code;
		} else {
			$data['kunjungan'] = '1';
			$data['nmpoli'] = $nmpoli;
			$data['tgl_hari_ini'] = date('d-m-Y');
			$data['code'] = '';
		}

		echo json_encode($data);


	}

	function print_antrian_queue($no_rm, $code, $warna){
		$three_db = $this->load->database('three', TRUE);
		$four_db = $this->load->database('four', TRUE);
		$nine_db = $this->load->database('nine', TRUE);

		$no_rm = $this->uri->segment(3);
		$code = $this->uri->segment(4);
		$warna = $this->uri->segment(5);
		
		date_default_timezone_set("Asia/Bangkok");

		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		$cek_data_pasien =  $nine_db->query("SELECT LTRIM(RTRIM(nama)) AS nama FROM Pasien WHERE norm = '$no_rm'");
		if($cek_data_pasien->num_rows() > '0' ){
			$cek_data_pasien = $cek_data_pasien->row();
			$nama_pasien = $cek_data_pasien->nama;
		}else{
			$nama_pasien = '';
		}

		$get_kunjungan_pasien = $nine_db->query("SELECT TOP(1)
			rr.noreg AS noreg,
			LTRIM(RTRIM(rp.nmpoli)) AS nmpoli,
			LTRIM(RTRIM(med.nama)) AS nmdokter
			FROM rj_reg rr
			INNER JOIN rj_poliklinik AS rp ON rp.kdpoli = rr.kdpoli
			INNER JOIN medis AS med ON med.kode = rr.kddokter
			WHERE rr.norm = '$no_rm'
			ORDER BY rr.tglinsert DESC");
		if ($get_kunjungan_pasien->num_rows() > 0) {
			$noreg = $get_kunjungan_pasien->row()->noreg;
			$nmpoli = $get_kunjungan_pasien->row()->nmpoli;
			$nmdokter = $get_kunjungan_pasien->row()->nmdokter;
		} else {
			$noreg = '';
			$nmpoli = '';
			$nmdokter = '';
		}


		// $cek_data_booking_n = $four_db->query("SELECT
		// 	MAX(code) AS code 
		// 	FROM queue
		// 	WHERE
		// 	1 = 1
		// 	AND patient_name = '$nama_pasien'");

		// if($cek_data_booking_n->num_rows() > 0){
		// 	$code = $cek_data_booking_n->row()->code;
		// } else {
		// 	$code = "";
		// }

		$data['norm'] = $no_rm;
		$data['code'] = $code;
		$data['nama_pasien'] = $nama_pasien;
		$data['tglinsert'] = $tglinsert;
		$data['tgl_hari_ini'] = $tgl_hari_ini;
		$data['noreg'] = $noreg;
		$data['nmpoli'] = $nmpoli;
		$data['nmdokter'] = $nmdokter;
		$data['warna'] = $warna;

		$this->load->view('v_print_antrian_offline', $data);

		
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
}
?>