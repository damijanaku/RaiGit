<div class="container">
    <h3>Seznam mojih novic</h3>
    <div class="article">
        <?php if (!empty($articles)) { ?>
            <?php foreach($articles as $article) { ?>
                <h4><?php echo htmlspecialchars($article->title); ?></h4>
                <p><?php echo htmlspecialchars($article->abstract); ?></p>
                <p><?php echo htmlspecialchars($article->text); ?></p>
            <?php } ?>
        <?php } else { ?>
            <p>No articles found.</p>
        <?php } ?>
    </div>
</div>