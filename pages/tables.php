<div class="tab-content">
    <div class="tab-pane active show" id="test">
        <table class="table table-hover" id="datatest">
            <thead class="text-info">
                <tr>
                    <th>NO</th>
                    <th>Berita</th>
                    <th>Text Preprocessing</th>
                    
                    <th>Prediksi</th>
                    <!-- <th>Option</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                $data_test = getrow("data_test", "order by id_test DESC");
                if (!empty($data_test)) {
                    foreach ($data_test as $key) {
                        echo "<tr>";
                        echo "<td>" . $key['id_test'] . "</td>";
                        echo "<td>" . $key['konten'] . "</td>";
                        echo "<td>" . $key['text_proses'] . "</td>";
                        // echo "<td>" . $key['kategori_aktual'] . "</td>";
                        echo "<td>" . $key['kategori'] . "</td>";
                        // echo '<td>
                        //     <a href="#" data-id="' . $key['id_test'] . '" data-table="data_test" data-toggle="modal" data-target="#edit_data" class="btn btn-info btn-sm"><i class="material-icons">edit</i></a>
                        //     <a id="delete" href="?delete_test=' . $key['id_test'] . '" class="btn btn-danger btn-sm"><i class="material-icons">delete_forever</i></a>
                        //   </td>';
                        // echo "</tr>";
                        $idd=$key['id_test'];
                        // echo '<td>
                        //     <form method="POST" enctype="multipart/form-data" style="margin-top:24px;">
                        //         <input  name="id" type="hidden" value="'.$idd.'">
                        //         <button name="resultfinal" value="'.$idd.'" type="submit" class="btn btn-success pull-right" style="bottom:0;margin-top:36px;">Submit</button>
                        //     </form>
                        // </td>';
                        echo '<td>
                           
                                <a type="button" class="btn btn-success" style="bottom:0;margin-top:36px;" onclick="jajal('.$idd.');">Submit</button>
                           
                        </td>';
                        echo "</tr>";
                    }
                
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="training">
        <table class="table table-hover" id="datatraining">
            <thead class="text-info">
                <tr>
                    <th>NO</th>
                    <th>Berita</th>
                    <th>Text Preprocessing</th>
                    <th>Kategori</th>
                    <th>Keyword</th>
                    <!-- <th>Option</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                $data_train = getrow("data_training", "LEFT JOIN `kategori` ON data_training.kategori = kategori.idkategori order by id_training DESC");
                if (!empty($data_train)) {
                    foreach ($data_train as $key) {
                        echo "<tr>";
                        echo "<td>" . $key['id_training'] . "</td>";
                        echo "<td>" . $key['konten'] . "</td>";
                        echo "<td>" . $key['text_proses'] . "</td>";
                        echo "<td>" . $key['namakategori'] . "</td>";
                        if($key['kategori']==1){
                            $sql="INNER JOIN `data_training` ON kategoripemerintahan.id_datatraining=data_training.id_training WHERE id_datatraining=".$key['id_training']."";
                            $data_keyword = getrow("kategoripemerintahan",$sql);
                        }
                        if($key['kategori']==2){
                            $sql="INNER JOIN `data_training` ON kategorinonpemerintahan.id_datatraining=data_training.id_training WHERE id_datatraining=".$key['id_training']."";
                            $data_keyword = getrow("kategorinonpemerintahan",$sql);
                        }
                        echo "<td>";
                        foreach ($data_keyword as $keyword) {
                            echo $keyword['keyword'].'<br>';
                        }
                        echo "</td>";
                        
                    //     echo '<td>
                    // <a href="#" data-id="' . $key['id_training'] . '" data-table="data_training" data-toggle="modal" data-target="#edit_data" class="btn btn-info btn-sm"><i class="material-icons">edit</i></a>
                    //         <a id="delete" href="?delete_training=' . $key['id_training'] . '" class="btn btn-danger btn-sm"><i class="material-icons">delete_forever</i></a>
                    //       </td>';
                        echo "</tr>";
                    }
                }
                // var_dump($data_train)
                ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="perhitungan">
        <table class="table table-hover" id="datatraining">
        <a href="simpandata.php" class="btn btn-success" id="simpan">simpan</a><td>
            <thead class="text-info">
                <tr>
                    
                    <th>Kata Banyak Muncul</th>
                    <!-- <th>Option</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                // include 'system/koneksi.php';
                // $coba = mysqli_query($konek,"SELECT * FROM tbindex ORDER BY Count DESC LIMIT 3");
                
                //     foreach ($coba as $row) {
                        
                //         echo "<tr>";
                //         echo "<td>" . $row['Term'] . "</td>";
                //         mysqli_query($konek,"INSERT INTO kategoriolahraga (keyword) VALUES ('$row[Term]')");
                //     //     echo '<td>
                //     // <a href="#" data-id="' . $key['id_training'] . '" data-table="data_training" data-toggle="modal" data-target="#edit_data" class="btn btn-info btn-sm"><i class="material-icons">edit</i></a>
                //     //         <a id="delete" href="?delete_training=' . $key['id_training'] . '" class="btn btn-danger btn-sm"><i class="material-icons">delete_forever</i></a>
                //     //       </td>';
                //     //     echo "</tr>";
                    
                // }
                ?>
                
            </tbody>
        </table>
    </div>
</div>