<?php
require 'ceklogin.php';

//Hitung jumlah pelanggan
$h1 = mysqli_query($c,"select * from pelanggan");
$h2 = mysqli_num_rows($h1); //jumlah pelanggan
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Data Pelanggan</title>

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
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
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
                        <h1 class="mt-4">Data Pelanggan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Welcome</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Jumlah Pelanggan:<?=$h2;?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
                            Tambah Pelanggan
                        </button>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Pelanggan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                            <tr>
                                            <th>No</th>
                                            <th>Nama Pelanggan</th>
                                            <th>No Telpon</th>
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>

                                    </thead>
                                    <tbody>

                                    <?php
                                    $get = mysqli_query($c, "SELECT * FROM pelanggan");
                                    $i = 1;//penomoran

                                    while ($p = mysqli_fetch_assoc($get)) {
                                        $namapelanggan = $p['namapelanggan'];
                                        $notelp = $p['notelp'];
                                        $alamat = $p['alamat'];
                                        $idpl = $p['idpelanggan'];
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $namapelanggan; ?></td>
                                        <td><?php echo $notelp; ?></td>
                                        <td><?php echo $alamat; ?></td>
                                        <td> 
                                            
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?=$idpl;?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?=$idpl;?>">
                                                Delete
                                            </button>
                                    
                                    </td>
                                </tr>
                                        <!--Modal edit-->                 
                                <div class="modal fade" id="edit<?=$idpl;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ubah <?=$namapelanggan;?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <input type="text" name="namapelanggan" class="form-control" id="namapelanggan" placeholder="Nama Pelanggan" value="<?=$namapelanggan;?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="text" name="notelp" class="form-control" id="netelp" placeholder="No Telpon"  value="<?=$notelp;?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="text" name="alamat" class="form-control" id="alamat" placeholder="Alamat" value="<?=$alamat;?>">
                                                    </div>
                                                    <input type="hidden" name="idpl" value="<?=$idpl;?>">
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success" name="editpelanggan">Submit</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!--Modal delete-->                 
                                <div class="modal fade" id="delete<?=$idpl;?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus <?=$namapelanggan;?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    Apakah anda yakin ingin menghapus pelanggan ini?
                                                    <input type="hidden" name="idpl" value="<?=$idpl;?>">
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success" name="hapuspelanggan">Submit</button>
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
                    <h5 class="modal-title">Tambah Data Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                            <!-- Modal Body -->
            <div class="modal-body">
                
                <div class="mb-3">
                    <input type="text" name="namapelanggan" class="form-control" id="namapelanggan" placeholder="Nama Pelanggan" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="notelp" class="form-control" id="notelp" placeholder="No Telepon" required>
                </div>
                <div class="mb-3">
                    <input type="alamat" name="alamat" class="form-control" id="alamat" placeholder="Alamat" required>
                </div>                
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" name="tambahpelanggan">Submit</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
    </form>
    </div>
</div>
</div>
