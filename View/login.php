<?php
if (isset($_SESSION['client'])) {
    $_SESSION['client'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <!-- login bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <!-- login form centered in the middle of the page -->
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-sm-8 col-md-6 col-lg-4">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Se connecter</h4>
                <form action="" method="post">
                <span class="text-danger"><?php echo $error; ?></span>
                <div class="form-group">
                  <label for="username-input">Nom d'utilisateur :</label>
                  <input type="text" name="username" class="form-control"/>
                </div>
                <div class="form-group">
                  <label for="password-input">Mot de passe :</label>
                  <input type="password" name="password" class="form-control"/>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
                <br/>
                <!-- continuer comme guest submit without post -->
                </form>
                <a  class="btn btn-secondry" href="../articles/ ">Continuer comme guest</a>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>


