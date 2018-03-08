window.mobileAndTabletcheck = function () {
	var check = false;
	(function (a) {
		if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
	})(navigator.userAgent || navigator.vendor || window.opera);
	return check;
};

$(function () {
	if (!window.mobileAndTabletcheck()) {
		$("body").html("<h1 style='color: #000;'>We cannot display content on desktop's browser</h1>");
		return false;
	}

	$(".navbar-toggler").click(function () {
		$("nav.navbar").toggleClass("navbar-opened");
		if ($("nav.navbar").hasClass("navbar-opened")) {
			$(".languages-container").hide();
		} else {
			$(".languages-container").show();
		}
	});
})

var clientIp = '';

function getParametters(param) {
	var vars = {};
	window.location.href.replace(location.hash, '').replace(
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function (m, key, value) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if (param) {
		return vars[param] ? vars[param] : null;
	}
	return vars;
}

function setUpIndexPage() {
	// Disable all divs
	disableDivContent('ficheproduit');

	disableDivContent('homepage');

	disableDivContent('fichegagnant');

	disableDivContent('ficheloto');

	disableDivContent('carre');

	disableDivContent('carreLocation');

	disableDivContent('quizz');

	disableDivContent('ficheproduiterreur');

	disableDivContent('ficheproduitexpire');

	hideShareButton();
}

function enableDivContent(divId) {
	$("#" + divId).show();
}

function disableDivContent(divId) {
	$("#" + divId).hide();
}

function showProductAuthenticationView() {
	enableDivContent('ficheproduit');
}

function showLotoView() {
	enableDivContent('carre');
	enableDivContent('ficheloto');
}

function showCarreView() {
	enableDivContent('carre');
}

function showQuizView() {
	console.log('enable quizz');
	enableDivContent('quizz');
}

function showGagnantView() {
	enableDivContent('fichegagnant');
	showShareButton();
}

function showGagnantView() {
	enableDivContent('fichegagnant');
	showShareButton();
}

function showErrorPage() {
	enableDivContent('ficheproduiterreur');
}

function showExpiredProductPage() {
	enableDivContent('ficheproduitexpire');
}

function showCarreLocation() {
	enableDivContent('carreLocation');
}

function showHomePage() {
	enableDivContent('homepage');
}

function showShareButton() {
	$(".a2a_kit").show();
	$("#social").hide();
}

function hideShareButton() {
	$(".a2a_kit").hide();
	$("#social").show();
}

function getGeoLocationFromBrowser() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(savePosition, positionError);
	} else {
		//Geolocation is not supported by this browser
		console.log("Error in getting user location from navigator");
	}
}

// handle the error here
function positionError(error) {
	// When user dening their location
	var action = 'saveTemporalUserLocation';

	var userLocation = {};
	userLocation.lat = "denied";
	userLocation.lon = "denied";
	var locStr = JSON.stringify(userLocation);

	var data = {
		'action': action,
		dataType: 'json',
		'loc': locStr
	};

	jQuery.post(ajax_object.ajax_url, data, function (response) {
		console.log("User denies sharing location: " + response);
	});
}

function savePosition(position) {
	var userLocation = {};
	userLocation.lat = position.coords.latitude;
	userLocation.lon = position.coords.longitude;
	var locStr = JSON.stringify(userLocation);

	// Saving user's location to DB
	var action = 'saveTemporalUserLocation';
	var data = {
		'action': action,
		dataType: 'json',
		'loc': locStr
	};

	jQuery.post(ajax_object.ajax_url, data, function (response) {
		console.log("Saving user's location is :" + response);
	});
}

