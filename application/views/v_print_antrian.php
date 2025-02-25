

<div id="printing_table" style="width:100%">
	<div id="content">
		<center>
			<div style="width:250px; font-size: 17px; margin-top: 1%;"> 
				<?php 
				if($warna != '-'){ 
					echo $warna; 
				} 
				?>
			</div>
			<img src="<?php echo base_url() . 'assets/img/royal.png'; ?>" height="80" width="140" />
			<div style="width:250px; font-size: 15px; margin-top: 1%"> Pendaftaran BPJS Melalui <b> <?= $updater; ?> </b>
			</div>
			<div style="width:250px; font-size: 12px; margin-top: 1%"> <?= $tglinsert; ?>
			</div>
			<div style="width:250px; font-size: 20px; margin-top: 1%"> <?= $nmpoli; ?>
			</div>
			<div style="width:250px; font-size: 20px; margin-top: 1%"> <?= $tgl_hari_ini; ?>
			</div>
			<div style="width:250px; font-size: 20px; margin-top: 1%"> <?= $nmdokter; ?>
			</div>
			<div style="width:250px; font-size: 35px; margin-top: 1%"><b> <?= $code; ?> </b>
			</div>
		</center>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> Kode Booking Antrian : <?= $noapp; ?>
		</div>
		<div style="width:250px; font-size: 15px; margin-top: 1%"> No kartu pasien : <?= $nokartu; ?> / <?= $NmKlsTanggungan; ?>
		</div>

		<div style="width:250px; font-size: 15px; margin-top: 1%"> Nama pasien : <?= $nama_pasien; ?> / ( <?= $norm; ?> )
		</div>
		<?php
		if($status == '1'){
			?>
			<div style="width:250px; font-size: 15px; margin-top: 1%"> <b> No SEP : <?= $noSep; ?> </b>
			</div>
			<?php
		} else {
			?>
			<div style="width:250px; font-size: 15px; margin-top: 1%"> <b> <?= $string_mess; ?> </b>
			</div>
			<?php
		}
		?>

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

