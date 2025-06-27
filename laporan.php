<?php include 'template/header.php';?>
<?php
// Fungsi format ribuan
function format_ribuan($nilai) {
    return number_format($nilai, 0, ',', '.');
}

// Filter tanggal jika ada
$filter_date = '';
$where_clause = '';
if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
    $filter_date = $_GET['tanggal'];
    $where_clause = "WHERE DATE(created_at) = '$filter_date'";
}

// Query untuk laporan
$query = mysqli_query($conn, "SELECT *, DATE(created_at) as tanggal_transaksi FROM laporanku $where_clause ORDER BY created_at DESC");

// Hitung total pendapatan
$query_total = mysqli_query($conn, "SELECT 
    SUM(subtotal) as total_pendapatan,
    SUM(quantity) as total_item,
    COUNT(DISTINCT no_transaksi) as total_transaksi
    FROM laporanku $where_clause");
$total_data = mysqli_fetch_assoc($query_total);
$total_pendapatan = $total_data['total_pendapatan'] ?? 0;
$total_item = $total_data['total_item'] ?? 0;
$total_transaksi = $total_data['total_transaksi'] ?? 0;
?>
  <div class="col-md-9 mb-2">
    <div class="row">
    
    <!-- Summary Cards -->
    <div class="col-md-12 mb-3">
        <div class="row">
            <div class="col-md-3 mb-2">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4><?php echo $total_transaksi; ?></h4>
                        <small>Total Transaksi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4><?php echo $total_item; ?></h4>
                        <small>Total Item Terjual</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h4>Rp <?php echo format_ribuan($total_pendapatan); ?></h4>
                        <small>Total Pendapatan<?php echo $filter_date ? ' - ' . date('d F Y', strtotime($filter_date)) : ''; ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-body py-2">
                <form method="GET" class="row align-items-center">
                    <div class="col-md-4">
                        <label class="small">Filter Tanggal:</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm" 
                               value="<?php echo $filter_date; ?>">
                    </div>
                    <div class="col-md-8">
                        <label class="small">&nbsp;</label><br>
                        <button type="submit" class="btn btn-purple btn-sm mr-2">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <a href="laporan.php" class="btn btn-secondary btn-sm">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                        <button type="button" class="btn btn-success btn-sm ml-2" onclick="exportToExcel()">
                            <i class="fa fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- table barang -->
    <div class="col-md-12 mb-2">
        <div class="card">
        <div class="card-header bg-purple">
                <div class="card-tittle text-white"><i class="fa fa-table"></i> <b>Data Laporan</b></div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-sm dt-responsive nowrap" id="table" width="100%">
                        <thead class="thead-purple">
                            <tr>
                            <th>No</th>
                            <th>No. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Sub-Total</th>
                            <th>Total Bayar</th>
                            <th>Kembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) {
                            while($d = mysqli_fetch_array($query)){
                                ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($d['no_transaksi']); ?></span></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($d['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($d['kode_barang']); ?></td>
                                <td><?php echo htmlspecialchars($d['nama_barang']); ?></td>
                                <td class="text-center"><?php echo $d['quantity']; ?></td>
                                <td class="text-right">Rp <?php echo format_ribuan($d['harga_barang']); ?></td>
                                <td class="text-right"><strong>Rp <?php echo format_ribuan($d['subtotal']); ?></strong></td>
                                <td class="text-right text-success"><strong>Rp <?php echo format_ribuan($d['bayar']); ?></strong></td>
                                <td class="text-right text-info">Rp <?php echo format_ribuan($d['kembalian']); ?></td>
                            </tr>
                            <?php 
                            }
                        } else {
                            echo '<tr><td colspan="10" class="text-center text-muted py-4">
                                    <i class="fa fa-inbox fa-3x mb-3"></i><br>
                                    Belum ada data laporan' . ($filter_date ? ' untuk tanggal ' . date('d F Y', strtotime($filter_date)) : '') . '
                                  </td></tr>';
                        }
                        ?>
					</tbody>
                    <tfoot>
                        <tr>
                        <th colspan="7" class="text-right"><b>TOTAL PENDAPATAN:</b></th>
                        <th class="text-right"><b>Rp <?php echo format_ribuan($total_pendapatan); ?></b></th>
                        <th></th>
                        <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- end table barang -->

    </div><!-- end row col-md-9 -->
  </div>

<script>
function exportToExcel() {
    // Simple export to Excel functionality
    var table = document.getElementById('table');
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = "laporan_kasir_<?php echo date('Y-m-d'); ?>.xls";
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

<?php include 'template/footer.php';?>
