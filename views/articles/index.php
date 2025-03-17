<div class="container">
    <h3>Seznam novic</h3>
    <?php foreach ($articles as $article): ?>
        <div class="article">
            <h4><?php echo htmlspecialchars($article->title); ?></h4>
            <p><?php echo htmlspecialchars($article->abstract); ?></p>
            <p>Objavil: <?php echo htmlspecialchars($article->user->username); ?>, 
                <?php echo date_format(date_create($article->date), 'd. m. Y \ob H:i:s'); ?>
            </p>
            <a href="/articles/show?id=<?php echo $article->id;?>"><button>Preberi več</button></a>
            <br><br>

            <!-- form -->
            <?php if (isset($_SESSION["USER_ID"])): ?> 
                <form action="/articles/comment" method="POST">
                    <input type="hidden" name="article_id" value="<?php echo $article->id; ?>">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" placeholder="Napišite komentar..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Objavi komentar</button>
                </form>
            <?php else: ?>
                <p><a href="/login">Prijavite se</a> za komentiranje.</p>
            <?php endif; ?>

            <hr>

            
            <h5>Komentarji:</h5>
            <?php 
            $comments = Comment::getComments($article->id); // fetch comments
            if (!empty($comments)): 
            ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong></p>
                            <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                            <small class="text-muted"><?php echo $comment['created_at']; ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Ni komentarjev.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>