<?php
include 'header.php';
include 'koneksi.php';
session_start();

// Redirect jika belum login
if (!isset($_SESSION['kd_cs'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

// Tambah produk ke keranjang
if (isset($_POST['tambah'])) {
    $kode_customer = $_SESSION['kd_cs'];
    $kode_produk   = $_POST['kode_produk'];
    $nama_produk   = $_POST['nama_produk'];
    $harga         = $_POST['harga'];
    $qty           = $_POST['qty'];

    // Cek jika sudah ada, update qty
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE kode_customer='$kode_customer' AND kode_produk='$kode_produk'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE keranjang SET qty = qty + $qty WHERE kode_customer='$kode_customer' AND kode_produk='$kode_produk'");
    } else {
        mysqli_query($conn, "INSERT INTO keranjang (kode_customer, kode_produk, nama_produk, harga, qty) VALUES ('$kode_customer', '$kode_produk', '$nama_produk', '$harga', '$qty')");
    }

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location='keranjang.php';</script>";
}

// Hapus produk dari keranjang
if (isset($_POST['hapus'])) {
    $kode_produk = $_POST['hapus_kode'];
    $kode_customer = $_SESSION['kd_cs'];

    mysqli_query($conn, "DELETE FROM keranjang WHERE kode_customer='$kode_customer' AND kode_produk='$kode_produk'");

    echo "<script>alert('Produk berhasil dihapus dari keranjang!'); window.location='keranjang.php';</script>";
}
?>

<div class="container" style="padding-bottom: 100px;">
    <h2 style="border-bottom: 4px solid #742322;"><b>Keranjang Anda</b></h2>

    <table class="table table-bordered mt-4">
        <thead class="table-dark text-center">
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php
            $kode_customer = $_SESSION['kd_cs'];
            $keranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE kode_customer='$kode_customer'");
            $grand_total = 0;
            $ada_data = false;

            while ($row = mysqli_fetch_array($keranjang)) {
                $ada_data = true;
                $subtotal = $row['qty'] * $row['harga'];
                $grand_total += $subtotal;
            ?>
            <tr>
                <td><img src="image/produk/<?= $row['kode_produk']; ?>.jpg" style="width: 80px; height: 80px; object-fit: cover;"></td>
                <td><?= $row['nama_produk']; ?></td>
                <td>Rp.<?= number_format($row['harga']); ?></td>
                <td><?= $row['qty']; ?></td>
                <td>Rp.<?= number_format($subtotal); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="hapus_kode" value="<?= $row['kode_produk']; ?>">
                        <button type="submit" name="hapus" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php } ?>

            <?php if ($ada_data) { ?>
            <tr>
                <td colspan="4" align="right"><strong>Total Belanja</strong></td>
                <td colspan="2"><strong>Rp.<?= number_format($grand_total); ?></strong></td>
            </tr>
            <?php } else { ?>
            <tr>
                <td colspan="6" class="text-center">Keranjang Anda kosong.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="text-end">
        <a href="index.php" class="btn btn-primary">Lanjut Belanja</a>
        <?php if ($ada_data) { ?>
        <a href="checkout.php" class="btn btn-success">Checkout</a>
        <?php } ?>
    </div>
</div>

<?php include 'footer.php'; ?>
