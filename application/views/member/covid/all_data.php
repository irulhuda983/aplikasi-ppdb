<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Data PPDB</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Covid</li>
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
                <div class="card-body">
                    <?php echo $this->session->flashdata('msgbox') ?>
                    <h5 class="card-title">Data PPDB</h5>
                    <div class="card-body">
                      <!-- <div class="form-group row">
                        <label for="fname" class="col-sm-3 text-right control-label col-form-label"></label>
                        <div class="col-sm-9">
                          <input type="radio" name="date_type" value="tanggal"  <?php if($_GET['date_type']){if($_GET['date_type'] == "tanggal"){echo "checked";}}else if(empty($_GET['date_type'])){echo "checked";} ?> >&nbsp;Tanggal Data Real<br>
                          <input type="radio" name="date_type" value ="created_date" <?php if($_GET['date_type']){if($_GET['date_type'] == "created_date"){echo "checked";}}else if(empty($_GET['date_type'])){echo "checked";} ?> >&nbsp;Tanggal Data Disimpan<br>
                          <input type="radio" name="date_type" value ="updated_date" <?php if($_GET['date_type']){if($_GET['date_type'] == "updated_date"){echo "checked";}}else if(empty($_GET['date_type'])){echo "checked";} ?> >&nbsp;Tanggal Data Diupdate<br>
                        </div>
                      </div> -->
                      <div class="form-group row">
                        <div class="col-sm-9">
                          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                            Tambah Data PPDB
                          </button>
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Upload Data PPDB
                          </button>
                        </div>

                        <div class="col-sm-3">
                          <form method="post" action="<?php echo base_url('admin/data_ppdb/truncate'); ?>">
                            <button type="submit" class="btn btn-danger">
                              Kosongkan Data
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 20px;">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Nama Kecamatan</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead> 
                            <tbody>
                                 <?php
                                 $no = 0 ;
                                 foreach($listData as $value){
                                    $dta = [
                                      'id' => $value->ppdb_wilayah_id,
                                      'nama' => $value->nama,
                                      'jumlah' => $value->jumlah,
                                      'tahun' => $value->tahun,
                                    ];

                                    $obj = json_encode($dta);
                                    $no++;
                                    ?>
                                    <tr>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $value->tahun ?></td>
                                        <td><?php echo $value->nama ?></td>
                                        <td><?php echo $value->jumlah ?></td>
                                        <td>
                                          <a href="#" class="btn btn-primary btn-xs updateDataPpdb" data-id="<?= $value->ppdb_wilayah_id ?>" data-nama="<?= $value->nama ?>" data-tahun="<?= $value->tahun ?>" data-jumlah="<?= $value->jumlah ?>" data-kecamatan="<?= $value->id_kecamatan ?>" data-toggle="modal" data-target="#modalUpdate"><i class="mdi mdi-pencil"></i></a>
                                          <form action="<?php echo base_url('admin/data_ppdb/destroy'); ?>" method="post" class="d-inline">
                                            <input type="hidden" value="<?= $value->ppdb_wilayah_id ?>" name="id">
                                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('apakah anda yakin')"><i class="fa fa-trash"></i></i></button>
                                          </form>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                                ?>   
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal upload -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-horizontal" method="post" action="<?php echo base_url('admin/data_ppdb/excel'); ?>" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="custom-file">
              <input name="file" type="file" class="custom-file-input" id="customFile">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
          </div>
          <div class="modal-footer">
            <a href="<?php echo base_url()?>/dist/excel/template_data_ppdb.xlsx" class="d-inline">Download Format</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModalTambah">Close</button>
            <button type="submit" class="btn btn-primary">Upload</button>
          </div>
        </div>
        </form>
      </div>
    </div>
    <!-- end modal upload -->

    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-horizontal" method="post" action="<?php echo base_url('admin/data_ppdb/store'); ?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahLabel">Tambah Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="kecamatan">Kecamatan</label>
              <select class="form-control" id="exampleFormControlSelect1">
                <option>Pilih Kecamatan</option>
                <?php foreach($kecamatan as $kec) : ?>
                <option value="<?= $kec['id'] ?>"><?= $kec['nama'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="jumlah">Jumlah</label>
              <input type="text" name="jumlah" class="form-control" id="jumlah">
            </div>
            <div class="form-group">
              <label for="tahun">Tahun</label>
              <input type="number" name="tahun" class="form-control" id="tahun">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModalTambah">Close</button>
            <button type="submit" class="btn btn-primary">Tambah</button>
          </div>
        </div>
        </form>
      </div>
    </div>
    <!-- end modal upload -->

    <!-- Modal update -->
    <div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-horizontal" method="post" action="<?php echo base_url('admin/data_ppdb/update'); ?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalUpdateLabel">Update Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input id="update-id" type="hidden" name="id" class="form-control">
              <input id="update-idkec" type="hidden" name="kecamatan" class="form-control">
              <label for="update-kec">Kecamatan</label>
              <input disabled id="update-kec" type="text" name="kec" class="form-control">
            </div>
            <div class="form-group">
              <label for="jumlah">Jumlah</label>
              <input id="update-jumlah" type="number" name="jumlah" class="form-control">
            </div>
            <div class="form-group">
              <label for="tahun">Tahun</label>
              <input id="update-tahun" type="number" name="tahun" class="form-control">
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
<!-- ============================================================== -->
<!-- End Container fluid  -->
<script>
  $( document ).ready(function() {
    $('#customFile').on('change',function(){
        var fileName = $(this)[0].files[0].name;
        $(this).next('.custom-file-label').html(fileName);
    })

    $('#closeModalTambah').on('click', () => {
      $('#customFile').next('.custom-file-label').html('Choose file');
    })

    $('.updateDataPpdb').on('click', function(e) {
      e.preventDefault();
      let id = $(this).data('id')
      let nama = $(this).data('nama')
      let kecamatan = $(this).data('kecamatan')
      let tahun = $(this).data('tahun')
      let jumlah = $(this).data('jumlah')

      $('#update-id').val(id)
      $('#update-idkec').val(kecamatan)
      $('#update-kec').val(nama)
      $('#update-jumlah').val(jumlah)
      $('#update-tahun').val(tahun)
    })
  });
</script>
