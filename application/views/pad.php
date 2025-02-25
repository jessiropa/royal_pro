<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Signature</title>
  <meta name="viewport"
    content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="0">
  <meta http-equiv="pragma" content="no-cache">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/signature-pad.css">
  
</head>

<body onselectstart="return false">
     
  <!--<div id="signature-pad" class="signature-pad">-->
    <div id="signature-pad">
  <div>
    <label><h1>Form Tanda Tangan Pasien:</h1></label>
    <label>No.RM </label>
    <input type="text" name="norm" id ="norm" placeholder="input Nomor Rekam Medis Pasien disini" />  
    <!-- <button type="button" class="button" data-action="get_rm">Cari</button>          -->
    <button type="button" class="button" onclick="cari_norm()">Cari</button>     
    <button type="button" class="button" data-action="home">Refresh</button>    
  </div>
  <br/>
  <!--<div id="canvas-wrapper" class="signature-pad--body">-->
  <center>  
  <fieldset>
      <legend>Info Pasien : </legend>
      <div style="text-align: left;">
        <p id="info_pasien"> 
        </p>
      </div>
  </fieldset>
  <div id="canvas-wrapper">   
  <fieldset>
      <legend>Pad Tanda tangan:</legend>      
      <canvas width="600" height="250" style="border:1px solid #000000;"></canvas>
      </fieldset>    
    </div>
    </center>
    <div class="signature-pad--footer">
    
      <div class="signature-pad--actions">
        <fieldset>
          <legend>Navigasi Pad:</legend>   
        <div class="column">
          <button type="button" class="button clear" data-action="clear">Hapus Ttd</button>
          <button type="button" class="button" data-action="undo" title="Ctrl-Z" disabled>Undo</button>
          <button type="button" class="button" data-action="redo" title="Ctrl-Y" disabled>Redo</button>
          <button type="button" class="button" data-action="change-color" disabled>Warna</button>
          <button type="button" class="button" data-action="change-width" disabled>Ukuran</button>
          <button type="button" class="button" data-action="change-background-color" disabled>Background</button>
        </div>
      </fieldset>
      <fieldset>
      <legend>Generate:</legend>
        <div class="column">
        <button type="button" class="button save" data-action="save-png">Download</button>
        <!-- <button type="button" class="button save" onclick="download_pict()">Download</button> -->
       
        </div>
     </fieldset>  
     <!-- <fieldset>
      <legend> Upload </legend>
        <div class="column">
        <form method="post" action="<?php echo base_url();?>index.php/pad/upload" enctype="multipart/form-data">    
        <fieldset>      
          <input type="file" name="gambar" accept="image/*" />
        </fieldset>  
          <button type="submit" class="button save" >Simpan</button>
        </div>
    </fieldset>   -->
        <!-- </form>       -->
        <fieldset>
          <legend>Upload</legend>
          <div class="column">
            <input type="file" name="gambar" id="gambar" accept="image/*" required />
            <button type="button" class="button save" onclick="simpan_gambar()">Simpan</button>
          </div>
        </fieldset>
        <!-- <button type="button" class="button" data-action="home">Home</button> -->
      </div>
    </div>
    <fieldset>
      <legend>Info:</legend>
      <li>Tanda tangan dahulu.</li>
      <li>Setelah tanda tangan, Download File dulu.</li>
      <li>Hasil file tanda tangan kemudian di Simpan ke server.</li>
    </fieldset>
   
    <fieldset>
      <legend>View:</legend>
      <!-- <div><img id="wadahgambar"/></div> -->
      <div class="col-md-12" id="pic_ttd">
      </div>
      <div id="updategambar"></div>  
    </fieldset>
  </div>

  
  <script src="<?php echo base_url(); ?>assets/js/signature_pad.umd.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/app.js"></script>
  <!-- Tambahkan di <head> atau sebelum </body> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
  
  
  // pencarian norm dan gambar pasien 
  function cari_norm(){
    // console.log('ini tombol cari norm');
   norm = document.getElementById('norm').value;

    var data = {
        norm: norm
    };
   
    $.ajax({
      type: 'POST',
      url: "<?php echo base_url('index.php/Pad/get_mr/') ?>",
      data: data,
      dataType: 'json',
      success: function(data) {
        // $("#pic_ttd").html(data);
        // console.log(data);
        if (data.nama) {
                $("#info_pasien").html(
                    "Nama Pasien : " + data.nama + "<br>" +
                    "Jenis Kelamin : " + data.jenis_kelamin + "<br>" +
                    "Umur : " + data.umur + " Tahun<br>" +
                    "Alamat : " + data.alamat
                );
            } else {
                $("#info_pasien").html("<p>Data tidak ditemukan</p>");
            }

            if (data.ttd) {
                $("#pic_ttd").html('<img src="' + data.ttd + '" width="200" alt="Tanda Tangan Pasien">');
            } else {
                $("#pic_ttd").html('<p>Tanda tangan tidak tersedia</p>');
            }
      },
      error: function(xhr, status, error) {
        console.log(error);
      },
    });
   
  }

  function simpan_gambar(){
    let fileInput = document.getElementById("gambar");
    
    if (fileInput.files.length === 0) {
      alert("Pilih gambar terlebih dahulu!");
      return;
    }

    let formData = new FormData();
    formData.append("gambar", fileInput.files[0]);

    $.ajax({
      url: "<?php echo base_url('index.php/pad/upload'); ?>",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let data = JSON.parse(response);

        if (data.status === "success") {
          // Tampilkan gambar terbaru di halaman
          let imagePath = "http://<?php echo $_SERVER['SERVER_ADDR']; ?>/royal_pro/ttd_pasien/" + data.filename;
          $("#pic_ttd").html('<img src="' + imagePath + '" width="200" height="100" />');
        } else {
          alert("Upload gagal, coba lagi!");
        }
      },
      error: function () {
        alert("Terjadi kesalahan saat mengunggah gambar.");
      }
    });
  }


  </script>
</body>

</html>
