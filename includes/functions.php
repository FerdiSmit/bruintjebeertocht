<?php

require('config.php');

/**
 * Valideert de ingevoerde gegevens in het formulier.
 * Hier wordt gecontroleerd of alles ingevuld is, of het wachtwoord
 * voldoet aan bepaalde eisen, of het email voldoet aan bepaalde eisen
 * en of het wachtwoord overeenkomt met de bevestigingswachtwoord.
 *
 * Wanneer alles klopt, kan de informatie door om op te slaan in de database.
 */
function validateRegistration()
{
    global $usernameErr, $emailErr, $passErr, $confPassErr;

    $username = checkData($_POST['username']);
    $email = checkData($_POST['email']);
    $password = checkData($_POST['password']);
    $confirmPassword = checkData($_POST['confirmPassword']);
    
    if (empty($username))
    {
        $error['username'] = 'Vul alstublieft een gebruikersnaam in';
    }

    if (empty($email))
    {
        $error['email'] = 'Vul alstublieft een emailadres in';
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error['email'] = 'Het ingevulde emailadres is niet geldig.';
    }

    if (empty($password))
    {
        $error['password'] = 'Vul alstublieft een wachtwoord in';
    }
    else if (strlen($password) < 6)
    {
        $error['password'] = 'Het wachtwoord moet minstens 6 tekens lang zijn.';
    }
    else if (!preg_match('/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $password))
    {
        $error['password'] = 'Het wachtwoord moet over minstens over 1 kleine letter, 1 hoofdletter en 1 nummer beschikken.';
    }
    
    if (empty($confirmPassword))
    {
        $error['confPass'] = 'Bevestig alstublieft uw wachtwoord';
    }
    else if ($password != $confirmPassword)
    {
        $error['confPass'] = 'De wachtwoorden komen niet met elkaar overeen.';
    }
    
    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'username')
            {
                $usernameErr = $value;
            }
            else if ($key == 'email')
            {
                $emailErr = $value;
            }
            else if ($key == 'password')
            {
                $passErr = $value;
            }
            else if ($key == 'confPass')
            {
                $confPassErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        saveRegistration($username, $email, $password);
    }
}

/**
 * Checkt de data en verwijderd vreemde tekens uit de ingevoerde data.
 *
 * @param $data     De ingevoerde data in de formulier velden.
 * @return string   De gecheckte ingevoerde data.
 */
function checkData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Slaat de nieuw geregistreerde gebruiker op. Er worden nog checks gedaan
 * of de gebruiker al bestaat. De check wordt zowel op email als op gebruikersnaam
 * uitgevoerd.
 *
 * @param $username
 * @param $email
 * @param $password
 */
function saveRegistration($username, $email, $password)
{
    global $db, $user;
    global $usernameExists, $emailExists;

    $now = new DateTime();

    $password_hash = $user->password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare("SELECT username FROM users WHERE username = :username");
    $stmt->execute(array(
        ':username' => $username,
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT email FROM users WHERE email = :email");
    $stmt->execute(array(
        ':email' => $email,
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($row['username']))
    {
        $error['username'] = 'De gebruikersnaam is al in gebruik. Probeer een andere gebruikersnaam.';
    }
    else if (!empty($row['email']))
    {
        $error['email'] = 'Het emailadres is al in gebruik. U kunt hier <a href="login.php">inloggen</a>';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'username')
            {
                $usernameExists = $value;
            }
            else if ($key == 'email')
            {
                $emailExists = $value;
            }
        }
    }
    else
    {
        $stmt = $db->prepare("INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, :created_at)");
        $stmt->execute(array(
            ':username' => $username,
            ':email' => $email,
            ':password' => $password_hash,
            ':created_at' => $now->format('Y-m-d H:i:s')
        ));
    }
}

function checkLogin()
{
    global $usernameErr, $passwordErr;

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username))
    {
        $error['username'] = 'Voert u alstublieft uw gebruikernaam in.';
    }

    if (empty($password))
    {
        $error['password'] = 'Voert u alstublieft uw wachtwoord in.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'username')
            {
                $usernameErr = $value;
            }
            else if ($key == 'password')
            {
                $passwordErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        login($username, $password);
    }
}

function login($username, $password)
{
    global $db, $user, $loginError;

    $now = new DateTime();

    $stmt = $db->prepare('SELECT userID, username, password FROM users WHERE username = :username');
    $stmt->execute(array(
        ':username' => $username
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user->password_verify($password, $row['password']) == 1)
    {
        $stmt = $db->prepare('UPDATE users SET last_login = :last_login WHERE username = :username');
        $stmt->execute(array(
            ':last_login' => $now->format('Y-m-d H:i:s'),
            ':username' => $username
        ));
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        echo $_SESSION['username'];

        header('Location: dashboard.php');
    }
    else
    {
        $error['loginerror'] = 'Wachtwoord en/of gebruikersnaam komen niet overeen';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'loginerror')
            {
                $loginError = $value;
            }
        }
    }
}

