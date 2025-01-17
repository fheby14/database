<?php

require 'ceklogin.php';

if (isset($_GET['idp'])) {
    $idp = $_GET['idp'];

    $ambilnamapelanggan = mysqli_query($c,"select * from pesanan p, pelanggan pl where p.idpelanggan=pl.idpelanggan and p.idorder='$idp'");
    $np = mysqli_fetch_array($ambilnamapelanggan);
    $namapel = $np['namapelanggan'];
} else {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Data Pesanan</title>

    <!-- Pustaka Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Stylesheet lainnya -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Aplikasi Kasir</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="far fa-comment-dots"></i></div>
                            Order
                        </a>
                        <a class="nav-link" href="stok.php">
                            <div class="sb-nav-link-icon"><i class="far fa-clipboard"></i></div>
                            Stock Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cart-plus"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="pelanggan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                            Kelola Pelanggan
                        </a>
                        <a class="nav-link" href="logout.php">
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Data Pesanan: <?= $idp; ?></h1>
                    <h3 class="mt-4">Nama Pelanggan: <?= $namapel; ?></h3>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Welcome</li>
                    </ol>

                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
                        Tambah Barang
                    </button>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Barang
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Sub-Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $get = mysqli_query($c, "
                                    SELECT 
                                        p.idproduk, 
                                        p.iddetailpesanan, -- Tambahkan iddetailpesanan
                                        pr.namaproduk, 
                                        pr.harga, 
                                        SUM(p.qty) AS qty,
                                        IFNULL(pr.deskripsi, '') AS deskripsi
                                    FROM detailpesanan p
                                    JOIN produk pr ON p.idproduk = pr.idproduk
                                    WHERE p.idpesanan = '$idp'
                                    GROUP BY p.idproduk
                                ");

                                $i = 1;
                                while ($p = mysqli_fetch_assoc($get)) {
                                    // Ambil data dari hasil query
                                    $idpr = $p['idproduk'];
                                    $iddp = $p['iddetailpesanan']; // Tambahkan iddetailpesanan
                                    $qty = $p['qty'];
                                    $harga = $p['harga'];
                                    $namaproduk = $p['namaproduk'];
                                    $deskripsi = $p['deskripsi'];
                                    $subtotal = $qty * $harga;
                                ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $namaproduk; ?> <?= (!empty($deskripsi) ? "($deskripsi)" : ""); ?></td>
                                        <td>Rp<?= number_format($harga); ?></td>
                                        <td><?= number_format($qty); ?></td>
                                        <td>Rp<?= number_format($subtotal); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?=$idpr;?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?=$idpr;?>">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="edit<?=$idpr;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ubah Data Detail Pesanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <input type="text" name="namaproduk" class="form-control" id="namaproduk" 
                                                                value="<?= $namaproduk; ?>: <?= $deskripsi; ?>" disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="number" name="qty" class="form-control" id="qty" 
                                                                value="<?= $qty; ?>" required>
                                                        </div>
                                                        <input type="hidden" name="iddp" value="<?= $iddp; ?>">
                                                        <input type="hidden" name="idp" value="<?= $idp; ?>">
                                                        <input type="hidden" name="idpr" value="<?= $idpr; ?>">
                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="editdetailpesanan">Submit</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus -->
                            <div class="modal fade" id="delete<?= $idpr; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus Barang</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus barang ini?</p>
                                                <input type="hidden" name="idp" value="<?= $iddp; ?>">
                                                <input type="hidden" name="idpr" value="<?= $idpr; ?>">
                                                <input type="hidden" name="idorder" value="<?= $idp; ?>">
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" name="Hapusprodukpesanan">Ya</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <label for="idproduk">Pilih Barang</label>
                    <select name="idproduk" class="form-control">
                    <?php
                    $getproduk = mysqli_query($c, "SELECT * FROM produk");
                    while ($pl = mysqli_fetch_assoc($getproduk)) {
                        $namaproduk = $pl['namaproduk'];
                        $stok = isset($pl['stok']) ? $pl['stok'] : 0; // Perbaikan di sini
                        $deskripsi = $pl['deskripsi'];
                        $idproduk = $pl['idproduk'];
                    ?>
                        <option value="<?= $idproduk; ?>">
                            <?= $namaproduk; ?> - <?= $deskripsi; ?> (stok: <?= $stok; ?>)
                        </option>
                    <?php } ?>

                    </select>

                    <input type="number" name="qty" class="form-control mt-3" placeholder="Jumlah" min="1" required>
                    <input type="hidden" name="idp" value="<?= $idp; ?>">
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="addproduk">Submit</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>
