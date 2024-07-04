<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id = $_POST['movie_id'];
    $renter_name = $_POST['renter_name'];
    $duration = $_POST['duration'];

    // Dapatkan informasi film dari database
    $sql_movie = "SELECT * FROM movies WHERE id = ?";
    $stmt_movie = $conn->prepare($sql_movie);
    $stmt_movie->bind_param("i", $movie_id);
    $stmt_movie->execute();
    $movie = $stmt_movie->get_result()->fetch_assoc();

    if ($movie) {
        $total_price = $movie['price'] * $duration;

        // Simpan data penyewaan ke database
        $sql = "INSERT INTO rentals (movie_id, renter_name, duration, total_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isid", $movie_id, $renter_name, $duration, $total_price);

        if ($stmt->execute()) {
            $success = "Penyewaan berhasil!";
        } else {
            $error = "Terjadi kesalahan: " . $stmt->error;
        }
    } else {
        $error = "Film tidak ditemukan.";
    }
}

$sql = "SELECT * FROM movies";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.php'; ?>
</head>
<body>
    <div class="container mt-4">
        <h2>Sewa VCD/DVD</h2>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <div class="card mt-4">
                <img src="assets/images/<?php echo $movie['image']; ?>" class="card-img-top" alt="<?php echo $movie['title']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $movie['title']; ?></h5>
                    <p class="card-text"><strong>Nama Penyewa: </strong><?php echo htmlspecialchars($renter_name); ?></p>
                    <p class="card-text"><strong>Durasi Penyewaan: </strong><?php echo $duration; ?> hari</p>
                    <p class="card-text"><strong>Total Harga Sewa: </strong><?php echo $total_price; ?> IDR</p>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="rent.php">
            <div class="form-group">
                <label for="movie_id">Pilih Film:</label>
                <select class="form-control" id="movie_id" name="movie_id" required>
                    <?php while ($movie = $result->fetch_assoc()): ?>
                        <option value="<?php echo $movie['id']; ?>"><?php echo $movie['title'] . " - " . $movie['price'] . " IDR"; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="renter_name">Nama Penyewa:</label>
                <input type="text" class="form-control" id="renter_name" name="renter_name" required>
            </div>
            <div class="form-group">
                <label for="duration">Durasi Penyewaan (hari):</label>
                <input type="number" class="form-control" id="duration" name="duration" required>
            </div>
            <button type="submit" class="btn btn-primary">Sewa</button>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