jQuery(document).ready(function ($) {

	// Ask for user's location
	getGeoLocationFromBrowser();

	var totalUrl = window.location.href;
	// Get variable value from POST request
	var operationId = getParametters('op');
	var prdOp = getParametters('prd');
	var action;
	if (operationId && prdOp) {
		switch (operationId) {
			case 'wn':
				console.log("DEBUG: Winner page");
				action = 'outputWinnerPage';
				break;
			default:
				action = 'outputErrorPage';
				break;
		}
	} else {
		action = 'scanningProduct';
		prdOp = totalUrl;
	}

	var data = {
		'action': action,
		'prd': prdOp,
	};
	setUpIndexPage();

	jQuery.post(ajax_object.ajax_url, data, function (response) {
		console.log("DEBUG: response = " + response);
		var err = 'error';
		var hp = 'homepage';
		var produitExpired = 'invalid';
		var errLoc = 'error location';
		if (response.includes(hp)) {
			console.log("DEBUG: prepare to show homepage");
			showHomePage();
			// Checking the cookies, if yes, output the loto/survey view: TBD
			if (isValidProductScannedByClient() == 1) {
				console.log('DEBUG: client has scanned an authentic product');
				// Show loto view
				showLotoView();
			} else {
				showCarreView();
			}
		} else if (response.includes(errLoc)) {
			showErrorPage();
			showCarreView();
			showCarreLocation();
		} else if (!response.includes(err)) {
			if (operationId === 'wn') {
				showGagnantView();
			} else if (response.includes(produitExpired)) {
				showExpiredProductPage();
				$('#produitExp').html((response.split(produitExpired))[1]);
				showLotoView();
			} else {
				console.log("DEBUG: Before showing authentication view");
				showProductAuthenticationView();
				$('#produit').html(response);
				// Writing cookies
				writeCookies();

				var event = null;

				if (vac_options.enable_lottery == 1) {
					event = 'loto';
				}
				if (vac_options.enable_survey == 1) {
					event = 'quiz';
				}

				/* set up quizz */
				setUpPopUp(vac_options.timeout_popup, event);

			}

			// Saving cookies in the client side: TBD

		} else {
			// Redirect to error page
			showErrorPage();
			showLotoView();
			console.log("DEBUG: " + response);
		}
	});

	// Rewrite the url
	//window.history.pushState("Evaflor", "Product Authentification", "/");	

});


//-----------------------------------------------------------------------------------
// Set up popup
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function setUpPopUp(timeout, event) {
	setTimeout(function () {
		$("#popup_loto_quizz").modal({
			keyboard: false,
			backdrop: 'static'
		});

		$("#accept_btn").click(function () {
			$("#popup_loto_quizz").modal('hide');

			if (event === 'loto') {
				showLotoView();
				$("#main").animate({
					scrollTop: $('#carre').offset().top - 50
				}, 500);
			} else if (event === 'quiz') {
				showQuizView();
				$("#main").animate({
					scrollTop: $('#quizz').offset().top - 50
				}, 500);

				/* set up quizz */
				setUpQuizz();
			}
		});
	}, timeout);
}

//-----------------------------------------------------------------------------------
// Set up Quizz
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------

function setUpQuizz() {
	//
	var $quizzForm = $("#quizForm1");
	var questionCount = $quizzForm.find('.quizz-question').length;
	$quizzForm.find('.quizz-question').hide();
	$quizzForm.find('.quiz_end').hide();
	$quizzForm.find('.quiz_begin').show();
	var $firstQuestion = $quizzForm.find('.question-section-id-1');
	$firstQuestion.show();

	$('.quizz-navigation-btn').click(function (e) {
		var questionId = $(this).parent().attr("id").split('-')[1];
		questionId = parseInt(questionId);
		$quizzForm.find('.quizz-question').hide();
		var destinationQuestionId = 0;
		if ($(this).hasClass('navigation-prev')) {
			destinationQuestionId = questionId - 1;
			$quizzForm.find('.quiz_end').hide();
		} else {
			destinationQuestionId = questionId + 1;
			if (questionId + 1 === questionCount)
				$quizzForm.find('.quiz_end').show();
		}
		$quizzForm.find('.question-section-id-' + destinationQuestionId).show();
		var main = document.getElementById('main');
		$("#main").animate({
			scrollTop: main.scrollHeight - window.innerHeight + $('.quiz_begin').offset().top
		}, 500);
	});
}




