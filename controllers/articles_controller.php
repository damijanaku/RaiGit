<?php
/*
    Controller za novice. Vključuje naslednje standardne akcije:
        index: izpiše vse novice
        show: izpiše posamezno novico
        
    TODO:
        list: izpiše novice prijavljenega uporabnika
        create: izpiše obrazec za vstavljanje novice
        store: vstavi novico v bazo
        edit: izpiše vmesnik za urejanje novice
        update: posodobi novico v bazi
        delete: izbriše novico iz baze
*/

class articles_controller
{
    public function index()
    {
        //s pomočjo statične metode modela, dobimo seznam vseh novic
        //$ads bo na voljo v pogledu za vse oglase index.php
        $articles = Article::all();

        //pogled bo oblikoval seznam vseh oglasov v html kodo
        require_once('views/articles/index.php');
    }

    public function show()
    {
        //preverimo, če je uporabnik podal informacijo, o oglasu, ki ga želi pogledati
        if (!isset($_GET['id'])) {
            return call('pages', 'error'); //če ne, kličemo akcijo napaka na kontrolerju stran
            //retun smo nastavil za to, da se izvajanje kode v tej akciji ne nadaljuje
        }
        //drugače najdemo oglas in ga prikažemo
        $article = Article::find($_GET['id']);
        require_once('views/articles/show.php');
    }

    public function create(){
        require_once('views/articles/create.php');
    }

    function store()
    {

        if (!isset($_POST['title']) || !isset($_POST['abstract']) || !isset($_POST['text'])) {
            die("Napaka: Manjkajoči podatki v obrazcu.");
        }

        if (!isset($_SESSION["USER_ID"])) {
            die("Napaka: Uporabnik ni prijavljen.");
        }

        $title = $_POST['title'];
        $abstract = $_POST['abstract'];
        $text = $_POST['text'];
        $user_id = $_SESSION["USER_ID"];

        if (Article::create($title, $abstract, $text, $user_id)) {
            header("Location: /"); 
            exit();
        } else {
            die("Napaka pri shranjevanju novice.");
        }
    }


    function list(){
        $articles = []; 
    
        if(isset($_SESSION["USER_ID"])){
            $articles = Article::list($_SESSION["USER_ID"]);
        }
    
        require_once('views/articles/list.php'); //form
    }

    function edit(){
        if(isset($_SESSION["USER_ID"])){
            if(isset($_GET['id'])){
                $article = Article::find($_GET['id']);


                require_once('views/articles/edit.php');
            }
        }
    }

    function update() {
        if (isset($_SESSION["USER_ID"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_POST["id"]) && !empty($_POST["title"]) && !empty($_POST["abstract"]) && !empty($_POST["text"])) {
                $id = $_POST["id"];
                $title = $_POST["title"];
                $abstract = $_POST["abstract"];
                $text = $_POST["text"];
    
                Article::update( $id, $title, $abstract, $text);
                header("Location: /"); 

                
            } else {
                header("Location: /articles/edit?id=" . $_POST["id"] . "&error=missing_data");
                exit();
            }
        } else {
            header("Location: /login");
            exit();
        }
    } 

    function delete(){
        if(isset($_SESSION["USER_ID"])){
            if(isset($_GET['id'])){
                Article::delete($_GET['id']);
                header("Location: /articles/list");

            }
        }
    }

    function comment(){
        if(isset($_SESSION["USER_ID"])){
            if(!empty($_POST["article_id"] && !empty($_POST["content"]))){
                $article_id = $_POST["article_id"];
                $user_id = $_SESSION["USER_ID"];
                $content = trim($_POST["content"]);


                Comment::add($article_id, $user_id, $content);
            }
        }

        
    }
    

}