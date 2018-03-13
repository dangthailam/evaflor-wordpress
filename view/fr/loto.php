<div id="ficheloto" style="display: none;">
	<div id="btnParticiple">
		<div class="text-center">
			<button type="submit" class="btn" onclick="return getLotteryPopup()">JE PARTICIPE*</button>
		</div>
		<p class="asterixDescription text-center">* et j'accepte de recevoir les offres Whisky.</p>
	</div>

	<div id="formulaire" style="display: none">
		<div class="form-group">
			<input type="text" class="form-control" id="formName" name="clientNameLottery" placeholder="Nom / Prénom">
		</div>
		<div class="form-group">
			<input type="text" class="form-control" id="formEmail" name="emailLottery" placeholder="Email">
		</div>
		<div class="form-group">
			<input type="text" class="form-control" id="formPostalCode" name="zipCodeLottery" placeholder="Ville / Pays">
		</div>
		<div class="form-group">
			<div class="g-recaptcha" data-sitekey="6LcTBEQUAAAAAIBeuuGulK0WbeZ3ZMVfYDOgNTFk"></div>
		</div>
		<div class="text-center">
			<button type="submit" class="btn" onclick="return saveLotteryClientInfo()">JE PARTICIPE*</button>
		</div>
		<p class="asterixDescription text-center">* et j'accepte de recevoir les offres Whisky.</p>
	</div>
</div>