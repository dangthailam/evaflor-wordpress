<div id="fichegagnant" class="main-gagnant container center-part text-center" style="display: none;">
        <div class="gagnant-content">
            <div class="prefix-content">
                <p>Congratulation</p>
                <p>to our winner</p>
            </div>
            <div class="winner">
                <span class="winner-name">
                    <h6>Mister</h6> 
                    <h1><?php echo (getWinnerName()); ?></h1>
                </span>
                <p>n° <?php echo (get_option('lottery_number')); ?></p>
            </div>
            <div class="prize">
                <img src="<?php echo get_bloginfo('template_directory'); ?>/images/whisky-with-pack.png" alt="" srcset="">
            </div>
        </div>
</div>
