<div id="ficheloto" style="display: none;">

<div id="btnParticiple">

<div class="text-center">
	<button type="submit" class="btn" onclick="return getLotteryPopup()">I AGREE*</button>
</div>

<p class="asterixDescription text-center">* and I accept to receive more offers from Evaflor.</p>

</div>

<div id="formulaire" style="display: none">

<div class="form-group">
	<input type="text" class="form-control" id="formName" name="clientNameLottery" placeholder="Firstname / Lastname">
</div>
<div class="form-group">
	<input type="text" class="form-control" id="formEmail" name="emailLottery" placeholder="Email">
</div>
<div class="form-group">
	<input type="text" class="form-control" id="formPostalCode" name="zipCodeLottery" placeholder="Zip code">
</div>

<div class="text-center">
	<button type="submit" class="btn" onclick="return saveLotteryClientInfo()" >I AGREE*</button>
</div>

<p class="asterixDescription text-center">* and I accept to receive more offers from Evaflor.</p>
</div>

<div id="lotteryPopup" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title text-primary">Votre numéro pour le tirage au sort</h2>
			  </div>
			  <div class="modal-body text-center" >
				<h4 id="nbLottery" style="color:#FF4500"></h4>
			  </div>
			  <div class="modal-footer" >
			    <span class="form-control-static pull-left text-primary"> Voulez-vous enregistrer ce numéro pour le prochain tirage au sort?</span>
				<button type="button" class="btn btn-primary" onclick="return getLotteryFormula()">Oui</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
			  </div>
			</div>

		  </div>
</div>
 
 </div>