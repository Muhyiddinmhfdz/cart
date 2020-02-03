<!-- <?php
require_once('../system/function.php');

$_GET['table'] == "data_test" ? $kondisi = "where id_test=".$_GET['id'] : $kondisi = "where id_training=".$_GET['id'] ; 
$data = getrow($_GET['table'],$kondisi);
?>
<h3>Edit <?php echo ucfirst(str_replace('_',' ',$_GET['table']))?></h3>
<form method="POST" style="margin-top:24px;">
    <input name="kon" value="<?php echo $kondisi ?>" readonly hidden>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group" style="margin-bottom:24px;">
                <label for="exampleFormControlInput1">Konten Berita</label>
                <textarea name="konten" class="form-control" rows="10" cols="80"><?php echo $data[0]['konten'] ?></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" style="margin-bottom:24px;">
                <label for="exampleFormControlInput1">Text Proses</label>
                <input value="<?php echo $data[0]['text_proses'] ?>" required name="text_proses" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Hasil text prosesing konten berita">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" style="margin-bottom:24px;margin-top: -10px;">
                <label for="exampleFormControlSelect1">Kategori</label>
                <select name="kategori" class="form-control selectpicker" data-container="body" data-style="btn btn-primary" id="Jenis Kelamin">
                    <option <?php echo $data[0]['kategori'] == 'NULL' ? "selected": ""; ?> value="NULL" selected>Pilih Kategori</option>
                    <option <?php echo $data[0]['kategori'] == 'Olahraga' ? "selected": ""; ?> >Olahraga</option>
                    <option <?php echo $data[0]['kategori'] == 'Ekonomi' ? "selected": ""; ?> >Ekonomi</option>
                    <option <?php echo $data[0]['kategori'] == 'Politik' ? "selected": ""; ?> >Politik</option>
                    <option <?php echo $data[0]['kategori'] == 'Teknologi' ? "selected": ""; ?> >Teknologi</option>
                </select>
            </div>
        </div>
    </div>
    <?php 
    if($_GET['table'] == "data_test"){
        echo'<button name="editTest" type="submit" class="btn btn-success pull-right" style="bottom:0;margin-top:36px;">Submit</button>';
    }else{
        echo'<button name="editTraining" type="submit" class="btn btn-success pull-right" style="bottom:0;margin-top:36px;">Submit</button>';
    }
    ?>
    
</form> -->