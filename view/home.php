<hrml>
        <header>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <title></title>
        </header>
        <body>
                <?php  echo $datainfo['name']; ?>
                <form method="post" action="">
                        用户名：<input name="username" type="text" value=""><br>
                        密码:   <input name="password" type="password"><br>
                        <input name="submit" type="submit" value="提交">
                </form>
        </body>
</html>