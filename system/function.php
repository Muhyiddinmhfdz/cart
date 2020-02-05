<?php

if($_GET){
    if(function_exists($_GET['f'])) {
        $_GET['f']();
     }
}

$aResult = array();
if(!empty($_POST['functionname'])){
    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            case 'check':
            if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) ) {
                $aResult['error'] = 'Error in arguments!';
            }
            else {
                $aResult['result'] = check($_POST['arguments'][0]);
            }
            break;

            default:
            $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
            break;
        }

    }

    echo json_encode($aResult);
}
    

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

function lastidtesting()
{
    include "koneksi.php";

    $result = mysqli_query($konek,"SELECT max(id_test) FROM data_test");

    // if (!$result) {
    //     die('Could not query:' . mysql_error());
    // }

    $id =mysqli_fetch_array($result);
    return $id[0];
}

function count_all()
{
    include "koneksi.php";
    $query="SELECT COUNT(*) FROM data_training";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_array($result);
        return $res[0];
    }
}

function count_katpemerintah()
{
    include "koneksi.php";
    $query="SELECT COUNT(*) FROM data_training WHERE kategori='1'";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_array($result);
        return $res[0];
    }
}

function count_katnonpemerintah()
{
    include "koneksi.php";
    $query="SELECT COUNT(*) FROM data_training WHERE kategori='2'";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_array($result);
        return $res[0];
    }
}

function kat_pemerintah($idtraining)
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
        $query="INSERT INTO kategoripemerintahan (id_datatraining, id_tbindex, keyword) VALUES ($idtraining, '$res[0]', '$res[1]')";
        mysqli_query($konek,$query);
    }
    return $res;
}

function kat_nonpemerintah($idtraining)
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
        $query="INSERT INTO kategorinonpemerintahan(id_datatraining, id_tbindex, keyword) VALUES ($idtraining, '$res[0]', '$res[1]')";
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

