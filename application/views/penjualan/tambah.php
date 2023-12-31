<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('partials/head.php') ?>
</head>

<body id="page-top">
	<div id="wrapper">
		<!-- load sidebar -->
		<?php $this->load->view('partials/sidebar.php') ?>

		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content" data-url="<?= base_url('penjualan') ?>">
				<!-- load Topbar -->
				<?php $this->load->view('partials/topbar.php') ?>

				<div class="container-fluid">
				<div class="clearfix">
					<div class="float-left">
						<h1 class="h3 m-0 text-gray-800"><?= $title ?></h1>
					</div>
					<div class="float-right">
						<a href="<?= base_url('penjualan') ?>" class="btn btn-secondary btn-sm"><i class="fa fa-reply"></i>&nbsp;&nbsp;Kembali</a>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col">
						<div class="card shadow">
							<div class="card-header"><strong>Isi Form Dibawah Ini!</strong></div>
							<div class="card-body">
								<form action="<?= base_url('penjualan/proses_tambah') ?>" id="form-tambah" method="POST">
									<h5>Data Transaksi</h5>
									<hr>
									<div class="form-row">
										<div class="form-group col-2">
											<label>No. Penjualan</label>
											<input type="text" name="no_transaction" value="PJ<?= time() ?>" readonly class="form-control">
										</div>
										<div class="form-group col-2">
											<label>Tanggal Penjualan</label>
											<input type="text" name="tgl_transaction" value="<?= date('d/m/Y') ?>" readonly class="form-control">
										</div>
									</div>
									<h5>Data Customer</h5>
									<hr>
									<div class="form-row">
										<div class="form-group col-2">
											<label>Nama Customer</label>
											<input type="text" name="kode_customer" placeholder="Nama Customer" autocomplete="off"  class="form-control" required>
										</div>
										<div class="form-group col-2">
											<label>Kode Customer</label>
											<input type="text" name="kode_customer" placeholder="Kode Customer" autocomplete="off"  class="form-control" required>
										</div>
										<div class="form-group col-2">
											<label>No. Telp Customer</label>
											<input type="text" name="telp_customer" placeholder="No. Telp" autocomplete="off"  class="form-control" required>
										</div>
									</div>
									<h5>Data Barang</h5>
									<hr>
									<div class="form-row">
										<div class="form-group col-3">
											<label for="nama_barang">Nama Barang</label>
											<select name="nama_barang" id="nama_barang" class="form-control">
												<option value="">Pilih Barang</option>
												<?php foreach ($all_barang as $barang): ?>
													<option value="<?= $barang->nama_barang ?>"><?= $barang->nama_barang ?></option>
												<?php endforeach ?>
											</select>
										</div>
										<div class="form-group col-2">
											<label>Kode Barang</label>
											<input type="text" name="kode_barang" value="" readonly class="form-control">
										</div>
										<div class="form-group col-2">
											<label>Harga Barang</label>
											<input type="text" name="harga_barang" value="" readonly class="form-control">
										</div>
										<div class="form-group col-2">
											<label>Jumlah</label>
											<input type="number" name="jumlah" value="" class="form-control" readonly min='1'>
										</div>
										<div class="form-group col-2">
											<label>SubTotal</label>
											<input type="number" name="subtotal_transaction" value="" class="form-control" readonly>
										</div>
										<div class="form-group col-1">
											<label for="">&nbsp;</label>
											<button disabled type="button" class="btn btn-primary btn-block" id="tambah"><i class="fa fa-plus"></i></button>
										</div>
										<input type="hidden" name="satuan" value="">
									</div>
									<div class="keranjang">
										<h5>Detail Pembelian</h5>
										<hr>
										<table class="table table-bordered" id="keranjang">
											<thead>
												<tr>
													<td width="35%">Nama Barang</td>
													<td width="15%">Harga</td>
													<td width="15%">Jumlah</td>
													<td width="10%">SubTotal</td>
													<td width="15%">Aksi</td>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
											<tfoot>
												<tr>
													<td colspan="4" align="right"><strong>Total : </strong></td>
													<td id="total"></td>
													
													<td>
														<input type="hidden" name="total_hidden" value="">
														<input type="hidden" name="max_hidden" value="">
														<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</form>
							</div>				
						</div>
					</div>
				</div>
				</div>
			</div>
			<!-- load footer -->
			<?php $this->load->view('partials/footer.php') ?>
		</div>
	</div>
	<?php $this->load->view('partials/js.php') ?>
	<script>
		$(document).ready(function(){
			$('tfoot').hide()

			$(document).keypress(function(event){
		    	if (event.which == '13') {
		      		event.preventDefault();
			   	}
			})

			$('#nama_barang').on('change', function(){

				if($(this).val() == '') reset()
				else {
					const url_get_all_barang = $('#content').data('url') + '/get_all_barang'
					$.ajax({
						url: url_get_all_barang,
						type: 'POST',
						dataType: 'json',
						data: {nama_barang: $(this).val()},
						success: function(data){
							$('input[name="kode_barang"]').val(data.kode_barang)
							$('input[name="harga_barang"]').val(data.harga_barang)
							$('input[name="jumlah"]').val(1)
							$('input[name="satuan"]').val(data.satuan)
							$('input[name="max_hidden"]').val(data.stok)
							$('input[name="jumlah"]').prop('readonly', false)
							$('button#tambah').prop('disabled', false)

							$('input[name="subtotal_transaction"]').val($('input[name="jumlah"]').val() * $('input[name="harga_barang"]').val())
							
							$('input[name="jumlah"]').on('keydown keyup change blur', function(){
								$('input[name="subtotal_transaction"]').val($('input[name="jumlah"]').val() * $('input[name="harga_barang"]').val())
							})
						}
					})
				}
			})

			$('#nama_customer').on('change', function(){

				if($(this).val() == '') reset()
				else {
					const url_get_all_customer = $('#content').data('url') + '/get_all_customer'
					$.ajax({
						url: url_get_all_customer,
						type: 'POST',
						dataType: 'json',
						data: {nama_customer: $(this).val()},
						success: function(data){
							$('input[name="nama_customer"]').val(data.nama_customer)
							$('input[name="kode_customer"]').val(data.kode_customer)
							$('input[name="telp_customer"]').val(data.telp_customer)

						}
					})
				}
			})


			$(document).on('click', '#tambah', function(e){
				const url_keranjang_barang = $('#content').data('url') + '/keranjang_barang'
				const data_keranjang = {
					nama_barang: $('select[name="nama_barang"]').val(),
					harga_barang: $('input[name="harga_barang"]').val(),
					jumlah: $('input[name="jumlah"]').val(),
					satuan: $('input[name="satuan"]').val(),
					subtotal_transaction: $('input[name="subtotal_transaction"]').val(),
				}

				if(parseInt($('input[name="max_hidden"]').val()) <= parseInt(data_keranjang.jumlah)) {
					alert('stok tidak tersedia! stok tersedia : ' + parseInt($('input[name="max_hidden"]').val()))	
				} else {
					$.ajax({
						url: url_keranjang_barang,
						type: 'POST',
						data: data_keranjang,
						success: function(data){
							if($('select[name="nama_barang"]').val() == data_keranjang.nama_barang) $('option[value="' + data_keranjang.nama_barang + '"]').hide()
							reset()

							$('table#keranjang tbody').append(data)
							$('tfoot').show()

							$('#total').html('<strong>' + hitung_total() + '</strong>')
							$('input[name="total_hidden"]').val(hitung_total())
						}
					})
				}

			})


			$(document).on('click', '#tombol-hapus', function(){
				$(this).closest('.row-keranjang').remove()

				$('option[value="' + $(this).data('nama-barang') + '"]').show()

				if($('tbody').children().length == 0) $('tfoot').hide()
			})

			$('button[type="submit"]').on('click', function(){
				$('input[name="kode_barang"]').prop('disabled', true)
				$('select[name="nama_barang"]').prop('disabled', true)
				$('input[name="harga_barang"]').prop('disabled', true)
				$('input[name="jumlah"]').prop('disabled', true)
				$('input[name="subtotal_transaction"]').prop('disabled', true)
			})

			function hitung_total(){
				let total = 0;
				$('.subtotal_transaction').each(function(){
					total += parseInt($(this).text())
				})

				return total;
			}

			function reset(){
				$('#nama_barang').val('')
				$('input[name="kode_barang"]').val('')
				$('input[name="harga_barang"]').val('')
				$('input[name="jumlah"]').val('')
				$('input[name="subtotal_transaction"]').val('')
				$('input[name="jumlah"]').prop('readonly', true)
				$('button#tambah').prop('disabled', true)
			}
		})
	</script>
</body>
</html>