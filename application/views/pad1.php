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

  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/signature-pad1.css"> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

</head>
<!-- <header style="background: #e8ffd3;padding-bottom: 1%;">
	<img src="<?php echo base_url() . 'assets/img/royal.png'; ?>" height="100" width="240" onclick="window.location.reload();">
	<div style="float: right;">
		<img src="<?php echo base_url() . 'assets/img/logo_bpjs.png'; ?>" height="100" width="400" />
	</div>

</header> -->

<body style="background: #A7D5F2;">
  <style>
    .ui-autocomplete {
    z-index: 1050; /* Pastikan dropdown muncul di atas */
    background: white;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    }
    .ui-menu-item {
        padding: 5px;
        cursor: pointer;
    }
    .ui-state-active {
        background: #007bff !important;
        color: white !important;
    }

  </style>
  <br>
  <div id="signature-pad">
    <div class="container-fluid">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">
                      <h5 class="card-title">Tanda Tangan Farmasi - RS Royal Surabaya</h5>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-4">
                              <input type="text" class="form-control" placeholder="Masukkan No. Rekam Medis" name="norm" id ="norm">
                          </div>
                          <div class="col-md-6">
                              <button type="button" class="btn btn-success" onclick="cari_norm()">Cari</button>
                              <button type="button" class="btn btn-info" onclick="refresh()">Refresh</button>
                          </div>
                      </div>

                      <br>
                      <div class="card">
                          <div class="card-body">
                              <h5 class="card-title">Info Pasien</h5>
                              <p class="card-text" id="info_pasien">
                              </p>
                          </div>
                      </div>
                      <br>

                      <div class="card">
                        <h5 class="card-header">Tanda Tangan Pasien</h5>
                        <div class="card-body">
                              <div class="row justify-content-center">
                                  <canvas class="w-100" height="350" style="border:1px solid #000000;"></canvas>
                              </div>
                        </div>
                        <div class="card-footer text-muted">
                            <div class="col-md-12 d-flex justify-content-between">
                              <!-- <button type="button" class="btn btn-danger" onclick="hapus_ttd()" style="float: right;">Hapus Tanda Tangan</button>
                              <button type="button" class="btn btn-warning" id="lihat_resep" style="float: right;">Lihat Resep</button>
                              <button type="button" class="btn btn-primary" onclick="download_pict()">Simpan Tanda Tangan</button> -->
                              <button type="button" class="btn btn-primary" onclick="download_pict()">Simpan Tanda Tangan</button>
                              <button type="button" class="btn btn-warning mx-auto" id="lihat_resep">Lihat Resep</button>
                              <button type="button" class="btn btn-danger" onclick="hapus_ttd()">Hapus Tanda Tangan</button>
                            </div>
                        </div>
                      </div>
                      <br>
                      <div class="col-md-12" id="konfirmasi">
                      </div> <br>
                      <div class="card">
                          <div class="card-body">
                              <div class="col-md-12" id="pic_ttd">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <br>
          </div>
      </div>
    </div>
  </div>
  <!-- <style>
    #modalLoading .modal-body {
        max-height: 100vh;
        overflow-y: auto;
    }
  </style> -->
  <div class="modal fade" id="modalLoading" tabindex="-1" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">RESEP FARMASI</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div id="loadingSpinner"  style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p>Sedang memproses data...</p>
                </div>
                <div id="lihat_resepi"></div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="simpan_resep_farmasi()">Simpan</button>
            </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="modalSukses" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2" id="sukses" ></p>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="modal fade" id="modalproses" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2" id="proses" ></p>
          <p class="mt-2">Harap tunggu, proses sedang berjalan...</p>
        </div>
      </div>
    </div>
  </div> -->
  <div class="modal fade" id="modalproses" tabindex="-1" aria-hidden="true">
    
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Harap tunggu, proses sedang berjalan...</p>
          </div>
        </div>
      </div>
    </div>


     

  <script src="<?php echo base_url(); ?>assets/js/signature_pad1.umd.min.js"></script>
  <!-- <script src="<?php echo base_url(); ?>assets/js/app1.js"></script> -->
  <!-- Tambahkan di <head> atau sebelum </body> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <!-- jQuery UI -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script>
  function enable_autocomplete(InputField) {
      console.log('parameter autocomplete:', InputField);
      console.log("Jumlah elemen:", $(InputField).length); // Debugging
      
      $(InputField).autocomplete({
          minLength: 2,
          source: function(request, response) {
              $.ajax({
                  url: "<?php echo base_url('index.php/Pad1/get_farmasi') ?>",
                  dataType: "json",
                  data: { q: request.term },
                  success: function(data) {
                      console.log('Respon dari get_farmasi:', data);
                      response(data);
                  }
              });
          },
          select: function(event, ui) {
              console.log("Petugas dipilih:", ui.item); // Debugging
              $(this).val(ui.item.value); 
              $(".id_farmasi").val(ui.item.id);
              return false;
          },
          focus: function(event, ui) {  // Tambahkan event ini
              event.preventDefault();
              $(this).val(ui.item.label);
          }
      });
  }


  $(document).ready(function() {
      enable_autocomplete($("#farmasiInput1")); // Ganti dengan ID input yang sesuai
      enable_autocomplete($("#farmasiInput2")); // Ganti dengan ID input yang sesuai
      enable_autocomplete($("#penyerahanfarmasi")); // Ganti dengan ID input yang sesuai
      enable_autocomplete($("#racikanfarmasi")); // Ganti dengan ID input yang sesuai
      enable_autocomplete($("#etiketfarmasi")); // Ganti dengan ID input yang sesuai
      enable_autocomplete($("#petugaskonseling")); // Ganti dengan ID input yang sesuai
  });

  // setting canvas untuk bisa di pake gambar 
  const wrapper = document.getElementById("signature-pad");
  const canvas = wrapper.querySelector("canvas");
  const signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)'
  });

  // ini untuk mengatur ukuran canvas
  function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);

    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

    signaturePad.fromData(signaturePad.toData());
  }

  window.onresize = resizeCanvas;
  resizeCanvas();

  window.addEventListener("keydown", (event) => {
    switch (true) {
      case event.key === "z" && event.ctrlKey:
        undoButton.click();
        break;
      case event.key === "y" && event.ctrlKey:
        redoButton.click();
        break;
    }
  });

  // hapus ttd
  function hapus_ttd(){
    signaturePad.clear();
  }

  function dataURLToBlob(dataURL) {
    const parts = dataURL.split(';base64,');
    const contentType = parts[0].split(":")[1];
    const raw = window.atob(parts[1]);
    const rawLength = raw.length;
    const uInt8Array = new Uint8Array(rawLength);

    for (let i = 0; i < rawLength; ++i) {
      uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], { type: contentType });
  }

  function download(dataURL, filename) {
    const blob = dataURLToBlob(dataURL);
    const url = window.URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.style = "display: none";
    a.href = url;
    a.download = filename;

    document.body.appendChild(a);
    a.click();

    window.URL.revokeObjectURL(url);
  }

  // download sekaligus upload
  function download_pict(){
    norm = document.getElementById('norm').value;
    dataURL = signaturePad.toDataURL();
    timestamp = Math.floor(Date.now() / 1000);
    if (signaturePad.isEmpty()) {
      alert("Tidak ada ttd");
    } else {
      // alert("Ada ttd");
      if (norm == null || norm == "") {
        alert("Belum di isi norm");
      } else {
        // alert("Sudah di isi norm");
        // download(dataURL, norm.trim() + "_" + ".png");
        filename = norm.trim() + "_" + timestamp + ".png";

        simpan_gambar(dataURL, filename);
      }  
    }
  }

  // pencarian norm dan gambar pasien 
  function cari_norm(){
    // console.log('ini tombol cari norm');
   norm = document.getElementById('norm').value;

    var data = {
        norm: norm
    };
   
    $.ajax({
      type: 'POST',
      url: "<?php echo base_url('index.php/Pad1/get_mr/') ?>",
      data: data,
      dataType: 'json',
      success: function(data) {
        hapus_ttd();
        $("#konfirmasi").html("");
        if (data.nama) {
                $("#info_pasien").html(
                    "Nama Pasien : " + data.nama + "<br>" +
                    "Jenis Kelamin : " + data.jenis_kelamin + "<br>" +
                    "Tgl Lahir / Umur : " + data.ttl +" /" + data.umur + " Tahun<br>" +
                    "Alamat : " + data.alamat + "<br>" +
                    "Noreg : " + data.noreg + "<br>" +
                    "Dokter / Poli : " + data.dokter +" /" + data.poli + "<br> <br>" 
                    // "<button type='button' class='btn btn-primary' onclick='lihat_resep(\""+data.noreg+"\")'>Lihat Resep</button>"
                );
                // $("#lihat_resep").html("<p>Data tidak ditemukan</p>");
                $("#lihat_resep").attr("onclick", "lihat_resep('" + data.noreg + "')");
            } else {
                $("#info_pasien").html("<p>Data tidak ditemukan</p>");
            }

            if (data.ttd) {
              if(data.konfirmasi == 1){
                $("#konfirmasi").html(
                  data.nama + " telah melakukan konfirmasi tanda tangan secara digital pada <b>"+data.tgl+"</b>. Apakah data telah sesuai ?" +
                  "<div class='btn-group'>" +
                      "<button type='button' class='btn btn-info mx-2 btn-sm' onclick='konfirmasi(\"" + norm + "\", \"Ya\", \"" + data.ttd + "\", \"" + data.noreg + "\")'>Ya</button>" +
                      "<button type='button' class='btn btn-danger mx-2 btn-sm' onclick='konfirmasi(\"" + norm + "\", \"Tidak\", \"" + data.ttd + "\", \"" + data.noreg + "\")'>Tidak</button>" +
                  "</div>"
                );
              }else{
                $("#konfirmasi").html(
                data.nama + " telah melakukan tanda tangan secara digital dan penerimaan resep farmasi, apakah data telah sesuai ?" +
                "<div class='btn-group'>" +
                    "<button type='button' class='btn btn-info mx-2 btn-sm' onclick='konfirmasi(\"" + norm + "\", \"Ya\", \"" + data.ttd + "\", \"" + data.noreg + "\")'>Ya</button>" +
                    "<button type='button' class='btn btn-danger mx-2 btn-sm' onclick='konfirmasi(\"" + norm + "\", \"Tidak\", \"" + data.ttd + "\", \"" + data.noreg + "\")'>Tidak</button>" +
                "</div>"
                );
              }
                $("#pic_ttd").html('<img src="' + data.ttd + '" width="500" height="250" alt="Tanda Tangan Pasien">');
            } else {
                $("#pic_ttd").html('<p>Tanda tangan tidak tersedia</p>');
            }
      },
      error: function(xhr, status, error) {
        console.log(error);
      },
    });
   
  }

  function konfirmasi(norm, status, ttd, noreg){
    // console.log(norm, status, ttd);
    var data = {
      norm : norm, 
      status : status,
      ttd : ttd,
      noreg : noreg
    }

    $.ajax({
      type: 'POST',
      url: "<?php echo base_url('index.php/Pad1/konfirmasi/') ?>",
      data: data,
      // dataType: 'json',
      success: function(data) {
        // $("#lihat_resepi").html(data);
        // $('#modalLoading').modal('show');
        if(data == 'Berhasil'){
          $("#konfirmasi").html("Terima kasih. Tanda Tangan Digital telah berhasil di Konfirmasi!");
        }
      },
      error: function(xhr, status, error) {
        console.log(error);
      },
    });
  }

  // lihat resep
  function lihat_resep(noreg){
    // console.log(noreg);
    var data = {
      noreg : noreg
    }
    

    $.ajax({
      type: 'POST',
      url: "<?php echo base_url('index.php/Pad1/get_resep/') ?>",
      data: data,
      // dataType: 'json',
      beforeSend: function() {
          setTimeout(function() {
            $('#modalLoading').modal('show');
          }, 200);
      },
      success: function(data) {

        setTimeout(function() {
            $('#modalLoading').modal('show');
        }, 400);

        // Tampilkan hasil setelah loading
        setTimeout(function() {
            $("#loadingSpinner").hide(); // Sembunyikan spinner
            $("#lihat_resepi").html(data); // Tampilkan data hasil AJAX
            enable_autocomplete($("#farmasiInput1")); // Ganti dengan ID input yang sesuai
            enable_autocomplete($("#farmasiInput2")); // Ganti dengan ID input yang sesuai
            enable_autocomplete($("#penyerahanfarmasi")); // Ganti dengan ID input yang sesuai
            enable_autocomplete($("#racikanfarmasi")); // Ganti dengan ID input yang sesuai
            enable_autocomplete($("#etiketfarmasi")); // Ganti dengan ID input yang sesuai
            enable_autocomplete($("#petugaskonseling")); // Ganti dengan ID input yang sesuai
        }, 800);
      },
      error: function(xhr, status, error) {
        console.log(error);
      },
    });
  }

  // simpan gambar
  function simpan_gambar(dataURL, filename){
    norm = document.getElementById('norm').value;
    let formData = new FormData();
    formData.append("gambar", dataURLToBlob(dataURL), filename);
    formData.append("norm", norm);
    $.ajax({
      url: "<?php echo base_url('index.php/pad1/upload'); ?>",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let data = JSON.parse(response);

        if (data.status === "success") {
          $("#sukses").html("Tanda Tangan Digital berhasil disimpan");
          $('#modalSukses').modal('show');
          setTimeout(function () {
            $('#modalSukses').modal('hide');
          }, 1000);

          let imagePath = "http://<?php echo $_SERVER['SERVER_ADDR']; ?>/royal_pro/assets/ttd_pasien/" + data.filename;
          $("#pic_ttd").html('<img src="' + imagePath + '" width="500" height="250" />');
        } else {
          alert("Upload gagal, coba lagi!");
        }
      },
      error: function () {
        alert("Terjadi kesalahan saat mengunggah gambar.");
      }
    });
  }

  // refresh halaman
  function refresh(){
    window.location.reload();
  }

  // simpan
  function simpan_resep_farmasi(){
    noreg = document.getElementById('noreg').value;
    norm = document.getElementById('norm').value;
    dokter_input = document.getElementById('inputanfarmasi').value;
    racikan_input = document.getElementById('racikanfarmasi').value;
    etiket_input = document.getElementById('etiketfarmasi').value;
    penyerahan_input = document.getElementById('penyerahanfarmasi').value;
    resep_lengkap = $("input[name ='resep_lengkap']:checked").val(); 
    farmasetis = $("input[name ='farmasetis']:checked").val(); 
    benar_pasien = $("input[name ='benar_pasien']:checked").val(); 
    benar_obat = $("input[name ='benar_obat']:checked").val();
    benar_dosis = $("input[name ='benar_dosis']:checked").val();
    benar_rute = $("input[name ='benar_rute']:checked").val();
    benar_waktu = $("input[name ='benar_waktu']:checked").val();
    benar_dokuemntasi = $("input[name ='benar_dokumentasi']:checked").val();
    kontra_indikasi = $("input[name ='kontra_indikasi']:checked").val();
    potensi_alergi = $("input[name ='potensi_alergi']:checked").val();
    duplikasi = $("input[name ='duplikasi']:checked").val();
    petugas_farmasi1 = document.getElementById('farmasiInput1').value;
    telaah_obat = $("input[name ='telaah_obat']:checked").val();
    kesesuaian_resep = $("input[name ='kesesuaian_resep']:checked").val();
    nama_obat = $("input[name ='nama_obat']:checked").val();
    jumlah_dosis = $("input[name ='jumlah_dosis']:checked").val();
    rute = $("input[name ='rute']:checked").val();
    waktu_frekuensi = $("input[name ='waktu_frekuensi']:checked").val();
    petugas_farmasi2 = document.getElementById('farmasiInput2').value;
    konseling = document.getElementById('konseling').value;
    petugaskonseling = document.getElementById('petugaskonseling').value;

    var data = {
      noreg : noreg,
      norm : norm,
      dokter_input : dokter_input,
      racikan_input : racikan_input,
      etiket_input : etiket_input,
      penyerahan_input : penyerahan_input, 
      resep_lengkap : resep_lengkap,
      farmasetis : farmasetis,
      benar_pasien : benar_pasien,
      benar_obat : benar_obat,
      benar_dosis : benar_dosis,
      benar_rute : benar_rute,
      benar_waktu : benar_waktu,
      benar_dokuemntasi : benar_dokuemntasi,
      kontra_indikasi : kontra_indikasi,
      potensi_alergi : potensi_alergi,
      duplikasi : duplikasi,
      petugas_farmasi1 : petugas_farmasi1,
      telaah_obat : telaah_obat,
      kesesuaian_resep : kesesuaian_resep,
      nama_obat : nama_obat,
      jumlah_dosis : jumlah_dosis,
      rute : rute,
      waktu_frekuensi : waktu_frekuensi,
      petugas_farmasi2 : petugas_farmasi2,
      konseling : konseling,
      petugaskonseling : petugaskonseling
    }

    // console.log(data);

    $.ajax({
      type: 'POST',
      url: "<?php echo base_url('index.php/Pad1/simpan_resep_farmasi/') ?>",
      data: data,
      // dataType: 'json',
      success: function(data) {
        if(data == 'Berhasil'){
          $("#sukses").html("Data berhasil disimpan");
          $('#modalSukses').modal('show');
          setTimeout(function () {
            $('#modalSukses').modal('hide');
            $('#modalLoading').modal('hide');
          }, 1000);

        }
      },
      error: function(xhr, status, error) {
        console.log(error);
      },
    });

  }


  </script>
  <footer class="text-center text-black mt-4" style="background-color: #e8ffd3; padding: 10px;">
    <?php $tahun = date('Y'); ?>
    <p class="mb-0"><?php echo $tahun; ?> &copy; TIM SIM RS Royal Surabaya</p>
</footer>
</body>

</html>
