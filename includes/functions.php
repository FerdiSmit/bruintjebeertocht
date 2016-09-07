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

function getNews()
{
    global $db;

    $stmt = $db->prepare("SELECT title, shortDesc, last_updated, longDesc FROM news WHERE last_updated = (SELECT MAX(last_updated) FROM news) LIMIT 5");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
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

    $stmt = $db->prepare('INSERT INTO poll(userID, question, answer1, answer2, answer3) VALUES (:userID, :question, :answer1, :answer2, :answer3)');
    $stmt->execute(array(
        ':userID' => getUserId(),
        ':question' => $question,
        ':answer1' => $answer1,
        ':answer2' => $answer2,
        ':answer3' => $answer3
    ));

    header('Location: dashboard.php?p=poll.php');
}

function getQuestion()
{
    global $db;

    $stmt = $db->prepare('SELECT question FROM poll');
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

function checkAbout()
{
    global $titleErr, $aboutErr;

    $title = checkData($_POST['title']);
    $about = checkData($_POST['about']);

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
        ':created_at' => $now->format('Y-m-d H:i:s')
    ));
}

function getAbout()
{
    global $db;

    $stmt = $db->prepare('SELECT aboutID, title, created_at, last_updated FROM about');
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
    $about = checkData($_POST['about']);

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
            ':last_updated' => $now->format('Y-m-d H:i:s'),
            ':aboutID' => $id
        ));

        header('Location: dashboard.php?ao=aboutOverview.php');
    }
}

function deleteAbout()
{
    echo 'test';
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('DELETE FROM about WHERE aboutID = :aboutID');
    $stmt->execute(array(
        ':aboutID' => $id
    ));

    header('Location: dashboard.php?ao=aboutOverview.php');
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
        ':created_at' => $now->format('Y-m-d H:i:s')
    ));

    header('Location: dashboard.php?alo=albumOverview.php');
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
            ':updated_at' => $now->format('Y-m-d H:i:s'),
            ':albumID' => $id
        ));

        header('Location: dashboard.php?alo=albumOverview.php');
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

    header('Location: dashboard.php?alo=albumOverview.php');
}

function checkPictures()
{
    global $pictureErr, $extensionErr, $sizeErr, $existErr;

    foreach ($_FILES['picture']['tmp_name'] as $key => $tmp_name)
    {
        $file_name = $_FILES['picture']['name'][$key];
        $file_size = $_FILES['picture']['size'][$key];
        $file_tmp = $_FILES['picture']['tmp_name'][$key];
        $file_type = $_FILES['picture']['type'][$key];

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
        ':added_at' => $now->format('Y-m-d H:i:s')
    ));

    header('Location: dashboard.php?alo=albumOverview.php');
}

function getPictures()
{
    global $db;

    $id = $_GET['id'];

    $stmt = $db->prepare('SELECT picture FROM picture WHERE albumID = :albumID');
    $stmt->execute(array(
        ':albumID' => $id
    ));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}