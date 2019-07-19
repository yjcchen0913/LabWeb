<?php
// Include the database configuration file
require_once 'config.php';
$statusMsg = '';

// File upload path
$targetDir = "uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
$image = "";
if(isset($_POST["submitppt"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('pptx','pptm','ppt','pdf');
    if(in_array($fileType, $allowTypes))
	{
        
        //move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath);
       

        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
		{
            
			/*$query = "INSERT into thesis (title) VALUES ('".$fileName."')";
			mysqli_query($link,$query);*/
			
			/*echo "您所上傳的檔案已成功新增置資料庫 <br>";
			echo "檔案名稱:",$fileName;
			echo "<br>";
			//$results = mysqli_query($link,"select MAX(id) from message");
			//$maxId = mysql_result($results,0);
			//echo $results;
			$sql = "select image from message ORDER BY id DESC LIMIT 1";
			$result = mysqli_query($link,$sql);
			$row = mysqli_fetch_array($result);

			$image = $row['image'];
            $image_src = "uploads/".$image;*/

            /*$statusMsg = "finish.";
            echo "<script>alert('$statusMsg');
            location.href = 'upload.php';
            </script>";*/
            session_start();
            $_SESSION['filename'] = $fileName;
            header("Location: upload.php?$fileName");
            


        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
            echo "<script>alert('$statusMsg');
            //location.href = 'upload.php';
            </script>";
        }
    }else{
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
        echo "<script>alert('$statusMsg');
        //location.href = 'upload.php';
        </script>";
    }
}else{
    $statusMsg = 'Please select a file to upload.';
    echo "<script>alert('$statusMsg');
    //location.href = 'upload.php';
    </script>";
	
}

// Display status message
echo $statusMsg;
?>