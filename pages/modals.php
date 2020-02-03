<!-- MODALS -->
<div id="tambah_test" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-nav-tabs card-plain">
                    <div class="card-header card-header-danger">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav nav-tabs" data-tabs="tabs">
                                    <!-- <li class="nav-item">
                                        <a class="nav-link active" href="#Manual_test" data-toggle="tab">Manual
                                            Input</a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#Export_test" data-toggle="tab">Export CSV</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane active" id="Export_test">
                    
                        <div class="form-group form-float form-group-lg">
                            <div class="form-line">
                                <input type="text" class="form-control" name="judul_berita" />
                                <label class="form-label">Judul Berita</label>
                            </div>
						</div>
                        <input name="file" type="file" multiple="" class="form-control" accept=".csv">
                        <button name="uploadTest" type="submit" class="btn btn-success pull-right" style="bottom:0;margin-top:36px;">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </div>
</div>
</div> -->

<div id="tambah_training" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Training</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-nav-tabs card-plain">
                    <div class="card-header card-header-danger">
                        
                    </div>

                    <div class="tab-pane active" id="Export_training">
                    
                        <?php 
                            include "./system/koneksi.php";
                            $result = mysqli_query($konek,"SELECT * FROM kategori");
                        ?> 
                        <form method="POST" enctype="multipart/form-data" style="margin-top:24px;">
                            <div class="form-group">
                                <label for="folder">Pilih Kategori untuk Berita</label> <small>Harus Diisi</small>
                                    <select class="form-control show-tick" name="kategori" required>
                                        <option value="" selected="selected">-- Select Kategori --</option>
                                            <?php while ($row = mysqli_fetch_object($result)) { ?>
                                                <option value="<?php echo $row->idkategori; ?>"><?php echo $row->namakategori; ?></option>
                                            <?php }?>
                                    </select>
                            </div>
                            <div class="form-group form-float form-group-lg">
                                <div class="form-line">
                                    <textarea class="form-control" name="isi"></textarea>
                                    <label class="form-label">Isi Berita</label>
                                </div>
                            </div>
                            <!-- <form method="POST" enctype="multipart/form-data" style="margin-top:24px;"> -->
                            <button name="uploadTraining" type="submit" class="btn btn-success pull-right" style="bottom:0;margin-top:36px;">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </div>
</div> -->

<!-- <div id="edit_data" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-nav-tabs card-plain">
                    <div class="card-header card-header-primary">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav nav-tabs" data-tabs="tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#Manual_test" data-toggle="tab">Manual
                                            Edit</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active edit_form" id="Manual_test">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 -->

<!--  <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="Manual_test">
                                <h3>Data Test</h3>
                                <form method="POST" style="margin-top:24px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom:24px;">
                                                <label for="exampleFormControlInput1">Konten Berita</label>
                                                <textarea name="konten" class="form-control" rows="10" cols="80"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom:24px;margin-top: -10px;">
                                                <label for="exampleFormControlSelect1">Kategori Aktual</label>
                                                <select name="aktual" class="form-control selectpicker" data-container="body" data-style="btn btn-danger" id="Jenis Kelamin">
                                                    <option value="NULL" selected>Pilih Kategori</option>
                                                    <option>Olahraga</option>
                                                    <option>Ekonomi</option>
                                                    <option>Politik</option>
                                                    <option>Teknologi</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div> -->
<!--  <button name="insertTest" type="submit" class="btn btn-success pull-right" style="bottom:0;margin-top:36px;">Submit</button> -->

<!-- <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="Manual_training">
                                <h3>Data Training</h3>
                                <form method="POST" style="margin-top:24px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom:24px;">
                                                <label for="exampleFormControlInput1">Konten Berita</label>
                                                <textarea name="konten" class="form-control" rows="10" cols="80"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom:24px;">
                                                <label for="exampleFormControlInput1"></label>
                                                <input  name="0" type="text" class="form-control" id="exampleFormControlInput1" placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group" style="margin-bottom:24px;margin-top: -10px;">
                                                <label for="exampleFormControlSelect1">Kategori</label>
                                                <select name="1" class="form-control selectpicker" data-container="body" data-style="btn btn-danger" id="Jenis Kelamin">
                                                    <option value="NULL" selected>Pilih Kategori</option>
                                                    <option>Olahraga</option>
                                                    <option>Ekonomi</option>
                                                    <option>Politik</option>
                                                    <option>Teknologi</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button name="insertTraining" type="submit" class="btn btn-success pull-right" style="bottom:0;margin-top:36px;">Submit</button>
                                </form>
                            </div> -->