<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pad extends CI_Controller {

	public function __costruct()
	{
		parent::__costruct();
		date_default_timezone_set('Asia/Jakarta');
		$dbsecond = $this->load->database('five',TRUE);
	}

	public function index()
	{
		$this->load->view('pad');
	}

	public function ins_data()
	{
	
		$norm = $this->input->post('norm');
		$ttd = $_SERVER['DOCUMENT_ROOT']."/".$this->input->post('file');
		$dateins= DATE("Y-m-d h:i:s");
		$ip_insert= $_SERVER['REMOTE_ADDR'];  
		$dbsecond = $this->load->database('five',TRUE);
		$dbsecond->query("INSERT INTO EMR_TTD_PASIEN (No_rm,ttd,tgl_insert,ip_insert) VALUES ('$norm','$ttd','$dateins','$ip_insert')");
		$this->load->view('pad');
	}

	public function get_all_sign()
	{
		$ttd['data']=$db_mr_ttd->query("SELECT * FROM ttd_pasien");
		$data=$ttd->result_array();
		$cnt=$ttd->num_rows($ttd); 
		$this->load->view('v_all_sign',$ttd);
	}

	public function get_mr()
	{
		$norm = $this->input->post('norm');
		$five_db = $this->load->database('five',TRUE);
		$three_db = $this->load->database('three',TRUE);

		// ambil data pasien
		$get_px = $three_db->query("SELECT nama, kdseks, umurtahun, jalan FROM Pasien WHERE norm = '$norm'");
		if($get_px->num_rows() > 0){
			$nama_pasien = $get_px->row()->nama;
			$jk = $get_px->row()->kdseks;
			$umur = $get_px->row()->umurtahun;
			$alamat = $get_px->row()->jalan;
		}else{
			$nama_pasien = '';
			$jk = '';
			$umur = '';
			$alamat = '';
		}

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

		echo json_encode([
			'nama' => $nama_pasien,
			'jenis_kelamin' => $jenis_kelamin,
			'umur' => $umur,
			'alamat' => $alamat,
			'ttd' => $url_ttd
		]);
		
	}

	public function upd_sign()
	{
		$norm = $this->input->post('norm');
		$ttd_img = $_FILES['gambar']['tmp_name'];
		$image_raw= addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
		$db_mr_ttd = $this->load->database('five',TRUE);
		$db_mr_ttd->query("Update EMR_TTD_PASIEN set img='{$image_raw}' WHERE No_rm='$norm'");
		echo "Patient Sign".$norm." Updated"; 
	}

	public function del_mr()
	{
		$norm = $this->input->get('norm');
		$db_mr_ttd = $this->load->database('five',TRUE);
		$db_mr_ttd->query("Delete FROM EMR_TTD_PASIEN WHERE No_rm='$norm'");
		echo "Data Deleted";
		$path="ttd_pasien/";
		$ImagePath=$path.$norm.".png";
		unlink($ImagePath);

	}

	public function upload()
	{ 
		//$dbsecond = $this->load->database('second',TRUE);
		// $dbfive = $this->load->database('five',TRUE);
		// date_default_timezone_set('Asia/Jakarta');
		// $dateins= DATE("Y-m-d h:i:s");
		// $ip_server= $_SERVER['SERVER_ADDR'];
		// $ip_insert= $_SERVER['REMOTE_ADDR'];
		// $norm=explode(".",$_FILES['gambar']['name']);
		// $rm_str=json_encode($norm[0]); 
		// $rm=str_replace("\"","",$rm_str);
		// $ttd_img = $_FILES['gambar']['tmp_name'];
		

		// //Check DB
		// $ttd=$dbfive->query("SELECT * FROM EMR_TTD_PASIEN WHERE No_rm='$norm'");
	
		// $data=$ttd->result_array();
		// $cnt=$ttd->num_rows($ttd); 
		
		// //echo "<script> alert($cnt)</script>";

		
		// //$image_raw= addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
		// //$image_raw= file_get_contents($_FILES['gambar']['tmp_name']);
		// $path="ttd_pasien/";
		// $ImagePath=$path.$rm.".png";
		// 	// Check Image Folder
		// $ttd_l ="http://".$ip_server."/royal_pro/".$ImagePath;
		// if (file_exists($ImagePath))
		// {
		// 	//echo "<center>Signature Already Exist";
		// 	//echo br(2);
		// 	//echo anchor('/Pad','<button="button" >Home</button>'); 
		// 	//echo "</center>";
		// 	unlink($ImagePath);
		// 	clearstatcache(true, $ImagePath);
		// 	if (file_exists($ImagePath)) {
		// 		die("File masih ada! Cek permission atau proses unlink()");
		// 	}
		// 	$sts="update";
		// 	$dbfive->query("UPDATE EMR_TTD_PASIEN set tgl_update ='$dateins',ip_insert='$ip_insert',status='$sts' WHERE No_rm='$rm'");
		// 	move_uploaded_file($ttd_img,$ImagePath);
		// 	touch($ttd_img);
		// 	//echo "<script> alert("File updated")</script>";
		// 	clearstatcache();
		// 	redirect('/pad');

 		// }
		// else
		// {
		// 	$sts="Baru";
		// 	move_uploaded_file($ttd_img,$ImagePath);
		// 	clearstatcache();
		// 	$dbfive->query("INSERT INTO EMR_TTD_PASIEN (No_rm,ttd,tgl_insert,ip_insert,status) VALUES ('$rm','$ttd_l','$dateins','$ip_insert','$sts')");
			
		// 	touch($ttd_img);
		// 	// redirect('/pad');
		// }

	
		// clearstatcache();
		// touch($ttd_img);
		// $this->output->delete_cache();

		// block browser untuk tidak simpan
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$dbfive = $this->load->database('five', TRUE);
		date_default_timezone_set('Asia/Jakarta');
		$dateins = DATE("Y-m-d h:i:s");
		$ip_server = $_SERVER['SERVER_ADDR'];
		$ip_insert = $_SERVER['REMOTE_ADDR'];

		$norm = explode(".", $_FILES['gambar']['name']);
		$rm_str = json_encode($norm[0]); 
		$rm = str_replace("\"", "", $rm_str);
		$ttd_img = $_FILES['gambar']['tmp_name'];

		$timestamp = time();
		$path = "ttd_pasien/";
		$nama_ttd = $rm ."_". $timestamp;
		$ImagePath = $path . $rm ."_". $timestamp . ".png";
		$ttd_l = "http://" . $ip_server . "/royal_pro/" . $ImagePath;

		// Hapus foto yang lama di dalam folder ttd_pasien
		$files = glob("ttd_pasien/" . $rm . "*.png");
		foreach ($files as $file) {
			unlink($file);
		}

		// cek data di database
		$cek_data = $dbfive->query("SELECT * FROM EMR_TTD_PASIEN WHERE No_rm = '$rm'");
		// jika ada
		if($cek_data->num_rows() > 0){
			// update data menjadi revisi
			$dbfive->query("UPDATE EMR_TTD_PASIEN SET tgl_update='$dateins', ip_insert='$ip_insert', status='REVISI' WHERE No_rm='$rm'");
		}

		// jika tidak ada maka insert baru 
		$dbfive->query("INSERT INTO EMR_TTD_PASIEN (No_rm, ttd, tgl_insert, ip_insert, status) VALUES ('$rm', '$ttd_l', '$dateins', '$ip_insert', 'BARU')");

		// if (file_exists($ImagePath)) {
		// 	unlink($ImagePath);
		// 	clearstatcache(true, realpath($ImagePath));
		// 	$sts = "update";
		// 	$dbfive->query("UPDATE EMR_TTD_PASIEN SET tgl_update='$dateins', ip_insert='$ip_insert', status='$sts' WHERE No_rm='$rm'");
		// } else {
		// 	$sts = "Baru";
		// 	$dbfive->query("INSERT INTO EMR_TTD_PASIEN (No_rm, ttd, tgl_insert, ip_insert, status) VALUES ('$rm', '$ttd_l', '$dateins', '$ip_insert', '$sts')");
		// }

		move_uploaded_file($ttd_img, $ImagePath);
		clearstatcache();

		// Response JSON untuk AJAX
		echo json_encode(["status" => "success", "filename" => $nama_ttd . ".png"]);
	}

	public function delete_file()
	{
		$norm=$this->input->post('norm');
		$ip_server= $_SERVER['SERVER_ADDR'];
		$ttd ="http://".$ip_server."/royal_pro/".$norm."png";
		ulink($ttd);

	}

	public function reload_img()
	{
		$rm=$this->input->post('norm');
		$ip_server= $_SERVER['SERVER_ADDR'];
		$path="ttd_pasien/";
		$ImagePath=$path.$rm.".png";
		//$ttd_l ="http://".$ip_server."/royal_pro/".$ImagePath;
		echo $ImagePath;
	
	}

}
