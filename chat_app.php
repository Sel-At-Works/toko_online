<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['lawan_id'])) {
    $lawan_id = (int) $_GET['lawan_id'];
    include $_SERVER['DOCUMENT_ROOT'].'/chat/mark_read.php';
}

/* ====== TENTUKAN DASHBOARD BERDASARKAN ROLE ====== */
$dashboard = 'dashboard.php';

if ($_SESSION['user']['role'] === 'penjual') {
    $dashboard = 'penjual/dashboard.php';
} elseif ($_SESSION['user']['role'] === 'pembeli') {
    $dashboard = 'pembeli/dashboard.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<title>Chat</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex bg-gray-100 text-gray-800">

<!-- ================= SIDEBAR ================= -->
<div class="w-1/3 max-w-sm bg-white border-r flex flex-col">

    <!-- JUDUL -->
    <div class="px-5 py-4 border-b text-lg font-semibold">
        💬 Chat
    </div>

    <!-- CHAT LIST -->
    <div id="chatList" class="flex-1 overflow-y-auto">
        <div class="p-4 text-gray-400 text-sm">Loading...</div>
    </div>

    <!-- TOMBOL BACK (PALING BAWAH) -->
  <a
    href="<?= $dashboard ?>"
    class="m-4 flex items-center justify-center gap-2
           text-sm text-gray-600 hover:text-gray-900
           border rounded-full py-2 transition">
    ← Kembali ke Dashboard
</a>

</div>

<!-- ================= CHAT AREA ================= -->
<div class="flex-1 flex flex-col">

    <!-- HEADER -->
    <div id="chatHeader" class="px-6 py-4 border-b bg-white font-medium">
        Pilih chat untuk mulai
    </div>

    <!-- CHAT BOX -->
    <div id="chatBox" class="flex-1 overflow-y-auto px-6 py-4 space-y-3 bg-gray-50">
        <div class="text-gray-400 text-center mt-10">
            Belum ada percakapan
        </div>
    </div>

    <!-- INPUT -->
    <form id="chatForm" class="hidden px-4 py-3 bg-white border-t flex gap-3 items-center">
        <input
            id="pesan"
            type="text"
            class="flex-1 border rounded-full px-5 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="Tulis pesan..."
            autocomplete="off"
        >
        <button
            class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-full transition">
            Kirim
        </button>
    </form>
</div>

<script>
let currentLawan = null;

function loadChatList() {
    fetch('ajax/chat_list.php')
        .then(r => r.text())
        .then(h => chatList.innerHTML = h);
}

// ====== INI LETAK openChat ======
function openChat(id, nama) {
    currentLawan = id;
    chatHeader.innerText = nama;
    chatForm.classList.remove('hidden');

    // tandai chat sudah dibaca
    fetch('ajax/mark_read.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'lawan_id=' + id
    }).then(() => {
        // hapus badge secara langsung tanpa menunggu reload list
        const badge = document.querySelector('.unread-badge[data-lawan="' + id + '"]');
        if (badge) badge.remove();
    });

    fetch('ajax/chat_box.php?lawan_id=' + id)
        .then(r => r.text())
        .then(h => {
            chatBox.innerHTML = h;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
}


chatForm.onsubmit = e => {
    e.preventDefault();
    if (!pesan.value.trim()) return;

    fetch('ajax/send_chat.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'lawan_id=' + currentLawan + '&pesan=' + encodeURIComponent(pesan.value)
    }).then(() => {
        pesan.value = '';
        openChat(currentLawan, chatHeader.innerText);
        loadChatList(); // Muat ulang daftar chat
    });
};


loadChatList();
setInterval(() => {
    loadChatList();
    if (currentLawan) openChat(currentLawan, chatHeader.innerText);
}, 3000);

function backToList() {
    currentLawan = null;
    chatHeader.innerText = 'Chat';
    chatBox.innerHTML = '';
    chatForm.classList.add('hidden');
}
const urlParams = new URLSearchParams(window.location.search);
const lawanFromUrl = urlParams.get('lawan_id');

if (lawanFromUrl) {
    openChat(lawanFromUrl, 'Chat Baru');
}

</script>

</body>
</html>
