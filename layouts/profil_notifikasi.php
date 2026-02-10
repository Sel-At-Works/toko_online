<?php
if (!isset($_SESSION['user'])) {
  return;
}

include $_SERVER['DOCUMENT_ROOT'] . '/config/koneksi.php';

$user    = $_SESSION['user'];
$user_id = $user['id'];
$role    = $user['role'];

$notif_chat    = 0;
$notif_pesanan = 0;

/* ===== PEMBELI ===== */
if ($role === 'pembeli') {

  // CHAT MASUK
  $q = mysqli_query($conn, "
        SELECT COUNT(*) AS total
FROM chat
WHERE penerima_id = '$user_id'
AND dibaca = 0
    ");
  $notif_chat = mysqli_fetch_assoc($q)['total'];
  
  // PESANAN
  $q = mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM transaksi
        WHERE pembeli_id = '$user_id'
        AND status IN ('diproses','MENUNGGU_VERIFIKASI')
    ");
  $notif_pesanan = mysqli_fetch_assoc($q)['total'];
}

/* ===== PENJUAL ===== */ elseif ($role === 'penjual') {

  // CHAT MASUK
  $q = mysqli_query($conn, "
      SELECT COUNT(*) AS total
FROM chat
WHERE penerima_id = '$user_id'
AND dibaca = 0

    ");
  $notif_chat = mysqli_fetch_assoc($q)['total'];

  // PESANAN BARU
  $q = mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM transaksi_penjual
        WHERE penjual_id = '$user_id'
        AND status IN ('MENUNGGU','MENUNGGU_VERIFIKASI')
    ");
  $notif_pesanan = mysqli_fetch_assoc($q)['total'];
}

$total_notif = $notif_chat + $notif_pesanan;
?>


<!-- NOTIFICATION + PROFILE -->
<div class="flex items-center gap-6 bg-white px-6 py-3 rounded-xl shadow">

  <!-- NOTIFICATION ICON (DROPDOWN) -->
  <div class="relative">
    <button id="notifBtn" class="relative focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg"
        class="w-6 h-6 text-gray-600 hover:text-teal-600 transition"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
               a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341
               C7.67 6.165 6 8.388 6 11v3.159
               c0 .538-.214 1.055-.595 1.436L4 17h5m6 0
               a3 3 0 11-6 0h6z" />
      </svg>

      <!-- BADGE -->
      <?php if ($total_notif > 0) { ?>
       <span id="notifTotal"
  class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px]
  w-4 h-4 flex items-center justify-center rounded-full"
  style="<?= $total_notif > 0 ? '' : 'display:none' ?>">
  <?= $total_notif ?>
</span>
      <?php } ?>
    </button>

    <?php
    $role = $_SESSION['user']['role'] ?? '';
    ?>

    <!-- DROPDOWN -->
    <div id="notifDropdown"
      class="hidden absolute right-0 mt-3 w-72 bg-white rounded-xl shadow-lg border z-50">

      <div class="p-4 font-semibold border-b">Notifikasi</div>

      <div class="max-h-60 overflow-y-auto">

        <?php if ($role === 'pembeli') { ?>

          <a href="/chat_app.php"
            class="block p-4 text-sm hover:bg-gray-50 flex justify-between items-center">

            <span>💬 Pesan baru dari penjual</span>

            <span id="notifChat"
              class="text-xs bg-teal-500 text-white px-2 rounded-full"
              style="<?= $notif_chat > 0 ? '' : 'display:none' ?>">
              <?= $notif_chat ?>
            </span>

          </a>

          <a href="/pembeli/pesanan_saya.php"
            class="block p-4 text-sm hover:bg-gray-50">
            📦 Pesanan sedang diproses
          </a>

        <?php } elseif ($role === 'penjual') { ?>

          <a href="/chat_app.php"
            class="block p-4 text-sm hover:bg-gray-50 flex justify-between items-center">

            <span>💬 Pesan baru dari pembeli</span>

            <span id="notifChat"
              class="text-xs bg-teal-500 text-white px-2 rounded-full"
              style="<?= $notif_chat > 0 ? '' : 'display:none' ?>">
              <?= $notif_chat ?>
            </span>


          </a>

          <a href="/pesanan/pesan_penjual.php"
            class="block p-4 text-sm hover:bg-gray-50 flex justify-between items-center">

            <span>📦 Pesanan baru masuk</span>

            <?php if ($notif_pesanan > 0) { ?>
              <span id="notifPesanan"
                class="text-xs bg-orange-500 text-white px-2 rounded-full"
                style="<?= $notif_pesanan > 0 ? '' : 'display:none' ?>">
                <?= $notif_pesanan ?>
              </span>

            <?php } ?>
          </a>

        <?php } else { ?>

          <div class="p-4 text-sm text-gray-400 text-center">
            Tidak ada notifikasi
          </div>

        <?php } ?>

      </div>

      <!-- <a href="/notifikasi.php"
        class="block text-center text-sm text-teal-600 py-2 hover:bg-gray-50 border-t">
        Lihat semua
      </a> -->

    </div>

  </div>

  <!-- USER INFO -->
  <a href="/profile.php"
    class="flex items-center gap-4 hover:opacity-80 transition">

    <div class="text-right">
      <p class="font-semibold">
        <?= ucwords(str_replace('_', ' ', $user['role'])); ?>
      </p>
      <p class="text-sm text-gray-500">
        <?= htmlspecialchars($user['email']); ?>
      </p>
    </div>

    <img src="/<?= !empty($user['foto'])
                  ? $user['foto']
                  : 'uploads/profile/default.png'; ?>"
      class="w-10 h-10 rounded-full object-cover border"
      alt="Profile">

  </a>
</div>
<script>
  const notifBtn = document.getElementById('notifBtn');
  const notifDropdown = document.getElementById('notifDropdown');

  notifBtn.addEventListener('click', e => {
    e.stopPropagation();
    notifDropdown.classList.toggle('hidden');
  });

  document.addEventListener('click', () => {
    notifDropdown.classList.add('hidden');
  });

  function loadNotif() {
    fetch('/ajax/get_notifikasi.php')
      .then(res => res.json())
      .then(data => {

        // TOTAL
        const totalBadge = document.getElementById('notifTotal');
        if (data.total > 0) {
          if (!totalBadge) location.reload();
          totalBadge.innerText = data.total;
          totalBadge.style.display = 'flex';
        } else if (totalBadge) {
          totalBadge.style.display = 'none';
        }

        // CHAT
        const chatBadge = document.getElementById('notifChat');
        if (chatBadge) {
          chatBadge.innerText = data.chat;
          chatBadge.style.display = data.chat > 0 ? 'inline-block' : 'none';
        }

        // PESANAN
        const pesananBadge = document.getElementById('notifPesanan');
        if (pesananBadge) {
          pesananBadge.innerText = data.pesanan;
          pesananBadge.style.display = data.pesanan > 0 ? 'inline-block' : 'none';
        }
      });
  }

  // polling tiap 5 detik
  setInterval(loadNotif, 5000);
</script>