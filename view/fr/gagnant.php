<div id="fichegagnant" class="main-gagnant container center-part text-center" style="display: none;">
        <div class="gagnant-content">
            <div class="prefix-content">
                <p>Félicitations</p>
                <p>à notre gagnant</p>
            </div>
            <div class="winner">
                <span class="winner-name">
                    <h6>Monsieur</h6> 
                    <h1><?php echo (getWinnerName()); ?></h1>
                </span>
                <p>n° <?php echo (get_option('lottery_number')); ?></p>
            </div>
            <div class="prize">
                <img src="<?php echo (get_option('winner_photo')); ?>" 
                        id="winnerimage"
                    alt="" srcset="">
            </div>
            <div class="text-center" id="editButtId">
                    <button type="submit" class="btn" 
                    onclick="return displayEditInfo()" >Modifier</button>
            </div>
            <div id="editInfoId" style="display:none;">

                <div class="form-group">
                    <input type="text" class="form-control" name="lmail" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="ladresse" placeholder="Adresse">
                </div>

                <div class="text-center">
                    <button type="submit" onclick="return submitWinnerInfo()" class="btn" id="submitButtId">Mettre à jour</button>
                </div>

                <div style="display: none;" >
                    <form method="post" action="<?php echo get_stylesheet_directory_uri() ?>/process_upload.php"
                        enctype="multipart/form-data"  id="submitImage" >
                        Your Photo: <input type="file" name="profilepicture" size="25" id="chooseFile" accept="image/*"/>
                        <input type="submit" name="submitBut" value="SubmitBut" id="submitFile"/>
                    </form>
                </div>
            </div>
        </div>
</div>
