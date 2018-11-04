<?php
    error_reporting(0);
    /*BEGINNING of php validation */
    if(!empty($_POST)){
        $errors = [];
        //name
        if(strlen($_POST["name"]) == 0){
            $errors[] = "Please enter a name.<br/>";
        }
        
        //subject
        if(strlen($_POST["subject"]) == 0){
            $errors[] = "Please enter a subject.<br/>";
        }
        
        //comment
        if(strlen($_POST["comment"]) == 0){
            $errors[] = "Please enter a comment.<br/>";
        }
        
        if(!empty($errors)){
            $feedback .= "There were some errors submitting the form:<br/>";
            
            for ($i = 0; $i < count($errors); $i++){
                $feedback .= $errors[$i];
            }
        }
    }
    
   // else{
      //  $feedback = "does this work?";
    //}
    /*END OF PHP VALIDATION */
    
?>
<!DOCTYPE html>
<html lang="en">
<?php $relpath= ""; $title="PR|Comments"; $page="comments";
$imgpath="http://serenity.ist.rit.edu/~dwmdis/240/project2/assets/media/images/";
$linkpath = "http://serenity.ist.rit.edu/~dwmdis/240/project2/";
$templinkpath = "http://localhost:8888/Web%20and%20Mobile%202/individualfinal/Web2IndividualFinal/";
?>
<?php include "assets/inc/head.php" ?>

        <?php include "assets/inc/header.php" ?>
        <?php include "assets/inc/db_con.php" ?>
    <script><?php include "assets/scripts/cs_validation.js" ?></script>
    <div id="shifteverythingdown"></div>
    <h1><span id="tagline">Leave Your Comments Here!</span></h1>
        <form id="comments-form"
              method = "POST"
              action= "comments.php"
              onsubmit = "return validate();">
 <!-- name --><p>
                <span class="span">Name:* &nbsp; </span>
                <input type = "text"
                       id = "name"
                       name= "name"
                       size = "25"
                       maxlength = "25"
                       value="<?= $_POST['name']  ?>"
                       onclick="changeColor(this.id)"/> 
              </p>
<!-- subject --><p>
                <span class="span">Subject:*</span>
                <input type = "text"
                       id = "subject"
                       name = "subject"
                       size = "47"
                       maxlength = "47"
                       value="<?= $_POST['subject']  ?>" 
                       onclick="changeColor(this.id)" />
               </p>
<!-- comment--><p>
                <textarea rows = "10"
                          cols = "56"
                          name = "comment"
                          id = "comment"
                          placeholder = "Leave a comment here..."
                          onclick="changeColor(this.id)"></textarea>
               </p>
                <div class="g-recaptcha" data-sitekey="6LfdMDwUAAAAAOsxcmAK8OWn38cU4OQm_0P8uzlN" data-callback="enableBtn"></div>
 <!--submit--><p>
                    <input type="submit"
                           value="Submit"
                           name = "btnsubmit"
                           class="btn-submit"
                           id="btnSubmit"/>
                </p>
        </form>
    <script>
        document.getElementById("btnSubmit").disabled = true;
        function enableBtn(){
            document.getElementById("btnSubmit").disabled = false;
        }
    </script>
    <hr/>
    <div id="chat-box-area">
        <?php
            //conncet to database on pageload
            $mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
                //CONNECT TO DATABASE
                if(!$mysqli){
                    echo "connection error: " . mysqli_connect_error();
                    die();
                }
                //QUERY THE DATABASE for comments to display
                $query = "SELECT * 
                          FROM final_exam_comments
                          ORDER BY `id` DESC";

                $result = mysqli_query($mysqli, $query);

                $num_rows = mysqli_affected_rows($mysqli);
                echo "<p>There are $num_rows comments.</p>";
                echo "<div id='display-comments'>";
                if($result && $num_rows > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<div id='comments'>Name: " . $row["name"] . "..." . "<br/>" . "Subject: " . $row["subject"] . "<br/>" . "Comment: " . "<em>" . $row["comment"] . "</em>" . "</div>" . "<br/><hr/>";
                    }
                }
                echo "</div>";
            //check if there is any feedback, display it to the screen
            if(!empty($feedback)){
                echo $feedback;
                echo $msg;
            }
            //SEND COMMENT TO DATABASE
            if(isset($_POST["btnsubmit"])){
                //block cross-site scripting, html entities(apersand etc), trim white space
                $name    =  htmlentities(strip_tags(trim($_POST["name"])));
                $subject =  htmlentities(strip_tags(trim($_POST["subject"])));
                $comment =  htmlentities(strip_tags(trim($_POST["comment"])));
        
                //block sql injections
                $name    = mysqli_real_escape_string($mysqli, $_POST["name"]);
                $subject = mysqli_real_escape_string($mysqli, $_POST["subject"]);
                $comment = mysqli_real_escape_string($mysqli, $_POST["comment"]);
        
                //build the database query
                $query   = "INSERT INTO final_exam_comments 
                            SET name = '" . $name . "',
                                subject = '" . $subject . "',
                                comment = '" . $comment. "'";
        
                $result = mysqli_query($mysqli, $query);
                $num_rows = mysqli_affected_rows($mysqli);
                
                //reset all the fields
                echo "<script type='text/javascript'> document.location = 'comments.php'; </script>";
                
                if($result && $num_rows > 0){
                    $msg = $_POST["name"] . " was successfully saved.";
                }
            }
        ?>
    </div>
<?php include "assets/inc/footer.html" ?>