//-----------------------------------------------------------------------------------
// Lottery management
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
function getLotteryPopup() {
	var max = 10000;
	var min = 1000;
	var nbLottery = Math.floor(Math.random() * (max - min + 1)) + min;
	console.log("DEBUG: nbLottery = " + nbLottery);
	$('#nbLottery').html(nbLottery);
	$("#lotteryPopup").modal();

	return false;
}

function displayEditInfo() {
	var isDisplay = $('#editInfoId').css('display');
	$('#editButtId').css('display', 'none');
	console.log(isDisplay);

	$('#winnerimage').click(function() {
		console.log('choosing image file');
		$("#chooseFile").click();
	});
	
	$("#chooseFile").change(function(e) {
		var input = this;
		if(fileSize(input)){
			readURL(input);
		}
		//$("#submitImage").submit();
	});

	function fileSize(input) {
		if(input.files && input.files[0]) {
			var size = ((input.files[0].size/1024)/1024).toFixed(4); // MB

			if(size > 2) {
				alert('file size is bigger than 2Mo');
				return false;
			}
		}
		return true;
	}
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#winnerimage').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	if(isDisplay === 'none') {
		$('#editInfoId').css('display', '');
		return false;
	}
	$('#editInfoId').css('display', 'none');

	return false;
}

function submitWinnerInfo() {
	var input = document.getElementById('chooseFile');
	
	//submit email, address
	var email = document.getElementsByName("lmail")[0].value;
	var address = document.getElementsByName("ladresse")[0].value;

	var xmlhttp = new XMLHttpRequest();
	var url = "http://localhost/wordpress/" +"wp-json/api/winner";
	xmlhttp.open("POST", url);

	xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	xmlhttp.onreadystatechange = function() {
		console.log(xmlhttp.response);
	};
	xmlhttp.send(JSON.stringify({email: email, address: address}));

	if(input.files && input.files[0]){
		$("#submitImage").submit();
	}
	$('#editInfoId').css('display', 'none');
}

function getLotteryFormula() {
	document.getElementById("btnParticiple").style.display = 'none';
	$('#lotteryPopup').modal('hide');
	document.getElementById("formulaire").style.display = '';
	return false;
}

function saveLocationScanInvalid() {
	var city = document.getElementsByName("cityScan")[0].value;
	var country = document.getElementsByName("countryScan")[0].value;
	var info = {
		'cs': city,
		'cts': country
	};
	var data = {
		'action': 'saveLocationScanInvalid',
		dataType: 'json',
		'json': JSON.stringify(info)
	};
	// Trigger the ajax event
	jQuery.post(ajax_object.ajax_url, data, function (response) {
		if (response.includes('success')) {
			setUpIndexPage();
			showHomePage();
			showLotoView();
		} else {
			alert("Erreur! L'enregistrement de la location a échoué!");
		}
	});

}

function saveLotteryClientInfo() {
	// Saving client info to DB
	var clientName = document.getElementsByName("clientNameLottery")[0].value;
	var email = document.getElementsByName("emailLottery")[0].value;
	var zipCode = document.getElementsByName("zipCodeLottery")[0].value;
	var lotteryNumber = document.getElementById("nbLottery").innerHTML;
	//Get current date for instance
	var lotteryDate = vac_options.choose_lottery;

	console.log("DEBUG: cliet Name = " + clientName);
	console.log("DEBUG: email = " + email);
	console.log("DEBUG: zip Code = " + zipCode);
	console.log("DEBUG: lottery number = " + lotteryNumber);
	console.log("DEBUG: lottery date = " + lotteryDate);

	var ltInfo = {
		'cn': clientName,
		'em': email,
		'zc': zipCode,
		'ln': lotteryNumber,
		'ld': lotteryDate
	};
	var data = {
		'action': 'saveLotteryInfo',
		dataType: 'json',
		'json': JSON.stringify(ltInfo)
	};
	// Trigger the ajax event
	jQuery.post(ajax_object.ajax_url, data, function (response) {
		console.log("DEBUG: response when saving loto result: " + response);
		if (response == 'success') {
			alert("Félicitations! Votre numéro de tirage au sort a été enregistré avec succès!");
			// Redirect to the evaflor homepage
			window.location.replace("https://www.evaflor.com");

		} else {
			alert("Erreur! L'enregistrement de votre numéro de tirage au sort a échoué!");
		}
	});

}

