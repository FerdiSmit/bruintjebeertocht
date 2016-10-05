<?php

require('config.php');
require('classes/loginattempt.php');

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
    else if (strpos($email, '@bruintjebeertocht.nl') === false)
    {
        $error['email'] = 'Het ingevulde emailadres is niet geldig!';
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
            ':created_at' => $now->format('d-m-Y H:i:s')
        ));
    }
}

function checkLogin()
{
    global $db, $emailErr, $validEmailErr, $passwordErr;

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email))
    {
        $error['email'] = 'Voert u alstublieft uw gebruikernaam in.';
    }

    if (end(explode('@', $email)) !== 'bruintjebeertocht.nl')
    {
        $error['validation'] = 'Het ingevoerde e-mailadres is geen geldig adres.';
    }

    if (empty($password))
    {
        $error['password'] = 'Voert u alstublieft uw wachtwoord in.';
    }

    try {
        $attempt = new LoginAttempt($_POST['email'], $_POST['password'], $db);
        $attempt->whenReady(function($success) {
            echo $success ? "Valid" : "Invalid";
        });
    }
    catch (Exception $e)
    {
        if ($e->getCode() == 503) {
            header('HTTP/1.1 503 Service Unavailable');
            exit;
        }
        elseif ($e->getCode() == 403) {
            header('HTTP/1.1 403 Forbidden');
        }
        else {
            echo 'Error: ' . $e->getMessage();
        }
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'email')
            {
                $emailErr = $value;
            }
            else if ($key == 'validation')
            {
                $validEmailErr = $value;
            }
            else if ($key == 'password')
            {
                $passwordErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        login($email, $password);
    }
}

