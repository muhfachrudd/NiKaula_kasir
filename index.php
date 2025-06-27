<?php include 'template/header.php'; ?>
<?php 
// Ambil data barang dari database
$barang = mysqli_query($conn, "SELECT * FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
$jsArray = "var harga_barang = new Array();\n";
$jsArray1 = "var nama_barang = new Array();\n";
$barang_count = 0;

// Cek apakah ada barang di database
if (!$barang) {
    die("Error query barang: " . mysqli_error($conn));
}
?>
<div class="col-md-9 mb-2">
    <div class="row">

        <!-- kasir -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body py-4">
                    <form method="POST">
                        <div class="form-group row mb-0">
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Tgl. Transaksi</b></label>
                            <div class="col-sm-8 mb-2">
                                <input type="text" class="form-control form-control-sm" name="tgl_input" value="<?php echo  date("j F Y"); ?>" readonly>
                            </div>
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Kode Menu</b></label>
                            <div class="col-sm-8 mb-2">
                                <div class="input-group">
                                    <input type="text" name="kode_barang" class="form-control form-control-sm border-right-0" 
                                           list="datalist1" onchange="changeValue(this.value)" 
                                           oninput="searchMenu(this.value)" aria-describedby="basic-addon2" 
                                           placeholder="Ketik atau pilih kode menu..." required>
                                    <datalist id="datalist1">
                                        <?php 
                                        if (mysqli_num_rows($barang) > 0) { 
                                            // Reset pointer untuk membaca ulang data
                                            mysqli_data_seek($barang, 0);
                                            while ($row_brg = mysqli_fetch_array($barang)) { 
                                                $barang_count++;
                                                ?>
                                                <option value="<?php echo htmlspecialchars($row_brg["id_barang"]) ?>"> 
                                                    <?php echo htmlspecialchars($row_brg["id_barang"]) ?> - <?php echo htmlspecialchars($row_brg["nama_barang"]) ?> - Rp <?php echo number_format($row_brg["harga_barang"], 0, ',', '.') ?>
                                                </option>
                                                <?php 
                                                // Generate JavaScript arrays untuk autocomplete
                                                $jsArray .= "harga_barang['" . addslashes($row_brg['id_barang']) . "'] = {harga_barang:" . $row_brg['harga_barang'] . "};\n";
                                                $jsArray1 .= "nama_barang['" . addslashes($row_brg['id_barang']) . "'] = {nama_barang:'" . addslashes($row_brg['nama_barang']) . "'};\n";
                                            } 
                                        } else {
                                            echo '<option value="">Tidak ada menu tersedia</option>';
                                        }
                                        ?>
                                        ?>
                                    </datalist>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-left-0" id="basic-addon2" style="border-radius:0px 15px 15px 0px;">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Nama Menu</b></label>
                            <div class="col-sm-8 mb-2">
                                <input type="text" class="form-control form-control-sm" name="nama_barang" id="nama_barang" readonly>
                            </div>
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Harga (Rp)</b></label>
                            <div class="col-sm-8 mb-2">
                                <input type="number" class="form-control form-control-sm" id="harga_barang" onchange="total()"
                                    name="harga_barang" readonly>
                            </div>
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Quantity</b></label>
                            <div class="col-sm-8 mb-2">
                                <input type="number" class="form-control form-control-sm" id="quantity" onchange="total()"
                                    name="quantity" placeholder="0" required>
                            </div>
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Sub-Total</b></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="subtotal" name="subtotal" onchange="total()"
                                        name="sub_total" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-purple btn-sm" name="save" value="simpan" type="submit">
                                            <i class="fa fa-plus mr-2"></i>Tambah</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['save'])) {
                        $idb = trim($_POST['kode_barang']);
                        $nama = trim($_POST['nama_barang']);
                        $harga = floatval($_POST['harga_barang']);
                        $qty = intval($_POST['quantity']);
                        $subtotal = $harga * $qty;
                        $tgl = $_POST['tgl_input'];

                        // Validasi data sebelum insert
                        if (empty($idb) || empty($nama) || $harga <= 0 || $qty <= 0) {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Mohon lengkapi semua data barang dengan benar!
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                  </div>';
                        } else {
                            // Cek apakah barang sudah ada di keranjang
                            $check_exist = mysqli_query($conn, "SELECT * FROM keranjang WHERE kode_barang = '$idb'");
                            
                            if (mysqli_num_rows($check_exist) > 0) {
                                // Update quantity jika barang sudah ada
                                $existing = mysqli_fetch_assoc($check_exist);
                                $new_qty = $existing['quantity'] + $qty;
                                $new_subtotal = $harga * $new_qty;
                                
                                $sql = "UPDATE keranjang SET quantity = $new_qty, subtotal = $new_subtotal WHERE kode_barang = '$idb'";
                            } else {
                                // Insert barang baru
                                $sql = "INSERT INTO keranjang (kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input)
                                        VALUES('$idb','$nama','$harga','$qty','$subtotal','$tgl')";
                            }
                            
                            $query = mysqli_query($conn, $sql);

                            if ($query) {
                                echo '<script>
                                        alert("Item berhasil ditambahkan ke keranjang!");
                                        window.location="index.php";
                                      </script>';
                            } else {
                                echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                            }
                        }
                    } ?>
                    <hr>
                    <?php
                    // Fungsi format ribuan
                    function format_ribuan($nilai) {
                        return number_format($nilai, 0, ',', '.');
                    }
                    
                    // Generate kode transaksi unik
                    $tgl = date("dmY");
                    $time = date("His");
                    $huruf = "NK"; // NiKaula
                    $kodeCart = $huruf . $tgl . $time;
                    
                    // Hitung total dari keranjang
                    $query_total = mysqli_query($conn, "SELECT SUM(subtotal) as total_belanja FROM keranjang");
                    $row_total = mysqli_fetch_assoc($query_total);
                    $tot_bayar = $row_total['total_belanja'] ?? 0;
                    
                    // Ambil data pembayaran dari keranjang (jika ada)
                    $query_bayar = mysqli_query($conn, "SELECT bayar, kembalian FROM keranjang LIMIT 1");
                    $data_bayar = mysqli_fetch_assoc($query_bayar);
                    $bayar = $data_bayar['bayar'] ?? 0;
                    $kembalian = $data_bayar['kembalian'] ?? 0;
                    ?>
                    <form method="POST">
                        <div class="form-group row mb-0">
                            <input type="hidden" class="form-control" name="no_transaksi" value="<?php echo $kodeCart; ?>" readonly>
                            <input type="hidden" class="form-control" value="<?php echo $tot_bayar; ?>" id="hargatotal" readonly>
                            
                            <div class="col-12 mb-3">
                                <div class="alert alert-info">
                                    <strong>Total Belanja: Rp <?php echo format_ribuan($tot_bayar); ?></strong>
                                </div>
                            </div>
                            
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Bayar (Rp)</b></label>
                            <div class="col-sm-8 mb-2">
                                <input type="number" class="form-control form-control-sm" name="bayar" id="bayarnya" 
                                       onchange="totalnya()" oninput="totalnya()" 
                                       placeholder="Masukkan jumlah bayar..." 
                                       value="<?php echo $bayar > 0 ? $bayar : ''; ?>" required>
                            </div>
                            <label class="col-sm-4 col-form-label col-form-label-sm"><b>Kembali (Rp)</b></label>
                            <div class="col-sm-8 mb-2">
                                <input type="number" class="form-control form-control-sm" name="kembalian" 
                                       id="total1" value="<?php echo $kembalian; ?>" readonly>
                            </div>
                        </div>
                        <div class="text-right">
                            <?php if ($tot_bayar > 0): ?>
                                <button class="btn btn-purple btn-sm" name="save1" value="simpan" type="submit">
                                    <i class="fa fa-shopping-cart mr-2"></i>Proses Bayar
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fa fa-shopping-cart mr-2"></i>Keranjang Kosong
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['save1'])) {
                        $notrans = $_POST['no_transaksi'];
                        $bayar = floatval($_POST['bayar']);
                        $kembalian = floatval($_POST['kembalian']);
                        
                        // Validasi pembayaran
                        if ($bayar < $tot_bayar) {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Jumlah bayar tidak mencukupi! 
                                    <br>Total: Rp ' . format_ribuan($tot_bayar) . ' | Bayar: Rp ' . format_ribuan($bayar) . '
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                  </div>';
                        } else {
                            // Update data pembayaran di keranjang
                            $sql = "UPDATE keranjang SET no_transaksi='$notrans', bayar='$bayar', kembalian='$kembalian'";
                            $query = mysqli_query($conn, $sql);
                            
                            if ($query) {
                                echo '<script>
                                        alert("Pembayaran berhasil diproses!");
                                        window.location="index.php";
                                      </script>';
                            } else {
                                echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                            }
                        }
                    } ?>
                </div>
            </div>
        </div>
        <!-- end kasir -->

        <!-- tes -->
        <div class="col-md-6 mb-3">
            <div class="card" id="print">
                <div class="card-header bg-white border-0 pb-0 pt-4">
                    <?php
                    // Cek apakah tabel login memiliki kolom nama_toko
                    $check_columns = mysqli_query($conn, "SHOW COLUMNS FROM login LIKE 'nama_toko'");
                    
                    if (mysqli_num_rows($check_columns) > 0) {
                        // Jika kolom nama_toko ada, gunakan query lengkap
                        $toko = mysqli_query($conn, "SELECT * FROM login WHERE nama_toko IS NOT NULL LIMIT 1");
                        if ($toko && mysqli_num_rows($toko) > 0) {
                            $dat = mysqli_fetch_array($toko);
                            $user = $dat['user'];
                            $nama_toko = $dat['nama_toko'];
                            $alamat = $dat['alamat'];
                            $telp = $dat['telp'];
                        } else {
                            // Fallback ke data default
                            $user = "Admin";
                            $nama_toko = "NiKaula Coffee Shop";
                            $alamat = "Jl. Coffee Street No. 123, Jakarta";
                            $telp = "021-1234567";
                        }
                    } else {
                        // Jika kolom nama_toko tidak ada, gunakan data default dan ambil user dari login
                        $toko = mysqli_query($conn, "SELECT user FROM login LIMIT 1");
                        if ($toko && mysqli_num_rows($toko) > 0) {
                            $dat = mysqli_fetch_array($toko);
                            $user = $dat['user'];
                        } else {
                            $user = "Admin";
                        }
                        $nama_toko = "NiKaula Coffee Shop";
                        $alamat = "Jl. Coffee Street No. 123, Jakarta";
                        $telp = "021-1234567";
                    }
                    
                    echo "<h5 class='card-title mb-0 text-center'><b>" . htmlspecialchars($nama_toko) . "</b></h5>
                          <p class='m-0 small text-center'>" . htmlspecialchars($alamat) . "</p>
                          <p class='small text-center'>Telp. " . htmlspecialchars($telp) . "</p>";
                    ?>
                    <div class="row">
                        <div class="col-8 col-sm-9 pr-0">
                            <ul class="pl-0 small" style="list-style: none;text-transform: uppercase;">
                                <li>NOTA : <?php
                                            // Ambil nomor transaksi dari keranjang
                                            $notrans = mysqli_query($conn, "SELECT no_transaksi FROM keranjang WHERE no_transaksi IS NOT NULL AND no_transaksi != '' LIMIT 1");
                                            if ($notrans && mysqli_num_rows($notrans) > 0) {
                                                $dat2 = mysqli_fetch_array($notrans);
                                                echo htmlspecialchars($dat2['no_transaksi']);
                                            } else {
                                                echo htmlspecialchars($kodeCart);
                                            }
                                            ?></li>
                                <li>KASIR : <?php echo htmlspecialchars($user); ?></li>
                            </ul>
                        </div>
                        <div class="col-4 col-sm-3 pl-0">
                            <ul class="pl-0 small" style="list-style: none;">
                                <li>TGL : <?php echo date("d-m-Y"); ?></li>
                                <li>JAM : <?php echo date("H:i:s"); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body small pt-0">
                    <hr class="mt-0">
                    <div class="row">
                        <div class="col-5 pr-0">
                            <span><b>Menu</b></span>
                        </div>
                        <div class="col-1 px-0 text-center">
                            <span><b>Qty</b></span>
                        </div>
                        <div class="col-3 px-0 text-right">
                            <span><b>Harga</b></span>
                        </div>
                        <div class="col-3 pl-0 text-right">
                            <span><b>Subtotal</b></span>
                        </div>
                        <div class="col-12">
                            <hr class="mt-2">
                        </div>
                        <?php
                        $data_barang = mysqli_query($conn, "SELECT * FROM keranjang ORDER BY id_cart ASC");
                        $item_count = 0;
                        if ($data_barang && mysqli_num_rows($data_barang) > 0) {
                            while ($d = mysqli_fetch_array($data_barang)) {
                                $item_count++;
                        ?>
                            <div class="col-5 pr-0">
                                <a href="?hapus=<?php echo $d['id_cart']; ?>" 
                                   onclick="return confirm('Hapus <?php echo htmlspecialchars($d['nama_barang']); ?> dari keranjang?');" 
                                   style="text-decoration:none;" title="Hapus item">
                                    <i class="fa fa-times fa-xs text-danger mr-1"></i>
                                    <span class="text-dark" style="font-size: 12px;"><?php echo htmlspecialchars($d['nama_barang']); ?></span>
                                </a>
                            </div>
                            <div class="col-1 px-0 text-center">
                                <span style="font-size: 12px;"><?php echo $d['quantity']; ?></span>
                            </div>
                            <div class="col-3 px-0 text-right">
                                <span style="font-size: 12px;"><?php echo format_ribuan($d['harga_barang']); ?></span>
                            </div>
                            <div class="col-3 pl-0 text-right">
                                <span style="font-size: 12px;"><strong><?php echo format_ribuan($d['subtotal']); ?></strong></span>
                            </div>
                        <?php 
                            }
                        } else { ?>
                            <div class="col-12 text-center py-3">
                                <span class="text-muted">Keranjang masih kosong</span>
                            </div>
                        <?php } ?>
                        <div class="col-12">
                            <hr class="mt-2">
                            <ul class="list-group border-0">
                                <li class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center">
                                    <b>Total</b>
                                    <span><b><?php echo format_ribuan($tot_bayar); ?></b></span>
                                </li>
                                <li class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center">
                                    <b>Bayar</b>
                                    <span><b><?php echo format_ribuan($bayar); ?></b></span>
                                </li>
                                <li class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center">
                                    <b>Kembalian</b>
                                    <span><b><?php echo format_ribuan($kembalian); ?></b></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-12 mt-3 text-center">
                            <p> TERIMA KASIH SUDAH BERKUNJUNG </p>
                            <p class="small">Semoga hari Anda menyenangkan!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mt-3">
                <form method="POST">
                    <?php if ($item_count > 0 && $bayar > 0): ?>
                        <button type="button" class="btn btn-purple-light btn-sm mr-2" onclick="printContent('print')">
                            <i class="fa fa-print mr-1"></i> Print Nota
                        </button>
                        <button class="btn btn-purple btn-sm" name="selesai" type="submit" 
                                onclick="return confirm('Selesaikan transaksi ini? Keranjang akan dikosongkan.')">
                            <i class="fa fa-check mr-1"></i> Selesai Transaksi
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-sm mr-2" disabled>
                            <i class="fa fa-print mr-1"></i> Print Nota
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" disabled>
                            <i class="fa fa-check mr-1"></i> Selesai Transaksi
                        </button>
                        <small class="text-muted d-block mt-2">
                            <?php if ($item_count == 0): ?>
                                Tambahkan item dan lakukan pembayaran terlebih dahulu
                            <?php else: ?>
                                Lakukan pembayaran terlebih dahulu
                            <?php endif; ?>
                        </small>
                    <?php endif; ?>
                </form>
            </div>
            <?php
            if (isset($_POST['selesai'])) {
                // Cek apakah ada item di keranjang dan sudah dibayar
                $check_cart = mysqli_query($conn, "SELECT COUNT(*) as total FROM keranjang WHERE bayar > 0");
                $cart_data = mysqli_fetch_assoc($check_cart);
                
                if ($cart_data['total'] > 0) {
                    // Pindahkan data ke laporan
                    $ambildata = mysqli_query($conn, "INSERT INTO laporanku (no_transaksi, bayar, kembalian, id_Cart, kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input)
                                                      SELECT no_transaksi, bayar, kembalian, id_cart, kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input
                                                      FROM keranjang WHERE bayar > 0") or die(mysqli_error($conn));
                    
                    if ($ambildata) {
                        // Hapus data dari keranjang setelah berhasil dipindahkan
                        $hapusdata = mysqli_query($conn, "DELETE FROM keranjang");
                        
                        if ($hapusdata) {
                            echo '<script>
                                    alert("Transaksi berhasil diselesaikan!");
                                    window.location="index.php";
                                  </script>';
                        } else {
                            echo '<div class="alert alert-danger">Error menghapus keranjang: ' . mysqli_error($conn) . '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger">Error menyimpan laporan: ' . mysqli_error($conn) . '</div>';
                    }
                } else {
                    echo '<script>alert("Tidak ada transaksi yang dapat diselesaikan!");</script>';
                }
            }
            
            // Handle hapus item dari keranjang
            if (isset($_GET['hapus']) && !empty($_GET['hapus'])) {
                $id = intval($_GET['hapus']);
                $hapus_data = mysqli_query($conn, "DELETE FROM keranjang WHERE id_cart = $id");
                
                if ($hapus_data) {
                    echo '<script>
                            alert("Item berhasil dihapus dari keranjang!");
                            window.location="index.php";
                          </script>';
                } else {
                    echo '<script>alert("Error menghapus item: ' . mysqli_error($conn) . '");</script>';
                }
            }
            ?>
        </div>
        <!-- end tes -->
    </div><!-- end row col-md-9 -->
</div>
<script type="text/javascript">
    // Debug: Show total menu loaded
    console.log('Loading menu data...');
    
    <?php echo $jsArray; ?>
    <?php echo $jsArray1; ?>
    
    console.log('Total menu loaded: <?php echo $barang_count; ?>');
    console.log('Harga barang array:', typeof harga_barang !== 'undefined' ? Object.keys(harga_barang).length : 0);
    console.log('Nama barang array:', typeof nama_barang !== 'undefined' ? Object.keys(nama_barang).length : 0);

    function searchMenu(searchTerm) {
        // Fungsi untuk membantu pencarian real-time
        if (searchTerm.length >= 2) {
            console.log('Searching for:', searchTerm);
        }
    }

    function changeValue(id_barang) {
        console.log('Selected barang:', id_barang);
        
        // Reset fields first
        document.getElementById("nama_barang").value = '';
        document.getElementById("harga_barang").value = '';
        document.getElementById("subtotal").value = '';
        
        if (id_barang && nama_barang[id_barang] && harga_barang[id_barang]) {
            document.getElementById("nama_barang").value = nama_barang[id_barang].nama_barang;
            document.getElementById("harga_barang").value = harga_barang[id_barang].harga_barang;
            console.log('Data loaded:', nama_barang[id_barang].nama_barang, harga_barang[id_barang].harga_barang);
            
            // Auto calculate if quantity is already filled
            var qty = document.getElementById('quantity').value;
            if (qty > 0) {
                total();
            }
            
            // Focus on quantity field for faster input
            document.getElementById('quantity').focus();
        } else if (id_barang) {
            console.log('Data not found for:', id_barang);
            alert('Data barang "' + id_barang + '" tidak ditemukan! Silakan pilih dari daftar yang tersedia.');
            // Clear the input if invalid
            document.querySelector('input[name="kode_barang"]').value = '';
        }
    }

    function total() {
        var harga = parseFloat(document.getElementById('harga_barang').value) || 0;
        var jumlah_beli = parseInt(document.getElementById('quantity').value) || 0;
        
        if (harga > 0 && jumlah_beli > 0) {
            var jumlah_harga = harga * jumlah_beli;
            document.getElementById('subtotal').value = jumlah_harga;
            console.log('Subtotal calculated:', jumlah_harga);
        } else {
            document.getElementById('subtotal').value = '';
        }
    }

    function totalnya() {
        var harga = parseFloat(document.getElementById('hargatotal').value) || 0;
        var pembayaran = parseFloat(document.getElementById('bayarnya').value) || 0;
        var kembali = pembayaran - harga;
        
        if (pembayaran === 0) {
            document.getElementById('total1').value = '';
        } else if (kembali < 0) {
            document.getElementById('total1').value = 0;
            // Show warning for insufficient payment
            if (pembayaran > 0) {
                console.log('Insufficient payment. Required:', harga, 'Paid:', pembayaran);
            }
        } else {
            document.getElementById('total1').value = kembali;
        }
        console.log('Payment calculation - Total:', harga, 'Paid:', pembayaran, 'Change:', kembali);
    }

    function printContent(print) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(print).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
        // Reload to restore functionality
        window.location.reload();
    }

    // Add event listeners when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('JavaScript loaded successfully');
        console.log('Available items:', typeof nama_barang !== 'undefined' ? Object.keys(nama_barang).length : 0);
        
        // Auto-focus on kode_barang input
        var kodeBarangInput = document.querySelector('input[name="kode_barang"]');
        if (kodeBarangInput) {
            kodeBarangInput.focus();
        }
        
        // Add Enter key support for quick navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                var activeElement = document.activeElement;
                if (activeElement.name === 'kode_barang') {
                    e.preventDefault();
                    document.getElementById('quantity').focus();
                } else if (activeElement.name === 'quantity') {
                    e.preventDefault();
                    // Auto-click add button if subtotal is calculated
                    var subtotal = document.getElementById('subtotal').value;
                    if (subtotal && parseFloat(subtotal) > 0) {
                        document.querySelector('button[name="save"]').click();
                    }
                }
            }
        });
        
        // Calculate total on page load if payment fields have values
        totalnya();
    });
</script>
<?php 
// Close database connection at the end
if (isset($conn)) {
    // mysqli_close($conn); // Moved to end of file
}
?>
<?php 
// Close database connection properly at the end
if (isset($conn) && $conn instanceof mysqli) {
    mysqli_close($conn);
}
include 'template/footer.php';?>