<?php
get_header(); ?>


<div id="main">

<?php

//get_template_part('view/fr/laune');

get_template_part('view/fr/authentic');

get_template_part('view/fr/not_authentic');

get_template_part('view/fr/404');

get_template_part('view/fr/tirage');

get_template_part('view/fr/loto');

get_template_part('view/fr/quiz');

get_template_part('view/fr/gagnant');

get_template_part('view/fr/invalid_location');

?>

</div>

<?php
get_footer();
?>