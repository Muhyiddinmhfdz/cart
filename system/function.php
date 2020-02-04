<?php

// if(function_exists($_GET['f'])) {
//     $_GET['f']();
//  }
//---------------------------- Bayes Libs - PHP AI
require_once __DIR__ . '/../vendor/autoload.php';

use Phpml\Classification\NaiveBayes;
use Phpml\Metric\Accuracy;
//use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Dataset\ArrayDataset;
use Phpml\Pipeline;
//----------------------------


//---------------------------- DB Connect
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "berita";
$db = new mysqli($servername, $username, $password, $dbname);



//---------------------------- Global Function
function bayes($data)
{
    // ambil dataTrain
    $data_train = getrow('data_training', 'order by id_training');
    foreach ($data_train as $key) {
        $samples[] = $key['text_proses'];
        $labels[] = $key['kategori'];
    }
    // ubah dataTest menjadi Array
    $dataTest[] = $data;

    // gunakan tokenisasi + TfIDF
    $transformers = [
        new TokenCountVectorizer(new WhitespaceTokenizer()),
        //new TfIdfTransformer(),
    ];

    $estimator = new NaiveBayes(); // gunakan Klasifikasi NBC
    $pipeline = new Pipeline($transformers, $estimator); // deklarasi pipeline

    $pipeline->train($samples, $labels); // training dataTrain
    $predicted = $pipeline->predict($dataTest); // prediksi dataTest

    return $predicted[0]; // return array pertama (hasil cuman 1)
}

function processing($kontent)
{
    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory(); // Gunakan Stemmer Sastrawi
    $stemmer  = $stemmerFactory->createStemmer(); // Buat Stemmer
    $kontent = strtolower($kontent); //mengubah huruf menjadi Lowercase
    $kontenArr = explode(' ', $kontent); // pecah kalimat menjadi perkata (Tokenisasi)
    $stopword = getrow('stopword', ''); // ambil daftar kata tak penting
    foreach ($stopword as $key) { // hilangkan kata tak penting (Stopword)
        if (in_array($key['stopword'], $kontenArr)) {
            $keyArr = array_search($key['stopword'], $kontenArr);
            unset($kontenArr[$keyArr]);
        }
    }
    $newKonten = implode(' ', $kontenArr); // gabungkan kata menjadi kalimat
    $hasil_stemming = $stemmer->stem($newKonten); // proses stemming
    return $hasil_stemming;
    
}

function lastid($coloumn,$table,$kategori)
{
    include "koneksi.php";

    $result = mysqli_query($konek,"SELECT max($coloumn) FROM $table WHERE kategori='$kategori'");

    // if (!$result) {
    //     die('Could not query:' . mysql_error());
    // }

    $id =mysqli_fetch_array($result);
    return $id[0];
}

function kat_olahraga($idtraining)
{
    include "koneksi.php";

    // $result = mysqli_query($konek,"SELECT * FROM tbindex WHERE kategori_id='1' AND DocId='$idtraining' LIMIT 3 ORDER BY 'Count' Desc ");

    // $id =mysqli_fetch_array($result);
    // $id=mysqli_error($result);
    $query="(SELECT * FROM tbindex WHERE kategori_id='1' AND DocId='$idtraining' ORDER BY Count DESC)LIMIT 3";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_all($result);
    }

    foreach($res as $res) {
        $query="INSERT INTO kategoriolahraga (id_datatraining, id_tbindex, keyword) VALUES ($idtraining, '$res[0]', '$res[1]')";
        mysqli_query($konek,$query);
    }
    return $res;
}

function kat_pendidikan($idtraining)
{
    include "koneksi.php";

    // $result = mysqli_query($konek,"SELECT * FROM tbindex WHERE kategori_id='1' AND DocId='$idtraining' LIMIT 3 ORDER BY 'Count' Desc ");

    // $id =mysqli_fetch_array($result);
    // $id=mysqli_error($result);
    $query="(SELECT * FROM tbindex WHERE kategori_id='2' AND DocId='$idtraining' ORDER BY Count DESC)LIMIT 3";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_all($result);
    }

    foreach($res as $res) {
        $query="INSERT INTO kategoripendidikan (id_datatraining, id_tbindex, keyword) VALUES ($idtraining, '$res[0]', '$res[1]')";
        mysqli_query($konek,$query);
    }
    return $res;
}

