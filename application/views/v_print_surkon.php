

<div id="printing_table" style="width:100%">
	<div id="content">
		<center>
			<img src="<?php echo base_url() . 'assets/img/royal.png'; ?>" height="80" width="140" />
			<div style="width:250px; font-size: 15px; margin-top: 1%"> Perubahan surat kontrol BPJS
			</div>
		</center>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> No kontrol : <?= $noSuratKontrol; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> Nama pasien : <?= $Nama; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> No kartu : <?= $NoKartu; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> <b> Tgl. kontrol : <?= $TglKontrol; ?></b>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> Dokter : <?= $NmDPJP; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> Keterangan : <?= $WarningMsg; ?>
		</div>
		

<script>
	window.onload = function() {
		window.print();
		setTimeout(function() {
			window.close()
		}, 4000);
		
	}
</script>

