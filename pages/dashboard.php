<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- widget -->
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-danger">
                        <div class="card-icon">
                            <i class="material-icons">looks_one</i>
                        </div>
                        <p class="card-header-danger">Data Testing</p>
                        <h3 class="card-title"><?php echo hitung("data_test") ?>
                            <small>Data</small>
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <button class="btn btn-social btn-fill btn-success btn-block" data-toggle="modal" data-target="#tambah_test">
                                <i class="fa fa-plus"></i>&emsp;Tambah Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-danger">
                        <div class="card-icon">
                            <i class="material-icons">looks_two</i>
                        </div>
                        <p class="card-header-danger">Data Training</p>
                        <h3 class="card-title"><?php echo hitung("data_training") ?>
                            <small>Data</small>
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <button class="btn btn-social btn-fill btn-success btn-block" data-toggle="modal" data-target="#tambah_training">
                                <i class="fa fa-plus"></i>&emsp;Tambah Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card card-stats ">
                    <div class="card-header card-header-danger">
                        <div class="card-icon">
                                <i class="material-icons">looks_two</i>

                        </div>
                       <p class="card-header-danger">Klasifikasi </p>
                       <h3 class="card-title">Naive Bayes</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats ">
                            <h2 class="card-title text-right ">Akurasi <?php echo hitung("data_test") != 0 ? akurasi() : "0" ?>%</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-danger">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav nav-tabs" data-tabs="tabs">
                                    <li class="nav-item" style="margin-right:8px;">
                                        <a class="nav-link active" href="#test" data-toggle="tab">
                                            <i class="material-icons">archive</i> Data Testing
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#training" data-toggle="tab">
                                            <i class="material-icons">archive</i> Data Training
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#perhitungan" data-toggle="tab">
                                            <i class="material-icons">archive</i> Perhitungan
                                            <div class="ripple-container"></div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        include("tables.php");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("modals.php");
?>
<!-- <button class="btn btn-primary" onclick="md.showNotification('top','right')">Top Right Notification</button> -->



<script>
    $(document).ready(function() {
        $('#datatest').DataTable({
            'info': false,
            "bLengthChange": false,
            "order": [[ 0, 'desc' ]]
        });
        $('#datatraining').DataTable({
            'info': false,
            "bLengthChange": false,
            "order": [[ 0, 'desc' ]]
        });
        $('#edit_data').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('id');
            var table = $(e.relatedTarget).data('table');
            $.ajax({
                type: "GET",
                url: "pages/form.php",
                data: {
                    table: table,
                    id: id,
                },
                success: function(data) {
                    $('.edit_form').html(data); //menampilkan data ke dalam modal
                    CKEDITOR.replace('editor3');
                    $('#edit_data').perfectScrollbar();
                    $('.selectpicker').selectpicker();
                }
            });
        });
    });
</script>


<?php

    ini_set('memory_limit', '500M');
    ini_set('max_execution_time', 120);

