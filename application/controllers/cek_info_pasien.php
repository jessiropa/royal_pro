<?php 

class cek_info_pasien extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('m_cek_info_pasien');
	}

	function index(){
		$this->load->view('v_cek_info_pasien');
	}

	function insert_antrian_rj(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET noAntrian = NULL
			WHERE noAntrian = ''");

		$tgl_mulai = date("Y-m-d", strtotime($this->input->post('tgl_mulai')));
		$tgl_akhir = date("Y-m-d", strtotime($this->input->post('tgl_akhir')));
		$keterangan = $this->input->post('keterangan'); 
		$cek_data_antrian_n = $five_db->query("SELECT egdt.noreg FROM EMR_GET_DATA_KUNJUNGAN egdt WHERE egdt.noAntrian IS NULL
			AND CAST(egdt.tgl_reg AS DATE) BETWEEN '$tgl_mulai' AND '$tgl_akhir'
			AND egdt.noreg LIKE '%OP%'");
		if($cek_data_antrian_n->num_rows() > '0'){
			$cek_data_antrian_n = $cek_data_antrian_n->row();
			$cek_noantrian = $three_db->query("SELECT
				x.noreg,
				x.norm,
				x.kdseks,
				x.nama_pasien,
				x.jamreg,
				x.nama_dokter,
				x.nminstansi,
				(
				CASE WHEN x.antrian IS NULL THEN 'B0004'
				ELSE x.antrian END
				) AS antrian,
				x.noapp,
				x.updater,
				(
				CASE WHEN x.noapp = '' THEN '0'
				ELSE '1' END
				) AS status_online
				FROM (

				SELECT
				LTRIM(RTRIM(rr.noreg)) AS noreg,
				LTRIM(RTRIM(rr.norm)) AS norm,
				pasien.kdseks AS kdseks,
				LTRIM(RTRIM(pasien.nama)) AS nama_pasien,
				rr.jamreg AS jamreg,
				LTRIM(RTRIM(medis.nama)) AS nama_dokter,
				LTRIM(RTRIM(instansi.nminstansi)) AS nminstansi,

				(
				CASE WHEN rr.noapp = '' THEN raa.antrian
				ELSE baa.noantrian END
				) AS antrian,
				rr.noapp,
				rja.updater
				FROM
				rj_reg rr
				FULL OUTER JOIN Pasien ON rr.norm = Pasien.norm
				FULL OUTER JOIN instansi ON rr.kdinstansi = instansi.kdinstansi
				FULL OUTER JOIN rj_poliklinik ON rr.kdpoli = rj_poliklinik.kdpoli
				FULL OUTER JOIN medis ON rr.kddokter = medis.kode
				FULL OUTER JOIN rj_antrian raa ON raa.noreg = rr.noreg
				LEFT JOIN BPJSAntrean baa ON baa.noapp = rr.noapp AND rr.noapp != ''
				LEFT JOIN rj_appointment rja ON rja.noapp = rr.noapp
				WHERE
				1 = 1
				AND rr.noreg = '$cek_data_antrian_n->noreg'
				)x
				ORDER BY x.antrian ASC")->row();
			$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET noAntrian = '$cek_noantrian->antrian', noApp = '$cek_noantrian->noapp',
				asal_online = '$cek_noantrian->updater', status_online = '$cek_noantrian->status_online'
				WHERE noreg = '$cek_data_antrian_n->noreg'");

			echo "Sukses Antrian untuk ".$cek_data_antrian_n->noreg;

		}else{
			echo "Data HABIS Untuk Antrian, Jangan MAKSA !";
		}
	}

	public function insert_noapp_rj(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		// $five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET noAntrian = NULL
		// 	WHERE noAntrian = ''");

		$tgl_mulai = date("Y-m-d", strtotime($this->input->post('tgl_mulai')));
		$tgl_akhir = date("Y-m-d", strtotime($this->input->post('tgl_akhir')));
		$keterangan = $this->input->post('keterangan'); 
		$cek_data_antrian_n = $five_db->query("SELECT egdt.noreg FROM EMR_GET_DATA_KUNJUNGAN egdt WHERE egdt.status_noApp = '0'
			AND CAST(egdt.tgl_reg AS DATE) BETWEEN '$tgl_mulai' AND '$tgl_akhir'
			AND egdt.noreg LIKE '%OP%'");
		if($cek_data_antrian_n->num_rows() > '0'){
			$cek_data_antrian_n = $cek_data_antrian_n->row();
			$cek_noantrian = $three_db->query("SELECT
				x.noreg,
				x.norm,
				x.kdseks,
				x.nama_pasien,
				x.jamreg,
				x.noapp,
				x.updater,
				(
				CASE WHEN x.noapp = '' THEN '0'
				ELSE '1' END
				) AS status_online
				FROM (			

				SELECT
				LTRIM(RTRIM(rr.noreg)) AS noreg,
				LTRIM(RTRIM(rr.norm)) AS norm,
				pasien.kdseks AS kdseks,
				LTRIM(RTRIM(pasien.nama)) AS nama_pasien,
				rr.jamreg AS jamreg,
				
				rr.noapp,
				rja.updater
				FROM
				rj_reg rr
				FULL OUTER JOIN Pasien ON rr.norm = Pasien.norm
				FULL OUTER JOIN rj_antrian raa ON raa.noreg = rr.noreg
				LEFT JOIN rj_appointment rja ON rja.noapp = rr.noapp
				WHERE
				1 = 1
				AND rr.noreg = '$cek_data_antrian_n->noreg'
				)x
				ORDER BY x.noreg ASC")->row();
			$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET noApp = '$cek_noantrian->noapp',
				asal_online = '$cek_noantrian->updater', status_online = '$cek_noantrian->status_online'
				WHERE noreg = '$cek_data_antrian_n->noreg'");

			$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET status_noApp = '1'
				WHERE noreg = '$cek_data_antrian_n->noreg'");

			$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET status_noApp = '2'
				WHERE status_online = '0' AND status_noApp = '1'");

			$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET status_online = '1'
				WHERE noAntrian LIKE '%A%'
				AND status_online = '0'");

			echo "Sukses Antrian untuk ".$cek_data_antrian_n->noreg;

		}else{
			echo "Data HABIS Untuk Antrian, Jangan MAKSA !";
		}
	}

	function insert_rj_emr(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$tgl_mulai = date("Y-m-d", strtotime($this->input->post('tgl_mulai')));
		$tgl_akhir = date("Y-m-d", strtotime($this->input->post('tgl_akhir')));
		$keterangan = $this->input->post('keterangan'); 
		$cek_rj_emr = $three_db->query("SELECT TOP(1)
			LTRIM(RTRIM(rr.noreg)) AS noreg,
			LTRIM(RTRIM(rr.norm)) AS norm,
			pasien.kdseks AS kdseks,
			LTRIM(RTRIM(pasien.nama)) AS nama_pasien,
			rr.jamreg AS jamreg,
			LTRIM(RTRIM(medis.nama)) AS nama_dokter,
			LTRIM(RTRIM(instansi.nminstansi)) AS nminstansi,
			LTRIM(RTRIM(std.nmkduser)) AS nmvisite,
			LTRIM(RTRIM(rr.kdtipevisit)) AS kdtipevisit,
			LTRIM(RTRIM(rj_poliklinik.nmpoli)) AS poli,
			LTRIM(RTRIM(rj_poliklinik.kdpoli)) AS kdpoli,
			rr.tglinsert,
			rr.tglregistrasi AS tgl_reg,
			LTRIM(RTRIM(rr.kddokter)) AS kddokter,
			LTRIM(RTRIM(instansi.kdinstansi)) AS kdinstansi,
			rr.batal,
			LTRIM(RTRIM(rr.noSEPBPJS)) AS noSEPBPJS,
			LTRIM(RTRIM(Pasien.NoPesertaBPJS)) AS NoPesertaBPJS,
			rr.umurhari,
			rr.umurbulan,
			rr.umurtahun,
			LTRIM(RTRIM(Pasien.jalan)) AS jalan,
			(
			CASE WHEN rr.noapp = '' THEN raa.antrian
			ELSE baa.noantrian END
			) AS antrian,
			rr.noapp	
			FROM
			rj_reg rr
			FULL OUTER JOIN Pasien ON rr.norm = Pasien.norm
			FULL OUTER JOIN instansi ON rr.kdinstansi = instansi.kdinstansi
			FULL OUTER JOIN rj_poliklinik ON rr.kdpoli = rj_poliklinik.kdpoli
			FULL OUTER JOIN medis ON rr.kddokter = medis.kode
			LEFT JOIN stdfielddt std ON rr.kdtipevisit = std.kduser 
			AND std.kdfield = 'TPVISIT' 
			FULL OUTER JOIN rj_antrian raa ON raa.noreg = rr.noreg
			LEFT JOIN BPJSAntrean baa ON baa.noapp = rr.noapp AND rr.noapp != ''
			WHERE
			CAST ( rr.tglregistrasi AS DATE ) BETWEEN '$tgl_mulai' AND '$tgl_akhir' 
			AND batal = 0
			AND rr.get_status_k IS NULL
			ORDER BY rr.tglinsert DESC
			");
		if($cek_rj_emr->num_rows() > 0){
			$cek_rj_emr_get = $cek_rj_emr->row();
			$cek_data = $five_db->query("SELECT noreg, norm FROM EMR_GET_DATA_KUNJUNGAN
				WHERE noreg = '$cek_rj_emr_get->noreg'");
			if($cek_data->num_rows() > 0){
				$three_db->query("UPDATE rj_reg SET get_status_k = '1' WHERE noreg = '$cek_rj_emr_get->noreg'");
				echo "Sudah Ada Data ".$cek_rj_emr_get->noreg;

				$cek_data_status_map_n = $five_db->query("SELECT TOP
					( 1 )
					est.STATUS_PASIEN AS STATUS_MAP
					FROM
					EMR_STATUS_PASIEN est					
					WHERE
					1 = 1 
					AND est.NOREG = '$cek_rj_emr_get->noreg'");
				
				if($cek_data_status_map->num_rows() > 0 ){

				}else{
					$cek_data_status_map = $five_db->query("SELECT TOP(1) aut.NORM, est.STATUS_PASIEN AS STATUS_MAP, est.OLEH_INSERT, est.TGL_INSERT, err.link 
						FROM EMR_STATUS_PASIEN est 
						INNER JOIN EMR_UTAMA_PERIKSA aut ON aut.NOREG = est.NOREG
						INNER JOIN EMR_RM_STATUS_MAP err ON err.jenis_status = est.STATUS_PASIEN
						WHERE 1 = 1
						AND est.STATUS = 'BARU'
						AND aut.NORM = '$cek_rj_emr_get->norm'
						AND est.TGL_INSERT = (SELECT (MAX(TGL_INSERT)) FROM EMR_STATUS_PASIEN
						WHERE NOREG = est.NOREG AND STATUS = 'BARU')");
					$cek_data_status_map = $cek_data_status_map->row();
					if($cek_data_status_map->STATUS_MAP != '' ){
						$five_db->query("INSERT INTO EMR_STATUS_PASIEN ([NOREG], [STATUS_PASIEN], [TGL_INSERT], [OLEH_INSERT], [STATUS]) 
							VALUES ('$cek_rj_emr_get->noreg', '$cek_data_status_map->STATUS_MAP', '$tgl_ins', 'System', 'BARU');
							");
					}else{

					}
				}
				
			}else{
				$tgl_ins = date('Y-m-d H:i:s');
				$nama_pasien = str_replace("'","`",$cek_rj_emr_get->nama_pasien);
				$nama_dokter = str_replace("'","`",$cek_rj_emr_get->nama_dokter);
				$jalan = str_replace("'","`",$cek_rj_emr_get->jalan);
				$nminstansi = str_replace("'","`",$cek_rj_emr_get->nminstansi);
				$five_db->query("INSERT INTO EMR_GET_DATA_KUNJUNGAN (
					noreg,
					norm,
					kdseks,
					nama_pasien,
					jamreg,
					nama_dokter,
					nminstansi,
					nmvisite,
					kdtipevisit,
					poli,
					kdpoli,
					tglinsert,
					tgl_reg,
					kddokter,
					kdinstansi,
					batal,
					noSEPBPJS,
					NoPesertaBPJS,
					umurhari,
					umurbulan,
					umurtahun,
					jalan,
					status,
					tgl_inject,
					noAntrian
					)
					VALUES
					(
					'$cek_rj_emr_get->noreg',
					'$cek_rj_emr_get->norm',
					'$cek_rj_emr_get->kdseks',
					'$nama_pasien',
					'$cek_rj_emr_get->jamreg',
					'$nama_dokter',
					'$nminstansi',
					'$cek_rj_emr_get->nmvisite',
					'$cek_rj_emr_get->kdtipevisit',
					'$cek_rj_emr_get->poli',
					'$cek_rj_emr_get->kdpoli',
					'$cek_rj_emr_get->tglinsert',
					'$cek_rj_emr_get->tgl_reg',
					'$cek_rj_emr_get->kddokter',
					'$cek_rj_emr_get->kdinstansi',
					'$cek_rj_emr_get->batal',
					'$cek_rj_emr_get->noSEPBPJS',
					'$cek_rj_emr_get->NoPesertaBPJS',
					'$cek_rj_emr_get->umurhari',
					'$cek_rj_emr_get->umurbulan',
					'$cek_rj_emr_get->umurtahun',
					'$jalan',
					0,
					'$tgl_ins',
					'$cek_rj_emr_get->antrian')");
				$three_db->query("UPDATE rj_reg SET get_status_k = '1' WHERE noreg = '$cek_rj_emr_get->noreg'");
				
				echo "Sudah Inject Data ".$cek_rj_emr_get->noreg;

				$cek_no_rm_kosong = $five_db->query("SELECT TOP (1) norm FROM EMR_GET_DATA_KUNJUNGAN
					WHERE tgl_lahir IS NULL ORDER BY tgl_reg DESC ");
				if($cek_no_rm_kosong->num_rows() > 0){
					$cek_no_rm_kosong = $cek_no_rm_kosong->row();
					$cek_tgl_lahir = $three_db->query("SELECT tgllahir FROM Pasien
						WHERE norm = '$cek_no_rm_kosong->norm'")->row();
					$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET tgl_lahir = '$cek_tgl_lahir->tgllahir' WHERE norm = '$cek_no_rm_kosong->norm'");
				}else{

				}

				$cek_data_status_map_n = $five_db->query("SELECT TOP
					( 1 )
					est.STATUS_PASIEN AS STATUS_MAP
					FROM
					EMR_STATUS_PASIEN est					
					WHERE
					1 = 1 
					AND est.NOREG = '$cek_rj_emr_get->noreg'");
				
				if($cek_data_status_map_n->num_rows() > 0 ){

				}else{
					$cek_data_status_map = $five_db->query("SELECT TOP(1) aut.NORM, est.STATUS_PASIEN AS STATUS_MAP, est.OLEH_INSERT, est.TGL_INSERT, err.link 
						FROM EMR_STATUS_PASIEN est 
						INNER JOIN EMR_UTAMA_PERIKSA aut ON aut.NOREG = est.NOREG
						INNER JOIN EMR_RM_STATUS_MAP err ON err.jenis_status = est.STATUS_PASIEN
						WHERE 1 = 1
						AND est.STATUS = 'BARU'
						AND aut.NORM = '$cek_rj_emr_get->norm'
						AND est.TGL_INSERT = (SELECT (MAX(TGL_INSERT)) FROM EMR_STATUS_PASIEN
						WHERE NOREG = est.NOREG AND STATUS = 'BARU')");
					$cek_data_status_map = $cek_data_status_map->row();
					if($cek_data_status_map->STATUS_MAP != '' ){
						$five_db->query("INSERT INTO EMR_STATUS_PASIEN ([NOREG], [STATUS_PASIEN], [TGL_INSERT], [OLEH_INSERT], [STATUS]) 
							VALUES ('$cek_rj_emr_get->noreg', '$cek_data_status_map->STATUS_MAP', '$tgl_ins', 'System', 'BARU');
							");
					}else{

					}
					
				}

			}
			// $five_db->query("DELETE FROM EMR_STATUS_PASIEN WHERE STATUS_PASIEN = ''");

		}else{
			echo "Tidak Ada Data";

			$cek_no_rm_kosong = $five_db->query("SELECT TOP (1) norm FROM EMR_GET_DATA_KUNJUNGAN
				WHERE tgl_lahir IS NULL ORDER BY tgl_reg DESC");
			if($cek_no_rm_kosong->num_rows() > 0){
				$cek_no_rm_kosong = $cek_no_rm_kosong->row();
				$cek_tgl_lahir = $three_db->query("SELECT tgllahir FROM Pasien
					WHERE norm = '$cek_no_rm_kosong->norm'")->row();
				$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET tgl_lahir = '$cek_tgl_lahir->tgllahir' WHERE norm = '$cek_no_rm_kosong->norm'");
			}else{

			}
		}


	}

	function insert_rd_emr(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$tgl_mulai = date("Y-m-d", strtotime($this->input->post('tgl_mulai')));
		$tgl_akhir = date("Y-m-d", strtotime($this->input->post('tgl_akhir')));
		$keterangan = $this->input->post('keterangan'); 
		$cek_rj_emr = $three_db->query("SELECT TOP (1)
			LTRIM(RTRIM(rd_reg.noreg)) AS noreg,
			LTRIM(RTRIM(rd_reg.norm)) AS norm,
			pasien.kdseks AS kdseks,
			LTRIM(RTRIM(pasien.nama)) AS nama_pasien,
			rd_reg.jamdatang AS jamreg,
			LTRIM(RTRIM(medis.nama))AS nama_dokter,
			LTRIM(RTRIM(instansi.nminstansi)) AS nminstansi,
			'' AS nmvisite,
			'0' AS kdtipevisit,
			'INSTALASI GAWAT DARURAT' AS poli,
			'IGD' AS kdpoli,
			rd_reg.tglinsert,
			rd_reg.tgldatang AS tgl_reg,
			LTRIM(RTRIM(rd_reg.kddokter)) AS kddokter,
			LTRIM(RTRIM(rd_reg.kdperusahaan)) AS kdinstansi,
			rd_reg.batal,
			rd_reg.noSEPBPJS,
			Pasien.NoPesertaBPJS,
			rd_reg.umurhari,
			rd_reg.umurbulan,
			rd_reg.umurtahun,
			LTRIM(RTRIM(Pasien.jalan)) AS jalan
			FROM
			rd_reg
			LEFT JOIN Pasien ON rd_reg.norm = Pasien.norm
			LEFT JOIN instansi ON rd_reg.kdperusahaan = instansi.kdinstansi
			LEFT JOIN medis ON rd_reg.kddokter = medis.kode
			WHERE
			CAST(rd_reg.tglinsert AS DATE) BETWEEN '$tgl_mulai' AND '$tgl_akhir'
			AND batal = 0
			AND get_status_k IS NULL
			ORDER BY rd_reg.tglinsert DESC
			");
		if($cek_rj_emr->num_rows() > '0'){
			$cek_rj_emr_get = $cek_rj_emr->row();
			$cek_data = $five_db->query("SELECT noreg, norm FROM EMR_GET_DATA_KUNJUNGAN
				WHERE noreg = '$cek_rj_emr_get->noreg'");
			if($cek_data->num_rows() > '0'){
				$three_db->query("UPDATE rd_reg SET get_status_k = '1' WHERE noreg = '$cek_rj_emr_get->noreg'");
				echo "Sudah Ada Data ".$cek_rj_emr_get->noreg;

			}else{
				$tgl_ins = date('Y-m-d H:i:s');
				$nama_pasien = str_replace("'","`",$cek_rj_emr_get->nama_pasien);
				$nama_dokter = str_replace("'","`",$cek_rj_emr_get->nama_dokter);
				$jalan = str_replace("'","`",$cek_rj_emr_get->jalan);
				$nminstansi = str_replace("'","`",$cek_rj_emr_get->nminstansi);
				$five_db->query("INSERT INTO EMR_GET_DATA_KUNJUNGAN (
					noreg,
					norm,
					kdseks,
					nama_pasien,
					jamreg,
					nama_dokter,
					nminstansi,
					nmvisite,
					kdtipevisit,
					poli,
					kdpoli,
					tglinsert,
					tgl_reg,
					kddokter,
					kdinstansi,
					batal,
					noSEPBPJS,
					NoPesertaBPJS,
					umurhari,
					umurbulan,
					umurtahun,
					jalan,
					status,
					tgl_inject
					)
					VALUES
					(
					'$cek_rj_emr_get->noreg',
					'$cek_rj_emr_get->norm',
					'$cek_rj_emr_get->kdseks',
					'$nama_pasien',
					'$cek_rj_emr_get->jamreg',
					'$nama_dokter',
					'$nminstansi',
					'$cek_rj_emr_get->nmvisite',
					'$cek_rj_emr_get->kdtipevisit',
					'$cek_rj_emr_get->poli',
					'$cek_rj_emr_get->kdpoli',
					'$cek_rj_emr_get->tglinsert',
					'$cek_rj_emr_get->tgl_reg',
					'$cek_rj_emr_get->kddokter',
					'$cek_rj_emr_get->kdinstansi',
					'$cek_rj_emr_get->batal',
					'$cek_rj_emr_get->noSEPBPJS',
					'$cek_rj_emr_get->NoPesertaBPJS',
					'$cek_rj_emr_get->umurhari',
					'$cek_rj_emr_get->umurbulan',
					'$cek_rj_emr_get->umurtahun',
					'$jalan',
					0,
					'$tgl_ins')");
				$three_db->query("UPDATE rd_reg SET get_status_k = '1' WHERE noreg = '$cek_rj_emr_get->noreg'");
				
				echo "Sudah Inject Data ".$cek_rj_emr_get->noreg;

				$cek_no_rm_kosong = $five_db->query("SELECT TOP (1) norm FROM EMR_GET_DATA_KUNJUNGAN
					WHERE tgl_lahir IS NULL ORDER BY tgl_reg DESC");
				if($cek_no_rm_kosong->num_rows() > '0'){
					$cek_no_rm_kosong = $cek_no_rm_kosong->row();
					$cek_tgl_lahir = $three_db->query("SELECT tgllahir FROM Pasien
						WHERE norm = '$cek_no_rm_kosong->norm'")->row();
					$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET tgl_lahir = '$cek_tgl_lahir->tgllahir' WHERE norm = '$cek_no_rm_kosong->norm'");
				}else{

				}

			}

		}else{
			echo "Tidak Ada Data";

			$cek_no_rm_kosong = $five_db->query("SELECT TOP (1) norm FROM EMR_GET_DATA_KUNJUNGAN
				WHERE tgl_lahir IS NULL ORDER BY tgl_reg DESC");
			if($cek_no_rm_kosong->num_rows() > '0'){
				$cek_no_rm_kosong = $cek_no_rm_kosong->row();
				$cek_tgl_lahir = $three_db->query("SELECT tgllahir FROM Pasien
					WHERE norm = '$cek_no_rm_kosong->norm'")->row();
				$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET tgl_lahir = '$cek_tgl_lahir->tgllahir' WHERE norm = '$cek_no_rm_kosong->norm'");
			}else{

			}

		}


	}


	function insert_ri_emr(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$tgl_mulai = date("Y-m-d", strtotime($this->input->post('tgl_mulai')));
		$tgl_akhir = date("Y-m-d", strtotime($this->input->post('tgl_akhir')));
		$keterangan = $this->input->post('keterangan'); 
		$cek_rj_emr = $three_db->query("SELECT TOP (1)
			LTRIM(RTRIM(ri_reg.noreg)) AS noreg,
			LTRIM(RTRIM(ri_reg.norm)) AS norm,
			pasien.kdseks AS kdseks,
			LTRIM(RTRIM(pasien.nama)) AS nama_pasien,
			ri_reg.jammasuk AS jamreg,
			LTRIM(RTRIM(medis.nama))AS nama_dokter,
			LTRIM(RTRIM(instansi.nminstansi)) AS nminstansi,
			'' AS nmvisite,
			'0' AS kdtipevisit,
			rg.nmruang AS poli,
			rg.kdruang AS kdpoli,
			ri_reg.tglinsert,
			ri_reg.tglmasuk AS tgl_reg,
			LTRIM(RTRIM(ri_reg.kddokter)) AS kddokter,
			LTRIM(RTRIM(instansi.kdinstansi)) AS kdinstansi,
			ri_reg.batal,
			ri_reg.noSEPBPJS,
			Pasien.NoPesertaBPJS,
			Pasien.umurhari,
			Pasien.umurbulan,
			Pasien.umurtahun,
			LTRIM(RTRIM(Pasien.jalan)) AS jalan,
			ri_reg.keluar,
			ri_reg.tglkeluar
			FROM
			ri_reg
			LEFT JOIN Pasien ON ri_reg.norm = Pasien.norm
			LEFT JOIN ri_penjaminBayar rpb ON rpb.noreg = ri_reg.noreg
			LEFT JOIN instansi ON rpb.kdinstansi = instansi.kdinstansi
			LEFT JOIN medis ON ri_reg.kddokter = medis.kode
			LEFT JOIN ruang rg ON rg.kdruang = ri_reg.kdruang
			WHERE
			CAST(ri_reg.tglinsert AS DATE) BETWEEN '$tgl_mulai' AND '$tgl_akhir'
			AND batal = 0
			AND get_status_k IS NULL
			ORDER BY ri_reg.tglinsert DESC
			");
		if($cek_rj_emr->num_rows() > '0'){
			$cek_rj_emr_get = $cek_rj_emr->row();
			$cek_data = $five_db->query("SELECT noreg, norm FROM EMR_GET_DATA_KUNJUNGAN
				WHERE noreg = '$cek_rj_emr_get->noreg'");
			if($cek_data->num_rows() > '0'){
				$three_db->query("UPDATE ri_reg SET get_status_k = '1' WHERE noreg = '$cek_rj_emr_get->noreg'");
				echo "Sudah Ada Data ".$cek_rj_emr_get->noreg;

				$cek_data_status_map_n = $five_db->query("SELECT TOP
					( 1 )
					est.STATUS_PASIEN AS STATUS_MAP
					FROM
					EMR_STATUS_PASIEN est					
					WHERE
					1 = 1 
					AND est.NOREG = '$cek_rj_emr_get->noreg'");
				
				if($cek_data_status_map_n->num_rows() > '0' ){

				}else{
					$cek_data_status_map = $five_db->query("SELECT TOP(1) aut.NORM, est.STATUS_PASIEN AS STATUS_MAP, est.OLEH_INSERT, est.TGL_INSERT, err.link 
						FROM EMR_STATUS_PASIEN est 
						INNER JOIN EMR_UTAMA_PERIKSA aut ON aut.NOREG = est.NOREG
						INNER JOIN EMR_RM_STATUS_MAP err ON err.jenis_status = est.STATUS_PASIEN
						WHERE 1 = 1
						AND est.STATUS = 'BARU'
						AND aut.NORM = '$cek_rj_emr_get->norm'
						AND est.TGL_INSERT = (SELECT (MAX(TGL_INSERT)) FROM EMR_STATUS_PASIEN
						WHERE NOREG = est.NOREG AND STATUS = 'BARU')");
					$cek_data_status_map = $cek_data_status_map->row();
					if($cek_data_status_map->STATUS_MAP != '' ){
						$five_db->query("INSERT INTO EMR_STATUS_PASIEN ([NOREG], [STATUS_PASIEN], [TGL_INSERT], [OLEH_INSERT], [STATUS]) 
							VALUES ('$cek_rj_emr_get->noreg', '$cek_data_status_map->STATUS_MAP', '$tgl_ins', 'System', 'BARU');
							");
					}else{

					}
					
				}

			}else{
				$tgl_ins = date('Y-m-d H:i:s');
				$nama_pasien = str_replace("'","`",$cek_rj_emr_get->nama_pasien);
				$nama_dokter = str_replace("'","`",$cek_rj_emr_get->nama_dokter);
				$jalan = str_replace("'","`",$cek_rj_emr_get->jalan);
				$nminstansi = str_replace("'","`",$cek_rj_emr_get->nminstansi);
				$five_db->query("INSERT INTO EMR_GET_DATA_KUNJUNGAN (
					noreg,
					norm,
					kdseks,
					nama_pasien,
					jamreg,
					nama_dokter,
					nminstansi,
					nmvisite,
					kdtipevisit,
					poli,
					kdpoli,
					tglinsert,
					tgl_reg,
					kddokter,
					kdinstansi,
					batal,
					noSEPBPJS,
					NoPesertaBPJS,
					umurhari,
					umurbulan,
					umurtahun,
					jalan,
					status,
					tgl_inject,
					keluar,
					tgl_keluar
					)
					VALUES
					(
					'$cek_rj_emr_get->noreg',
					'$cek_rj_emr_get->norm',
					'$cek_rj_emr_get->kdseks',
					'$nama_pasien',
					'$cek_rj_emr_get->jamreg',
					'$nama_dokter',
					'$nminstansi',
					'$cek_rj_emr_get->nmvisite',
					'$cek_rj_emr_get->kdtipevisit',
					'$cek_rj_emr_get->poli',
					'$cek_rj_emr_get->kdpoli',
					'$cek_rj_emr_get->tglinsert',
					'$cek_rj_emr_get->tgl_reg',
					'$cek_rj_emr_get->kddokter',
					'$cek_rj_emr_get->kdinstansi',
					'$cek_rj_emr_get->batal',
					'$cek_rj_emr_get->noSEPBPJS',
					'$cek_rj_emr_get->NoPesertaBPJS',
					'$cek_rj_emr_get->umurhari',
					'$cek_rj_emr_get->umurbulan',
					'$cek_rj_emr_get->umurtahun',
					'$jalan',
					0,
					'$tgl_ins',
					'$cek_rj_emr_get->keluar',
					'$cek_rj_emr_get->tglkeluar')");
				$three_db->query("UPDATE ri_reg SET get_status_k = '1' WHERE noreg = '$cek_rj_emr_get->noreg'");
				$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN
					SET tgl_keluar = NULL
					WHERE tgl_keluar = '1900-01-01 00:00:00.000'");
				
				echo "Sudah Inject Data ".$cek_rj_emr_get->noreg;

				$cek_no_rm_kosong = $five_db->query("SELECT TOP (1) norm FROM EMR_GET_DATA_KUNJUNGAN
					WHERE tgl_lahir IS NULL ORDER BY tgl_reg DESC");
				if($cek_no_rm_kosong->num_rows() > '0'){
					$cek_no_rm_kosong = $cek_no_rm_kosong->row();
					$cek_tgl_lahir = $three_db->query("SELECT tgllahir FROM Pasien
						WHERE norm = '$cek_no_rm_kosong->norm'")->row();
					$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET tgl_lahir = '$cek_tgl_lahir->tgllahir' WHERE norm = '$cek_no_rm_kosong->norm'");
				}else{

				}

				
				$cek_data_status_map_n = $five_db->query("SELECT TOP
					( 1 )
					est.STATUS_PASIEN AS STATUS_MAP
					FROM
					EMR_STATUS_PASIEN est					
					WHERE
					1 = 1 
					AND est.NOREG = '$cek_rj_emr_get->noreg'");
				
				if($cek_data_status_map_n->num_rows() > '0' ){

				}else{
					$cek_data_status_map = $five_db->query("SELECT TOP(1) aut.NORM, est.STATUS_PASIEN AS STATUS_MAP, est.OLEH_INSERT, est.TGL_INSERT, err.link 
						FROM EMR_STATUS_PASIEN est 
						INNER JOIN EMR_UTAMA_PERIKSA aut ON aut.NOREG = est.NOREG
						INNER JOIN EMR_RM_STATUS_MAP err ON err.jenis_status = est.STATUS_PASIEN
						WHERE 1 = 1
						AND est.STATUS = 'BARU'
						AND aut.NORM = '$cek_rj_emr_get->norm'
						AND est.TGL_INSERT = (SELECT (MAX(TGL_INSERT)) FROM EMR_STATUS_PASIEN
						WHERE NOREG = est.NOREG AND STATUS = 'BARU')");
					$cek_data_status_map = $cek_data_status_map->row();
					if($cek_data_status_map->STATUS_MAP != '' ){
						$five_db->query("INSERT INTO EMR_STATUS_PASIEN ([NOREG], [STATUS_PASIEN], [TGL_INSERT], [OLEH_INSERT], [STATUS]) 
							VALUES ('$cek_rj_emr_get->noreg', '$cek_data_status_map->STATUS_MAP', '$tgl_ins', 'System', 'BARU');
							");
					}else{

					}
					
				}


			}

		}else{
			$cek_no_rm_kosong = $five_db->query("SELECT TOP (1) norm FROM EMR_GET_DATA_KUNJUNGAN
				WHERE tgl_lahir IS NULL ORDER BY tgl_reg DESC");
			if($cek_no_rm_kosong->num_rows() > '0'){
				$cek_no_rm_kosong = $cek_no_rm_kosong->row();
				$cek_tgl_lahir = $three_db->query("SELECT tgllahir FROM Pasien
					WHERE norm = '$cek_no_rm_kosong->norm'")->row();
				$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET tgl_lahir = '$cek_tgl_lahir->tgllahir' WHERE norm = '$cek_no_rm_kosong->norm'");
			}else{

			}

			echo "Tidak Ada Data";
		}


	}

	function insert_ri_emr_lengkap(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$cek_data_ri_non_lengkap = $five_db->query("SELECT TOP(1) egdtr.noreg FROM EMR_GET_DATA_KUNJUNGAN egdtr 
			WHERE egdtr.noreg LIKE '%RI%'
			AND (egdtr.status_ri_lengkap = '0' OR egdtr.status_ri_lengkap = NULL)
			ORDER BY egdtr.noreg DESC");

		if($cek_data_ri_non_lengkap->num_rows() > '0'){
			$get_no_reg_lengkap = $cek_data_ri_non_lengkap->row();
			$cek_data_ri_reg = $three_db->query("SELECT
				rr.noreg AS noreg,
				rr.jammasuk AS jammasuk,
				medis.nama AS nama_dokter,
				ruang.nmruang AS nmruang,
				rr.kdruang,
				ri_kelas.nmkelas AS nmkelas,
				rr.tglinsert,
				rr.tglmasuk AS tgl_reg,
				rr.kdjmbayar,
				rr.noregrd,
				rr.noregrj,
				rr.tgltutup,
				rr.kdkelas
				FROM
				ri_reg rr
				FULL OUTER JOIN ruang ON rr.kdruang = ruang.kdruang
				FULL OUTER JOIN ri_kelas ON rr.kdkelas = ri_kelas.kdkelas
				FULL OUTER JOIN medis ON rr.kddokter = medis.kode
				WHERE
				rr.noreg = '$get_no_reg_lengkap->noreg'
				ORDER BY
				rr.tglinsert DESC")->row();
			$five_db->query("INSERT INTO EMR_GET_DATA_DETAIL_KUNJUNGAN_RI (no_reg_ri, noregrd, noregrj, tgltutup, kdkelas, nmkelas, tgl_insert) 
				VALUES ('$cek_data_ri_reg->noreg', '$cek_data_ri_reg->noregrd', '$cek_data_ri_reg->noregrj', '$cek_data_ri_reg->tgltutup', '$cek_data_ri_reg->kdkelas', '$cek_data_ri_reg->nmkelas', '".date('Y-m-d H:i:s')."');
				");
			$five_db->query("UPDATE EMR_GET_DATA_KUNJUNGAN SET status_ri_lengkap = '1'
				WHERE noreg = '$cek_data_ri_reg->noreg'");
			$five_db->query("DELETE T
				FROM
				(
				SELECT *
				, DupRank = ROW_NUMBER() OVER (
				PARTITION BY ID_PEMERIKSAAN, DIAGNOSA, DIAGNOSA_LAIN, DIAGNOSA_BIDAN, [NO], OLEH_INSERT, PPA
				
				ORDER BY (SELECT NULL)
				)
				FROM EMR_DIAGNOSA_PERAWAT
				) AS T
				WHERE DupRank > 1 ");
			$five_db->query("DELETE T
				FROM
				(
				SELECT *
				, DupRank = ROW_NUMBER() OVER (
				PARTITION BY ID_PEMERIKSAAN,
				NOREG,
				CPPT_S,
				INSTRUKSI,
				CATATAN_LAIN,
				CPPT_O,
				TANGGAL,
				JAM,
				PPA,
				STATUS,
				[NO],
				STATUS_APPROVE,
				APPROVE_OLEH,
				CPPT_A,
				CPPT_P,
				STATUS_SIMPAN,
				TUJUAN,
				KODE_TUJUAN


				ORDER BY (SELECT NULL)
				)
				FROM EMR_CPPT_RANAP
				) AS T
				WHERE DupRank > 1");

		}else{
			echo "Tidak Ada Data";
			$five_db->query("DELETE T
				FROM
				(
				SELECT *
				, DupRank = ROW_NUMBER() OVER (
				PARTITION BY ID_PEMERIKSAAN, DIAGNOSA, DIAGNOSA_LAIN, DIAGNOSA_BIDAN, [NO], OLEH_INSERT, PPA
				
				ORDER BY (SELECT NULL)
				)
				FROM EMR_DIAGNOSA_PERAWAT
				) AS T
				WHERE DupRank > 1 ");

			$five_db->query("DELETE T
				FROM
				(
				SELECT *
				, DupRank = ROW_NUMBER() OVER (
				PARTITION BY ID_PEMERIKSAAN,
				NOREG,
				CPPT_S,
				INSTRUKSI,
				CATATAN_LAIN,
				CPPT_O,
				TANGGAL,
				JAM,
				PPA,
				STATUS,
				[NO],
				STATUS_APPROVE,
				APPROVE_OLEH,
				CPPT_A,
				CPPT_P,
				STATUS_SIMPAN,
				TUJUAN,
				KODE_TUJUAN


				ORDER BY (SELECT NULL)
				)
				FROM EMR_CPPT_RANAP
				) AS T
				WHERE DupRank > 1");	
		}

	}

	function submit_info_pasien_reg(){
		$second_db = $this->load->database('second', TRUE);
		$three_db = $this->load->database('three', TRUE);
		$five_db = $this->load->database('five', TRUE);
		date_default_timezone_set("Asia/Bangkok");

		$tgl_mulai = date("Y-m-d", strtotime($this->input->post('tgl_mulai')));
		$tgl_akhir = date("Y-m-d", strtotime($this->input->post('tgl_akhir')));
		$keterangan = $this->input->post('keterangan');

		if($keterangan == '1'){
			$cek_no_reg_ins = $second_db->query("SELECT
				bri.noreg
				FROM
				bpjs_reg_insert_ri AS bri
				WHERE
				1 = 1 AND
				CAST(bri.tgl_insert AS DATE) BETWEEN '$tgl_mulai' AND '$tgl_akhir'
				AND insert_info_pasien = '0'
				ORDER BY bri.tgl_insert ASC LIMIT 1")->result_array();
			foreach ($cek_no_reg_ins as $cek_no_reg_ins_new) {
				$noreg = $cek_no_reg_ins_new['noreg'];
				$cek_no_reg_ri = $three_db->query("SELECT
					LTRIM(RTRIM(rr.norm)) AS norm,
					LTRIM(RTRIM(rr.noreg)) AS noreg,
					LTRIM(RTRIM(pas.nama)) AS nama,
					pas.notelepon,
					rr.kdruang,
					rr.kdkelas,
					rr.kddokter,
					rr.jammasuk,
					rr.usrinsert,
					rr.tglinsert,
					pas.tgllahir,
					pas.kdseks
					FROM ri_reg rr
					INNER JOIN Pasien pas ON LTRIM(RTRIM(pas.norm)) = LTRIM(RTRIM(rr.norm))
					WHERE 1 = 1
					AND LTRIM(RTRIM(rr.noreg)) = '$noreg'
					")->result_array();

				foreach ($cek_no_reg_ri as $cek_no_reg_new) {
					$noreg = $cek_no_reg_new['noreg'];
					$norm = $cek_no_reg_new['norm'];
					// $nama = $cek_no_reg_new['nama'];
					$nama = str_replace("'","`",$cek_no_reg_new['nama']);
					$tgllahir = $cek_no_reg_new['tgllahir'];
					$kdseks = $cek_no_reg_new['kdseks'];

					$cek_bpjs_insert_reg_ri = $second_db->query("SELECT
						brinf.noreg,
						brinf.norm,
						brinf.nama_pasien,
						brinf.tgl_lahir,
						brinf.seks
						FROM `bpjs_reg_info` brinf
						WHERE 1 = 1
						AND brinf.noreg = '$noreg'");
					if($cek_bpjs_insert_reg_ri->num_rows() == '0'){
						$second_db->query("INSERT INTO `bpjs_reg_info` (
							`noreg`,
							`norm`,
							`nama_pasien`,
							`tgl_lahir`,
							`seks`,
							tgl_input
							)
							VALUES
							('$noreg', '$norm', '$nama', '$tgllahir', '$kdseks','".date('Y-m-d H:i:s')."');

							");
						echo "Sukses INSERT INFO ".$noreg."";
						$second_db->query("UPDATE bpjs_reg_insert_ri SET insert_info_pasien = '1' WHERE noreg = '$noreg'");
					}else{
						$second_db->query("UPDATE bpjs_reg_insert_ri SET insert_info_pasien = '1' WHERE noreg = '$noreg'");
						echo "Sudah INSERT INFO ".$noreg."";
					}

				}
			}
			
		} else if($keterangan == '2'){

			$cek_no_reg_ins = $second_db->query("SELECT
				bri.noreg
				FROM
				bpjs_reg_insert AS bri
				WHERE
				1 = 1 AND
				CAST(bri.tgl_insert AS DATE) BETWEEN '$tgl_mulai' AND '$tgl_akhir'
				AND insert_info_pasien = '0'
				ORDER BY bri.tgl_insert ASC LIMIT 1")->result_array();
			foreach ($cek_no_reg_ins as $cek_no_reg_ins_new) {
				$noreg = $cek_no_reg_ins_new['noreg'];
				$cek_no_reg_rj = $three_db->query("SELECT
					LTRIM(RTRIM(rr.norm)) AS norm,
					LTRIM(RTRIM(rr.noreg)) AS noreg,
					LTRIM(RTRIM(pas.nama)) AS nama,
					pas.notelepon,
					rr.kdinstansi,
					rr.ketinstansi,
					rr.kdpoli,
					rr.kddokter,
					rr.jamreg,
					rr.usrinsert,
					rr.tglinsert,
					pas.tgllahir,
					pas.kdseks
					FROM rj_reg rr
					INNER JOIN Pasien pas ON LTRIM(RTRIM(pas.norm)) = LTRIM(RTRIM(rr.norm))
					WHERE 1 = 1
					AND LTRIM(RTRIM(rr.noreg)) = '$noreg'
					")->result_array();

				foreach ($cek_no_reg_rj as $cek_no_reg_new) {
					$noreg = $cek_no_reg_new['noreg'];
					$norm = $cek_no_reg_new['norm'];
					$nama = str_replace("'","`",$cek_no_reg_new['nama']);
					$tgllahir = $cek_no_reg_new['tgllahir'];
					$kdseks = $cek_no_reg_new['kdseks'];

					$cek_bpjs_insert_reg_rj = $second_db->query("SELECT
						brinf.noreg,
						brinf.norm,
						brinf.nama_pasien,
						brinf.tgl_lahir,
						brinf.seks
						FROM `bpjs_reg_info` brinf
						WHERE 1 = 1
						AND brinf.noreg = '$noreg'");
					if($cek_bpjs_insert_reg_rj->num_rows() == '0'){
						$second_db->query("INSERT INTO `bpjs_reg_info` (
							`noreg`,
							`norm`,
							`nama_pasien`,
							`tgl_lahir`,
							`seks`,
							tgl_input
							)
							VALUES
							('$noreg', '$norm', '$nama', '$tgllahir', '$kdseks','".date('Y-m-d H:i:s')."');

							");
						echo "Sukses INSERT INFO ".$noreg."";
						$second_db->query("UPDATE bpjs_reg_insert SET insert_info_pasien = '1' WHERE noreg = '$noreg'");
					}else{
						$second_db->query("UPDATE bpjs_reg_insert SET insert_info_pasien = '1' WHERE noreg = '$noreg'");
						echo "Sudah INSERT INFO ".$noreg."";
					}

				}
			}

			
		} else {
			$cek_no_reg_ins = $second_db->query("SELECT
				bri.noreg
				FROM
				bpjs_reg_insert_rd AS bri
				WHERE
				1 = 1 AND
				CAST(bri.tgl_insert AS DATE) BETWEEN '$tgl_mulai' AND '$tgl_akhir'
				AND insert_info_pasien = '0'
				ORDER BY bri.tgl_insert ASC LIMIT 1")->result_array();
			foreach ($cek_no_reg_ins as $cek_no_reg_ins_new) {
				$noreg = $cek_no_reg_ins_new['noreg'];
				$cek_no_reg_rd = $three_db->query("SELECT
					LTRIM(RTRIM(rr.norm)) AS norm,
					LTRIM(RTRIM(rr.noreg)) AS noreg,
					LTRIM(RTRIM(pas.nama)) AS nama,
					pas.notelepon,
					rr.kdperusahaan,
					rr.ketinstansi,
					rr.kddokter,
					rr.jamdatang,
					rr.usrinsert,
					rr.tglinsert,
					pas.tgllahir,
					pas.kdseks
					FROM rd_reg rr
					INNER JOIN Pasien pas ON LTRIM(RTRIM(pas.norm)) = LTRIM(RTRIM(rr.norm))
					WHERE 1 = 1
					AND LTRIM(RTRIM(rr.noreg)) = '$noreg'
					")->result_array();

				foreach ($cek_no_reg_rd as $cek_no_reg_new) {
					$noreg = $cek_no_reg_new['noreg'];
					$norm = $cek_no_reg_new['norm'];
			// $nama = $cek_no_reg_new['nama'];
					$nama = str_replace("'","`",$cek_no_reg_new['nama']);
					$tgllahir = $cek_no_reg_new['tgllahir'];
					$kdseks = $cek_no_reg_new['kdseks'];

					$cek_bpjs_insert_reg_rd = $second_db->query("SELECT
						brinf.noreg,
						brinf.norm,
						brinf.nama_pasien,
						brinf.tgl_lahir,
						brinf.seks
						FROM `bpjs_reg_info` brinf
						WHERE 1 = 1
						AND brinf.noreg = '$noreg'");
					if($cek_bpjs_insert_reg_rd->num_rows() == '0'){
						$second_db->query("INSERT INTO `bpjs_reg_info` (
							`noreg`,
							`norm`,
							`nama_pasien`,
							`tgl_lahir`,
							`seks`,
							tgl_input
							)
							VALUES
							('$noreg', '$norm', '$nama', '$tgllahir', '$kdseks','".date('Y-m-d H:i:s')."');

							");
						echo "Sukses INSERT INFO ".$noreg."";
						$second_db->query("UPDATE bpjs_reg_insert_rd SET insert_info_pasien = '1' WHERE noreg = '$noreg'");
					}else{
						$second_db->query("UPDATE bpjs_reg_insert_rd SET insert_info_pasien = '1' WHERE noreg = '$noreg'");
						echo "Sudah INSERT INFO ".$noreg."";
					}

				}
			}
			
		}
	}


}
?>