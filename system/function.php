<?php
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

function lastid()
{
    include "koneksi.php";

    $result = mysqli_query($konek,"SELECT max(id_training) FROM data_training WHERE kategori='1'");

    // if (!$result) {
    //     die('Could not query:' . mysql_error());
    // }

    $id =mysqli_fetch_array($result);
    return $id[0];
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