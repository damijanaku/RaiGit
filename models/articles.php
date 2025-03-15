<?php
/*
    Model za novico. Vsebuje lastnosti, ki definirajo strukturo novice in sovpadajo s stolpci v bazi.

    V modelu moramo definirati tudi relacije oz. povezane entitete/modele. V primeru novice je to $user, ki 
    povezuje novico z uporabnikom, ki je novico objavil. Relacija nam poskrbi za nalaganje podatkov o uporabniku, 
    da nimamo samo user_id, ampak tudi username, ...
*/

require_once 'users.php'; // Vključimo model za uporabnike

class Article
{
    public $id;
    public $title;
    public $abstract;
    public $text;
    public $date;
    public $user;

    // Konstruktor
    public function __construct($id, $title, $abstract, $text, $date, $user_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->abstract = $abstract;
        $this->text = $text;
        $this->date = $date;
        $this->user = User::find($user_id); //naložimo podatke o uporabniku
    }

    // Metoda, ki iz baze vrne vse novice tap tap tap
    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM articles;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $articles = array();
        while ($article = $res->fetch_object()) {
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $articles
            array_push($articles, new Article($article->id, $article->title, $article->abstract, $article->text, $article->date, $article->user_id));
        }
        return $articles;
    }

    // Metoda, ki vrne eno novico z določenim id-jem iz baze
    public static function find($id)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM articles WHERE id = '$id';";
        $res = $db->query($query);
        if ($article = $res->fetch_object()) {
            return new Article($article->id, $article->title, $article->abstract, $article->text, $article->date, $article->user_id);
        }
        return null;
    }

    public static function create($title, $abstract, $text, $user_id)
    {
        $db = Db::getInstance();
        // query
        $query = "INSERT INTO articles (title, abstract, text, date, user_id) VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $db->prepare($query);
        if (!$stmt) {
            die("Napaka pri pripravi poizvedbe: " . $db->error);
        }
        $stmt->bind_param("sssi", $title, $abstract, $text, $user_id);
        if ($stmt->execute()) { // zazeni
            return true;
        } else {
            die("Napaka pri shranjevanju: " . $stmt->error);
        }
    
    
    }

    public static function list($user_id){
        $db = Db::getInstance();
        
        $stmt = $db->prepare("SELECT id, title, abstract, text, date, user_id FROM articles WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
    
        $articles = [];
        while ($article = $res->fetch_object()) {
            $articles[] = new Article(
                $article->id, 
                $article->title, 
                $article->abstract, 
                $article->text, 
                $article->date, 
                $article->user_id 
            );
        }
    
        return $articles; 
    }

    public static function update($id, $title, $abstract, $text){
        $db = Db::getInstance();
        $query = "UPDATE articles SET title = '$title', abstract = '$abstract', text = '$text' WHERE id = '$id'";
        $stmt = $db->prepare($query);
        $stmt->execute();
    }

    public static function delete($id){
        $db = Db::getInstance();
        $query = "DELETE FROM articles WHERE id = '$id'";
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
}