function kat_teknologi($idtraining)
{
    include "koneksi.php";

    // $result = mysqli_query($konek,"SELECT * FROM tbindex WHERE kategori_id='1' AND DocId='$idtraining' LIMIT 3 ORDER BY 'Count' Desc ");

    // $id =mysqli_fetch_array($result);
    // $id=mysqli_error($result);
    $query="(SELECT * FROM tbindex WHERE kategori_id='3' AND DocId='$idtraining' ORDER BY Count DESC)LIMIT 3";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_all($result);
    }

    foreach($res as $res) {
        $query="INSERT INTO kategoriteknologi (id_datatraining, id_tbindex, keyword) VALUES ($idtraining, '$res[0]', '$res[1]')";
        mysqli_query($konek,$query);
    }
    return $res;
}

function kat_pemerintahan($idtraining)
{
    include "koneksi.php";

    // $result = mysqli_query($konek,"SELECT * FROM tbindex WHERE kategori_id='1' AND DocId='$idtraining' LIMIT 3 ORDER BY 'Count' Desc ");

    // $id =mysqli_fetch_array($result);
    // $id=mysqli_error($result);
    $query="(SELECT * FROM tbindex WHERE kategori_id='4' AND DocId='$idtraining' ORDER BY Count DESC)LIMIT 3";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_all($result);
    }

    foreach($res as $res) {
        $query="INSERT INTO kategoripemerintahan (id_datatraining, id_tbindex, keyword) VALUES ($idtraining, '$res[0]', '$res[1]')";
        mysqli_query($konek,$query);
    }
    return $res;
}

//fungsi untuk membuat index
function buatindex($id) {
       include "koneksi.php";
		//hapus index sebelumnya
		// mysqli_query($konek,"TRUNCATE TABLE tbindex");

		//ambil semua berita (teks)
		$resBerita = mysqli_query($konek,"SELECT * FROM data_training WHERE id_training='$id'");
		$num_rows = mysqli_num_rows($resBerita);
		print("Mengindeks sebanyak " . $num_rows . " dokumen. <br />");

		while($row = mysqli_fetch_array($resBerita)) {
			$docId = $row['id_training'];
            $berita = $row['text_proses'];
            $id_kategori=$row['kategori'];

  			//simpan ke inverted index (tbindex)
  			$aberita = explode(" ", trim($berita));

  			foreach ($aberita as $j => $value) {
				//hanya jika Term tidak null atau nil, tidak kosong
				if ($aberita[$j] != "") {

					//berapa baris hasil yang dikembalikan query tersebut?
					$rescount = mysqli_query($konek,"SELECT Count FROM tbindex WHERE Term = '$aberita[$j]' AND DocId = $docId");
					$num_rows = mysqli_num_rows($rescount);

					//jika sudah ada DocId dan Term tersebut	, naikkan Count (+1)
					if ($num_rows > 0) {
						$rowcount = mysqli_fetch_array($rescount);
						$count = $rowcount['Count'];
						$count++;

						mysqli_query($konek,"UPDATE tbindex SET Count = $count WHERE Term = '$aberita[$j]' AND DocId = $docId");
					}
					//jika belum ada, langsung simpan ke tbindex
					else {
						mysqli_query($konek,"INSERT INTO tbindex (Term, DocId, kategori_id, Count) VALUES ('$aberita[$j]', $docId, $id_kategori, 1)");
					}
				} //end if
			} //end foreach
  		} //end while
} //end function buatindex()

function simpandata()
{
        include "koneksi.php";
        $sql_simpan = mysql_query ("INSERT into kategoriolahraga(term) VALUES ('$row')");
       	mysql_query($sql_simpan);
}

