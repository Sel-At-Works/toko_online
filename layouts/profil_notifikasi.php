<?php
// file ini diasumsikan di-include dari dashboard
// session_start() SUDAH ADA di dashboard

if (!isset($_SESSION['user'])) {
    return; // kalau belum login, jangan tampilkan apa-apa
}

$user = $_SESSION['user'];
?>

<!-- NOTIFICATION + PROFILE -->
<div class="flex items-center gap-6 bg-white px-6 py-3 rounded-xl shadow">

  <!-- NOTIFICATION ICON -->
  <div class="relative cursor-pointer">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-6 h-6 text-gray-600 hover:text-teal-600 transition"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
               a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341
               C7.67 6.165 6 8.388 6 11v3.159
               c0 .538-.214 1.055-.595 1.436L4 17h5m6 0
               a3 3 0 11-6 0h6z"/>
    </svg>

    <!-- BADGE -->
    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px]
                 w-4 h-4 flex items-center justify-center rounded-full">
      3
    </span>
  </div>

  <!-- USER INFO -->
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
