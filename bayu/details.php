<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM movies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.php'; ?>
</head>
<body>
    <div class="container">
        <h2><?php echo $movie['title']; ?></h2>
        <p><?php echo $movie['description']; ?></p>
        <a href="rent.php?id=<?php echo $movie['id']; ?>" class="btn btn-primary">Sewa Sekarang</a>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
