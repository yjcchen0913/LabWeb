<?php
// Include config file
require_once 'config.php';
$username = $password = $confirm_password = "";
//$date = $title = $journal = $author = $reporter = $classification = "";
$date_err = $title_err = $journal_err = $author_err = $reporter_err = $classification_err = $pptname_err = ""; 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["date"]))){
        $date_err = "Please enter a date.";
    }
    elseif (empty(trim($_POST["journal"]))) {
        $journal_err = "Please enter a journal.";
    }
    elseif (empty(trim($_POST["author"]))) {
        $author_err = "Please enter a author.";
    }
    elseif (empty(trim($_POST["reporter"]))) {
        $reporter_err = "Please enter a reporter.";
    }
    elseif (empty(trim($_POST["classification"]))) {
        $classification_err = "Please enter a classification.";
    }

   
    // Check input errors before inserting in database
    if(empty($date_err)& empty($journal_err)&& empty($author_err)&& empty($reporter_err)&& empty($classification_err)){

        $sql = "INSERT INTO thesis (reportday,title,journal,author,reporter,classification, download) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssd", $date,$title,$journal,$author,$reporter,$classification,$download);
             
            session_start();
            // Set parameters
            $date = trim($_POST["date"]);
            $title = $_SESSION["filename"];
            $journal = trim($_POST["journal"]);
            $author = trim($_POST["author"]);
            $reporter = trim($_POST["reporter"]);
            $classification = trim($_POST["classification"]);
            $download = 0;
            session_destroy();
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
             
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>

<style>
    body,html{
		margin: 0;
        background-image: url("./img/ccu_008.jpg");
        background-repeat:no-repeat;
        background-size:cover;
		background-color: black;
	    color: White;
	}
</style>

<body>
    <a href="login.php" class="btn btn-danger">Thesis</a>
    <div class="wrapper">
        <h2>投影片上傳</h2>
        <p>請輸入下列表格內容.</p>
        <form action="uploadppt.php" method="post" enctype="multipart/form-data">
            <label>Select PPT to upload:</label>
            <input type="file" name="file" id="fileToUpload"></br>
            <input type="submit" class="btn btn-default" value="Upload PPT" name="submitppt">
        </form>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
                <label>報告日期(格式為yyyy-mm-dd)</label>
                <input type="text" name="date"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $date_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                <label>論文標題</label>
                <input type="text" name="title" class="form-control" value="<?php session_start();if(!empty($_SESSION["filename"])):echo $_SESSION["filename"];?>
                <?php endif; ?>
                ">
                <span class="help-block"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($journal_err)) ? 'has-error' : ''; ?>">
                <label>論文期刊</label>
                <input type="text" name="journal" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $journal_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($author_err)) ? 'has-error' : ''; ?>">
                <label>論文作者</label>
                <input type="text" name="author" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $author_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($reporter_err)) ? 'has-error' : ''; ?>">
                <label>報告者</label>
                <input type="text" name="reporter" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $reporter_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($classification_err)) ? 'has-error' : ''; ?>">
                <label>分類</label>
                <input type="text" name="classification" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $classification_err; ?></span>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>