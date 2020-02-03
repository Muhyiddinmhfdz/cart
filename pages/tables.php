<div class="tab-content">
    <div class="tab-pane active show" id="test">
        <table class="table table-hover" id="datatest">
            <thead class="text-info">
                <tr>
                    <th>NO</th>
                    <th>Berita</th>
                    <th>Text Preprocessing</th>
                    <th>Kelas Aktual</th>
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
                        echo "<td>" . $key['kategori_aktual'] . "</td>";
                        echo "<td>" . $key['kategori'] . "</td>";
                        // echo '<td>
                        //     <a href="#" data-id="' . $key['id_test'] . '" data-table="data_test" data-toggle="modal" data-target="#edit_data" class="btn btn-info btn-sm"><i class="material-icons">edit</i></a>
                        //     <a id="delete" href="?delete_test=' . $key['id_test'] . '" class="btn btn-danger btn-sm"><i class="material-icons">delete_forever</i></a>
                        //   </td>';
                        // echo "</tr>";
                        
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
                    //     echo '<td>
                    // <a href="#" data-id="' . $key['id_training'] . '" data-table="data_training" data-toggle="modal" data-target="#edit_data" class="btn btn-info btn-sm"><i class="material-icons">edit</i></a>
                    //         <a id="delete" href="?delete_training=' . $key['id_training'] . '" class="btn btn-danger btn-sm"><i class="material-icons">delete_forever</i></a>
                    //       </td>';
                    //     echo "</tr>";
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