function login($email, $password)
{
    global $db, $user, $loginError;

    $now = new DateTime();

    $stmt = $db->prepare('SELECT userID, email, password FROM users WHERE email = :email');
    $stmt->execute(array(
        ':email' => $email
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user->password_verify($password, $row['password']) == 1)
    {
        $stmt = $db->prepare('UPDATE users SET last_login = :last_login WHERE email = :email');
        $stmt->execute(array(
            ':last_login' => $now->format('d-m-Y H:i:s'),
            ':email' => $email
        ));
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;

        header('Location: dashboard/dashboard.php');
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

function getUserByEmail($email)
{
    global $db;

    $stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
    $stmt->execute(array(
        ':email' => $email
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getUsers()
{
    global $db;

    $stmt = $db->prepare('SELECT userID, username, email, last_login FROM users');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function deleteUsers()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('DELETE FROM users WHERE userID = :userID');
    $stmt->execute(array(
        ':userID' => $id
    ));

    header('Location: users.php');
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

    header('Location: news.php');
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

    if (isset($_GET['id']))
    {
        $id = $_GET['id'];
    }

    $stmt = $db->prepare("SELECT title, shortDesc, last_updated, longDesc FROM news WHERE newsID = :newsID");
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
            ':last_updated' => $now->format('d-m-Y H:i:s'),
            ':longDesc' => $longDesc,
            ':newsID' => $id
        ));

        header('Location: news.php');
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

    header('Location: news.php');
}

function getNews()
{
    global $db;

    $stmt = $db->prepare("SELECT newsID, title, shortDesc, last_updated, longDesc FROM news ORDER BY created_date DESC LIMIT 5");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getNewsForPagination()
{
    $query = 'SELECT newsID, title, shortDesc, last_updated, longDesc FROM news ORDER BY created_date DESC';

    return $query;
}

function checkPoll()
{
    global $questionErr, $answerErr1, $answerErr2, $answerErr3;

    $question = checkData($_POST['question']);
    $answer1 = checkData($_POST['answer1']);
    $answer2 = checkData($_POST['answer2']);
    $answer3 = checkData($_POST['answer3']);

    if (empty($question))
    {
        $error['question'] = 'U dient een vraag in te voeren';
    }

    if (empty($answer1))
    {
        $error['answer1'] = 'U dient een antwoord in te vullen';
    }

    if (empty($answer2))
    {
        $error['answer2'] = 'U dient een antwoord in te vullen';
    }

    if (empty($answer3))
    {
        $error['answer3'] = 'U dient een antwoord in te vullen';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'question')
            {
                $questionErr = $value;
            }
            else if($key == 'answer1')
            {
                $answerErr1 = $value;
            }
            else if($key == 'answer2')
            {
                $answerErr2 = $value;
            }
            else if($key == 'answer3')
            {
                $answerErr3 = $value;
            }
        }
    }

    if (!isset($error))
    {
        savePoll($question, $answer1, $answer2, $answer3);
    }
}

function savePoll($question, $answer1, $answer2, $answer3)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('INSERT INTO poll(userID, question, answer1, answer2, answer3, created_at) VALUES (:userID, :question, :answer1, :answer2, :answer3, :created_at)');
    $stmt->execute(array(
        ':userID' => getUserId(),
        ':question' => $question,
        ':answer1' => $answer1,
        ':answer2' => $answer2,
        ':answer3' => $answer3,
        ':created_at' => $now->format('d-m-Y H:i:s')
    ));

    header('Location: poll.php');
}

function getQuestion()
{
    global $db;

    $stmt = $db->prepare('SELECT pollID, question, created_at, updated_at FROM poll');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getPoll()
{
    global $db;

    $stmt = $db->prepare("SELECT question, answer1, answer2, answer3 FROM poll");
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function getPollById()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('SELECT pollID, question, answer1, answer2, answer3 FROM poll WHERE pollID = :pollID');
    $stmt->execute(array(
        ':pollID' => $id
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function updatePoll()
{
    global $db, $questionUpdateErr, $answerUpdateErr1, $answerUpdateErr2, $answerUpdateErr3;

    $question = checkData($_POST['question']);
    $answer1 = checkData($_POST['answer1']);
    $answer2 = checkData($_POST['answer2']);
    $answer3 = checkData($_POST['answer3']);

    $now = new DateTime();

    $id = $_GET['id'];

    if (empty($question))
    {
        $error['question'] = 'U dient een vraag in te voeren';
    }

    if (empty($answer1))
    {
        $error['answer1'] = 'U dient een antwoord in te vullen';
    }

    if (empty($answer2))
    {
        $error['answer2'] = 'U dient een antwoord in te vullen';
    }

    if (empty($answer3))
    {
        $error['answer3'] = 'U dient een antwoord in te vullen';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'question')
            {
                $questionUpdateErr = $value;
            }
            elseif ($key == 'answer1')
            {
                $answerUpdateErr1 = $value;
            }
            elseif ($key == 'answer2')
            {
                $answerUpdateErr2 = $value;
            }
            elseif ($key == 'answer3')
            {
                $answerUpdateErr3 = $value;
            }
        }
    }

    if (!isset($error))
    {
        $stmt = $db->prepare('UPDATE poll SET question = :question, answer1 = :answer1, answer2 = :answer2, answer3 = :answer3, updated_at = :updated_at WHERE pollID = :pollID');
        $stmt->execute(array(
            ':question' => $question,
            ':answer1' => $answer1,
            ':answer2' => $answer2,
            ':answer3' => $answer3,
            ':updated_at' => $now->format('d-m-Y H:i:s'),
            ':pollID' => $id
        ));

        header('Location: poll.php');
    }
}

function deletePoll()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('DELETE FROM poll WHERE pollID = :pollID');
    $stmt->execute(array(
        ':pollID' => $id
    ));

    header('Location: poll.php');
}

function getUserId()
{
    global $db;

    $email = $_SESSION['email'];

    $stmt = $db->prepare('SELECT userID FROM users WHERE email = :email');
    $stmt->execute(array(
        ':email' => $email
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['userID'];
}

function checkAbout()
{
    global $titleErr, $aboutErr;

    $title = checkData($_POST['title']);
    $about = $_POST['about'];

    if (empty($title))
    {
        $error['title'] = 'Voert u alstublieft een titel in.';
    }

    if (empty($about))
    {
        $error['about'] = 'Voert u alstublieft informatie over de BBT in.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $titleErr = $value;
            }
            else if ($key == 'about')
            {
                $aboutErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        saveAbout($title, $about);
    }
}

function saveAbout($title, $about)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('INSERT INTO about(userID, title, about, created_at) VALUES(:userID, :title, :about, :created_at)');
    $stmt->execute(array(
        ':userID' => getUserId(),
        ':title' => $title,
        ':about' => $about,
        ':created_at' => $now->format('d-m-Y H:i:s')
    ));

    header('Location: aboutOverview.php');
}

function getAbout()
{
    global $db;

    $stmt = $db->prepare('SELECT aboutID, title, about, created_at, last_updated FROM about');
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function getAboutById()
{
    global $db;

    $id = '';

    if (isset($_GET['id']))
    {
        $id = $_GET['id'];
    }

    $stmt = $db->prepare('SELECT title, about FROM about WHERE aboutID = :aboutID');
    $stmt->execute(array(
        ':aboutID' => $id
    ));
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function updateAbout()
{
    global $db, $updateTitleErr, $updateAboutErr;

    $id = $_GET['id'];
    $now = new DateTime();

    $title = checkData($_POST['title']);
    $about = $_POST['about'];

    if (empty($title))
    {
        $error['title'] = 'Voert u alstublieft een titel in.';
    }

    if (empty($about))
    {
        $error['about'] = 'Voert u alstublieft informatie over de BBT in.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $updateTitleErr = $value;
            }
            else if ($key == 'about')
            {
                $updateAboutErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $stmt = $db->prepare('UPDATE about SET title = :title, about = :about, last_updated = :last_updated WHERE aboutID = :aboutID');
        $stmt->execute(array(
            ':title' => $title,
            ':about' => $about,
            ':last_updated' => $now->format('d-m-Y H:i:s'),
            ':aboutID' => $id
        ));

        header('Location: aboutOverview.php');
    }
}

function deleteAbout()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('DELETE FROM about WHERE aboutID = :aboutID');
    $stmt->execute(array(
        ':aboutID' => $id
    ));

    header('Location: aboutOverview.php');
}

function checkAlbum()
{
    global $titleErr, $descErr, $albumExistErr;

    $title = checkData($_POST['title']);
    $desc = checkData($_POST['description']);

    if (empty($title))
    {
        $error['title'] = 'Voert u alstublieft een titel in voor het album';
    }

    if (empty($desc))
    {
        $error['description'] = 'Voert u alstublieft een korte omschrijving in voor het album';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $titleErr = $value;
            }
            elseif ($key == 'description')
            {
                $descErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $albumDir = '/../albums/';

        if (!is_dir(__DIR__ . $albumDir . $title))
        {
            mkdir(__DIR__ . $albumDir . $title, 0700);
            saveAlbum($title, $desc);
        }
        else
        {
            $albumExistErr = 'Het album ' .  $title . ' bestaat al.';
        }
    }
}

function saveAlbum($title, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('INSERT INTO album(userID, title, description, created_at) VALUES(:userID, :title, :description, :created_at)');
    $stmt->execute(array(
        ':userID' => getUserId(),
        ':title' => $title,
        ':description' => $description,
        ':created_at' => $now->format('d-m-Y H:i:s')
    ));

    header('Location: albumOverview.php');
}

function getAlbums()
{
    global $db;

    $stmt = $db->prepare('SELECT albumID, title, description, created_at, updated_at FROM album');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getAlbumById()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('SELECT title, description FROM album WHERE albumID = :albumID');
    $stmt->execute(array(
        ':albumID' => $id
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function updateAlbum()
{
    global $db, $updateTitleErr, $updateDescErr;

    $id = $_GET['id'];
    $now = new DateTime();

    $title = checkData($_POST['title']);
    $description = checkData($_POST['description']);

    if (empty($title))
    {
        $error['title'] = 'Voert u alstublieft een titel in voor het album.';
    }

    if (empty($description))
    {
        $error['description'] = 'Voert u alstublieft een korte omschrijving in voor het album';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $updateTitleErr = $value;
            }
            elseif ($key == 'description')
            {
                $updateDescErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $stmt = $db->prepare('UPDATE album SET title = :title, description = :description, updated_at = :updated_at WHERE albumID = :albumID');
        $stmt->execute(array(
            ':title' => $title,
            ':description' => $description,
            ':updated_at' => $now->format('d-m-Y H:i:s'),
            ':albumID' => $id
        ));

        header('Location: albumOverview.php');
    }
}

function getAlbumByNameAndId($id)
{
    global $db;

    $stmt = $db->prepare('SELECT title FROM album WHERE albumID = :albumID');
    $stmt->execute(array(
        ':albumID' => $id
    ));
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;

}

function deleteAlbum()
{
    global $db;

    $id = $_GET['id'];
    $albums = getAlbumByNameAndId($id);
    $albumDir = '/../albums/' . $albums['title'] . '/';

    if (!getPictures())
    {
        if (is_dir(__DIR__ . $albumDir))
        {
            rmdir(__DIR__ . $albumDir);
        }

        $stmt = $db->prepare('DELETE FROM album WHERE albumID = :albumID');
        $stmt->execute(array(
            ':albumID' => $id
        ));
    }
    else
    {
        $results = getPictures();

        foreach ($results as $result)
        {
            if (file_exists(__DIR__ . $albumDir . $result['picture']))
            {
                unlink(__DIR__ . $albumDir . $result['picture']);
            }
        }

        if (is_dir(__DIR__ . $albumDir))
        {
            rmdir(__DIR__ . $albumDir);
        }

        $stmt = $db->prepare('DELETE FROM picture WHERE albumID = :albumID');
        $stmt->execute(array(
            'albumID' => $id
        ));

        $stmt = $db->prepare('DELETE FROM album WHERE albumID = :albumID');
        $stmt->execute(array(
            'albumID' => $id
        ));
    }

    header('Location: albumOverview.php');
}

function checkPictures()
{
    global $pictureErr, $extensionErr, $sizeErr, $existErr;

    foreach ($_FILES['picture']['tmp_name'] as $key => $tmp_name)
    {
        $file_name = $_FILES['picture']['name'][$key];
        $file_size = $_FILES['picture']['size'][$key];
        $file_tmp = $_FILES['picture']['tmp_name'][$key];

        $extensions = array("jpeg", "jpg", "png");

        $file_ext = explode('.', $_FILES['picture']['name'][$key]);
        $file_ext = end($file_ext);
        $file_ext = strtolower(end(explode('.', $_FILES['picture']['name'][$key])));

        if ($_FILES['picture']['name'][$key] == "")
        {
            $error['pictures'] = 'Selecteert u alstublieft 1 of meerdere afbeeldingen.';
        }

        if (in_array($file_ext, $extensions) === false)
        {
            $error['extension'] = 'Alleen JPEG, JPG en PNG afbeeldingen zijn toegestaan.';
        }

        if ($_FILES['picture']['size'][$key] == 0)
        {
            $error['sizes'] = 'De afbeeldingen mogen niet groter dan 2MB zijn.';
        }


        if (isset($error))
        {
            foreach ($error as $keys => $value)
            {
                if ($keys == 'pictures')
                {
                    $pictureErr = $value;
                }
                elseif ($keys == 'extension')
                {
                    $extensionErr = $value;
                }
                elseif ($keys == 'sizes')
                {
                    $sizeErr = $value;
                }
            }
        }

        if (!isset($error))
        {
            $id = $_GET['id'];

            $albumDir = '/../albums/';

            $dir = getAlbumByNameAndId($id);

            echo __DIR__ . $albumDir . $dir;

            if (!is_dir(__DIR__ . $albumDir . $dir['title']))
            {
                echo 'true';
                mkdir(__DIR__ . $albumDir . $dir, 0700);
                if (!file_exists(__DIR__ . $albumDir . $dir['title'] . '/' . $file_name))
                {
                    move_uploaded_file($file_tmp, __DIR__ . $albumDir . $dir['title'] . '/' . $file_name);
                    savePictures($file_name, $file_ext, $file_size);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $file_name . '). Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
            else
            {
                if (!file_exists(__DIR__ . $albumDir . $dir['title'] . '/' . $file_name))
                {
                    move_uploaded_file($file_tmp, __DIR__ . $albumDir . $dir['title'] . '/' . $file_name);
                    savePictures($file_name, $file_ext, $file_size);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $file_name . '). Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
        }
    }
}

function savePictures($file_name, $file_ext, $file_size)
{
    global $db;

    $id = $_GET['id'];
    $now = new DateTime();

    $stmt = $db->prepare('INSERT INTO picture(albumID, picture, size, extension, added_at) VALUES(:albumID, :picture, :size, :extension, :added_at)');
    $stmt->execute(array(
        ':albumID' => $id,
        ':picture' => $file_name,
        ':size' => $file_size,
        ':extension' => $file_ext,
        ':added_at' => $now->format('d-m-Y H:i:s')
    ));

    header('Location: dashboard.php?alo=albumOverview.php');
}

function getPictures()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('SELECT pictureID, picture FROM picture WHERE albumID = :albumID');
    $stmt->execute(array(
        ':albumID' => $id
    ));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getPicturesForPagination()
{
    $id = $_GET['id'];

    $query = "SELECT pictureID, picture FROM picture WHERE albumID = $id";

    return $query;
}

function checkRoute()
{
    global $titleErr, $mapErr, $sizeErr, $extErr, $descErr;

    $title = checkData($_POST['title']);
    $filename = $_FILES['map']['name'];
    $filesize = $_FILES['map']['size'];
    $filetmp = $_FILES['map']['tmp_name'];
    $file_ext = strtolower(end(explode('.', $_FILES['map']['name'])));
    $description = $_POST['description'];

    $extensions = array('jpg', 'jpeg', 'png');

    if (empty($title))
    {
        $error['title'] = 'Voert u alstublieft een titel in voor de route.';
    }

    if ($filename == "")
    {
        $error['map'] = 'Selecteert u altublieft een route.';
    }

    if ($filesize == 0)
    {
        $error['sizes'] = 'De afbeeldingen mogen niet groter dan 2MB zijn.';
    }

    if (in_array($file_ext, $extensions) === false)
    {
        $error['extension'] = 'Alleen JPEG, JPG en PNG afbeeldingen zijn toegestaan.';
    }

    if (empty($description))
    {
        $error['description'] = 'Voert u alstublieft een omschrijving van de route in.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $titleErr = $value;
            }
            elseif ($key == 'map')
            {
                $mapErr = $value;
            }
            elseif ($key == 'sizes')
            {
                $sizeErr = $value;
            }
            elseif ($key == 'extension')
            {
                $extErr = $value;
            }
            elseif ($key == 'description')
            {
                $descErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $routeDir = '/../routes/';

        if (!is_dir(__DIR__ . $routeDir))
        {
            mkdir(__DIR__ . $routeDir, 0700);
            if (!file_exists(__DIR__ . $routeDir . $filename))
            {
                move_uploaded_file($filetmp, __DIR__ . $routeDir . $filename);
                saveRoute($title, $filename, $filesize, $file_ext, $description);
            }
            else
            {
                $existErr = 'Dit bestand bestaa al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
            }
        }
        else
        {
            if (!file_exists(__DIR__ . $routeDir . $filename))
            {
                move_uploaded_file($filetmp, __DIR__ . $routeDir . $filename);
                saveRoute($title, $filename, $filesize, $file_ext, $description);
            }
            else
            {
                $existErr = 'Dit bestand bestaa al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
            }
        }
    }
}

function saveRoute($title, $filename, $filesize, $file_ext, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('INSERT INTO route(userID, title, map, size, extension, description, added_at) VALUES(:userID, :title, :map, :size, :extension, :description, :added_at)');
    $stmt->execute(array(
        ':userID' => getuserId(),
        ':title' => $title,
        ':map' => $filename,
        ':size' => $filesize,
        ':extension' => $file_ext,
        ':description' => $description,
        ':added_at' => $now->format('d-m-Y H:i:s')
    ));

    header('Location: route.php');
}

function getRoutes()
{
    global $db;

    $stmt = $db->prepare('SELECT routeID, title, map, description, added_at, updated_at FROM route');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getRouteById()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('SELECT title, map, description FROM route WHERE routeID = :routeID');
    $stmt->execute(array(
        ':routeID' => $id
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function updateRoute()
{
    global $titleUpdateErr, $sizeUpdateErr, $extensionUpdateErr, $descriptionUpdateErr;

    $title = checkData($_POST['title']);
    $filename = $_FILES['map']['name'];
    $filesize = $_FILES['map']['size'];
    $file_ext = strtolower(end(explode('.', $_FILES['map']['name'])));
    $file_tmp = $_FILES['map']['tmp_name'];
    $description = $_POST['description'];

    $extensions = array('jpg', 'jpeg', 'png');

    $id = $_GET['id'];

    if (empty($filename))
    {
        if (empty($title))
        {
            $error['title'] = 'Voert u alstublieft een titel voor de route in.';
        }

        if (empty($description))
        {
            $error['description'] = 'Voert u alstublieft een omschrijving van de route in.';
        }

        if (isset($error))
        {
            foreach ($error as $key => $value)
            {
                if ($key == 'title')
                {
                    $titleUpdateErr = $value;
                }
                elseif ($key == 'description')
                {
                    $descriptionUpdateErr = $value;
                }
            }
        }

        if (!isset($error))
        {
            saveRouteWithoutImage($id, $title, $description);
        }
    }

    if (!empty($filename))
    {
        if (empty($title))
        {
            $error['title'] = 'Voert u alstublieft een titel voor de route in.';
        }

        if ($filesize == 0)
        {
            $error['size'] = 'De afbeelding mag niet groter zijn dan 2MB.';
        }

        if (in_array($file_ext, $extensions) === false)
        {
            $error['extension'] = 'Alleen JPEG, JPG en PNG zijn toegestaan.';
        }

        if (empty($description))
        {
            $error['description'] = 'Voert u alstublieft een omschrijving van de route in.';
        }

        if (isset($error))
        {
            foreach ($error as $key => $value)
            {
                if ($key == 'title')
                {
                    $titleUpdateErr = $value;
                }
                elseif ($key == 'size')
                {
                    $sizeUpdateErr = $value;
                }
                elseif ($key == 'extension')
                {
                    $extensionUpdateErr = $value;
                }
                elseif ($key == 'description')
                {
                    $descriptionUpdateErr = $value;
                }
            }
        }

        if (!isset($error))
        {
            $routeDir = '/../routes/';

            if (!is_dir(__DIR__ . $routeDir))
            {
                mkdir(__DIR__ . $routeDir, 0700);
                if (!file_exists(__DIR__ . $routeDir . $filename))
                {
                    move_uploaded_file($file_tmp, __DIR__ . $routeDir . $filename);
                    saveRouteWithImage($id, $title, $filename, $filesize, $file_ext, $description);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
            else
            {
                if (!file_exists(__DIR__ . $routeDir . $filename))
                {
                    move_uploaded_file($file_tmp, __DIR__ . $routeDir . $filename);
                    saveRouteWithImage($id, $title, $filename, $filesize, $file_ext, $description);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
        }
    }
}

function saveRouteWithoutImage($id, $title, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('UPDATE route SET title = :title, description = :description, updated_at = :updated_at WHERE routeID = :routeID');
    $stmt->execute(array(
        ':title' => $title,
        ':description' => $description,
        ':updated_at' => $now->format('d-m-Y H:i:s'),
        ':routeID' => $id
    ));

    header('Location: route.php');
}

function saveRouteWithImage($id, $title, $filename, $filesize, $file_ext, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('UPDATE route SET title = :title, map = :map, size = :size, extension = :extension, description = :description, updated_at = :updated_at WHERE routeID = :routeID');
    $stmt->execute(array(
        ':title' => $title,
        ':map' => $filename,
        ':size' => $filesize,
        ':extension' => $file_ext,
        ':description' => $description,
        ':updated_at' => $now->format('d-m-Y H:i:s'),
        ':routeID' => $id
    ));

    header('Location: route.php');
}

function deleteRoute()
{
    global $db;

    $id = $_GET['id'];

    $result = getRouteById();

    $routeDir = '/../routes/';

    if (file_exists(__DIR__ . $routeDir . $result['map']))
    {
        unlink(__DIR__ . $routeDir . $result['map']);
    }

    $stmt = $db->prepare('DELETE FROM route WHERE routeID = :routeID');
    $stmt->execute(array(
        ':routeID' => $id
    ));

    header('Location: route.php');
}

function checkAmbassador()
{
    global $nameErr, $ambassadorErr, $sizeErr, $extErr, $descErr;

    $name = checkData($_POST['name']);
    $filename = $_FILES['ambassador']['name'];
    $filesize = $_FILES['ambassador']['size'];
    $filetmp = $_FILES['ambassador']['tmp_name'];
    $file_ext = strtolower(end(explode('.', $_FILES['ambassador']['name'])));
    $description = $_POST['description'];

    $extensions = array('jpg', 'jpeg', 'png');

    if (empty($name))
    {
        $error['name'] = 'Voert u alstublieft de naam van de ambassadeur in.';
    }

    if ($filename == "")
    {
        $error['ambassador'] = 'Selecteert u altublieft een foto van de ambassadeur.';
    }

    if ($filesize == 0)
    {
        $error['sizes'] = 'De afbeeldingen mogen niet groter dan 2MB zijn.';
    }

    if (in_array($file_ext, $extensions) === false)
    {
        $error['extension'] = 'Alleen JPEG, JPG en PNG afbeeldingen zijn toegestaan.';
    }

    if (empty($description))
    {
        $error['description'] = 'Voert u alstublieft een beschrijving van de ambassedeur in.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'name')
            {
                $nameErr = $value;
            }
            elseif ($key == 'ambassador')
            {
                $ambassadorErr = $value;
            }
            elseif ($key == 'sizes')
            {
                $sizeErr = $value;
            }
            elseif ($key == 'extension')
            {
                $extErr = $value;
            }
            elseif ($key == 'description')
            {
                $descErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $ambassadorDir = '/../ambassadors/';

        if (!is_dir(__DIR__ . $ambassadorDir))
        {
            mkdir(__DIR__ . $ambassadorDir, 0700);
            if (!file_exists(__DIR__ . $ambassadorDir . $filename))
            {
                move_uploaded_file($filetmp, __DIR__ . $ambassadorDir . $filename);
                saveAmbassador($name, $filename, $filesize, $file_ext, $description);
            }
            else
            {
                $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
            }
        }
        else
        {
            if (!file_exists(__DIR__ . $ambassadorDir . $filename))
            {
                move_uploaded_file($filetmp, __DIR__ . $ambassadorDir . $filename);
                saveAmbassador($name, $filename, $filesize, $file_ext, $description);
            }
            else
            {
                $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
            }
        }
    }
}

function saveAmbassador($name, $filename, $filesize, $file_ext, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('INSERT INTO ambassador(userID, ambassador, image, size, extension, description, added_at) VALUES(:userID, :ambassador, :image, :size, :extension, :description, :added_at)');
    $stmt->execute(array(
        ':userID' => getUserId(),
        ':ambassador' => $name,
        ':image' => $filename,
        ':size' => $filesize,
        ':extension' => $file_ext,
        ':description' => $description,
        ':added_at' => $now->format('d-m-Y H:i:s')
    ));

    header('Location: ambassador.php');
}

function getAmbassadors()
{
    global $db;

    $stmt = $db->prepare('SELECT ambassadorID, ambassador, image, description, added_at, updated_at FROM ambassador');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getAmbassadorById()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('SELECT ambassador, image, description FROM ambassador WHERE ambassadorID = :ambassadorID');
    $stmt->execute(array(
        ':ambassadorID' => $id
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function updateAmbassador()
{
    global $nameUpdateErr, $sizeUpdateErr, $extensionUpdateErr, $descriptionUpdateErr;

    $name = checkData($_POST['name']);
    $filename = $_FILES['ambassador']['name'];
    $filesize = $_FILES['ambassador']['size'];
    $file_ext = strtolower(end(explode('.', $_FILES['ambassador']['name'])));
    $file_tmp = $_FILES['ambassador']['tmp_name'];
    $description = $_POST['description'];

    $extensions = array('jpg', 'jpeg', 'png');

    $id = $_GET['id'];

    if (empty($filename))
    {
        if (empty($name))
        {
            $error['name'] = 'Voert u alstublieft een titel voor de route in.';
        }

        if (empty($description))
        {
            $error['description'] = 'Voert u alstublieft een omschrijving van de route in.';
        }

        if (isset($error))
        {
            foreach ($error as $key => $value)
            {
                if ($key == 'name')
                {
                    $nameUpdateErr = $value;
                }
                elseif ($key == 'description')
                {
                    $descriptionUpdateErr = $value;
                }
            }
        }

        if (!isset($error))
        {
            saveAmbassadorWithoutImage($id, $name, $description);
        }
    }

    if (!empty($filename))
    {
        if (empty($name))
        {
            $error['name'] = 'Voert u alstublieft een titel voor de route in.';
        }

        if ($filesize == 0)
        {
            $error['size'] = 'De afbeelding mag niet groter zijn dan 2MB.';
        }

        if (in_array($file_ext, $extensions) === false)
        {
            $error['extension'] = 'Alleen JPEG, JPG en PNG zijn toegestaan.';
        }

        if (empty($description))
        {
            $error['description'] = 'Voert u alstublieft een omschrijving van de route in.';
        }

        if (isset($error))
        {
            foreach ($error as $key => $value)
            {
                if ($key == 'name')
                {
                    $nameUpdateErr = $value;
                }
                elseif ($key == 'size')
                {
                    $sizeUpdateErr = $value;
                }
                elseif ($key == 'extension')
                {
                    $extensionUpdateErr = $value;
                }
                elseif ($key == 'description')
                {
                    $descriptionUpdateErr = $value;
                }
            }
        }

        if (!isset($error))
        {
            $ambassadorDir = '/../ambassadors/';

            if (!is_dir(__DIR__ . $ambassadorDir))
            {
                mkdir(__DIR__ . $ambassadorDir, 0700);
                if (!file_exists(__DIR__ . $ambassadorDir . $filename))
                {
                    move_uploaded_file($file_tmp, __DIR__ . $ambassadorDir . $filename);
                    saveAmbassadorWithImage($id, $name, $filename, $filesize, $file_ext, $description);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
            else
            {
                if (!file_exists(__DIR__ . $ambassadorDir . $filename))
                {
                    move_uploaded_file($file_tmp, __DIR__ . $ambassadorDir . $filename);
                    saveAmbassadorWithImage($id, $name, $filename, $filesize, $file_ext, $description);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
        }
    }
}

function saveAmbassadorWithoutImage($id, $name, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('UPDATE ambassador SET ambassador = :ambassador, description = :description, updated_at = :updated_at WHERE ambassadorID = :ambassadorID');
    $stmt->execute(array(
        ':ambassador' => $name,
        ':description' => $description,
        ':updated_at' => $now->format('d-m-Y H:i:s'),
        ':ambassadorID' => $id
    ));

    header('Location: ambassador.php');
}

function saveAmbassadorWithImage($id, $name, $filename, $filesize, $file_ext, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('UPDATE ambassador SET ambassador = :ambassador, image = :image, size = :size, extension = :extension, description = :description, updated_at = :updated_at WHERE ambassadorID = :ambassadorID');
    $stmt->execute(array(
        ':ambassador' => $name,
        ':image' => $filename,
        ':size' => $filesize,
        ':extension' => $file_ext,
        ':description' => $description,
        ':updated_at' => $now->format('d-m-Y H:i:s'),
        ':ambassadorID' => $id
    ));

    header('Location: ambassador.php');
}

function deleteAmbassador()
{
    global $db;

    $id = $_GET['id'];

    $result = getAmbassadorById();

    $ambassadorDir = '/../ambassadors/';

    if (file_exists(__DIR__ . $ambassadorDir . $result['image']))
    {
        unlink(__DIR__ . $ambassadorDir . $result['image']);
    }

    $stmt = $db->prepare('DELETE FROM ambassador WHERE ambassadorID = :ambassadorID');
    $stmt->execute(array(
        ':ambassadorID' => $id
    ));

    header('Location: ambassador.php');
}

function checkCharity()
{
    global $titleErr, $charityErr, $sizeErr, $extErr, $descErr;

    $title = checkData($_POST['title']);
    $filename = $_FILES['charity']['name'];
    $filesize = $_FILES['charity']['size'];
    $filetmp = $_FILES['charity']['tmp_name'];
    $file_ext = strtolower(end(explode('.', $_FILES['charity']['name'])));
    $description = $_POST['description'];

    $extensions = array('jpg', 'jpeg', 'png');

    if (empty($title))
    {
        $error['title'] = 'Voert u alstublieft de naam van het goede doel in.';
    }

    if ($filename == "")
    {
        $error['charity'] = 'Selecteert u altublieft een logo van het goede doel.';
    }

    if ($filesize == 0)
    {
        $error['sizes'] = 'De afbeeldingen mogen niet groter dan 2MB zijn.';
    }

    if (in_array($file_ext, $extensions) === false)
    {
        $error['extension'] = 'Alleen JPEG, JPG en PNG afbeeldingen zijn toegestaan.';
    }

    if (empty($description))
    {
        $error['description'] = 'Voert u alstublieft een beschrijving van het goede doel in.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'title')
            {
                $titleErr = $value;
            }
            elseif ($key == 'charity')
            {
                $charityErr = $value;
            }
            elseif ($key == 'sizes')
            {
                $sizeErr = $value;
            }
            elseif ($key == 'extension')
            {
                $extErr = $value;
            }
            elseif ($key == 'description')
            {
                $descErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $charityDir = '/../charities/';

        if (!is_dir(__DIR__ . $charityDir))
        {
            mkdir(__DIR__ . $charityDir, 0700);
            if (!file_exists(__DIR__ . $charityDir . $filename))
            {
                move_uploaded_file($filetmp, __DIR__ . $charityDir . $filename);
                saveCharity($title, $filename, $filesize, $file_ext, $description);
            }
            else
            {
                $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
            }
        }
        else
        {
            if (!file_exists(__DIR__ . $charityDir . $filename))
            {
                move_uploaded_file($filetmp, __DIR__ . $charityDir . $filename);
                saveCharity($title, $filename, $filesize, $file_ext, $description);
            }
            else
            {
                $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
            }
        }
    }
}

function saveCharity($title, $filename, $filesize, $file_ext, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('INSERT INTO charity(userID, title, image, size, extension, description, added_at) VALUES(:userID, :title, :image, :size, :extension, :description, :added_at)');
    $stmt->execute(array(
        ':userID' => getUserId(),
        ':title' => $title,
        ':image' => $filename,
        ':size' => $filesize,
        ':extension' => $file_ext,
        ':description' => $description,
        ':added_at' => $now->format('d-m-Y H:i:s')
    ));

    header('Location: charity.php');
}

function getCharities()
{
    global $db;

    $stmt = $db->prepare('SELECT charityID, title, image, description, added_at, updated_at FROM charity');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getCharityById()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('SELECT title, image, description FROM charity WHERE charityID = :charityID');
    $stmt->execute(array(
        ':charityID' => $id
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function updateCharity()
{
    global $titleUpdateErr, $sizeUpdateErr, $extensionUpdateErr, $descriptionUpdateErr;

    $title = checkData($_POST['title']);
    $filename = $_FILES['charity']['name'];
    $filesize = $_FILES['charity']['size'];
    $file_ext = strtolower(end(explode('.', $_FILES['charity']['name'])));
    $file_tmp = $_FILES['charity']['tmp_name'];
    $description = $_POST['description'];

    $extensions = array('jpg', 'jpeg', 'png');

    $id = $_GET['id'];

    if (empty($filename))
    {
        if (empty($title))
        {
            $error['title'] = 'Voert u alstublieft een titel voor de route in.';
        }

        if (empty($description))
        {
            $error['description'] = 'Voert u alstublieft een omschrijving van de route in.';
        }

        if (isset($error))
        {
            foreach ($error as $key => $value)
            {
                if ($key == 'title')
                {
                    $titleUpdateErr = $value;
                }
                elseif ($key == 'description')
                {
                    $descriptionUpdateErr = $value;
                }
            }
        }

        if (!isset($error))
        {
            saveCharityWithoutImage($id, $title, $description);
        }
    }

    if (!empty($filename))
    {
        if (empty($title))
        {
            $error['title'] = 'Voert u alstublieft een titel voor de route in.';
        }

        if ($filesize == 0)
        {
            $error['size'] = 'De afbeelding mag niet groter zijn dan 2MB.';
        }

        if (in_array($file_ext, $extensions) === false)
        {
            $error['extension'] = 'Alleen JPEG, JPG en PNG zijn toegestaan.';
        }

        if (empty($description))
        {
            $error['description'] = 'Voert u alstublieft een omschrijving van de route in.';
        }

        if (isset($error))
        {
            foreach ($error as $key => $value)
            {
                if ($key == 'title')
                {
                    $titleUpdateErr = $value;
                }
                elseif ($key == 'size')
                {
                    $sizeUpdateErr = $value;
                }
                elseif ($key == 'extension')
                {
                    $extensionUpdateErr = $value;
                }
                elseif ($key == 'description')
                {
                    $descriptionUpdateErr = $value;
                }
            }
        }

        if (!isset($error))
        {
            $charityDir = '/../charities/';



            if (!is_dir(__DIR__ . $charityDir))
            {
                mkdir(__DIR__ . $charityDir, 0700);
                if (!file_exists(__DIR__ . $charityDir . $filename))
                {
                    move_uploaded_file($file_tmp, __DIR__ . $charityDir . $filename);
                    saveCharityWithImage($id, $title, $filename, $filesize, $file_ext, $description);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
            else
            {
                if (!file_exists(__DIR__ . $charityDir . $filename))
                {

                    move_uploaded_file($file_tmp, __DIR__ . $charityDir . $filename);
                    saveCharityWithImage($id, $title, $filename, $filesize, $file_ext, $description);
                }
                else
                {
                    $existErr = 'Dit bestand bestaat al (' . $filename . ') . Geef het bestand een andere naam, of u kunt annuleren.';
                }
            }
        }
    }
}

function saveCharityWithoutImage($id, $title, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('UPDATE charity SET title = :title, description = :description, updated_at = :updated_at WHERE charityID = :charityID');
    $stmt->execute(array(
        ':title' => $title,
        ':description' => $description,
        ':updated_at' => $now->format('d-m-Y H:i:s'),
        ':charityID' => $id
    ));

    header('Location: charity.php');
}

function saveCharityWithImage($id, $title, $filename, $filesize, $file_ext, $description)
{
    global $db;

    $now = new DateTime();

    $stmt = $db->prepare('UPDATE charity SET title = :title, image = :image, size = :size, extension = :extension, description = :description, updated_at = :updated_at WHERE charityID = :charityID');
    $stmt->execute(array(
        ':title' => $title,
        ':image' => $filename,
        ':size' => $filesize,
        ':extension' => $file_ext,
        ':description' => $description,
        ':updated_at' => $now->format('d-m-Y H:i:s'),
        ':charityID' => $id
    ));

    header('Location: charity.php');
}

function deleteCharity()
{
    global $db;

    $id = $_GET['id'];

    $result = getCharityById();

    $charityDir = '/../charities/';

    if (file_exists(__DIR__ . $charityDir . $result['image']))
    {
        unlink(__DIR__ . $charityDir . $result['image']);
    }

    $stmt = $db->prepare('DELETE FROM charity WHERE charityID = :charityID');
    $stmt->execute(array(
        ':charityID' => $id
    ));

    header('Location: charity.php');
}

function checkContactForm()
{
    global $nameErr, $emailErr, $subjectErr, $messageErr, $humanErr, $result;

    $name = checkData($_POST['name']);
    $email = checkData($_POST['email']);
    $subject = checkData($_POST['subject']);
    $message = checkData($_POST['message']);
    $human = checkData($_POST['human']);

    if (empty($name))
    {
        $error['name'] = 'Vult u alstublieft uw naam in.';
    }

    if (empty($email))
    {
        $error['email'] = 'Vult u alstublieft uw emailadres in.';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error['email'] = 'Het ingevulde emailadres is niet geldig.';
    }

    if (empty($subject))
    {
        $error['subject'] = 'Voert u alstublieft een onderwerp in.';
    }

    if (empty($message))
    {
        $error['message'] = 'Voert u alstublieft een bericht in.';
    }

    if ($_SESSION['answer'] != $human)
    {
        $error['human'] = 'Uw antwoordt is niet correct.';
    }

    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'name')
            {
                $nameErr = $value;
            }
            elseif ($key == 'email')
            {
                $emailErr = $value;
            }
            elseif ($key == 'subject')
            {
                $subjectErr = $value;
            }
            elseif ($key == 'message')
            {
                $messageErr = $value;
            }
            elseif ($key == 'human')
            {
                $humanErr = $value;
            }
        }
    }

    if (!isset($error))
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From:' . $name . " <" . $email . ">\r\n";
        $headers .= 'Reply-To: ' . $email . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $to = 'info@bruintjebeertocht.nl';
        $subject = 'Bericht - Contactformulier Bruintje Beer Tocht';
        $body =
            "<table>" .
            "<tr><td>Naam</td><td>" . $name . "</td></tr>" .
            "<tr><td>Email</td><td>" . $email . "</td></tr>" .
            "<tr><td>Onderwerp</td><td>" . $subject . "</td></tr>" .
            "<tr><td>Bericht</td><td>" . $message . "</td></tr>";

        if (mail($to, $subject, $body, $headers))
        {
            $result = 'Bedankt voor uw bericht! U hoort spoedig van ons!';
        }
        else
        {
            $result = 'Er is een fout opgetreden tijdens het verzenden. Probeert u het later nog eens.';
        }
    }
}

function captcha()
{
    //$math = 0;

    $digit1 = mt_rand(1,20);
    $digit2 = mt_rand(1,20);
    if( mt_rand(0,1) === 1 ) {
        $math = "$digit1 + $digit2";
        $_SESSION['answer'] = $digit1 + $digit2;
    } else {
        $math = "$digit1 - $digit2";
        $_SESSION['answer'] = $digit1 - $digit2;
    }

    return $math;
}