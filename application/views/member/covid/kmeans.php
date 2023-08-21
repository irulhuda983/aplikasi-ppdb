<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">K-means</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Analisa K-means</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- centro -->
                <div class="card-body">
                    <?php echo $this->session->flashdata('msgbox') ?>
                    <h5 class="card-title">Centroid Awal</h5>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>C1</th>
                                <th>C2</th>
                                <th>C3</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $centroid['center1']; ?></td>
                                <td><?= $centroid['center2']; ?></td>
                                <td><?= $centroid['center3']; ?></td>
                                <td style="width: 10px">
                                    <a href="#" data-id="<?= $centroid['id']; ?>" data-c1="<?= $centroid['center1']; ?>" data-c2="<?= $centroid['center2']; ?>" data-c3="<?= $centroid['center3']; ?>" id="updateDataCentroid" data-toggle="modal" data-target="#modalUpdateCentroid"><button class="btn btn-primary btn-sm btnEmptySaldo"   style="margin-left:2px"><i class="mdi mdi-pencil" style="font-size: 14px;"></i></button></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- end centro -->
                <div class="card-body">
                    <h5 class="card-title">Data Peserta Didik Baru</h5>
                    <form class="form-horizontal" method="get" action="<?php echo base_url('admin/kmeans/daftar_kmeans'); ?>" enctype="multipart/form-data">
                      <div class="card-body">
                        
                        <div class="form-group row">
                          <label for="fname" class="col-sm-3 text-right control-label col-form-label">Tahun</label>
                          <div class="col-sm-9">
                            
                            <select name="tahun" id="tahun" class="form-control" required>
                                <option value="">Pilih Tahun</option>
                                <?php foreach($tahun as $item) : ?>
                                <option value="<?= $item['tahun'] ?>"><?= $item['tahun'] ?></option>
                                <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        
                        <div class="form-group row">
                          <div class="col-sm-offset-2 col-sm-9">
                            <button type="submit" class="btn btn-default">Analisa</button>
                            <a href="<?php echo base_url('admin/kmeans/daftar_kmeans'); ?>" class="btn btn-secondary">Reset</a>
                          </div>
                        </div>
                      </div>
                    </form>
                </div>
                <?php if($kmeans !== null) { ?>
                    <div class="card-body">
                        <h5 class="card-title">Perhitungan K-means</h5>

                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headinggOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Step #1
                                    </button>
                                </h2>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headinggOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Daerah</th>
                                                    <th>Tahun</th>
                                                    <th>Jumlah Pendaftar</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php
                                                $nor=1;
                                                foreach ($kmeans['data_kumpul'] as $key => $value) { ?>
                                                <tr>
                                                    <th><?= $nor++ ?></th>
                                                    <th><?= $value['kecamatan'] ?></th>
                                                    <th><?= $value['tahun'] ?></th>
                                                    <th><?= number_format($value['jumlah'],0) ?></th>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table> 
                                    </div>
                                </div>
                            </div>
                            <?php
                                $numbering = [];
                                $totalIterasi = count($kmeans['data_mining']);
                                foreach ($kmeans['data_mining'] as $key => $value) { 
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $key; ?>">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse<?php echo $key; ?>" aria-expanded="false" aria-controls="collap <?php echo $key; ?>">
                                    <?php echo $value['nama']; ?>
                                    </button>
                                </h2>
                                </div>
                                <div id="collapse<?php echo $key; ?>" class="collapse" aria-labelledby="heading<?php echo $key; ?>" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Provinsi</th>
                                                    <th>C1 (Tinggi)</th>
                                                    <th>C2 (Sedang)</th>
                                                    <th>C3 (Rendah)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($value['data'] as $keys => $values) { ?>
                                                <tr>
                                                    <th><?= $keys+1 ?></th>
                                                    <th><?= $values['kecamatan'] ?> (<?= 'C'.$values['cluster'] ?> )</th>
                                                    <th><?= (!is_null($values['C1'])?number_format(floatval($values['C1']),2):'') ?>
                                                    </th>
                                                    <th><?= (!is_null($values['C2'])?number_format(floatval($values['C2']),2):'') ?>
                                                    </th>
                                                    <th><?= (!is_null($values['C3'])?number_format(floatval($values['C3']),2):'') ?>
                                                    </th>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        
                        
                            <p>Jumlah Keseluruhan</p>
                            <?php
                            $c1 = $kmeans['cluster']['c1'];
                            $c2 = $kmeans['cluster']['c2'];
                            $c3 = $kmeans['cluster']['c3'];
                            $hitungTotal = array_sum([count($c1), count($c2), count($c3)]);
                            ?>
                            <p>Jumlah Peserta Didik Baru Tinggi, total = <?= count($c1) ?></p>
                            <p>Jumlah Peserta Didik Baru Sedang, total = <?= count($c2) ?></p>
                            <p>Jumlah Peserta Didik Baru Rendah, total = <?= count($c3) ?></p>
                            <p>Total <?= $hitungTotal ?></p>
                        </div>
                    </div>
                <?php } else { ?>
                    <p class="text-center"><i>data tidak ditemukan</i></p>
                <?php } ?>
            </div>
        </div>
    
        <!-- Modal update -->
        <div class="modal fade" id="modalUpdateCentroid" tabindex="-1" role="dialog" aria-labelledby="modalUpdateCentroidLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="form-horizontal" method="post" action="<?php echo base_url('admin/kmeans/update_centro'); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUpdateCentroidLabel">Update Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="update-id" type="hidden" name="id" class="form-control">
                        <div class="form-group">
                            <label for="update-c1">C1</label>
                            <input id="update-c1" type="text" name="c1" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="update-c2">C2</label>
                            <input id="update-c2" type="text" name="c2" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="update-c3">C3</label>
                            <input id="update-c3" type="text" name="c3" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModalTambah">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- end modal upload -->
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->

<script>
$( document ).ready(function() {

    $('#updateDataCentroid').on('click', function(e) {
      e.preventDefault();
      let id = $(this).data('id')
      let c1 = $(this).data('c1')
      let c2 = $(this).data('c2')
      let c3 = $(this).data('c3')

      $('#update-id').val(id)
      $('#update-c1').val(c1)
      $('#update-c2').val(c2)
      $('#update-c3').val(c3)
  });
})
</script>
