<?php
session_start();
ob_start();

function startConnection()
{
    try {
        $user = "root";
        $pass = "strongpassword";
        $dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

function insertMessage($customerid, $message)
{
    $date = date('Y-m-d H:i:s');
    $q = "INSERT INTO `messages`(`messageBY`, `message`, `date`) VALUES ('$customerid','$message','$date')";
    $s = startConnection()->prepare($q);
    if($s->execute()){
        getMessages();
    } else {
        echo "Something went wrong";
    }
}

function getMessages()
{
    $q = "SELECT * FROM messages ORDER BY date DESC LIMIT 10";
    $s = startConnection()->prepare($q);
    $s->execute();
    $result = $s->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($result as $message) {
        echo "<p> " . getUserName($message['messageBY']) . ":  ". $message['message'] . "</p>";
    }
}

function getUserName($customerid)
{
    $q = "SELECT username FROM users WHERE customerid='$customerid'";
    $s = startConnection()->prepare($q);
    $s->execute();
    $result = $s->fetch(PDO::FETCH_ASSOC);

    return $result['username'];
}

if (isset($_POST['msg'])) {
    $customerid = $_SESSION['customerid'];
    $message = $_POST['msg'];

    insertMessage($customerid, $message);
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $s = startConnection()->prepare($q);
    $s->execute();
    $m = $s->fetch(PDO::FETCH_ASSOC);
    $count = $s->rowCount();

    if ($count == 1) {
        $_SESSION['username'] = $m['username'];
        $_SESSION['customerid'] = $m['customerid'];

        echo "Wellcome back " . $m['username'];

        echo '
        <script>
            setTimeout(function () {
                window.location.href = "index.php";
            }, 2000);
        </script>
         ';
    } else {
        echo "Wrong username or password";
    }
    return;
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        session_destroy();
        unset($_SESSION['customerid']);
        unset($_SESSION['username']);

        echo '
    <script>
        setTimeout(function () {
            window.location.href = "index.php";
        }, 2000);
    </script>
    ';
    }
}

if(isset($_POST['getBackend']))
{
    getMessages();
}
