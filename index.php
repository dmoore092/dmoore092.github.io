<!DOCTYPE html>
<html>
    <head>
        <rel="stylesheet" type="text/css" href="css/main.css" />
        <meta http-equiv="content-type" content="text/php; charset=utf-8" />

        <title>Site Template - Welcome!</title>
    </head>
    <body>
        <div class="Container">
            <div class="Header">

            </div><!-- end Header -->

            <div class="Body">
                <div id="info-form"
                     method = "POST"
                     action= "index.html"
                     onsubmit = "" >
                    <p>
                        <span class="span">Name:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">High School:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">City:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">State:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">Graduation Year:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">GPA:* &nbsp; </span>
                        <input type="text">
                    </p>
                </div><!-- end Form1 -->
                <div class="Form2">
                    <p>
                        <span class="span">Sport:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">Primary Position:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">Secondary Position:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">Height:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">Weight:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">Travel Team:* &nbsp; </span>
                        <input type="text">
                    </p>
                    <p>
                        <span class="span">Email:* &nbsp; </span>
                        <input type="text">
                    </p>
                </div><!-- end Form2 -->
            </div><!-- end Body -->
        </div><!-- End Container -->

    </body>
    <footer>
        <div class="Footer">
            <b>Copyright - 2018</b>
        </div>
    </footer>
</html>

<?php
            //conncet to database on pageload
            /* $mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_name); */
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