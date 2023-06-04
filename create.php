<?php
// Inclure le fichier config
require_once "config.php";
 
// Definir les variables
$nom  = $prenom = $contacte = "";
$name_err = $prenom_err = $contacte_err = "";
 
// Traitement
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["nom"]);
    if(empty($input_name)){
        $name_err = "Veillez entrez un nom.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Veillez entrez votre nom .";
    } else{
        $nom = $input_name;
    }
    
    // Validate prenom
    $input_prenom = trim($_POST["prenom"]);
    if(empty($input_prenom)){
        $prenom_err = "Veillez entrez votre prenom.";     
    } else{
        $prenom = $input_prenom;
    }
    
    // Validate contacte
    $input_contacte = trim($_POST["contacte"]);
    if(empty($input_contacte)){
        $contacte_err = "Veillez entrez le contacte.";     
    } elseif(!ctype_digit($input_contacte)){
        $contacte_err = "Veillez entrez une valeur positive.";
    } else{
        $contacte = $input_contacte;
    }
    
    // verifiez les erreurs avant enregistrement
    if(empty($name_err) && empty($prenom_err) && empty($contacte_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO students (nom, prenom, contacte) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind les variables à la requette preparée
            mysqli_stmt_bind_param($stmt, "ssd", $param_nom, $param_prenom, $param_contacte);
            
            // Set parameters
            $param_nom = $nom;
            $param_prenom = $prenom;
            $param_contacte= $contacte;
            
            // executer la requette
            if(mysqli_stmt_execute($stmt)){
                // opération effectuée, retour
                header("location: index.php");
                exit();
            } else{
                echo "Oops! une erreur est survenue.";
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
    <title>Create Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .wrapper{
            width: 700px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Créer un enregistrement</h2>
                    <p>Remplir le formulaire pour enregistrer les aprenants dans la base de données</p>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nom; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>prenom</label>
                            <input type="text" name="prenom" class="form-control <?php echo
                             (!empty($prenom_err)) ? 'is-invalid' : ''; ?>" 
                             value="<?php echo $prenom_err; ?>">
                             <span class="invalid-feedback"><?php echo $prenom_err;?></span>
                        </div>
                        
                        
                        <div class="form-group">
                            <label>contacte</label>
                            <input type="number" name="contacte" class="form-control <?php echo 
                            (!empty($contacte_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $contacte; ?>">
                            <span class="invalid-feedback"><?php echo $contacte_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enregistrer">
                        <a href="index.php" class="btn btn-secondary ml-2">Annuler</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>