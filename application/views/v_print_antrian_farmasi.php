

<div id="printing_table" style="width:100%">
	<div id="content">
		<center>
			<img src="<?php echo base_url() . 'assets/img/royal.png'; ?>" height="80" width="140" />
		</center>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> Waktu Pengambilan : <?= $tglinsert; ?>
		</div>
		<center>
			<div style="width:250px; font-size: 25px; margin-top: 1%"><b> Antrian Farmasi BPJS </b>
			</div>
			<div style="width:250px; font-size: 25px; margin-top: 1%"> No Antrian : <?= $form_no; ?>
			</div>
		</center>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> No RM : <?= $norm; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> Nama pasien : <?= $nama_pasien; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> <?= $nmpoli; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> <?= $nmdokter; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> <?= $noreg . ' / ' . $tgl_hari_ini; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> Waktu Cetak : <?= $tgl_cetak; ?>
		</div>
		<div style="width:250px; font-size: 20px; margin-top: 1%"> <b> <?= $keterangan; ?> </b>
		</div>

	</div>
</div>

<script>
	window.onload = function() {
		window.print();
		setTimeout(function() {
			window.close()
		}, 4000);
		
	}
</script>

