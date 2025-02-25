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

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/signature-pad.css">
  
</head>

<body onselectstart="return false">
     
 <!-- <div id="signature-pad" class="signature-pad"> -->
 <div id="signature-pad"> 
  <div>
    <label><h1>Form Tanda Tangan Pasien:</h1></label>
    <label>No.RM </label>
    <input type="text" name="norm" placeholder="input Nomor Rekam Medis Pasien disini" />  
    <button type="button" class="button" data-action="get_rm">Cari</button>         
  </div>
  <br/>
  <!-- <div id="canvas-wrapper" class="signature-pad--body"> -->
  <center>  
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
          <legend>Tanda Tangan:</legend>   
        <div class="column">
          <button type="button" class="button clear" data-action="clear">Clear</button>
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
        </div>
     </fieldset>  
     <fieldset>
      <legend> Upload </legend>
        <div class="column">
        <form method="post" action="<?php echo base_url();?>index.php/ttd/upload" enctype="multipart/form-data">    
        <fieldset>      
          <input type="file" name="gambar" accept="image/*" />
        </fieldset>  
          <button type="submit" class="button save" >Simpan</button>
        </div>
    </fieldset>  
        </form>      
        <button type="button" class="button" data-action="home">Home</button>
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
      <img id="wadahgambar"></img> 
    </fieldset>

  </div>


  <script src="<?php echo base_url(); ?>assets/js/signature_pad.umd.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/app_ok.js"></script>
</body>

</html>
