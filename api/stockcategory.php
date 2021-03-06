<?php
require 'Slim/Slim.php';
require 'conf.php';
require 'logerrors.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->get('/getstock', function () use ($app) 
{
 	$sql = "select id,name FROM tbl_stockcategory";
    try 
    {
        $db = getConnection();
        $stmt = $db->query($sql);
        $company = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($company);
    } 
    catch(PDOException $e) 
    		{
    			echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
});

 // GET route with parameter
$app->get('/stockdetails/:id', function ($id) use ($app) {
 
   $sql = "select `id`,`name`, `address`, `createdby`, `createdon`, `modifiedby`, `modifiedon` from `tbl_stockcategory` WHERE id=:id";
    try {
        $conn = getConnection();
		$stmt = $conn->prepare($sql);
		$stmt->bindParam('id',$id);
		
    	$stmt->execute();    
        $company = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($company);
    } catch(PDOException $e) {
        logerrors::writelog('stockdetails','api/stock.php/stockdetails/id',$e->getMessage());
		$app->response()->header('Content-Type', 'application/json');
		echo json_encode('-1');
		return;
    }
});
 


// POST route
$app->post('/addstockcategory', function () use ($app) {
	
	$request = (array) json_decode($app->request()->getBody());
	//var_dump($request);
try{
	$createdby=1;//$_SESSION["createdby"];
	$modifiedby=1;//$_SESSION["modifiedby"];
	$name=$request["name"];
	$alias=$request["alias"];
	$companyid=$request["companyid"];
	}
catch (Exception $e) 
	{
	logerrors:: writelog('addstockcategory','api/stockcategory.php',$e->getMessage());
	$app->response()->header('Content-Type', 'application/json');
	echo json_encode('-1');
	return;
	}
try
	{
	$conn=getConnection();
	$sql= "insert into tbl_stockcategory(name,alias,companyid,createdby,createdon,modifiedby,modifiedon) values (:name,:alias,:companyid,:createdby,CURRENT_TIMESTAMP(),:modifiedby,CURRENT_TIMESTAMP())";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('name',$name);
	$stmt->bindParam('alias',$alias);
	$stmt->bindParam('companyid',$companyid);
	$stmt->bindParam('createdby',$createdby);
	$stmt->bindParam('modifiedby',$modifiedby);
	$stmt->execute();			
	
	if($stmt->errorCode() == 0) {
		$insert_id = $conn->lastInsertId();
	} else {
		$insert_id=-1;
		$errors = $stmt->errorInfo();
		//echo($errors[2]);
		}
	}
catch (PDOException $e) 
	{
	logerrors::writelog('addstockcategory','api/stockcategory.php',$e->getMessage().$name.$alias.$companyid.'*');
	$app->response()->header('Content-Type', 'application/json');
	echo json_encode('-1');
	return;
}
echo json_encode($insert_id);
});

// PUT route
$app->put('/updatestock/:id', function ($id) use ($app) {
	$request = (array) json_decode($app->request()->getBody());
try{
	$name=$request["name"];
	$alias=$request["alias"];
}
catch (Exception $e) 
	{
	logerrors::writelog('updatestock','api/stock.php',$e->getMessage());
	$app->response()->header('Content-Type', 'application/json');
	echo json_encode('-1');
	return;
	}
try{

	$sql="UPDATE tbl_stockcategory SET name=:name,alias=:alias,modifiedby=:modifiedby,modifiedon=CURRENT_TIMESTAMP() where id=:id";

	$conn = getConnection();
	$stmt = $conn->prepare($sql);

	$stmt->bindParam('name',$name);
	$stmt->bindParam('alias',$alias);
	$stmt->bindParam('modifiedby',$modifiedby);
	$stmt->bindParam('id',$id);
	$stmt->execute();

	if($stmt->errorCode() == 0) 
		{
		$app->response()->header('Content-Type', 'application/json');
		echo json_encode($id);
		return;
		} 
	}
	catch (PDOException $e) 
	{
	logerrors::writelog('updatestock2','api/stock.php',$e->getMessage());
	$app->response()->header('Content-Type', 'application/json');
	echo json_encode('-1');
	return;
	}
});

/*$app->put('/company/:id', function () use ($app) {

	$request = (array) json_decode($app->request()->getBody());
	
	// use $request['id'] to update database based on id and create response...
	
	$app->response()->header('Content-Type', 'application/json');
	echo json_encode($request);
	
});*/

// DELETE route
$app->delete('/deletestock/:id', function ($id) use ($app) {
	$request = (array) json_decode($app->request()->getBody());

try{
	
	$sql="DELETE FROM tbl_stockcategory WHERE id=:id";

	$conn = getConnection();
	$stmt = $conn->prepare($sql);

	$stmt->bindParam('id',$id);
	$stmt->execute();

	if($stmt->errorCode() == 0) 
		{
		$app->response()->header('Content-Type', 'application/json');
		echo json_encode($id);
		return;
		} 
	}
	catch (PDOException $e) 
	{
	logerrors::writelog('deletestock','api/stock.php',$e->getMessage());
	$app->response()->header('Content-Type', 'application/json');
	echo json_encode('-1');
	return;
	}
});


$app->run();
?>

<!--$app->delete('/company/:id', function () use ($app) {

	$request = (array) json_decode($app->request()->getBody());	
	
	//use $request['id'] to remove database entry based on id...
	
	$app->response()->header('Content-Type', 'application/json');
	echo json_encode($request);
});

$app->run();-->