function checkCreateNews()
{
    global $titleErr, $summaryErr, $startDateErr, $endDateErr, $descErr;

    $title = checkData($_POST['title']);
    $summary = checkData($_POST['summary']);
    $startDate = checkData($_POST['startdate']);
    $description = $_POST['description'];

    if (empty($title))
    {
        $error['title'] = 'Voer alstublief een titel in.';
    }

    if (empty($summary))
    {
        $error['summary'] = 'Vul alstublieft een korte beschrijving in.';
    }

    if (empty($startDate))
    {
        $error['startDate'] = 'Vul alstublieft een startdatum in.';
    }

    if (empty($description))
    {
        $error['description'] = 'Vul alstublieft een beschrijving in';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $titleErr = $value;
            }
            else if ($key == 'summary')
            {
                $summaryErr = $value;
            }
            else if ($key == 'startDate')
            {
                $startDateErr = $value;
            }
            else if ($key == 'description')
            {
                $descErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        saveNews($title, $summary, $startDate, $description);
    }
}

function saveNews($title, $summary, $startDate, $description)
{
    global $db;

    $stmt = $db->prepare('INSERT INTO news (userID, title, shortDesc, created_date, longDesc) VALUES (:userID, :title, :shortDesc, :created_date, :longDesc)');
    $stmt->execute(array(
        ':userID' => getUserId(),
        ':title' => $title,
        ':shortDesc' => $summary,
        ':created_date' => $startDate,
        ':longDesc' => $description
    ));
}

function newsList()
{
    global $db;

    $stmt = $db->prepare("SELECT newsID, title, created_date, last_updated FROM news");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getNewsById()
{
    global $db;

    $id = '';
    $error = '';

    if (isset($_GET['id']))
    {
        $id = $_GET['id'];
    }
    else
    {
        $error = 'Geen geldig nieuwsbericht';
    }

    $stmt = $db->prepare("SELECT title, shortDesc, longDesc FROM news WHERE newsID = :newsID");
    $stmt->execute(array(
        ':newsID' => $id
    ));
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function updateNews()
{
    global $db;
    global $updateTitleErr, $updateShortDescErr, $updateLongDescErr;

    $id = $_GET['id'];

    $title = checkData($_POST['title']);
    $shortDesc = checkData($_POST['summary']);
    $longDesc = $_POST['description'];

    if (empty($title))
    {
        $error['title'] = 'De titelveld mag niet leeg zijn.';
    }

    if (empty($shortDesc))
    {
        $error['summary'] = 'Het veld voor de korte omschrijving mag niet leeg zijn.';
    }

    if (empty($longDesc))
    {
        $error['description'] = 'Het veld voor de uitgebreide omschrijving mag niet leeg zijn.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $updateTitleErr = $value;
            }
            else if ($key == 'summary')
            {
                $updateShortDescErr = $value;
            }
            else if ($key == 'description')
            {
                $updateLongDescErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $now = new DateTime();

        $stmt = $db->prepare("UPDATE news SET title = :title, shortDesc = :shortDesc, last_updated = :last_updated, longDesc = :longDesc WHERE newsID = :newsID");
        $stmt->execute(array(
            ':title' => $title,
            ':shortDesc' => $shortDesc,
            ':last_updated' => $now->format('Y-m-d H:i:s'),
            ':longDesc' => $longDesc,
            ':newsID' => $id
        ));

        header('Location: http://localhost/bruintjebeertocht/dashboard.php?n=news.php');
    }
}

function deleteNews()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare("DELETE FROM news WHERE newsID = :newsID");
    $stmt->execute(array(
        'newsID' => $id
    ));

    header('Location: http://localhost/bruintjebeertocht/dashboard.php?n=news.php');
}

function getUserId()
{
    global $db;

    $username = $_SESSION['username'];

    $stmt = $db->prepare('SELECT userID FROM users WHERE username = :username');
    $stmt->execute(array(
        ':username' => $username
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['userID'];
}