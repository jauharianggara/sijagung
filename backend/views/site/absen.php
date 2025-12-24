<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
			<h3 class="card-title">Rekapitulasi Laporan Plant </h3>
			</div>

				<div class="card-body">
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th rowspan="2">#</th>
								<th rowspan="2">Nama Plant</th>
								<th colspan="2"><?= $bulanLabel1?></th>
								<th colspan="2"><?= $bulanLabel2?></th>
								<th colspan="2"><?= $bulanLabel3?></th>
								<th colspan="2"><?= $bulanLabel4?></th>
								<th colspan="2"><?= $bulanLabel5?></th>
								<th colspan="2"><?= $bulanLabel6?></th>
								<th colspan="2"><?= $bulanLabel7?></th>
								<th colspan="2"><?= $bulanLabel8?></th>
								<th colspan="2"><?= $bulanLabel9?></th>
								<th colspan="2"><?= $bulanLabel10?></th>
								<th colspan="2"><?= $bulanLabel11?></th>
								<th colspan="2"><?= $bulanLabel12?></th>
							</tr>
							<tr>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
								<th>P</th>
								<th>S</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i = 1;
								$data = $dataProvider->getModels();
								foreach($data as $item){
							?>
								<tr>
									<td><?= $i?></td>
									<td><?= $item['nama_plant']?></td>
									<td>
										<?php
											if($item['pembelian1']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok1']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian2']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok2']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian3']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok3']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian4']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok4']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian5']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok5']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian6']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok6']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian7']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok7']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian8']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok8']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian9']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok9']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian10']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok10']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian11']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok11']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['pembelian12']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
									<td>
										<?php
											if($item['stok12']>0){
												echo '<span class="text-success"><strong>&#10003;</strong></span>';
											}else{
												echo '<span class="text-danger"><strong>&#10005;</strong></span>';
											}
										?>
									</td>
								</tr>

						<?php
							$i++;
						}
						?>
					</tbody>
				</table>

                
            </div>
        </div>
    </div>