// Get current date in format yyyy-mm-dd
function getCurrentDate() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; //January is 0!
	var yyyy = today.getFullYear();

	if (dd < 10) {
		dd = '0' + dd
	}

	if (mm < 10) {
		mm = '0' + mm
	}

	today = yyyy + '-' + mm + '-' + dd;
	return today;
}

// Cokie management
// Structure
// { createdDate: date1, lastModifiedDate: date2, successfullScanedProduct: [productId1, productId2]}

function writeCookies() {
	console.log('DEBUG: Writing cookies ...');

	var today = new Date();
	var date = today.getTime();
	var successfullScanedProduct = [];
	var productId = window.location.pathname.replace('/', '');
	console.log('DEBUG: Product Id = ' + productId);
	var prefix = "evaflorPF";
	var suffix = "evaflorSF";
	// struct cookie
	// { createdDate: date1, lastModifiedDate: date2, successfullScanedProduct: [productId1, productId2]}
	var obj;
	var cookie = document.cookie;
	console.log(cookie);
	if (cookie.indexOf(prefix) != -1) {
		var value = cookie.substring(cookie.indexOf(prefix) + 9, cookie.indexOf(suffix));
		console.log(value);
		obj = JSON.parse(value);
		obj.successfullScanedProduct.push(productId);
		obj.lastProductId = productId;
		obj.lastModifiedDate = date;
	} else {
		successfullScanedProduct.push(productId);
		obj = {
			createdDate: date,
			lastModifiedDate: date,
			successfullScanedProduct: successfullScanedProduct
		};
	}
	document.cookie = prefix + JSON.stringify(obj) + suffix;
}

// Verify if the valid scanned product is still valid to let the client go directly to the loto/game page
function isValidProductScannedByClient() {
	var cookies = getCookies();
	console.log(cookies);
	if (cookies !== "NO_COOKIES" && cookies.lastProductId !== "test") {

		var scannedDate = cookies.lastModifiedDate;
		console.log(scannedDate);
		var today = new Date();
		var timeDiff = Math.abs(parseInt(today.getTime()) - parseInt(scannedDate));
		// time different in days between last successful scan and now
		var dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
		var validTimeLap = vac_options.validation_period;

		console.log("DEBUG: daydiff " + dayDiff + ' timelap' + validTimeLap);
		if (dayDiff < validTimeLap)
			return 1;
	}
	console.log("DEBUG: returning 0....");
	return 0;
}

function getCookies() {
	// struct cookie
	// { createdDate: date1, lastModifiedDate: date2, successfullScanedProduct: [productId1, productId2]}
	console.log('DEBUG: Get cookie ...');
	var prefix = "evaflorPF";
	var suffix = "evaflorSF";
	var cookie = document.cookie;
	var obj;
	if (cookie.indexOf(prefix) != -1) {
		var value = cookie.substring(cookie.indexOf(prefix) + 9, cookie.indexOf(suffix));
		console.log(value);
		obj = JSON.parse(value);
		console.log("DEBUG: COOKIES = " + JSON.stringify(obj));
		return obj;
	}
	return "NO_COOKIES";
}