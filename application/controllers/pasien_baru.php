<?php

class pasien_baru extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pasien_baru');
	}

	function index()
	{
		$this->load->view('v_pasien_baru');
	}

	public function daftar_pasien()
	{
		$three_db = $this->load->database('three', TRUE);
		$nine_db = $this->load->database('nine', TRUE);
		$four_db = $this->load->database('four', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$nik_pasien = $this->input->post('nik_pasien');
		$nama_pasien = $this->input->post('nama_pasien');
		$jk = $this->input->post('jk');
		$tmplahir = $this->input->post('tmplahir');
		$tgllahir = $this->input->post('tgllahir');
		$alamat = $this->input->post('alamat');
		$kota = $this->input->post('kota');
		$provinsi = $this->input->post('provinsi');
		$pendidikan = $this->input->post('pendidikan');
		$agama = $this->input->post('agama');
		$status_kawin = $this->input->post('status_kawin');
		$notelp = $this->input->post('notelp');
		$pekerjaan = $this->input->post('pekerjaan');
		$nama_keluarga = $this->input->post('nama_keluarga');
		$hub_keluarga = $this->input->post('hub_keluarga');
		$kdpoli = $this->input->post('kdpoli');
		$nmpoli = $this->input->post('nmpoli');
		$kddokter = $this->input->post('kddokter');
		$nmdokter = $this->input->post('nmdokter');
		$serviceid = $this->input->post('serviceid');
		$doctorid = $this->input->post('doctorid');
		$penjamin = $this->input->post('penjamin');
		$tgl_hari_ini = date('Y-m-d');
		$jam_hari_ini = date('H:i');
		$tglinsert = date('Y-m-d H:i:s');

		// ambil norm 
		$get_rs_norm = $nine_db->query("SELECT TOP(1) 
			rs_nomor_set 
			FROM rs_nomor_value
			WHERE rs_nomor_set > (SELECT nomor FROM rs_nomor)
			ORDER BY rs_nomor_set ASC");

		$three_db = $this->load->database('three', TRUE);
		$data_hub_keluarga = $three_db->query("SELECT 
			LTRIM(RTRIM(nmkduser)) AS hub_keluarga, 
			LTRIM(RTRIM(kduser)) AS kdhub_keluarga 
			FROM stdfielddt 
			WHERE 
			1=1 
			AND kdfield = 'HUBKLG' 
			AND aktif ='1'
			AND kduser = '$hub_keluarga'")->row();

		$nama_hub_keluarga = $data_hub_keluarga->hub_keluarga;

		if ($get_rs_norm->num_rows() > 0) {
			
			$cek_daftar = $nine_db->query("SELECT norm FROM Pasien WHERE noktpsim = '$nik_pasien'");

			if ($cek_daftar->num_rows() < 1) {
				$new_norm = $get_rs_norm->row()->rs_nomor_set;

				// UPDATE NORM DI RS_NOMOR
				$nine_db->query("UPDATE rs_nomor SET nomor = '$new_norm'");

				$nine_db->query("UPDATE rs_nomor_value SET status = '1' WHERE rs_nomor_set = '$new_norm'");

				// insert data pasien 
				$px['norm'] = $new_norm;
				$px['nama'] = $nama_pasien;
				$px['kdseks'] = $jk;
				$px['tmplahir'] = $tmplahir;
				$px['tgllahir'] = $tgllahir;
				$px['jalan'] = $alamat;
				$px['kota'] = $kota;
				$px['kdpropinsi'] = $provinsi;
				$px['kdagama'] = $agama;
				$px['kdstkawin'] = $status_kawin;
				$px['kdpendidikan'] = $pendidikan;
				$px['kdpekerjaan'] = $pekerjaan;
				$px['notelepon'] = $notelp;
				$px['noktpsim'] = $nik_pasien;
				$px['updater'] = 'Website Pro';

				// hubungan keluarga untuk ayah dan ibu 
				if ($hub_keluarga == '01') {
					$px['namaayah'] = $nama_keluarga;
					$px['nmEmergencyContact'] = $nama_keluarga;
					$px['hubEmergencyContact'] = $nama_hub_keluarga;
				} else if ($hub_keluarga == '02') {
					$px['namaibu'] = $nama_keluarga;
					$px['nmEmergencyContact'] = $nama_keluarga;
					$px['hubEmergencyContact'] = $nama_hub_keluarga;
				} else {
					$px['nmEmergencyContact'] = $nama_keluarga;
					$px['hubEmergencyContact'] = $nama_hub_keluarga;
				}

				$nine_db->insert('Pasien', $px);
			} else {
				$new_norm = $cek_daftar->row()->norm;
			}

			

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

			$lahir = new DateTime($tgllahir);
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
			$rj_reg['kdinstansi'] = $penjamin;
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
				AND doctor_id = '$doctorid'
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

			if ($cek_data_booking_n->num_rows() > 0) {
				$code_n = $cek_data_booking_n->row()->code;

				$no_antrian_int = (preg_replace("/[^0-9]/", '', $code_n)) + 1;

				$code = "A" . $no_antrian_int . "-" . $kdpoli;
			} else {
				$no_antrian_int = "1";
				$code = "A1-" . $kdpoli;
			}

			// insert queue
			$queue['service_id'] = $serviceid;
			$queue['doctor_id'] = $doctorid;
			$queue['insurer_id'] = '3';
			$queue['online_reg_number'] = NULL;
			$queue['online_verified'] = 'f';
			$queue['is_new_patient'] = 't';
			$queue['patient_name'] = $nama_pasien;
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
						$output_soap .= $nama_pasien;
						$output_soap .= ' / ( ';
						$output_soap .= $new_norm;
						$output_soap .= ')
					</div>
				</center>
				<div style="width:100%; font-size: 15px; margin-top: 1%"><br><b> *Pendaftaran berhasil, simpan/capture sebagai bukti pendaftaran<br>
					*Silahkan menunggu panggilan kasir untuk pembayaran </b>
				</div>

			</div>
		</div>';

		echo $output_soap;
	}
}
