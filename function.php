<?php
session_start();

// Membuat koneksi ke database
$c = mysqli_connect('localhost', 'root', '', 'kasir');

// Cek koneksi
if (!$c) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Login
if (isset($_POST['login'])) {
    // Ambil data dari form login
    $username = mysqli_real_escape_string($c, $_POST['username']);
    $password = mysqli_real_escape_string($c, $_POST['password']);

    // Query untuk cek username dan password
    $check = mysqli_query($c, "SELECT * FROM user WHERE username='$username' AND password='$password'");
    $hitung = mysqli_num_rows($check);

    if ($hitung > 0) {
        // Jika data ditemukan, berhasil login
        $_SESSION['Login'] = 'True';

        // Redirect untuk menghindari pesan browser
        header('HTTP/1.1 303 See Other');
        header('Location: index.php');
        exit();
    } else {
        // Jika data tidak ditemukan, gagal login
        echo '
        <script>
            alert("Username atau Password salah");
            window.location.href="login.php";
        </script>
        ';
        exit();
    }
}



if(isset($_POST['tambahbarang'])){
    $namaproduk = $_POST['namaproduk'];  // Tambahkan titik koma di sini
    $deskrip = $_POST['deskripsi'];      // Pastikan nama variabel konsisten
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Perbaiki penggunaan $deskrip yang seharusnya $deskripsi
    $insert = mysqli_query($c,"INSERT INTO produk (namaproduk, deskripsi, harga, stok) values ('$namaproduk','$deskrip','$harga','$stok')");
    var_dump($insert);
    if($insert){
        header('location:stok.php');
    } else {
        echo '
        <script>alert("Gagal menambah barang baru");
        window.location.href="stok.php"
        </script>
        ';
    }
}



