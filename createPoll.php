<?php

if (isset($_POST['submit']))
{
    checkPoll();
}

?>

<div id="poll">
    <form role="form" method="post" class="form-horizontal">
        <div class="form-group col-sm-7">
            <label for="question">Vraag</label>
            <input type="text" name="question" id="question" class="form-control">
            <?php
            if (isset($questionErr))
            {
                echo '<span class="error">' . $questionErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="answer1">Antwoord 1</label>
            <input type="text" name="answer1" id="answer1" class="form-control">
            <?php
            if (isset($answerErr1))
            {
                echo '<span class="error">' . $answerErr1 . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="answer2">Antwoord 2</label>
            <input type="text" name="answer2" id="answer2" class="form-control">
            <?php
            if (isset($answerErr2))
            {
                echo '<span class="error">' . $answerErr2 . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="answer1">Antwoord 3</label>
            <input type="text" name="answer3" id="answer3" class="form-control">
            <?php
            if (isset($answerErr3))
            {
                echo '<span class="error">' . $answerErr3 . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <input type="submit" class="btn btn-primary" name="submit" value="Opslaan">
        </div>
    </form>
</div>
