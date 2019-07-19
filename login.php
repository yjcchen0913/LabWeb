<?php
// Include config file
require_once 'config.php';
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
		
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;
							/*$cookie_username = $username; 
							setcookie($cookie_username, time() + (86400 * 30), "/");*/
                            header("location: upload.php");
                        }else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                }else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        //mysqli_stmt_close($stmt);
    }
    
    // Close connection
    //mysqli_close($link);
}
?>
<?php
require_once 'config.php';
$sql = 'SELECT id ,reportday, title,journal,author,reporter,classification, download FROM thesis ORDER BY id desc';
$result = mysqli_query($link,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<style type="text/css">
	body,html{
		margin: 0;
        background-image: url("./img/4.png");
        background-repeat:no-repeat;
        background-size:cover;
		background-color: black;
	    color: White;
	}
	body{
		padding: 0px 30px;
		padding-bottom: 40px; 
	}

	#page_title div{
	display: inline-block;
	}
	#page_title .a{
		font-size: 32px;
		font-weight: bold;
		margin: 16px 16px 16px 0px;


	}
	#search{
	    margin: 20px 0 5px 0;
	}
	#report_list th{
		background-color: #white; 
		color: #FFFFFF;
		padding: 5px 0px;
	}
	.a_report_data td{
        padding: 10px;
	}
	.p_title{
		font-weight: bold;
	}
	.submit{
        margin-top: 5px; 
	}
	a{
		text-decoration: none;
	}
	#search_text{
		width: 30%;
		min-width: 200px;
	}
	.spx{
        background-color: #DC8516;
        border-style: solid;
        border-width: 0 2px 2px 0;
        border-color: #C67A18;
	}
	.spx2{
		padding: 3px;
        font-weight: bold;
	    margin-right: 20px;
	}
	.a_report_data td{
		border-style: solid;
        border-width: 0 0 2px 0;
        border-color: #888888;
	}
	#page_ctrl{
		padding: 5px;
	}
    #page_ctrl .Prev_page{
		float: left;
		visibility: hidden;
	}
	#page_ctrl .Next_page{

		visibility: hidden;
	}

</style>

<body>
    <a href="index.html" class="btn btn-danger">首頁</a>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username"class="form-control" value="<?php echo $username;?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>

    <div id="report_list">
    <table width="100%" border="1" cellspacing="3" bordercolor="#FFFFFF" cellpadding="3"><tr><td>
	
    <table width="100%">
    <tr>
	    <th style="width:100px;">報告日期</th>
	    <th>論文標題</th>
	    <th>論文期刊</th>
	    <th>論文作者</th>

	    <th style="width:80px;">報告者</th>
	    <th style="width:60px;">分類</th>
	    <th style="width:80px;">下載次數</th>
    </tr>
    <?php while( $row = mysqli_fetch_assoc($result) ) : ?>
    <tr class="a_report_data">
        <td><?php echo $row['reportday']; ?></td>
        <td>
            <a href="download.php?<?php echo $row['id'];?>" class="p_title" style="color: white"><?php echo $row['title']; ?></a>
        </td>
        <td><?php echo $row['journal']; ?></td>
        <td><?php echo $row['author']; ?></td>
        <td><?php echo $row['reporter']; ?></td>
        <td><?php echo $row['classification']; ?></td>
        <td><?php echo $row['download']; ?></td>
    </tr>
    <?php endwhile ?>
    </table>
    </div>
    
</body>
</html>