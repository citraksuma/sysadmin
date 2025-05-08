<?php
include 'header.php';
include 'koneksi.php';
session_start();
?>

<div class="container" style="padding-bottom: 100px;">
  <h2 style="border-bottom: 4px solid #742322;"><b>Produk Kami</b></h2>
  <div class="row">

    <?php
    // Menampilkan semua produk dari database
    $produk = mysqli_query($conn, "SELECT * FROM produk");
    while ($row = mysqli_fetch_array($produk)) {
    ?>

    <div class="col-md-4">
      <div class="card mb-4">
        <img src="image/produk/<?= $row['image']; ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="<?= $row['nama_produk']; ?>">
        <div class="card-body text-center">
          <h5 class="card-title"><?= $row['nama_produk']; ?></h5>
          <p class="card-text">Rp.<?= number_format($row['harga']); ?></p>
          <form method="POST" action="">
            <input type="hidden" name="kode_produk" value="<?= $row['kode_produk']; ?>">
            <input type="hidden" name="nama_produk" value="<?= $row['nama_produk']; ?>">
            <input type="hidden" name="harga" value="<?= $row['harga']; ?>">
            <input type="number" name="qty" value="1" min="1" class="form-control mb-2" style="width: 60px; display: inline;">
            <a href="#" class="btn btn-warning">Detail</a>
            <button type="submit" name="tambah" class="btn btn-success"><i class="fa fa-shopping-cart"></i> Tambah</button>
          </form>
        </div>
      </div>
    </div>

    <?php } ?>

  </div>
</div>

<?php
// Proses tambah ke keranjang langsung di file ini
if (isset($_POST['tambah'])) {
    if (!isset($_SESSION['kd_cs'])) {
        echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
        exit;
    }

    $kode_customer = $_SESSION['kd_cs'];
    $kode_produk   = $_POST['kode_produk'];
    $nama_produk   = $_POST['nama_produk'];
    $harga         = $_POST['harga'];
    $qty           = $_POST['qty'];

    // Cek apakah produk sudah ada di keranjang
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE kode_customer='$kode_customer' AND kode_produk='$kode_produk'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE keranjang SET qty = qty + $qty WHERE kode_customer='$kode_customer' AND kode_produk='$kode_produk'");
    } else {
        mysqli_query($conn, "INSERT INTO keranjang (kode_customer, kode_produk, nama_produk, harga, qty) VALUES ('$kode_customer', '$kode_produk', '$nama_produk', '$harga', '$qty')");
    }

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location='index.php';</script>";
}
?>

<?php include 'footer.php'; ?>
