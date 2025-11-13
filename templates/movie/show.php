<?php

require_once _ROOTPATH_ . '/templates/header.php';
/** @var App\Entity\Movie $movie */


?>

<h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3"><?= $movie->getTitle() ?></h1>
<!-- class "row" : faire une nouvelle ligne -->
<div class="row">
    <div class="col">
        Date de sortie :
        <?php
        if ($movie->getReleaseDate()) {
            echo $movie->getReleaseDate()->format("d/m/Y");
        } else {
            echo "N/C";
        }
        ?>
    </div>
    <div class="col">
        Duree : <?php
                if ($movie->getDuration()) {
                    echo $movie->getDuration()->format("H\hi");
                } else {
                    echo "N/C";
                }
                ?>
    </div>
    <div class="col">
        Genre : <?php foreach ($genres as $genre) {
                    /** @var App\Entity\Genre $genre */ ?>
            <span><?php echo $genre->getName(); ?> </span>
        <?php } ?>
    </div>
    <div class="col">
        Réalisateur(s) : <?php foreach ($directors as $director) {
                                /** @var App\Entity\Director $director */ ?>
            <span><?php echo $director->getFirstName(). " " . $director->getLastName(); ?> </span>
        <?php } ?>
    </div>
</div>

<div class="row align-items-center g-5 py-5">
    <div class="col-lg-8">
        <p class="lead"><?= $movie->getSynopsis() ?> ?></p>
    </div>
    <div class="col-12 col-sm-8 col-lg-4">
        <img src="<?= $movie->getImagePath() ?>" class="d-block mx-lg-auto img-fluid" alt="<?= $movie->getTitle() ?>" width="700" loading="lazy">
    </div>

</div>
</div>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>