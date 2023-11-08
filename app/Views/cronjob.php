<?php
	use Config\Email;
	$config = new Email();
	
	$req = service('request');
	$db = db_connect();
	
	if($filter == 'pegawai') {
    	$getPegawai = $db->table('pegawai')->getWhere(['id_pegawai' => $id])->getRowArray();
		$getProjectPegawai = $db->table('vw_project_pegawai')->getWhere(['id_pegawai' => $id])->getResultArray();
    }else{
        $getVendor = $db->table('vendor')->getWhere(['id_vendor' => $id])->getRowArray();
		$getProjectVendor = $db->table('vw_project_vendor')->getWhere(['id_vendor' => $id])->getResultArray();
    }

?>
<body class="hold-transition light-skin sidebar-mini theme-primary fixed" style=" font-family: Arial, sans-serif;">

	<section class="invoice printableArea">
		<div class="row">
			<div class="col-12">
				<div class="page-header" style="text-align: center;">
					<h2 class="d-inline" style="font-size: 30px;"><span class="fs-30">Laporan Harian Project</span></h2><br>
                  					<center>
                       <img src="https://i.imgur.com/fC0SoNN.gif" alt="" width="300px">
                  </center>
					<div class="pull-right text-end">
						<h3 style="font-size: 20px;"><?= date('d M Y') ?></h3>
					</div>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<div class="row invoice-info">
			<div class="col-md-6 invoice-col">
				<strong>From</strong>
				<address>
					<strong class="text-blue fs-24" style="font-size: 19px;"><?= $config->fromName ?></strong><br>
					<strong>Email: <a href="mailto:<?= $config->fromEmail ?>" style="color: #007bff; text-decoration: none;"><?= $config->fromEmail ?></a></strong>
				</address>
			</div>
			<!-- /.col -->
			<?php if ($filter == 'pegawai') { ?>
				<div class="col-md-6 invoice-col text-end">
					<br>
                   <strong>To</strong>
					<address>
						<strong class="text-blue fs-24" style=" font-size: 19px;"><?= $getPegawai['pegawai_name'] ?></strong><br>
						<strong>Email: <a href="mailto:<?= $getPegawai['email'] ?>" style="color: #007bff; text-decoration: none;"><?= $getPegawai['email'] ?></a></strong>
					</address>
				</div>
			<?php } else { ?>
				<div class="col-md-6 invoice-col text-end">
                    <br>
					<strong>To</strong>
					<address>
						<strong class="text-blue fs-24" style=" font-size: 19px;"><?= $getVendor['vendor_name'] ?></strong><br>
						<strong>Email: <a href="mailto:<?= $getVendor['email'] ?>" style="color: #007bff; text-decoration: none;"><?= $getVendor['email'] ?></a></strong>
					</address>
				</div>
			<?php } ?>
			<!-- /.col -->
			<div class="col-sm-12 invoice-col mb-15">
				<div class="invoice-details row no-margin">
					<center>
						<b style="font-size: 18px;">Informasi Pekerjaan</b>
					</center>
                  	<br>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card" style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);">
					<div class="table-responsive">
						<table class="table table-bordered" style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
							<thead>
								<tr>
									<th style="text-align: center;">No</th>
									<th style="text-align: center;">Project Name</th>
									<th style="text-align: center;">Deadline</th>
									<th style="text-align: center;">Link project</th>
									
		 							<th style="text-align: center;">Status </th>
								</tr>
							</thead>
							<tbody>
								<?php
								$noPP = 1;
								if ($filter == 'pegawai') :
									foreach ($getProjectPegawai as $gp) :
										if (!$gp['status'] == 1) :
								?>
											<tr>
												<td style="text-align: center;"><?= $noPP++ ?></td>
												<td style="text-align: center;"><?= $gp['project_name'] ?></td>
												<td style="text-align: center;"><?= $gp['end_date'] ?></td>
												<td style="text-align: center;"><a href="<?= $gp['link'] ?>" style="color: #007bff; text-decoration: none;"><?= $gp['link'] ?></a></td>
										<td style="color:red;"><?= date('Y-m-d H:i:s') > $gp['end_date'] ? 'Telah lewat batas' : 'Belum selesai'; ?></td>
											</tr>
								<?php
										endif;
									endforeach;
								endif;
								?>

								<?php
								$noPV = 1;
								
								
								
								if ($filter == 'vendor') :
									foreach ($getProjectVendor as $gp) :
										if (!$gp['status'] == 1) :
									
								?>
											<tr>
												<td style="text-align: center;"><?= $noPV++ ?></td>
												<td><?= $gp['project_name'] ?></td>
												<td style="text-align: center;"><?= $gp['end_date'] ?></td>
													<td style="text-align: center;"><a href="<?= $gp['link_project'] ?>" style="color: #007bff; text-decoration: none;"><?= $gp['link_project'] ?></a></td>
										<td style="color:red;"><?= date('Y-m-d H:i:s') > $gp['end_date'] ? 'Telah lewat batas' : 'Belum selesai'; ?></td>
											</tr>
								<?php
										endif;
									endforeach;
								endif;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<div class="row">
			<div class="col-12 text-end">
				<div class="total-payment">
					<!-- Total payment details here -->
				</div>
			</div>
			<!-- /.col -->
		</div>
	</section>
	<!-- /.content -->
</body>
