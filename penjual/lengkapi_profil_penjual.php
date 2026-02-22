<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php");
    exit;
}

$uid = $_SESSION['user_id'];
/* ================= AMBIL DATA PENJUAL ================= */
$profilPenjual = [
    'bank' => '',
    'no_rekening' => '',
    'qris' => null
];

$q = mysqli_query($conn, "
    SELECT bank, no_rekening, qris
    FROM penjual_profile
    WHERE user_id = $uid
    LIMIT 1
");

if ($q && mysqli_num_rows($q) > 0) {
    $profilPenjual = mysqli_fetch_assoc($q);
}

// PASTIKAN PROFIL ADA
$cek = mysqli_query($conn, "
    SELECT id FROM penjual_profile WHERE user_id = $uid LIMIT 1
");

if (mysqli_num_rows($cek) == 0) {
  mysqli_query($conn, "
   INSERT INTO penjual_profile (user_id, bank, no_rekening, qris)
VALUES ($uid, '', '', NULL)
");
}

/* ================= PROSES SIMPAN ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bank   = mysqli_real_escape_string($conn, $_POST['bank']);
    $no_rek = mysqli_real_escape_string($conn, $_POST['no_rekening']);

    // CEK UPLOAD QRIS
    if (!empty($_FILES['qris']['name'])) {

        $nama = time() . '_' . $_FILES['qris']['name'];
        move_uploaded_file(
            $_FILES['qris']['tmp_name'],
            "../uploads/qris/$nama"
        );

        $qris = "uploads/qris/$nama";

        // UPDATE DENGAN QRIS
        mysqli_query($conn, "
            UPDATE penjual_profile
            SET bank='$bank',
                no_rekening='$no_rek',
                qris='$qris'
            WHERE user_id=$uid
        ");

    } else {

        // UPDATE TANPA SENTUH QRIS
        mysqli_query($conn, "
            UPDATE penjual_profile
            SET bank='$bank',
                no_rekening='$no_rek'
            WHERE user_id=$uid
        ");
    }

    header("Location: ../profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lengkapi Profil Penjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen flex items-center justify-center px-4">

<div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-teal-600 to-emerald-500 px-8 py-6 text-white">
        <h2 class="text-2xl font-bold">Lengkapi Profil Penjual</h2>
        <p class="text-teal-100 text-sm mt-1">
            Data ini akan digunakan untuk proses pembayaran
        </p>
    </div>

    <!-- FORM -->
    <form method="post" enctype="multipart/form-data" class="p-8 space-y-6">

        <!-- BANK -->
<div>
    <label class="block text-sm font-semibold text-gray-600 mb-1">
        Nama Bank
    </label>

 <input
    id="bank"
    name="bank"
    list="bank_list"
    required
    value="<?= htmlspecialchars($profilPenjual['bank']) ?>"
    placeholder="Pilih atau ketik nama bank"
    class="w-full rounded-xl border px-4 py-2
           focus:ring-2 focus:ring-teal-500 focus:outline-none">


<datalist id="bank_list">
    <option value="BCA">
    <option value="BRI">
    <option value="BNI">
    <option value="Mandiri">
    <option value="CIMB Niaga">
    <option value="Danamon">
    <option value="Permata">
    <option value="BTN">
    <option value="BSI">
</datalist>


    <p class="text-xs text-gray-400 mt-1">
        Bisa pilih dari daftar atau ketik manual
    </p>
</div>



        <!-- NO REKENING -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Nomor Rekening
            </label>
           <input type="text"
       name="no_rekening"
       id="no_rekening"
       required
       value="<?= htmlspecialchars($profilPenjual['no_rekening']) ?>"
       placeholder="Masukkan nomor rekening"
       class="w-full rounded-xl border px-4 py-2 font-mono
              focus:ring-2 focus:ring-teal-500 focus:outline-none">

        </div>

   <!-- QRIS -->
<div>
    <label class="block text-sm font-semibold text-gray-600 mb-1">
        QRIS (Opsional)
    </label>

    <input type="file" name="qris" accept="image/*"
           class="w-full text-sm file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:bg-teal-50 file:text-teal-600
                  hover:file:bg-teal-100 cursor-pointer">

    <p class="text-xs text-gray-400 mt-1">
        Unggah gambar QRIS jika tersedia
    </p>

    <!-- 🔽 TARUH DI SINI -->
    <?php if (!empty($profilPenjual['qris'])): ?>
        <div class="mt-3">
            <p class="text-xs text-gray-500 mb-1">QRIS Saat Ini</p>
            <img src="../<?= htmlspecialchars($profilPenjual['qris']) ?>"
                 class="w-28 rounded-lg border shadow">
        </div>
    <?php endif; ?>

</div>


        <!-- ACTION -->
        <div class="flex items-center justify-between pt-6">
            <a href="../profile.php"
               class="text-gray-500 hover:text-teal-600 font-medium">
                ← Kembali
            </a>

            <button type="submit"
                    class="bg-teal-600 text-white px-8 py-2 rounded-full
                           font-semibold shadow-lg
                           hover:bg-teal-700 transition">
                Simpan Data
            </button>
        </div>

    </form>

</div>
<script>
const bankInput = document.getElementById('bank');
const rekInput  = document.getElementById('no_rekening');
const rekError  = document.getElementById('rekError');
const submitBtn = document.querySelector('button[type="submit"]');

const aturanBank = {
    "BCA": 10,
    "BRI": 15,
    "BNI": 10,
    "MANDIRI": 13,
    "CIMB NIAGA": 13,
    "DANAMON": 10,
    "PERMATA": 10,
    "BTN": 10,
    "BSI": 10
};

function validasiRekening() {
    const bank = bankInput.value.toUpperCase();
    let rek = rekInput.value.replace(/\D/g, '');

    if (aturanBank[bank]) {
        const maxDigit = aturanBank[bank];

        // POTONG kalau lebih
        if (rek.length > maxDigit) {
            rek = rek.slice(0, maxDigit);
        }

        rekInput.value = rek;

        if (rek.length !== maxDigit) {
            rekError.textContent = `Nomor rekening ${bank} harus ${maxDigit} digit`;
            rekError.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            rekError.classList.add('hidden');
            submitBtn.disabled = false;
        }
    } else {
        rekInput.value = rek;
        rekError.classList.add('hidden');
        submitBtn.disabled = false;
    }
}

bankInput.addEventListener('blur', function () {
    this.value = this.value.toUpperCase();
    validasiRekening();
});

rekInput.addEventListener('input', validasiRekening);
</script>


</body>
</html>
