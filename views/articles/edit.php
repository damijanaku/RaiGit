<div class="container">
<form action="/articles/update" method="POST">
    <h3>Uredi svojo novico</h3>
    <input type="hidden" name="id" value="<?php echo isset($article->id) ? htmlspecialchars($article->id) : ''; ?>">
    <div class="mb-3">
    <label for="naslov" class="form-label">Naslov: </label>
    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($article->title); ?>" required>
    </div>

    <div class="mb-3">
    <label for="abstract" class="form-label">Kratki opis: </label>
    <input type="text" name="abstract" class="form-control" value="<?php echo htmlspecialchars($article->abstract); ?>" required>
    </div>

    <div class="mb-3">
    <label for="vsebina" class="form-label">Vsebina: </label>
    <input name="text" class="form-control" value="<?php echo htmlspecialchars($article->text); ?>" required></input>
    </div>
    <button type="submit" class="btn btn-primary">Posodobi</button>
    </form>
</div> 

