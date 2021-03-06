<?php
/**
 * Created by PhpStorm.

 * User: wilder4
 * Date: 06/10/17
 * Time: 00:39
 */

include 'header.php';

require_once 'api_init.php';
require_once '../src/functions.php';

$search = false;
$results = '';
if (!empty($_GET['search'])) {
    $cleaned = cleanVar($_GET['search']);
    $results = $api_movies->search($cleaned);
    $search = true;
    $results = $results->movies;
} else if (!empty($_GET['id'])) {
    $cleaned = cleanVar($_GET['id']);
    $results = $api_movies->getInfosFromID($cleaned);

    var_dump($results);
}

if (empty($results)) {
    if ($search) {
        ?><p class="annonce">Il n'y a aucun résultat pour votre recherche.</p>
        <?php
    } else {
        ?><p class="annonce">Vous n'avez pas encore ajouté d'éléments à cette catégorie.</p>
        <?php
    }
} else {
    ?>
    <div class="container category">
        <?php if ($search) : ?>
            <h1>Résultat(s) des films</h1>
        <?php else : ?>
            <h1>Mes films</h1>
        <?php endif; ?>
        <?php
        $count = 0;
        foreach ($results as $obj) :
            $state = getStateFromIds($pdo, $obj->id, 1);
            if ($count == 0) : ?>
                <div class="row">
            <?php endif; ?>
            <div class="col-xs-6 col-md-3">
                <a href="#">
                    <figure class="thumbnail">
                        <img src="<?= property_exists($obj, 'poster') ? $obj->poster : '' ?>" class="image">
                        <figcaption class="caption">
                            <p class="text-center"><?= property_exists($obj, 'title') ? $obj->title : '' ?></p>
                            <p class="text"><?= property_exists($obj, 'title') ? $obj->synopsis : '' ?></p>
                            <div>
                                <?php if ($state == 1 || $state == 0) : ?>
                                    <form action="setstate.php" method="post" class="formThumbnail">
                                        <input type="hidden" name="category" value="2"/>
                                        <input type="hidden" name="id" value="id"/>
                                        <input type="hidden" name="state" value="1"/>
                                        <button type="submit" class="btn btn-danger">J'ai</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($state == 2 || $state == 0) : ?>
                                    <form action="setstate.php" method="post" class="formThumbnail">
                                        <input type="hidden" name="category" value="2"/>
                                        <input type="hidden" name="id" value="id"/>
                                        <input type="hidden" name="state" value="2"/>
                                        <button type="submit" class="btn btn-danger want">Je veux</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <?php
            $count++;
            if ($count == 4) : ?>
                </div>
                <?php
                $count = 0;
            endif;
        endforeach; ?>
    </div>
    <?php
}
include 'footer.php';
?>

