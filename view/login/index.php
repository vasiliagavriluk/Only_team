<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Авторизация</title>

    
    <!-- Theme style -->
    <script src="js/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style>
.form-signin 
{
  max-width: 400px;
  padding: 19px 29px 29px;
          padding-top: 40px;
  margin: 200px auto 0px auto;
  background-color: #fff;
  border: 1px solid #e5e5e5;
  -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;
  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
     -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
          box-shadow: 0 1px 2px rgba(0,0,0,.05);
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin input[type="text"],
.form-signin input[type="login"],
.form-signin input[type="password"]
{
  font-size: 16px;
  height: auto;
  margin-bottom: 15px;
  padding: 7px 9px;
}
	  
.form-signin-heading
{
    margin-top: 10px;
}
	
.fon{
    background-color: #fff;
	
    -moz-background-size: 100%; /* Firefox 3.6+ */
    -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
    -o-background-size: 100%; /* Opera 9.6+ */
    background-size: 100%; /* Современные браузеры */
	}
.login-logo
{ 
    color: #fff; font-family: 'oD7s698j';
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

.btn {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    border-radius: 0.25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}


.btn-primary {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.has-feedback
{
    padding: 0px 0px 15px 0px;
}
</style>
	
</head>

<body class="form-signin fon">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="login-box-body">
      <p class="form-signin-heading"><div id="error">Авторизуйтесь, чтобы начать сеанс</div></p>

      <div class="form-group has-feedback">
          <input id="login" type="login" class="form-control" placeholder="Телефон/Email">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
          <input id="password" type="password" class="form-control" placeholder="Пароль">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
	  
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
            <div class="g-recaptcha" data-sitekey="6LdrFVIjAAAAAIGDLEe4dCxRX7Gv2rTcN3UTsmg-"></div>
            <input type="submit" id="aut"  class="btn btn-primary btn-block btn-flat" value="Вход">
            <input type="submit" id="registration"  class="btn btn-primary btn-block btn-flat" value="Регистрация">
        </div>
        <!-- /.col -->
      </div>

    <!-- /.social-auth-links -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script>
  $(function ()
    { 
        $("#aut").click(function(e)
        {
            e.preventDefault();
            
            if($('#login').val() === '' || $('#password').val() === '')
            {
              $('#error').html("Данные введены не верно или не заполнены"); 
                return false;
            }            

            $.post( "login/aut", 
                {  
                    'login'    : $('#login').val(),
                    'password' : $('#password').val(),
                    'capcha'  : grecaptcha.getResponse()
                }, function(data)
                {
                    document.getElementById('error').innerHTML ='';
                    var json = $.parseJSON(data);                    
                    switch (json.Error) 
                    {
                        case 'true':
                            console.log(json.Text)
                            $('#error').html(json.Text);
                          break;
                        case 'false':
                          location.replace(json.Text);
                          break;
                    }
                });
        });

        $("#registration").click(function(e)
        {
          location.replace("registration");
        });


    });
</script>








</body>
</html>
