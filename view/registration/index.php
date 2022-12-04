<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Регистрация</title>

    
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <script src="js/jquery.min.js"></script>
	
</head>
<body>
  <div class="col-md-12">
    <h4 class="mb-3">Регистрация</h4>
        <div id="error"></div>
    <div class="needs-validation">
        <label for="username">Имя</label>
        <div class="input-group">
          <input type="text" class="form-control" id="username" placeholder="Имя" required="">
        </div>
        

        <label for="username">Телефон</label>
        <div class="input-group">
          <input type="text" class="form-control" id="phone" placeholder="Телефон" required="">
        </div>

        <label for="username">Почта</label>
        <div class="input-group">
          <input type="text" class="form-control" id="email" placeholder="Почта" required="">
        </div>

        <label for="username">Пароль</label>
        <div class="input-group">
          <input type="text" class="form-control" id="pass" placeholder="Пароль" required="">
        </div>

        <label for="username">Повтор пароля</label>
        <div class="input-group">
          <input type="text" class="form-control" id="repeat_pass" placeholder="Повтор пароля" required="">
        </div>

      <hr class="mb-4">
      <button id="reg" class="btn btn-primary btn-lg" type="submit">Зарегистрироваться</button>
    </div>
  </div>

  <script>
  $(function () 
    { 
        $("#reg").click(function(e)
        {
            e.preventDefault();

            $.post( "registration/reg", 
                {  
                    'username'    : $('#username').val(), 
                    'phone'       : $('#phone').val(), 
                    'email'       : $('#email').val(), 
                    'pass'        : $('#pass').val(), 
                    'repeat_pass' : $('#repeat_pass').val() 
                }, function(data)
                {

                    document.getElementById('error').innerHTML ='';
                    var json = JSON.parse(data);
                    console.log(json);
                    switch (json.Error)
                    {
                        case 'true':
                            const list = document.getElementById('error');
                            for (var key in json.Text) {
                                let li = document.createElement('li');
                                li.innerText =key + ': '+ json.Text[key];
                                list.append(li);
                            }
                            break;
                        case 'false':
                            location.replace(json.Text);
                            break;
                    }
                });
        });
    });
</script>


</body>

</html>
