<?php
	try{
		$conn = new MongoDB\Driver\Manager("mongodb+srv://projbanmilena:S3nh45123@projetoban2udesc.fhhuj0w.mongodb.net/test");
	} catch (MongoDBDriverExceptionException $e) {
		echo 'Failed to connect to MongoDB, is the service intalled and running?<br /><br />';
		echo $e->getMessage();
		exit();
	}

	$cmd = new MongoDB\Driver\Command(['listDatabases' => 1]);
	try {
		$result = $conn->executeCommand('admin', $cmd);
		$dbArray = $result->toArray()[0];
	} catch(MongoDB\Driver\Exception $e) {
		echo $e->getMessage().'<br />';
		exit;
	}


?>