// if (isset($_POST['insertTest'])) {
//     $kontenText = $_POST['konten'];
//     $aktual = $_POST['aktual'];
//     $proses_text = processing($kontenText);
//     $kategori = bayes($proses_text);
//     $arraytopost = [
//         'kontent' => $kontenText,
//         'text_pro' => $proses_text,
//         'kategori' => $kategori,
//         'aktual' => $aktual,
//         'success' => true
//     ];
//     $input_data = input("data_test", $arraytopost);
//     echo $input_data == true ? "<script>$(document).ready(function(){ success('Insert') });</script>" : "<script>$(document).ready(function(){ gagal('Insert')}); </script>";
// }
if (isset($_POST['uploadTest'])) {
    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
            
                $proses_text = processing($getData[0]);
                if($proses_text != ""){
                $kategori = bayes($proses_text);    
                }
                
                $arraytopost = [
                    'kontent' => $getData[0],
                    'text_pro' => $proses_text,
                    'kategori' => $kategori,
                    'aktual' => $getData[1],
                ];
                $input_data = upload("data_test", $arraytopost);
        
        }
        echo $input_data == true ? "<script>$(document).ready(function(){ success('Upload') });</script>" : "<script>$(document).ready(function(){ gagal('Upload')});</script>";
    }
}
// if (isset($_POST['insertTraining'])) {
//     $input_data = input("data_training", $_POST);
//     echo $input_data == true ? "<script>$(document).ready(function(){ success('Insert') });</script>" : "<script>$(document).ready(function(){ gagal('Insert')});</script>";
// }
if (isset($_POST['uploadTraining'])) {
    // $filename = $_FILES["file"]["tmp_name"];
    // if ($_FILES["file"]["size"] > 0) {
    //     $file = fopen($filename, "r");
    //     while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
    //         $proses_text = processing($getData[0]);
    //         $arraytopost = [
    //             'kontent' => $getData[0],
    //             'text_pro' => $proses_text,
    //             'kategori' => $getData[1]
    //         ];
    //         $input_data = upload("data_training", $arraytopost);
    //         // print_r($arraytopost);
    //     }
    //     echo $input_data == true ? "<script>$(document).ready(function(){ success('Upload') });</script>" : "<script>$(document).ready(function(){ gagal('Upload')});</script>";
    // }
    
    $proses_text = processing($_POST['isi']);
    $arraytopost = [
        'kontent' => $_POST['isi'],
        'text_pro' => $proses_text,
        'kategori' => $_POST['kategori']
    ];
    $input_data = upload("data_training", $arraytopost);
    // $last_id=lastid("data_training",$_POST['kategori']);
    buatindex(lastid("id_training","data_training",$_POST['kategori']));
    if($_POST['kategori']==1){
        kat_olahraga(lastid("id_training","data_training",$_POST['kategori']));
        hitungbobotolahraga();
    }
    if($_POST['kategori']==2){
        kat_pendidikan(lastid("id_training","data_training",$_POST['kategori']));
        hitungbobotpendidikan();
    }
    if($_POST['kategori']==3){
        kat_teknologi(lastid("id_training","data_training",$_POST['kategori']));
        hitungbobotteknologi();
    }
    if($_POST['kategori']==4){
        kat_pemerintahan(lastid("id_training","data_training",$_POST['kategori']));
        hitungbobotpemerintahan();
    }
    // echo $_POST['isi'];
    
    echo $input_data == true ? "<script>$(document).ready(function(){ success('Upload') });</script>" : "<script>$(document).ready(function(){ gagal('Upload')});</script>";

}
// if (isset($_GET['delete_test'])) {
//     $delete_data = delete_data("data_test", "where id_test = '" . $_GET['delete_test'] . "'");
//     echo $delete_data == true ? "<script>$(document).ready(function(){ success('Delete') });</script>" : "<script>$(document).ready(function(){ gagal('Delete')});</script>";
// }
// if (isset($_GET['delete_training'])) {
//     $delete_data = delete_data("data_training", "where id_training = '" . $_GET['delete_training'] . "'");
//     echo $delete_data == true ? "<script>$(document).ready(function(){ success('Delete') });</script>" : "<script>$(document).ready(function(){ gagal('Delete')});</script>";
// }

// if (isset($_POST['editTest'])) {
//     $kondisi = $_POST['kon'];
//     $EditTest = edit_data("data_test", $_POST, $kondisi);
//     echo $EditTest == true ? "<script>$(document).ready(function(){ success('Edit') });</script>" : "<script>$(document).ready(function(){ gagal('Edit')});</script>";
// }
// if (isset($_POST['editTraining'])) {
//     $kondisi = $_POST['kon'];
//     $editTraining = edit_data("data_training", $_POST, $kondisi);
//     echo $editTraining == true ? "<script>$(document).ready(function(){ success('Edit') });</script>" : "<script>$(document).ready(function(){ gagal('Edit')});</script>";
// }
?>