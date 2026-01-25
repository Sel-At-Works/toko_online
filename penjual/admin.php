<?php
session_start();
include '../config/koneksi.php';

// ambil data penjual
$query = mysqli_query($conn, "
    SELECT * 
    FROM users 
    WHERE role_id = 2
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Account Penjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans h-screen overflow-hidden">


  <div class="flex h-screen">


        <!-- SIDEBAR -->
        <?php include '../layouts/sidebar_penjual.php'; ?>

       <main class="flex-1 p-8 overflow-y-auto">


            <!-- TOP BAR -->
            <div class="flex justify-end mb-8">
                <?php include '../layouts/profil_notifikasi.php'; ?>
            </div>

            <!-- HEADER -->
            <div class="relative flex items-center mb-6">

                <h1 class="absolute left-1/2 -translate-x-1/2 text-2xl font-semibold">
                    Account Penjual
                    <span class="block w-24 h-1 bg-teal-500 mx-auto mt-2 rounded-full"></span>
                </h1>

                <div class="ml-auto flex items-center gap-4">
                    <input
                        id="searchPenjual"
                        type="text"
                        placeholder="Search Here"
                        class="px-4 py-2 rounded-full border focus:outline-none">

                    <a href="tambah_penjual.php" class="text-3xl font-bold">+</a>
                </div>

            </div>

            <!-- LIST CARD -->
            <div class="flex gap-6 flex-wrap">

                <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                    <?php
                    // FOTO
                    $path_server = $_SERVER['DOCUMENT_ROOT'] . '/toko_online/' . $row['foto'];
                    $path_url    = '/toko_online/' . $row['foto'];
                    $ada_foto    = !empty($row['foto']) && file_exists($path_server);
                    ?>

                    <?php
                    $bgCard = ($row['status_login'] === 'online')
                        ? 'bg-gradient-to-b from-green-400 to-green-600'
                        : 'bg-gradient-to-b from-red-400 to-red-600';
                    ?>

                    <div id="penjual-<?= $row['id'] ?>"
                        class="penjual-card relative w-72 rounded-3xl p-6 text-white <?= $bgCard ?>">

                        <!-- Status Login -->
                        <span class="absolute top-4 right-4 text-xs px-3 py-1 rounded-full
                        <?= $row['status_login'] === 'online' ? 'bg-green-800' : 'bg-red-800' ?>">
                        <?= strtoupper($row['status_login']) ?>
                        </span>


                        <!-- FOTO -->
                        <div class="flex justify-center mb-4">
                            <div class="w-20 h-20 rounded-full border-4 border-white shadow
                                    flex items-center justify-center bg-white/20 overflow-hidden">

                                <?php if ($ada_foto): ?>
                                    <img src="<?= $path_url ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-sm">No Photo</span>
                                <?php endif; ?>

                            </div>
                        </div>

                        <!-- DATA -->
                        <div class="text-sm space-y-1">
                            <p><b>NIK</b> : <?= $row['nik'] ?></p>
                            <p><b>Nama</b> : <?= $row['nama'] ?></p>
                            <p><b>Email</b> : <?= $row['email'] ?></p>
                            <p><b>Password</b> : ******</p>
                            <p><b>Alamat</b> : <?= $row['alamat'] ?: '-' ?></p>
                        </div>

                        <!-- ACTION -->
                        <div class="flex justify-center gap-5 mt-6">

                            <a href="edit_penjual.php?id=<?= $row['id'] ?>"
                                class="p-2 rounded-full bg-white/20 hover:bg-yellow-400">✏️</a>

                            <a href="detail_penjual.php?id=<?= $row['id'] ?>"
                                class="p-2 rounded-full bg-white/20 hover:bg-blue-400">👁️</a>

                            <button
                                onclick="hapusPenjual(<?= $row['id'] ?>)"
                                class="p-2 rounded-full bg-white/20 hover:bg-red-500">
                                🗑️
                            </button>


                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        </main>
    </div>

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function hapusPenjual(id) {
        Swal.fire({
            title: 'Yakin?',
            text: 'Data penjual akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                fetch('hapus_penjual.php?id=' + id)
                    .then(res => res.text())
                    .then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data penjual dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // hilangkan card / row
                        document.getElementById('penjual-' + id)?.remove();
                    });
            }
        });
    }
</script>

<!-- Untuk System Search -->
<script>
    document.getElementById('searchPenjual').addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();
        const cards = document.querySelectorAll('.penjual-card');

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();

            if (text.includes(keyword)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>