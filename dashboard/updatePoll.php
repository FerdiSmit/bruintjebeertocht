<?php

include('dashboard.php');

$result = getPollById();

if (isset($_POST['submit']))
{
    updatePoll();
}

?>

    <div id="poll">
        <form role="form" method="post" class="form-horizontal">
            <div class="col-xs-10">
                <div class="form-group col-sm-7">
                    <label for="question">Vraag</label>
                    <input type="text" name="question" id="question" class="form-control" value="<?php echo $result['question']; ?>">
                    <?php
                    if (isset($questionUpdateErr))
                    {
                        echo '<span class="error">' . $questionUpdateErr . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-sm-7">
                    <label for="answer1">Antwoord 1</label>
                    <input type="text" name="answer1" id="answer1" class="form-control" value="<?php echo $result['answer1']; ?>">
                    <?php
                    if (isset($answerUpdateErr1))
                    {
                        echo '<span class="error">' . $answerUpdateErr1 . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-sm-7">
                    <label for="answer2">Antwoord 2</label>
                    <input type="text" name="answer2" id="answer2" class="form-control" value="<?php echo $result['answer2']; ?>">
                    <?php
                    if (isset($answerUpdateErr2))
                    {
                        echo '<span class="error">' . $answerUpdateErr2 . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-sm-7">
                    <label for="answer1">Antwoord 3</label>
                    <input type="text" name="answer3" id="answer3" class="form-control" value="<?php echo $result['answer3']; ?>">
                    <?php
                    if (isset($answerUpdateErr3))
                    {
                        echo '<span class="error">' . $answerUpdateErr3 . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-sm-7">
                    <input type="submit" class="btn btn-primary" name="submit" value="Opslaan">
                </div>
            </div>
        </form>
    </div>

<?php
include('footer.php');
?>