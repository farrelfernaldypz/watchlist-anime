<?php
require 'connection.php';
$stmt = $pdo->query("SELECT * FROM watchlist ORDER BY status DESC, updated_at DESC");
$anime_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>My Anime Watchlist</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container">
    <header>
      <h2>My Anime Watchlist ⛩️</h2>
    </header>

    <div class="action-bar">
      <a href="tambah.php" class="btn-tambah">
        <i class="fas fa-plus-circle"></i> Tambah Anime Baru
      </a>
    </div>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Judul Anime</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Rating</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($anime_list as $row): ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['judul']); ?></strong></td>
              <td>
                <div style="font-size: 0.8rem; margin-bottom: 4px;">
                  Ep <?= $row['episode_nonton']; ?> / <?= $row['episode_total']; ?>
                </div>
                <div style="width: 100%; background: #eee; height: 6px; border-radius: 10px;">
                  <?php
                  $percent = ($row['episode_total'] > 0) ? ($row['episode_nonton'] / $row['episode_total']) * 100 : 0;
                  $barColor = ($percent == 100) ? '#2ecc71' : '#9CD5FF';
                  ?>
                  <div style="width: <?= $percent; ?>%; background: <?= $barColor; ?>; height: 100%; border-radius: 10px; transition: width 0.5s;"></div>
                </div>
              </td>
              <td>
                <span class="status-badge <?= strtolower(str_replace(' ', '-', $row['status'])); ?>">
                  <?= $row['status']; ?>
                </span>
              </td>
              <td><i class="fas fa-star" style="color: #f1c40f;"></i> <?= $row['rating']; ?>/10</td>
              <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-hapus delete-btn">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const pesan = urlParams.get('pesan');

    if (pesan) {
      let title = "";
      let text = "";

      if (pesan === 'tambah_sukses') {
        title = "Berhasil!";
        text = "Anime baru telah ditambahkan.";
      } else if (pesan === 'update_sukses') {
        title = "Diperbarui!";
        text = "Progress kamu berhasil disimpan.";
      } else if (pesan === 'hapus_sukses') {
        title = "Terhapus!";
        text = "Anime telah dihapus dari daftar.";
      }

      if (title) {
        Swal.fire({
          icon: 'success',
          title: title,
          text: text,
          confirmButtonColor: '#355872',
          timer: 2500
        });
      }
      window.history.replaceState({}, document.title, window.location.pathname);
    }

    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');

        Swal.fire({
          title: 'Yakin mau hapus?',
          text: "Data yang dihapus tidak bisa dikembalikan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#355872',
          cancelButtonColor: '#e74c3c',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          background: '#F7F8F0',
          borderRadius: '20px'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = href;
          }
        });
      });
    });
  </script>
</body>

</html>
