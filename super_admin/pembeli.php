<?php
session_start();
include '../config/koneksi.php';


/* =========================
   MODE AJAX (tanpa reload)
========================= */
if (isset($_GET['ajax']) && $_GET['ajax'] === 'status') {

    $result = mysqli_query($conn, "
        SELECT id, status_login 
        FROM users 
        WHERE role_id = 3
    ");

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ambil data pembeli (role_id = 3)
$query = mysqli_query($conn, "
    SELECT * 
    FROM users 
    WHERE role_id = 3
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Account Pembeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <?php include '../layouts/sidebar.php'; ?>

        <main class="flex-1 p-8">

            <!-- TOP BAR -->
            <div class="flex justify-end mb-8">
                <?php include '../layouts/profil_notifikasi.php'; ?>
            </div>

            <!-- HEADER -->
            <div class="relative flex items-center mb-6">

                <h1 class="absolute left-1/2 -translate-x-1/2 text-2xl font-semibold">
                    Account Pembeli
                    <span class="block w-24 h-1 bg-teal-500 mx-auto mt-2 rounded-full"></span>
                </h1>

                <div class="ml-auto flex items-center gap-4">
                    <input
                        id="searchPembeli"
                        type="text"
                        placeholder="Search Here"
                        class="px-4 py-2 rounded-full border focus:outline-none">

                </div>

            </div>

            <!-- LIST CARD -->
            <div class="flex gap-6 flex-wrap">

                <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                    <!-- untuk warna -->
                    <?php
                    $bgCard = ($row['status_login'] === 'online')
                        ? 'bg-gradient-to-b from-green-400 to-green-600'
                        : 'bg-gradient-to-b from-red-400 to-red-600';
                    ?>


                    <?php
                    // FOTO
                    $path_server = $_SERVER['DOCUMENT_ROOT'] . '/' . $row['foto'];
                    $path_url    = '/' . $row['foto'];
                    $ada_foto    = !empty($row['foto']) && file_exists($path_server);
                    ?>

                    <!-- CARD PEMBELI -->
                    <div id="pembeli-<?= $row['id'] ?>"
                        class="pembeli-card relative w-72 rounded-3xl p-6 text-white <?= $bgCard ?>">

                        <!-- Status Login -->
                        <span id="status-<?= $row['id'] ?>"
                            class="absolute top-4 right-4 text-xs px-3 py-1 rounded-full
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
                            <p><b>Alamat</b> : <?= $row['alamat'] ?: '-' ?></p>
                        </div>

                        <!-- ACTION -->
                        <!-- ACTION -->
                        <div class="flex justify-center gap-5 mt-6">

                            <!-- EDIT -->
                            <a href="edit_pembeli.php?id=<?= $row['id'] ?>"
                                class="p-2 rounded-full bg-white/20 hover:bg-yellow-400">✏️</a>

                            <!-- DETAIL -->
                            <a href="detail_pembeli.php?id=<?= $row['id'] ?>"
                                class="p-2 rounded-full bg-white/20 hover:bg-blue-400">👁️</a>

                            <!-- DELETE -->
                            <button
                                onclick="hapusPembeli(<?= $row['id'] ?>)"
                                class="p-2 rounded-full bg-white/20 hover:bg-red-500">
                                🗑️
                            </button>

                        </div>


                    </div>

                <?php endwhile; ?>

            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function hapusPembeli(id) {
            Swal.fire({
                title: 'Yakin?',
                text: 'Data pembeli akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch('hapus_pembeli.php?id=' + id)
                        .then(res => res.text())
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data pembeli dihapus',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // 🔥 HILANGKAN CARD TANPA RELOAD
                            document.getElementById('pembeli-' + id)?.remove();
                        });
                }
            });
        }
    </script>
    <script>
        document.getElementById('searchPembeli').addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            const cards = document.querySelectorAll('.pembeli-card');

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        function updateStatusPembeli() {
    fetch('?ajax=status')
        .then(res => res.json())
        .then(data => {
            data.forEach(user => {
                const card  = document.getElementById('pembeli-' + user.id);
                const badge = document.getElementById('status-' + user.id);

                if (!card || !badge) return;

                // reset warna
                card.classList.remove(
                    'from-green-400','to-green-600',
                    'from-red-400','to-red-600'
                );
                badge.classList.remove('bg-green-800','bg-red-800');

                if (user.status_login === 'online') {
                    card.classList.add('from-green-400','to-green-600');
                    badge.classList.add('bg-green-800');
                    badge.innerText = 'ONLINE';
                } else {
                    card.classList.add('from-red-400','to-red-600');
                    badge.classList.add('bg-red-800');
                    badge.innerText = 'OFFLINE';
                }
            });
        });
}

// cek tiap 5 detik
setInterval(updateStatusPembeli, 5000);
    </script>




</body>

</html>