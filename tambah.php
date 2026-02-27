<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $ep_total = (int)$_POST['episode_total'];
    $ep_nonton = (int)$_POST['episode_nonton'];
    $status = $_POST['status'];
    $rating = $_POST['rating'];

    if ($ep_nonton < 0 || $ep_total < 1) {
        die("Input tidak valid: Angka tidak boleh minus!");
    }

    if ($ep_nonton > $ep_total) {
        $ep_nonton = $ep_total;
    }

    $sql = "INSERT INTO watchlist (judul, episode_total, episode_nonton, status, rating) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$judul, $ep_total, $ep_nonton, $status, $rating])) {
        header("Location: index.php?pesan=tambah_sukses");
        exit;
    } else {
        echo "Gagal menyimpan data!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Anime - Watchlist</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container" style="max-width: 500px;">
        <h2>Tambah Anime 🎬</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Judul Anime</label>
                <input type="text" name="judul" value="<?= isset($data) ? htmlspecialchars($data['judul']) : ''; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ep. Nonton</label>
                    <input type="number" name="episode_nonton" id="ep_nonton"
                        value="<?= isset($data) ? $data['episode_nonton'] : '0'; ?>"
                        min="0" oninput="validasiEpisode()">
                </div>
                <div class="form-group">
                    <label>Total Ep.</label>
                    <input type="number" name="episode_total" id="total_ep"
                        value="<?= isset($data) ? $data['episode_total'] : ''; ?>"
                        required min="1" oninput="validasiEpisode()">
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="Watching">Watching</option>
                    <option value="Completed">Completed</option>
                    <option value="Plan to Watch">Plan to Watch</option>
                    <option value="Dropped">Dropped</option>
                </select>
            </div>

            <div class="form-group">
                <label>Rating</label>
                <select name="rating">
                    <option value="0">Pilih Skor</option>
                    <?php for ($i = 10; $i >= 1; $i--): ?>
                        <option value="<?= $i; ?>">⭐ <?= $i; ?> / 10</option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan ke List</button>
            <a href="index.php" class="btn-batal-form">Kembali ke Watchlist</a>
        </form>
    </div>
    <script>
        function validasiEpisode() {
            const nonton = document.getElementById('ep_nonton');
            const total = document.getElementById('total_ep');
            const statusSelect = document.querySelector('select[name="status"]');

            if (parseInt(nonton.value) > parseInt(total.value)) {
                nonton.value = total.value;
            }

            if (nonton.value !== "" && total.value !== "" && parseInt(nonton.value) === parseInt(total.value)) {
                statusSelect.value = "Completed";
            } else if (parseInt(nonton.value) > 0 && parseInt(nonton.value) < parseInt(total.value)) {
                statusSelect.value = "Watching";
            }
        }
    </script>
</body>

</html>