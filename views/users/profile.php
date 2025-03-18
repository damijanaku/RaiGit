<div class="container">
    <h2>Moj profil</h2>
    <p><strong>Uporabniško ime:</strong> <?php echo htmlspecialchars($user->username); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>
    <p><strong>Število novic:</strong> <?php echo $counts['article_count']; ?></p>
    <p><strong>Število komentarjev:</strong> <?php echo $counts['comment_count']; ?></p>
    <a href="/users/edit" class="btn btn-primary">Uredi profil</a>
</div>
