<?php
include('header.php');
?>

<div id="middle" class="col-xs-8">
    <div class="contact">

        <div class="contact-header">
            <h3>Contactformulier</h3>
        </div>
        <hr/>

        <div class="contact-body">
            <form role="form" method="post">
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="name">Naam</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="subject">Onderwerp</label>
                        <input type="text" name="subject" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label for="message">Bericht</label>
                        <textarea class="form-control" name="message"></textarea>
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