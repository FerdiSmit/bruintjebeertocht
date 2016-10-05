<?php
include('header.php');

if (isset($_POST['submit']))
{
    checkContactForm();
}
?>

<div id="middle" class="col-xs-8">
    <div class="contact">

        <div class="contact-header">
            <h3>Contactformulier</h3>
        </div>
        <hr/>

        <?php
        if (isset($result))
        {
            echo '<span class="success">' . $result . '</span>';
        }
        ?>

        <div class="contact-body">
            <form role="form" method="post">
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="name">Naam</label>
                        <input type="text" name="name" class="form-control" value="<?php if(isset($_POST['name'])) echo checkData($_POST['name']); ?>">
                        <?php
                        if (isset($nameErr))
                        {
                            echo '<span class="error">' . $nameErr . '</span>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="email">E-mail</label>
                        <input type="text" name="email" class="form-control" value="<?php if(isset($_POST['email'])) echo checkData($_POST['email']); ?>">
                        <?php
                        if (isset($emailErr))
                        {
                            echo '<span class="error">' . $emailErr . '</span>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="subject">Onderwerp</label>
                        <input type="text" name="subject" class="form-control" value="<?php if(isset($_POST['subject'])) echo checkData($_POST['subject']); ?>">
                        <?php
                        if (isset($subjectErr))
                        {
                            echo '<span class="error">' . $subjectErr . '</span>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="message">Bericht</label>
                        <textarea class="form-control" name="message"><?php if(isset($_POST['message'])) echo checkData($_POST['message']); ?></textarea>
                        <?php
                        if (isset($messageErr))
                        {
                            echo '<span class="error">' . $messageErr . '</span>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="human">Wat is <?php echo captcha() ?> = </label>
                        <input type="text" name="human" class="form-control">
                        <?php
                        if (isset($humanErr))
                        {
                            echo '<span class="error">' . $humanErr . '</span>';
                        }
                        ?>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <input type="submit" name="submit" class="btn btn-primary" value="Verzenden">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>