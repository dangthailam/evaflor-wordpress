<?php
get_header(); ?>

<!-- POP UP QUIZZ OR LOTO -->
<div class="modal fade" tabindex="-1" role="dialog" id="popup_loto_quizz">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Voulez-vous un cadeau?</h4>
      </div>
      <div class="modal-body">
        <button type="button" class="btn" id="accept_btn">Je participe</button>
        <button type="button" class="btn" id="refuse_btn">Non plus tard</button>
      </div>
    </div>
  </div>
</div>

<!-- POP UP LOTO NUMBER -->
<div id="lotteryPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-primary">Votre numéro pour le tirage au sort</h2>
            </div>
            <div class="modal-body text-center">
                <h4 id="nbLottery" style="color:#FF4500"></h4>
            </div>
            <div class="modal-footer">
                <span class="form-control-static pull-left text-primary"> Voulez-vous enregistrer ce numéro pour le prochain tirage au sort?</span>
                <button type="button" class="btn btn-primary" onclick="return getLotteryFormula()">Oui</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
            </div>
        </div>
    </div>
</div>
    
<div id="main">

<?php

//get_template_part('view/fr/laune');
// Get user-defined language
$lang = 'fr';
$prefix = 'view/' . $lang . '/';

get_template_part($prefix . 'authentic');

get_template_part($prefix . 'not_authentic');

get_template_part($prefix . '404');

get_template_part($prefix . 'tirage');

get_template_part($prefix . 'loto');

get_template_part($prefix . 'quiz');

get_template_part($prefix . 'gagnant');

get_template_part($prefix . 'invalid_location');

?>

</div>

<?php
get_footer();