//fungsi untuk membuat index
function buatindextesting($id) {
    include "koneksi.php";
     //hapus index sebelumnya
     // mysqli_query($konek,"TRUNCATE TABLE tbindex");

     //ambil semua berita (teks)
     $resBerita = mysqli_query($konek,"SELECT * FROM data_test WHERE id_test='$id'");
     $num_rows = mysqli_num_rows($resBerita);

     while($row = mysqli_fetch_array($resBerita)) {
         $docId = $row['id_test'];
         $berita = $row['text_proses'];
        //  $id_kategori=$row['kategori'];

           //simpan ke inverted index (tbindex)
           $aberita = explode(" ", trim($berita));

           foreach ($aberita as $j => $value) {
             //hanya jika Term tidak null atau nil, tidak kosong
             if ($aberita[$j] != "") {

                 //berapa baris hasil yang dikembalikan query tersebut?
                 $rescount = mysqli_query($konek,"SELECT Count FROM tbindextesting WHERE Term = '$aberita[$j]' AND DocId = $docId");
                 $num_rows = mysqli_num_rows($rescount);

                 //jika sudah ada DocId dan Term tersebut	, naikkan Count (+1)
                 if ($num_rows > 0) {
                     $rowcount = mysqli_fetch_array($rescount);
                     $count = $rowcount['Count'];
                     $count++;

                     mysqli_query($konek,"UPDATE tbindextesting SET Count = $count WHERE Term = '$aberita[$j]' AND DocId = $docId");
                 }
                 //jika belum ada, langsung simpan ke tbindex
                 else {
                     mysqli_query($konek,"INSERT INTO tbindextesting (Term, DocId, Count) VALUES ('$aberita[$j]', $docId, 1)");
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

function getindextesting($id)
{
    include "koneksi.php";
    $query="SELECT * FROM tbindextesting WHERE DocId='$id'";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_all($result);
        return $res;
    }
}

function getindextraining($kategori,$tblfrom,$tbljoin)
{
    include "koneksi.php";
    $query="SELECT * FROM $tblfrom INNER JOIN $tbljoin ON $tbljoin.id = $tblfrom.id_tbindex WHERE kategori_id='$kategori'";
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_all($result);
        return $res;
    }
}

function count_f($table,$where=NULL)
{
    include "koneksi.php";
    if(empty($where)){
        $query="SELECT COUNT(DISTINCT keyword) FROM $table";
    }
    else{
        $query="SELECT COUNT(DISTINCT Term) FROM $table WHERE $where";
    }
    
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_array($result);
        return $res[0];
    }
}

function count_dataset($where=NULL)
{
    include "koneksi.php";
    if(empty($where)){
        $query="SELECT COUNT(*) FROM data_training";
    }
    else{
        $query="SELECT COUNT(*) FROM data_training WHERE $where";
    }
    
    
    if (!mysqli_query($konek,$query)) {
        echo("Error description: " . mysqli_error($konek));
    }
    else{
        $result = mysqli_query($konek,$query);
        $res =mysqli_fetch_array($result);
        return $res[0];
    }
}

function check($documentid)
{
    // $documentid=$_POST['idval'];
    $countdataset=count_dataset();//hitung jumlah dataset seluruhnya
    $countdatasetpemerintahan=count_dataset("kategori='1'"); //hitung jumlah dataset pemerintahan
    $countdatasetnonpemerintahan=count_dataset("kategori='2'");//hitung jumlah dataset nonpemerintahan
    $katatesting=count_f("tbindextesting","DocId='$documentid'"); //docid //hitung jumlah term dari dokumen yang ditesting
    $katatrainingpemerintahan=count_f("kategoripemerintahan"); //hitung jumlah term training pemerintahan
    $katatrainingnonpemerintahan=count_f("kategorinonpemerintahan"); //hitung jumlah term training nonpemerintahan

    $datatesting=getindextesting("$documentid"); //docID
    $datatrainingpemerintahan=getindextraining("1","kategoripemerintahan","tbindex"); //kategori
    $datatrainingnonpemerintahan=getindextraining("2","kategorinonpemerintahan","tbindex");

    $nilai_kategori_pemerintah=$countdatasetpemerintahan/$countdataset; //nilai kategori pemerintah
    $nilai_kategori_nonpemerintah=$countdatasetnonpemerintahan/$countdataset; //nilai kategori non pemerintah
    // echo var_dump($datatraining[0]);
    // echo var_dump($datatrainingnonpemerintahan);

    $countpemerintahan=0;
    $countnonpemerintahan=0;

    //Pemerintahan
    foreach($datatesting as $testing)
    {
        for ($x = 0; $x <$katatrainingpemerintahan ; $x++) {
            if($testing[1]==$datatrainingpemerintahan[$x][3])
            {
                $countpemerintahan++;
            }
        }
    }

    //nonpemerintahan
    foreach($datatesting as $testing)
    {
        for ($x = 0; $x <$katatrainingnonpemerintahan ; $x++) {
            if($testing[1]==$datatrainingnonpemerintahan[$x][3]){
                $countnonpemerintahan++;
            }
        }
    }

    $trainingpemerintahan=$countpemerintahan/$katatesting;
    $trainingnonpemerintahan=$countnonpemerintahan/$katatesting;
    $respemerintahan=$nilai_kategori_pemerintah-$trainingpemerintahan;
    $resnonpemerintahan=$nilai_kategori_nonpemerintah-$trainingnonpemerintahan;
    $final;
    // echo $countpemerintahan."/".$countnonpemerintahan;
    // echo "==== JUMLAH DATA ====<br>";
    // echo "Jumlah Dataset= ".$countdataset."<br>";
    // echo "Dataset Kategori Pemerintahan= ".$countdatasetpemerintahan."<br>";
    // echo "Dataset Kategori NonPemerintahan= ".$countdatasetnonpemerintahan."<br>";
    // echo "==== NILAI KATEGORI ====<br>";
    // echo "Kategori Pemerintahan= ".$nilai_kategori_pemerintah."<br>";
    // echo "Kategori NonPemerintahan= ".$nilai_kategori_nonpemerintah."<br>";
    // echo "==== NILAI TRAINING ====<br>";
    // echo "Kategori Pemerintahan= ".$trainingpemerintahan."<br>";
    // echo "Kategori NonPemerintahan= ".$trainingnonpemerintahan."<br>";
    // echo "==== HASIL ====<br>";
    // echo "Kategori Pemerintahan= ".$respemerintahan."<br>";
    // echo "Kategori NonPemerintahan= ".$resnonpemerintahan."<br>";
    // echo "==== HASIL AKHIR ====<br>";
    if($respemerintahan<$resnonpemerintahan)
    {
        // echo "PEMERINTAHAN";
        $final="PEMERINTAHAN";
    }
    else{
        // echo "NON-PEMERINTAHAN";
        $final="NON-PEMERINTAHAN";
    }

    $dataresult=array(
        'countdataset'=>$countdataset,
        'countdatasetpemerintahan'=>$countdatasetpemerintahan,
        'countdatasetnonpemerintahan'=>$countdatasetnonpemerintahan,
        'nilai_kategori_pemerintah'=>$nilai_kategori_pemerintah,
        'nilai_kategori_nonpemerintah'=>$nilai_kategori_nonpemerintah,
        'trainingpemerintahan'=>$trainingpemerintahan,
        'trainingnonpemerintahan'=>$trainingnonpemerintahan,
        'respemerintahan'=>$respemerintahan,
        'resnonpemerintahan'=>$resnonpemerintahan,
        'final'=>$final
    );
    return $dataresult;
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

function hitungbobotpemerintah() {
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

function hitungbobotnonpemerintahan() {
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
