<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Dashboard</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Library</li>
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
    <!-- Sales Cards  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Sales chart -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- isi disini -->
                        <div class="col-lg-12" >
                            <div class="alert alert-primary" role="alert">
                                IMPLEMENTASI ALGORITMA K-MEANS CLUSTERING ANALYSIS PESERTA DIDIK BARU MTs ISLAMIYYAH BANAT.
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                <?php if($last_iteration !== null) { ?>
                                        <canvas id="doughnut_chart"></canvas>
                                <?php } else { ?>
                                        <p class="text-center"><i>data tidak ditemukan</i></p>
                                <?php } ?>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?= site_url('home') ?>" method="GET">
                                        <div class="input-group mb-3">
                                        <select name="tahun" id="tahun" class="form-control" required>
                                            <option value="">Pilih Tahun</option>
                                            <?php foreach($tahun as $item) : ?>
                                            <option value="<?= $item['tahun'] ?>"><?= $item['tahun'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="submit">Cari</button>
                                            </div>
                                            <div class="input-group-append">
                                                <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="show_provinces"></div>
                                </div>
                            </div>
                        </div>
                        <!-- column
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Sales chart -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Recent comment and chats -->
    <!-- ============================================================== -->
    
    <!-- ============================================================== -->
    <!-- Recent comment and chats -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>
<script>
    $(document).ready(function(){
        var last_iteration = '<?= json_encode($last_iteration)?>'
        var last_object = JSON.parse(last_iteration)
        var tinggi = last_object.c1.length
        var sedang = last_object.c2.length
        var rendah = last_object.c3.length
        new Chart(document.getElementById("doughnut_chart"), {
            type: 'doughnut',
            data: {
            labels: ["Tinggi", "Sedang", "Rendah"],
            datasets: [{
                label: "Jumlah Peserta Didik Baru",
                backgroundColor: ["#ff4747", "#ffc933", "#4cff42"],
                data: [tinggi,sedang,rendah],
                metadata: {
                    'c1': tinggi,
                    'c2': sedang,
                    'c3': rendah
                }
            }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Jumlah Peserta Didik Baru di MA'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                return parseInt(previousValue) + parseInt(currentValue);
                            });
                            var index = tooltipItem.index
                            var currentValue = dataset.data[index]
                            if(index == 0) {
                                var provinces = "<h4>Provinsi dengan Kasus Tinggi</h4>"
                                provinces += "<ul>"
                                for (var key in last_object.c1) {
                                    var province = last_object.c1[key].kecamatan;
                                    // console.log(province)
                                    provinces += "<li>" + province + "</li>"
                                }
                                provinces += "</ul>"
                            } else if(index == 1) {
                                var provinces = "<h4>Provinsi dengan Kasus Sedang</h4>"
                                provinces += "<ul>"
                                for (var key in last_object.c2) {
                                    var province = last_object.c2[key].kecamatan;
                                    // console.log(province)
                                    provinces += "<li>" + province + "</li>"
                                }
                                provinces += "</ul>"
                            } else {
                                var provinces = "<h4>Provinsi dengan Kasus Rendah</h4>"
                                provinces += "<ul>"
                                for (var key in last_object.c3) {
                                    var province = last_object.c3[key].kecamatan;
                                    // console.log(province)
                                    provinces += "<li>" + province + "</li>"
                                }
                                provinces += "</ul>"
                            }
                            document.getElementById("show_provinces").innerHTML = provinces;
                            var percentage = Math.floor(((currentValue/total) * 100)+0.5);  
                            return percentage + "%"
                        }
                    }
                }
            }
        });
    });
</script>