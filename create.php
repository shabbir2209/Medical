<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $surname = $id_number = $address = $med_description = "";
$name_err = $surname_err = $id_number_err = $address_err = $med_description_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate surname
    $input_surname = trim($_POST["surname"]);
    if(empty($input_surname)){
        $surname_err = "Please enter a surname.";
    } elseif(!filter_var($input_surname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $surname = $input_surname;
    }

     // Validate ID Number
     $input_id_number = trim($_POST["id_number"]);
     if(empty($input_id_number)){
         $id_number_err = "Please enter patients ID NUM/PASSPORT NUM.";     
     } else{
         $id_number = $input_id_number;
     }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = 'Please enter an address';     
    } else{
        $address = $input_address;
    }

     // Validate medical description
     $input_med_description = trim($_POST["med_description"]);
    if(empty($input_med_description)){
        $med_description_err = 'Please enter an description.';     
    } else{
        $med_description = $input_med_description;
    }
    
   
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($surname_err) && empty($id_number_err) && empty($address_err) && empty($med_description_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO patients (name, surname, id_number, address, med_description) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_surname, $param_id_number, $param_address ,$param_med_description);
            
            // Set parameters
            $param_name = $name;
            $param_surname = $surname;
            $param_id_number = $id_number;
            $param_address = $address;
            $param_med_description = $med_description;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
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
    <title>Create Patient Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Insert Patient Details Here</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- name input -->
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                    <!-- surname input -->
                        <div class="form-group <?php echo (!empty($surname_err)) ? 'has-error' : ''; ?>">
                            <label>Surname</label>
                            <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
                            <span class="help-block"><?php echo $surname_err;?></span>
                        </div>
                    <!-- id number -->
                        <div class="form-group <?php echo (!empty($id_number_err)) ? 'has-error' : ''; ?>">
                            <label>ID Number / Passport Number</label>
                            <input type="text" name="id_number" class="form-control" value="<?php echo $id_number; ?>">
                            <span class="help-block"><?php echo $id_number_err;?></span>
                        </div>
                    <!-- address -->
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                    <!-- medical description -->
                    <div class="form-group <?php echo (!empty($med_description_err)) ? 'has-error' : ''; ?>">
                            <label>Medical Description</label>
                            <textarea name="med_description" class="form-control"><?php echo $med_description; ?></textarea>
                            <span class="help-block"><?php echo $med_description_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>