function akurasi()
{
    $data_test = getrow('data_test');
    foreach ($data_test as $key) {
        $actualLabels[] = $key['kategori_aktual'];
        $predictedLabels[] = $key['kategori'];
    }
    $accur = accuracy::score($actualLabels, $predictedLabels);
    $hasil = number_format($accur, 4) * 100;
    return $hasil;
}

function getrow($table = "", $kondisi = "")
{
    global $db;
    $val = array();
    $sql = mysqli_query($db, "select * from $table $kondisi");
    while ($data = mysqli_fetch_assoc($sql)) {
        $val[] = $data;
    }
    return $val;
}

function input($table = "", $post)
{
    global $db;
    array_pop($post); // button
    array_unshift($post, NULL); //id : pk
    $data = "'" . implode("','", $post) . "'";
    $sql = mysqli_query($db, "insert into $table values($data)");
    return $sql ? true : false;
}

function upload($table = "", $post)
{
    global $db;
    array_unshift($post, NULL); //id : pk
    $data = "'" . implode("','", $post) . "'";
    $sql = mysqli_query($db, "insert into $table values($data)");
    return $sql ? true : false;
}

function hitung($table = "", $kondisi = "")
{
    global $db;
    return mysqli_num_rows(mysqli_query($db, "select * from $table $kondisi"));
}

function hitungbobotolahraga() {
	include "koneksi.php";
	//berapa jumlah DocId total?, n
	$resn = mysqli_query($konek,"SELECT DISTINCT Id FROM tbindex");
	$n = mysqli_num_rows($resn);

	//ambil setiap record dalam tabel tbindex
    //hitung bobot untuk setiap Term dalam setiap DocId
    $query="SELECT * FROM tbindex WHERE kategori_id='1' ORDER BY Id";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $resBobot = mysqli_query($konek,$query);
        $num_rows = mysqli_num_rows($resBobot);
        print("Terdapat " . $num_rows . " Term yang diberikan bobot. <br />");
        while($rowbobot = mysqli_fetch_array($resBobot)) {
            //$w = tf * log (n/N)
        	$term = $rowbobot['Term'];
        	$tf = $rowbobot['Count'];
        	$id = $rowbobot['Id'];

        	//berapa jumlah dokumen yang mengandung term tersebut?, N
        	$resNTerm = mysqli_query($konek,"SELECT Count(*) as N FROM tbindex WHERE Term = '$term' AND kategori_id='1'");
        	$rowNTerm = mysqli_fetch_array($resNTerm);
        	$NTerm = $rowNTerm['N'];

        	$w = $tf * log($n/$NTerm);

        	//update bobot dari term tersebut
        	$resUpdateBobot = mysqli_query($konek,"UPDATE tbindex SET Bobot = $w WHERE Id = $id");
        } //end while $rowbobot
    }
} //end function hitungbobot
function hitungbobotpendidikan() {
	include "koneksi.php";
	//berapa jumlah DocId total?, n
	$resn = mysqli_query($konek,"SELECT DISTINCT Id FROM tbindex");
	$n = mysqli_num_rows($resn);

	//ambil setiap record dalam tabel tbindex
    //hitung bobot untuk setiap Term dalam setiap DocId
    $query="SELECT * FROM tbindex WHERE kategori_id='2' ORDER BY Id";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $resBobot = mysqli_query($konek,$query);
        $num_rows = mysqli_num_rows($resBobot);
        print("Terdapat " . $num_rows . " Term yang diberikan bobot. <br />");
        while($rowbobot = mysqli_fetch_array($resBobot)) {
            //$w = tf * log (n/N)
        	$term = $rowbobot['Term'];
        	$tf = $rowbobot['Count'];
        	$id = $rowbobot['Id'];

        	//berapa jumlah dokumen yang mengandung term tersebut?, N
        	$resNTerm = mysqli_query($konek,"SELECT Count(*) as N FROM tbindex WHERE Term = '$term' AND kategori_id='2'");
        	$rowNTerm = mysqli_fetch_array($resNTerm);
        	$NTerm = $rowNTerm['N'];

        	$w = $tf * log($n/$NTerm);

        	//update bobot dari term tersebut
        	$resUpdateBobot = mysqli_query($konek,"UPDATE tbindex SET Bobot = $w WHERE Id = $id");
        } //end while $rowbobot
    }
} //end function hitungbobot

