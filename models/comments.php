<?php

class Comment {

    public $id;
    public $article_id;
    public $user_id;
    public $content;
    public $created_at;

    public function __construct($id, $article_id, $user_id, $content, $created_at)
    {
        $this->id = $id;
        $this->article_id = Article::find($article_id);
        $this->user_id = User::find($user_id);
        $this->content = $content;
        $this->created_at = $created_at;
    }

    public static function add($article_id, $user_id, $content)
    {
        $db = Db::getInstance();
        $query = "INSERT INTO comments (article_id, user_id, content) VALUE (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iis", $article_id, $user_id, $content);
        $stmt->execute();
    }

    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM comments;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $comments = array();
        while ($comment = $res->fetch_object()) {
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $articles
            array_push($comments, new Comment($comment->id, $comment->article_id, $comment->user_id, $comment->content, $comment->created_at));
        }
        return $comments;
    }

    public static function find($id)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM comments WHERE id = '$id';";
        $res = $db->query($query);
        if ($comment = $res->fetch_object()) {
            return new Comment($comment->id, $comment->article_id, $comment->user_id, $comment->content, $comment->created_at);
        }
        return null;
    }

    public static function getComments($article_id)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT comments.*, users.username FROM comments 
                              JOIN users ON comments.user_id = users.id 
                              WHERE article_id = ? ORDER BY created_at DESC");
        if (!$stmt) {
            die("Napaka pri pripravi poizvedbe: " . $db->error);
        }
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}

?>
