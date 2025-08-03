<?php
session_start();
require_once('link.php');

if (isset($_GET['item'])) {
    $product_id = $_GET['item'];
    
    $sql = "SELECT id, name, price, FROM products WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    
    if (!$product) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $username = $_POST['username'] ?? 'Anonymous';
    $comment = $_POST['comment'] ?? '';
    
    if (!empty($comment)) {
        $username = htmlspecialchars(trim($username));
        $comment = htmlspecialchars(trim($comment));
        
        $sql = "INSERT INTO product_comments (product_id, username, comment) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iss", $product_id, $username, $comment);
        $stmt->execute();
        
        header('Location: product.php?item='.$product_id);
        exit;
    }
}

$sql = "SELECT username, comment, created_at FROM product_comments WHERE product_id = ? ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

require_once('template/head.php');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p class="h4">$<?= number_format($product['price'], 2) ?></p>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <a href="?add_to_cart=<?= $product['id'] ?>" class="btn btn-success">Add to Cart</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Comments</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="mb-3">
                                <h5><?= htmlspecialchars($comment['username']) ?></h5>
                                <small class="text-muted"><?= date('F j, Y, g:i a', strtotime($comment['created_at'])) ?></small>
                                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Комментариев пока нет. Оставьте комментарий первым!</p>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Name</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '' ?>" 
                                   placeholder="Your name (optional)">
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="submit_comment" class="btn btn-primary">Submit Comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once('template/footer.php');
?>