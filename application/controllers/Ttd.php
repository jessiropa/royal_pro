<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ttd extends CI_Controller {

	public function __costruct()
	{
		parent::__costruct();
		date_default_timezone_set('Asia/Jakarta');
		$dbsecond = $this->load->database('five',TRUE);
	}

	public function index()
	{
		$this->load->view('ttd');
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
		$norm = $this->input->get('norm');
		$db_mr_ttd = $this->load->database('five',TRUE);
		//$db_mr_ttd = $this->load->database('second',TRUE);
		$ttd=$db_mr_ttd->query("SELECT * FROM EMR_TTD_PASIEN WHERE No_rm='$norm'");
		//$ttd=$db_mr_ttd->query("SELECT * FROM ttd_pasien WHERE No_rm='$norm'");
		$data=$ttd->result_array();
		$cnt=$ttd->num_rows($ttd); 
		switch ($cnt)
		{
		case 0 :
		$wsiquery["RC"]="00";
		$wsiquery["data"]["status"]="00";
		$wsiquery["data"]["norm"]=$norm;
		$wsiquery["data"]["message"]="IMAGE Not Found";
		$json_re= json_encode($wsiquery);				
		echo $json_re;
		break;
		case 1 :	
		$image_data=$data[0]['ttd'];	
		//echo "<img style='width:300;height:300;' src='$image_data'/>";
		$wsiquery["RC"]="86";
		$wsiquery["data"]["status"]="86";
		$wsiquery["data"]["norm"]=$norm;
		$wsiquery["data"]["message"]=$image_data;
		$json_re= json_encode($wsiquery);				
		echo $json_re;
		//echo "<img style='width:300;height:300;' src='$image_data' />";	
			// Show BLOB IMAGE
			//echo "<center><img src='data:image;base64,".base64_encode($image_data)."' style='height:180px;width:200px;margin-left:22px;'></center>";
		break;
		default:
		$wsiquery["RC"]="00";
		$wsiquery["data"]["status"]="00";
		$wsiquery["data"]["norm"]=$norm;
		$wsiquery["data"]["message"]="IMAGE Not Found";
		$json_re= json_encode($wsiquery);				
		echo $json_re;
		break;	
		}
		
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
		$dbfive = $this->load->database('five',TRUE);
		date_default_timezone_set('Asia/Jakarta');
		$dateins= DATE("Y-m-d h:i:s");
		$ip_server= $_SERVER['SERVER_ADDR'];
		$ip_insert= $_SERVER['REMOTE_ADDR'];
		$norm=explode(".",$_FILES['gambar']['name']);
		$rm_str=json_encode($norm[0]); 
		$rm=str_replace("\"","",$rm_str);
		$ttd_img = $_FILES['gambar']['tmp_name'];
		

		//Check DB
		$ttd=$dbfive->query("SELECT * FROM EMR_TTD_PASIEN WHERE No_rm='$norm'");
	
		$data=$ttd->result_array();
		$cnt=$ttd->num_rows($ttd); 
		
		switch ($cnt)
		{
		case 0 :
		//$image_raw= addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
		//$image_raw= file_get_contents($_FILES['gambar']['tmp_name']);
		$path="ttd_pasien/";
		$ImagePath=$path.$rm.".png";

		$ttd_l ="http://".$ip_server."/royal_pro/".$ImagePath;
		if (file_exists($ImagePath))
		{
			//echo "<center>Signature Already Exist";
			//echo br(2);
			//echo anchor('/Pad','<button="button" >Home</button>'); 
			//echo "</center>";
			unlink($ImagePath);
			$dbfive->query("UPDATE EMR_TTD_PASIEN set tgl_update ='$dateins',ip_insert='$ip_insert' WHERE No_rm='$rm'");
			move_uploaded_file($ttd_img,$ImagePath);	
			clearstatcache('true',$ImagePath);
			redirect('/pad');
			

 		}
		else
		{

		//upload file to server folder used if need;	
		move_uploaded_file($ttd_img,$ImagePath);
		clearstatcache();
		$dbfive->query("INSERT INTO EMR_TTD_PASIEN (No_rm,ttd,tgl_insert,ip_insert) VALUES ('$rm','$ttd_l','$dateins','$ip_insert')");
		redirect('/pad');

		}

		break;
		case 1 :	
			echo "<script> $('#Modal').modal('show') </script>"; 
		break;
		default:
		   echo "<script> $('#Modal').modal('show') </script>";
		break;	
		}

		clearstatcache();
		touch($ttd_img);
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