if(isset($_POST['tambahpelanggan'])){
    $namapelanggan = $_POST['namapelanggan'];  // Tambahkan titik koma di sini
    $notelp = $_POST['notelp'];      // Pastikan nama variabel konsisten
    $alamat = $_POST['alamat'];

    // Perbaiki penggunaan $deskrip yang seharusnya $deskripsi
    $insert = mysqli_query($c,"INSERT INTO pelanggan (namapelanggan, notelp, alamat) values ('$namapelanggan','$notelp','$alamat')");
    var_dump($insert);
    if($insert){
        header('location:pelanggan.php');
    } else {
        echo '
        <script>alert("Gagal menambah pelanggan baru");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}



if(isset($_POST['tambahpesanan'])){
    $idpelanggan = $_POST['idpelanggan'];  // Tambahkan titik koma di sini

    // Perbaiki penggunaan $deskrip yang seharusnya $deskripsi
    $insert = mysqli_query($c,"INSERT INTO pesanan (idpelanggan) values (' $idpelanggan')");
    var_dump($insert);
    if($insert){
        header('location:index.php');
    } else {
        echo '
        <script>alert("Gagal menambah pesanan baru");
        window.location.href="index.php"
        </script>
        ';
    }
}


//produk dipilih di pesanan
if(isset($_POST['addproduk'])){
    $idproduk = $_POST['idproduk'];  // Tambahkan titik koma di sini
    $idp = $_POST['idp'];//id pesanan
    $qty = $_POST['qty'];//Jumlah yang mau dikeluarin

    //hitung stok sekarang ada berapa
    $hitung1 = mysqli_query($c,"select * from produk where idproduk='$idproduk'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stoksekarang = $hitung2['stok']; //stok barang saat ini

    if($stoksekarang>=$qty){

        //kurangi stoknya dengan jumlah ayang akan dikeluarkan
        $selisih = $stoksekarang-$qty;

        //stoknya cukup
        // Perbaiki penggunaan $deskrip yang seharusnya $deskripsi
    $insert = mysqli_query($c,"INSERT INTO detailpesanan (idpesanan,idproduk,qty) values (' $idp','$idproduk',' $qty')");
    var_dump($insert);
    $update = mysqli_query($c,"update produk set stok='$selisih' where idproduk='$idproduk'");
    
    if($insert&&$update){
        header('location:view.php?idp'.$idp);
    } else {
        echo '
        <script>alert("Gagal menambah pesanan baru");
        window.location.href="view.php?idp='.$idp.'"
        </script>
        ';
    }

    } else {
        //stok ga cukup
        echo '
        <script>alert("stok barang tidak cukup");
        window.location.href="view.php?idp='.$idp.'"
        </script>
        ';
        
    }

}




//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $idproduk = $_POST['idproduk'];
    $qty = $_POST['qty'];
    
    $insertbarangmasuk = mysqli_query($c,"insert into masuk (idproduk,qty) values('$idproduk','$qty')");

    if($insertbarangmasuk){
        header('location:masuk.php');
    } else {
        echo '
        <script>alert("gagal");
        window.location.href="masuk.php"
        </script>
        ';
    }
}




//hapus produk pesanan
if(isset($_POST['hapusprodukpesanan'])){
    $idp = $_POST['idp']; //iddetailpesanan
    $idr = $_POST['idr'];
    $idorder = $_POST['idorder'];

    //cek qty sekarang
    $cek1 = mysqli_query($c,"select * from detailpesanan where iddetailpesanan='$idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    //cek stok sekarang
    $cek3 = mysqli_query($c, "select * from produk where idproduk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stoksekarang = $cek4['stok'];

    $hitung = $stoksekarang+$qtysekarang;

    $update = mysqli_query($c, "update produk set stok='$hitung' where idproduk='$idpr'");//update stok
    $hapus = mysqli_query($c,"delete from detailpesanan where idproduk='$idpr' and iddetailpesanan='$idp'");

    if($update&&$hapus){
        header('location:view.php?idp'.$idorder);
    } else {
        echo '
        <script>alert("Gagal menghapus barang");
        window.location.href="view.php?idp='.$idorder.'"
        </script>
        ';
    }

}


//edit barang
if(isset($_POST['editbarang'])){
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $idp = $_POST['idp']; //idproduk

    $query = mysqli_query($c,"update produk set namaproduk='$namaproduk', deskripsi='$deskripsi', harga='$harga' where idproduk='$idp' ");

    if($query){
        header('location:stok.php');
    } else {
        echo '
        <script>alert("gagal");
        window.location.href="stok.php"
        </script>
        ';
    }
}


//Hapus barang
if(isset($_POST['hapusbarang'])){
    $idp = $_POST['idp'];

    $query = mysqli_query($c,"delete from produk where idproduk='$idp'");
    if($query){
        header('location:stok.php');
    } else {
        echo '
        <script>alert("gagal");
        window.location.href="stok.php"
        </script>
        ';
    }
}


//edit pelanggan
if(isset($_POST['editpelanggan'])){  
    $np = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];
    $idpl = $_POST['idpl'];

    $query = mysqli_query($c,"update pelanggan set namapelanggan='$np', notelp='$notelp', alamat='$alamat' where idpelanggan='$idpl' ");

    if($query){
        header('location:pelanggan.php');
    } else {
        echo '
        <script>alert("gagal");
        window.location.href="pelanggan.php"
        </script>
 
        ';
    }
}


//Hapus pelanggan
if(isset($_POST['hapuspelanggan'])){
    $idpl = $_POST['idpl'];

    $query = mysqli_query($c,"delete from pelanggan where idpelanggan='$idpl'");
    if($query){
        header('location:pelanggan.php');
    } else {
        echo '
        <script>alert("gagal");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}


//edit barang masuk
if(isset($_POST['editdatabarangmasuk'])){  
    $qty = $_POST['qty'];
    $idm = $_POST['idm']; // id masuk
    $idp = $_POST['idp'];// id produk

    //cari tahu qty sekarang
    $caritahu = mysqli_query($c, "SELECT * FROM masuk WHERE idmasuk='$idm'");
    $caritahu2 = mysqli_fetch_array($caritahu);
    $qtysekarang = $caritahu2['qty'];

    //cari tahu stok sekarang
    $caristok = mysqli_query($c, "SELECT * FROM produk WHERE idproduk='$idp'");
    $caristok2 = mysqli_fetch_array($caristok);
    $stoksekarang = $caristok2['stok'];

    if($qty >= $qtysekarang){
        // Jika inputan user lebih besar dari qty yang tercatat
        $selisih = $qty - $qtysekarang;
        $newstok = $stoksekarang + $selisih;

        $query1 = mysqli_query($c, "UPDATE masuk SET qty='$qty' WHERE idmasuk='$idm'");
        $query2 = mysqli_query($c, "UPDATE produk SET stok='$newstok' WHERE idproduk='$idp'");

        if($query1 && $query2){
            header('location:masuk.php');
        } else {
            echo '
                <script>alert("Gagal");
                window.location.href="masuk.php"
                </script>';
        }
    } else {
        // Jika inputan user lebih kecil dari qty yang tercatat
        $selisih = $qtysekarang - $qty;
        $newstok = $stoksekarang - $selisih; // Perbaikan ada di sini

        $query1 = mysqli_query($c, "UPDATE masuk SET qty='$qty' WHERE idmasuk='$idm'");
        $query2 = mysqli_query($c, "UPDATE produk SET stok='$newstok' WHERE idproduk='$idp'");

        if($query1 && $query2){
            header('location:masuk.php');
        } else {
            echo '
                <script>alert("Gagal");
                window.location.href="masuk.php"
                </script>';
        }    
    }
}


if(isset($_POST['hapusdatabarangmasuk'])) {
    $idm = $_POST['idm']; // ID masuk
    $idp = $_POST['idp']; // ID produk

    // Cari tahu qty sekarang
    $caritahu = mysqli_query($c, "SELECT * FROM masuk WHERE idmasuk='$idm'");
    $caritahu2 = mysqli_fetch_array($caritahu);
    $qtysekarang = $caritahu2['qty'];

    // Cari tahu stok sekarang
    $caristok = mysqli_query($c, "SELECT * FROM produk WHERE idproduk='$idp'");
    $caristok2 = mysqli_fetch_array($caristok);
    $stoksekarang = $caristok2['stok'];
    
    // Hitung stok baru (stok dikurangi jumlah barang masuk yang dihapus)
    $newstok = $stoksekarang - $qtysekarang;

    // Hapus data barang masuk dan update stok
    $query1 = mysqli_query($c, "DELETE FROM masuk WHERE idmasuk='$idm'");
    $query2 = mysqli_query($c, "UPDATE produk SET stok='$newstok' WHERE idproduk='$idp'");

    if($query1 && $query2) {
        header('location:masuk.php');
    } else {
        echo '
            <script>alert("Gagal menghapus data");
            window.location.href="masuk.php"
            </script>';
    } 
}


//hapus order
if(isset($_POST['hapusorder'])){
    $ido = $_POST['ido'];

    $cekdata = mysqli_query($c,"select * from detailpesanan dp where idpesanan='$ido' ");

    while($ok=mysqli_fetch_array($cekdata)){
        //balikin stok
        $qty = $ok['qty'];
        $idproduk = $ok['idproduk'];
        $iddp = $ok['iddetailpesanan'];

        // Cari tahu stok sekarang
        $caristok = mysqli_query($c, "SELECT * FROM produk WHERE idproduk='$idproduk'");
        $caristok2 = mysqli_fetch_array($caristok);
        $stoksekarang = $caristok2['stok'];

        $newstok = $stoksekarang+$qty;

        $queryupdate = mysqli_query($c, "UPDATE produk SET stok='$newstok' WHERE idproduk='$idproduk'");


        //hapus data
        $querydelete = mysqli_query($c, "delete from detailpesanan where iddetailpesanan='$iddp' ");
        
    }

    $query = mysqli_query($c,"delete from pesanan where idorder='$ido'");

    if($queryupdate && $querydelete && $query){
        header('location:index.php');
    } else {
        echo '
        <script>alert("gagal");
        window.location.href="index.php"
        </script>
        ';
    }
}


// Logika Edit Detail Pesanan
if (isset($_POST['editdetailpesanan'])) {
    $iddp = $_POST['iddp']; // ID detail pesanan
    $idp = $_POST['idp'];   // ID pesanan
    $idpr = $_POST['idpr']; // ID produk
    $new_qty = $_POST['qty']; // Jumlah baru

    // Ambil jumlah lama dari database
    $get_old_qty = mysqli_query($c, "SELECT qty FROM detailpesanan WHERE iddetailpesanan = '$iddp'");
    $old_qty = mysqli_fetch_assoc($get_old_qty)['qty'];

    // Hitung selisih jumlah
    $selisih = $new_qty - $old_qty;

    // Update stok produk
    mysqli_query($c, "UPDATE produk SET stok = stok + $selisih WHERE idproduk = '$idpr'");

    // Update jumlah di detailpesanan
    mysqli_query($c, "UPDATE detailpesanan SET qty = '$new_qty' WHERE iddetailpesanan = '$iddp'");

    // Redirect untuk menyegarkan halaman
    header("Location: view.php?id=$idp");
    exit;
}

?>
