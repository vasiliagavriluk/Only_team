<?php include(PathList::GetPath(PathList::FILE_PATH_VIEW).'header.php'); ?>
<style>
    label, #error{
        color: white;
    }
</style>
<div id="content" class="app-content">
    <div id="error"></div>
    <div style="width: 250px">
            <div class="mb-3"></div>
            <label for="username" id="userid">ID: </label>
            <div class="mb-3"></div>

            <label for="username">Имя</label>
            <div class="input-group">
                <input type="text" class="form-control" id="username" placeholder="Имя" required="">
            </div>
            <div class="mb-3"></div>

            <label for="username">Телефон</label>
            <div class="input-group">
                <input type="text" class="form-control" id="phone" placeholder="Телефон" required="">
            </div>
            <div class="mb-3"></div>

            <label for="username">Почта</label>
            <div class="input-group">
                <input type="text" class="form-control" id="email" placeholder="Почта" required="">
            </div>
            <div class="mb-3"></div>

            <label for="password">Пароль</label>
            <div class="input-group">
                <input type="password" class="form-control" id="pass" placeholder="Пароль" required="">
            </div>
            <div class="mb-3"></div>

            <button id="btn_update" class="btn btn-primary btn-lg" type="submit">Изменить</button>
    </div>
</div>

<script>
        $(function ()
        {
            load();

            function load()
            {
                $.post( "users/edit",
                    {}, function(data)
                    {
                        var json = JSON.parse(data);
                        $('#userid').html(json.ID.toString());
                        $('#username').val(json.Names);
                        $('#phone').val(json.Phone);
                        $('#email').val(json.Email);
                        $('#pass').val('**********');
                    });

            };


            $("#btn_update").click(function(e)
            {
                e.preventDefault();

                $.post( "users/update",
                    {
                        'id'          : $('#userid').text(),
                        'username'    : $('#username').val(),
                        'phone'       : $('#phone').val(),
                        'email'       : $('#email').val(),
                        'pass'        : $('#pass').val()
                    }, function(data)
                    {
                        document.getElementById('error').innerHTML ='';
                        var json = JSON.parse(data);
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
<?php include(PathList::GetPath(PathList::FILE_PATH_VIEW).'footer.php'); ?>