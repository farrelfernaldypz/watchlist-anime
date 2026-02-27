<?php
require 'connection.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM watchlist WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $ep_total = (int)$_POST['episode_total'];
    $ep_nonton = (int)$_POST['episode_nonton'];
    $status = $_POST['status'];
    $rating = $_POST['rating'];

    if ($ep_nonton < 0 || $ep_total < 1) {
        die("Input tidak valid!");
    }

    if ($ep_nonton > $ep_total) {
        $ep_nonton = $ep_total;
    }

    $sql = "UPDATE watchlist SET judul = ?, episode_total = ?, episode_nonton = ?, status = ?, rating = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$judul, $ep_total, $ep_nonton, $status, $rating, $id])) {
        header("Location: index.php?pesan=update_sukses");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Progress - <?= htmlspecialchars($data['judul']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>Update Progress ✍️</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Judul Anime</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ep. Nonton</label>
                    <input type="number" name="episode_nonton" id="ep_nonton"
                        value="<?= $data['episode_nonton']; ?>"
                        min="0" oninput="validasiEpisode()">
                </div>
                <div class="form-group">
                    <label>Total Ep.</label>
                    <input type="number" name="episode_total" id="total_ep"
                        value="<?= $data['episode_total']; ?>"
                        required min="1" oninput="validasiEpisode()">
                </div>
            </div>

            <div class="form-group">
                <label>Status Menonton</label>
                <select name="status" id="status_select">
                    <option value="Watching" <?= $data['status'] == 'Watching' ? 'selected' : ''; ?>>Watching</option>
                    <option value="Completed" <?= $data['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="Plan to Watch" <?= $data['status'] == 'Plan to Watch' ? 'selected' : ''; ?>>Plan to Watch</option>
                    <option value="Dropped" <?= $data['status'] == 'Dropped' ? 'selected' : ''; ?>>Dropped</option>
                </select>
            </div>

            <div class="form-group">
                <label>Rating</label>
                <select name="rating">
                    <option value="0">Pilih Skor</option>
                    <?php for ($i = 10; $i >= 1; $i--): ?>
                        <option value="<?= $i; ?>" <?= ($data['rating'] == $i) ? 'selected' : ''; ?>>
                            ⭐ <?= $i; ?> / 10
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="index.php" class="btn-batal-form">Batal</a>
        </form>
    </div>

    <script>
        function validasiEpisode() {
            const nonton = document.getElementById('ep_nonton');
            const total = document.getElementById('total_ep');
            const statusSelect = document.getElementById('status_select');

            if (parseInt(nonton.value) > parseInt(total.value)) {
                nonton.value = total.value;
            }

            if (nonton.value !== "" && total.value !== "" && parseInt(nonton.value) === parseInt(total.value)) {
                statusSelect.value = "Completed";
            }
        }
    </script>
</body>
</html>