<?php
include 'includes/db.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT * FROM movies WHERE title LIKE ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$search = "%{$query}%";
$stmt->bind_param("s", $search);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.php'; ?>
    <!-- Masukkan link ke file CSS Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

</head>
<body>
    <div class="container mt-4">
        <div class="jumbotron text-center">
            <h1>Rental VCD/DVD Bayu</h1>
            <p>Welcome rental VCD/DVD Bayu</p>
            <form method="get" action="index.php" class="form-inline justify-content-center">
                <input class="form-control mr-sm-2" type="search" name="query" placeholder="Cari film..." aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cari</button>
            </form>
        </div>

        <div class="container mt-4">
    <div class="row card-deck">
        <?php while ($movie = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="assets/images/<?php echo $movie['image']; ?>" class="card-img-top" alt="<?php echo $movie['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $movie['title']; ?></h5>
                        <p class="card-text"><?php echo $movie['description']; ?></p>
                        <p class="card-text"><strong>Harga Sewa: </strong><?php echo $movie['price']; ?> IDR</p>
                        <a href="rent.php?movie_id=<?php echo $movie['id']; ?>" class="btn btn-primary">Sewa</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