function hitungbobotteknologi() {
	include "koneksi.php";
	//berapa jumlah DocId total?, n
	$resn = mysqli_query($konek,"SELECT DISTINCT Id FROM tbindex");
	$n = mysqli_num_rows($resn);

	//ambil setiap record dalam tabel tbindex
    //hitung bobot untuk setiap Term dalam setiap DocId
    $query="SELECT * FROM tbindex WHERE kategori_id='3' ORDER BY Id";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $resBobot = mysqli_query($konek,$query);
        $num_rows = mysqli_num_rows($resBobot);
        print("Terdapat " . $num_rows . " Term yang diberikan bobot. <br />");
        while($rowbobot = mysqli_fetch_array($resBobot)) {
            //$w = tf * log (n/N)
        	$term = $rowbobot['Term'];
        	$tf = $rowbobot['Count'];
        	$id = $rowbobot['Id'];

        	//berapa jumlah dokumen yang mengandung term tersebut?, N
        	$resNTerm = mysqli_query($konek,"SELECT Count(*) as N FROM tbindex WHERE Term = '$term' AND kategori_id='3'");
        	$rowNTerm = mysqli_fetch_array($resNTerm);
        	$NTerm = $rowNTerm['N'];

        	$w = $tf * log($n/$NTerm);

        	//update bobot dari term tersebut
        	$resUpdateBobot = mysqli_query($konek,"UPDATE tbindex SET Bobot = $w WHERE Id = $id");
        } //end while $rowbobot
    }
} //end function hitungbobot
function hitungbobotpemerintahan() {
	include "koneksi.php";
	//berapa jumlah DocId total?, n
	$resn = mysqli_query($konek,"SELECT DISTINCT Id FROM tbindex");
	$n = mysqli_num_rows($resn);

	//ambil setiap record dalam tabel tbindex
    //hitung bobot untuk setiap Term dalam setiap DocId
    $query="SELECT * FROM tbindex WHERE kategori_id='4' ORDER BY Id";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $resBobot = mysqli_query($konek,$query);
        $num_rows = mysqli_num_rows($resBobot);
        print("Terdapat " . $num_rows . " Term yang diberikan bobot. <br />");
        while($rowbobot = mysqli_fetch_array($resBobot)) {
            //$w = tf * log (n/N)
        	$term = $rowbobot['Term'];
        	$tf = $rowbobot['Count'];
        	$id = $rowbobot['Id'];

        	//berapa jumlah dokumen yang mengandung term tersebut?, N
        	$resNTerm = mysqli_query($konek,"SELECT Count(*) as N FROM tbindex WHERE Term = '$term' AND kategori_id='4'");
        	$rowNTerm = mysqli_fetch_array($resNTerm);
        	$NTerm = $rowNTerm['N'];

        	$w = $tf * log($n/$NTerm);

        	//update bobot dari term tersebut
        	$resUpdateBobot = mysqli_query($konek,"UPDATE tbindex SET Bobot = $w WHERE Id = $id");
        } //end while $rowbobot
    }
} //end function hitungbobot
// function delete_data($table = "", $kondisi = "")
// {
//     global $db;
//     $sql = mysqli_query($db, "delete from $table $kondisi");
//     return $sql ? true : false;
// }
// function edit_data($table = "", $post, $id)
// {
//     global $db;
//     array_pop($post); // button
//     array_shift($post); //id : pk
//     $jumlah = count($post);
//     $num = 1;
//     $contt = '';
//     foreach ($post as $key => $val) {
//         $contt .= $key . "='" . $val . "'";
//         $num < $jumlah ? $contt .= "," : $contt .= " " . $id;
//         $num++;
//     }

//     $sql = mysqli_query($db, "Update " . $table . " set " . $contt);
//     return $sql ? true : false;
// }
