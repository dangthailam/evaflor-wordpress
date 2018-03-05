 <?php
    header('Content-Type: application/json');
	
	//include database and object files
	include_once './database.php';
	include_once './lottery.php';
	
	//instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();

	//initialize object
	$lotteryInst = new Lottery($db);
	
    $aResult = array();
	

    if( !isset($_POST['functionName']) ) { $aResult['error'] = 'No function name!   '; }

    if( !isset($_POST['cn']) ) { $aResult['error'] += 'No function argument: clientName!   '; }
	if( !isset($_POST['em']) ) { $aResult['error'] += 'No function argument: email!  '; }
	if( !isset($_POST['zc']) ) { $aResult['error'] += 'No function argument: zipCode!  '; }
	if( !isset($_POST['ln']) ) { $aResult['error'] += 'No function argument: lotteryNumber!  '; }
	if( !isset($_POST['ld']) ) { $aResult['error'] += 'No function argument: lotteryDate!  '; }
	
    if( !isset($aResult['error']) ) {

        switch($_POST['functionName']) {
            case 'saveLotteryData':
               $lotteryInst->prepareDataToInsert($_POST['cn'], $_POST['em'], $_POST['zc'], $_POST['ln'], $_POST['ld']);
               $aResult['result'] = $lotteryInst->saveLotteryInfo();
               break;

            default:
               $aResult['error'] = 'Not found function '.$_POST['functionName'].'!';
               break;
        }

    }

    echo json_encode($aResult);

?>