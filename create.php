<?php
// Include config file
require_once "dbinfo.php";
 
// Define variables and initialize with empty values
$firstName = $lastName = $email = $contact = $position = "";
$firstName_err = $lastName_err = $email_err = $contact_err = $position_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_firstName = trim($_POST["firstName"]);
    if(empty($input_firstName)){
        $firstName_err = "Please enter a first name.";
    } elseif(!filter_var($input_firstName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstName_err = "Please enter a valid first name.";
    } else{
        $firstName = $input_firstName;
    }
    
    $input_lastName = trim($_POST["lastName"]);
    if(empty($input_lastName)){
        $lastName_err = "Please enter a last name.";
    } elseif(!filter_var($input_lastName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $lastName_err = "Please enter a valid last name.";
    } else{
        $lastName = $input_lastName;
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    $input_email2 = filter_var($input_email, FILTER_SANITIZE_EMAIL);
    if (empty($input_email2)) {
        $email_err = "Please enter email.";
    } elseif (filter_var($input_email2, FILTER_VALIDATE_EMAIL)){
        $email = $input_email2;
    } else {
        $email_err = "Email is not a valid email address";
    }
  
    // Validate contact
    $input_contact = trim($_POST["contact"]);
    if(empty($input_contact)){
        $contact_err = "Please enter the phone contact.";     
    } elseif(!ctype_digit($input_contact)){
        $contact_err = "Please enter a positive integer value.";
    } else{
        $contact = $input_contact;
    }
    
    // Validate position
    $input_position = trim($_POST["position"]);
    if(empty($input_position)){
        $position_err = "Please enter the position.";     
    } else{
        $position = $input_position;
    }
    
    // Check input errors before inserting in database
    if(empty($firstName_err) && empty($lastName_err) && empty($email_err) && empty($contact_err) && empty($position_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (firstName, lastName, email, contact, position) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssis", $param_firstName, $param_lastName, $param_email, $param_contact, $param_position);
            
            // Set parameters
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_email = $email;
            $param_contact = $contact;
            $param_position = $position;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="firstName" class="form-control <?php echo (!empty($firstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>">
                            <span class="invalid-feedback"><?php echo $firstName_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="lastName" class="form-control <?php echo (!empty($lastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>">
                            <span class="invalid-feedback"><?php echo $lastName_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Contact</label>
                            <input type="text" name="contact" class="form-control <?php echo (!empty($contact_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $contact; ?>">
                            <span class="invalid-feedback"><?php echo $contact_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" name="position" class="form-control <?php echo (!empty($position_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $position; ?>">
                            <span class="invalid-feedback"><?php echo $position_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>