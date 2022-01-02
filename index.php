<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="public/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="public/js.js"></script>
</head>

<body>
    <?php if (isset($_SESSION['customerid'])) { ?>
        <form method="post">
            <div class="chat">
                <div class="chat-title">
                    <h1>VChat</h1>
                    <h2>Beta Test</h2>
                    <h2><a href="backend.php?action=logout">Logout</a></h2>
                    <figure class="avatar">
                        <img src="public/profile-80.png" />
                    </figure>
                </div>
                <div class="messages">
                    <div class="messages-content"></div>
                </div>
                <div class="message-box">
                    <textarea id="msgInput" type="text" class="message-input" placeholder="Type message..."></textarea>
                    <button id="submit" type="button" class="message-submit">Send</button>
                </div>
            </div>
        </form>
        <div class="bg"></div>
    <?php } ?>
    <?php if (!isset($_SESSION['customerid'])) { ?>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <p id="result"> </p>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    <button id="login" type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    <?php } ?>


    <script type="text/javascript">
        $(document).ready(function() {
            getMessages();
            function getMessages() {
                $.ajax({
                    type: 'POST',
                    url: 'backend.php',
                    data: {
                        getBackend: 'get'
                    },
                    success: function(data) {
                        $('.messages-content').html(data);
                    },
                    complete: function() {
                        // Schedule the next request when the current one's complete
                        setTimeout(getMessages, 2000);
                    }
                })
            }
        });
        $('#submit').click(function() {
            var message = document.getElementById('msgInput').value;
            $.ajax({
                type: "POST",
                url: 'backend.php',
                data: {
                    msg: message
                },
                success: function(data) {
                    $('.messages-content').html(data);
                    $('#msgInput').val('');
                },
            });


        });

        $('#login').click(function() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            $.ajax({
                type: 'POST',
                url: 'backend.php',
                data: {
                    username: username,
                    password: password
                },
                success: function(data) {
                    $("#result").html(data)
                }
            });
        });
    </script>
</body>

</html>