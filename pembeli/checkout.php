<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user']['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit;
}

$pembeli_id = intval($_SESSION['user_id']);
$total = 0;
$items = [];

$query = mysqli_query($conn, "
    SELECT k.produk_id, k.qty, p.nama_produk, p.harga
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    WHERE k.pembeli_id = $pembeli_id
");

while ($row = mysqli_fetch_assoc($query)) {
    $items[] = $row;
    $total += $row['harga'] * $row['qty'];
}

if (count($items) === 0) {
    header("Location: keranjang.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
<div class="max-w-7xl mx-auto py-10 px-6">


<div class="bg-white rounded-3xl shadow-lg p-10 grid grid-cols-1 lg:grid-cols-[1.4fr_1fr] gap-12">



<!-- ================= LEFT ================= -->
<div class="bg-gray-50 rounded-2xl p-6 flex flex-col justify-between">




    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
        🧾 Ringkasan Pesanan
    </h2>

    <div class="space-y-3 max-h-[360px] overflow-y-auto pr-2">


        <?php foreach ($items as $item) { ?>

            <div class="bg-white rounded-xl p-4 shadow-sm flex justify-between items-center">
                <div class="max-w-[65%]">
                    <p class="font-semibold truncate">
                        <?= htmlspecialchars($item['nama_produk']) ?>
                    </p>
                    <p class="text-xs text-gray-500">
                        Qty: <?= $item['qty'] ?>
                    </p>
                </div>

                <p class="font-semibold text-sm text-teal-600">
                    Rp <?= number_format($item['harga'] * $item['qty']) ?>
                </p>
            </div>
        <?php } ?>
    </div>

    <div class="border-t pt-4 mt-4 flex justify-between text-lg font-extrabold text-teal-700">
        <span>Total</span>
        <span>Rp <?= number_format($total) ?></span>
    </div>

</div>

<!-- ================= RIGHT ================= -->
<!-- ================= RIGHT ================= -->
<div class="bg-white rounded-2xl p-6 flex flex-col justify-between">

    <div>
        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
            💳 Informasi Pembayaran
        </h2>

        <form action="checkout_proses.php" method="POST" enctype="multipart/form-data" class="space-y-4">

            <!-- BANK -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Bank Tujuan
                </label>

                <input type="text"
                       name="bank"
                       id="bank"
                       list="bankList"
                       required
                       placeholder="Pilih atau ketik bank"
                       class="w-full border rounded-xl px-4 py-3 text-sm
                              focus:ring-2 focus:ring-teal-400 outline-none">

                <datalist id="bankList">
                    <option value="BNI">
                    <option value="BRI">
                    <option value="Mandiri">
                    <option value="BCA">
                    <option value="BTN">
                    <option value="BSI">
                    <option value="CIMB Niaga">
                    <option value="Danamon">
                    <option value="Bukopin">
                    <option value="OCBC NISP">
                    <option value="Sinarmas Syariah">
                </datalist>

                <p id="bank-info" class="text-[11px] text-gray-500 mt-1"></p>
            </div>

            <!-- REKENING PENJUAL -->
<div>
    <label class="block text-xs font-semibold text-gray-600 mb-1">
        Nomor Rekening Penjual
    </label>

    <input type="text"
           name="no_rekening_penjual"
           id="no_rekening_penjual"
           readonly
           class="w-full border rounded-xl px-4 py-3 text-sm bg-gray-100
                  focus:ring-0 outline-none">

    <p id="penjual-info" class="text-[11px] text-gray-500 mt-1"></p>
</div>


            <!-- REKENING -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Nomor Rekening
                </label>

                <input type="text"
                       name="no_rekening"
                       id="no_rekening"
                       required
                       placeholder="Masukkan nomor rekening"
                       class="w-full border rounded-xl px-4 py-3 text-sm
                              focus:ring-2 focus:ring-teal-400 outline-none">

                <p id="rekening-info" class="text-[11px] text-gray-500 mt-1"></p>
            </div>

            <!-- TELEPON -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Nomor Telepon
                </label>

                <input type="text"
                       name="no_telepon"
                       id="no_telepon"
                       required
                       maxlength="13"
                       placeholder="08xxxxxxxxxx"
                       class="w-full border rounded-xl px-4 py-3 text-sm
                              focus:ring-2 focus:ring-teal-400 outline-none">

                <p id="telepon-info" class="text-[11px] text-gray-500 mt-1"></p>
            </div>

            <!-- BUKTI -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Bukti Transfer <span class="text-gray-400">(Opsional)</span>
                </label>

                <input type="file"
                       name="bukti"
                       accept="image/*"
                       class="w-full border rounded-xl px-4 py-3 text-sm bg-gray-50">
            </div>

            <!-- INFO -->
            <div class="bg-teal-50 border border-teal-100 rounded-xl p-3 text-xs text-teal-700 leading-relaxed">
                • Jika bukti belum diupload, status <b>Pending</b><br>
                • Admin akan memverifikasi pembayaran
            </div>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full bg-teal-500 hover:bg-teal-600 transition
                           text-white py-4 rounded-full font-bold tracking-wide
                           shadow-lg active:scale-[0.98]">
                Konfirmasi Pesanan
            </button>

        </form>
    </div>

</div>

</div>
</body>
</html>
<script>
const bankInput = document.getElementById('bank');
const rekeningInput = document.getElementById('no_rekening');
const infoText = document.getElementById('rekening-info');
const bankInfo = document.getElementById('bank-info');

// mapping bank → jumlah digit
const bankDigit = {
    'bni': 10,
    'bri': 15,
    'mandiri': 13,
    'bca': 10,
    'btn': 16,
    'bsi': 10,
    'cimb niaga': 13,
    'danamon': 10,
    'bukopin': 10,
    'ocbc nisp': 12,
    'sinarmas syariah': 10
};

bankInput.addEventListener('input', function () {
    const key = this.value.toLowerCase().trim();

    if (bankDigit[key]) {
        const digit = bankDigit[key];

        // rekening PEMBELI
        rekeningInput.value = '';
        rekeningInput.maxLength = digit;
        rekeningInput.placeholder = `Harus ${digit} digit`;
        infoText.textContent = `Nomor rekening harus ${digit} digit`;

        // 🔥 REKENING PENJUAL (INI YANG KURANG)
        rekeningPenjualInput.value = rekeningPenjual[key] ?? '';
        penjualInfo.textContent = `Rekening resmi penjual (${this.value})`;

        bankInfo.textContent = `Bank terpilih: ${this.value}`;
    } else {
        rekeningInput.maxLength = '';
        rekeningInput.value = '';
        infoText.textContent = '';

        rekeningPenjualInput.value = '';
        penjualInfo.textContent = '';

        bankInfo.textContent = 'Bank tidak dikenali';
    }
});

// hanya angka
rekeningInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
});
const telpInput = document.getElementById('no_telepon');
const telpInfo = document.getElementById('telepon-info');

telpInput.addEventListener('input', function () {
    // hanya angka
    this.value = this.value.replace(/[^0-9]/g, '');

    // potong jika lebih dari 13 digit
    if (this.value.length > 13) {
        this.value = this.value.slice(0, 13);
    }

    // validasi info
    if (this.value.length < 10) {
        telpInfo.textContent = 'Nomor telepon minimal 10 digit';
    } else {
        telpInfo.textContent = '';
    }
});
// rekening penjual per bank
const rekeningPenjual = {
    'bni': '1234567890',
    'bri': '123456789012345',
    'mandiri': '1234567890123',
    'bca': '1234567890',
    'btn': '1234567890123456',
    'bsi': '1234567890',
    'cimb niaga': '1234567890123',
    'danamon': '1234567890',
    'bukopin': '1234567890',
    'ocbc nisp': '123456789012',
    'sinarmas syariah': '1234567890'
};

const rekeningPenjualInput = document.getElementById('no_rekening_penjual');
const penjualInfo = document.getElementById('penjual-info');
</script>
