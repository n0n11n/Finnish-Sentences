<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Suomen kielioppisivu">
    <meta name="author" content="Lukas Heyny">
    <title>Suomalaisia lauseita</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto&display=swap&subset=latin-ext">

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-md5@0.7.3/src/md5.min.js"></script>

    
        <script>
        //function to check the finnish sentence for correctness by using an md5-hash
        var check = function(sender,hash) {
            var sentence = "";
            sender.siblings("select").each(function(i,select){
                sentence += select.value; //all the right words together no space
            })
            if(md5(sentence)==hash){
                sender.parent().siblings(".placeholder").hide();
                sender.parent().siblings(".incorrect").hide();
                sender.parent().siblings(".correct").show();
            }else{
                sender.parent().siblings(".placeholder").hide();
                sender.parent().siblings(".correct").hide();
                sender.parent().siblings(".incorrect").show();
            }
        }

        //function to go forward and backward between exercises. 
        //If half or more of an exercise is already visible then the function will go to the one after that exercise. 
        //f==1 = forward f==-1 = backward
        var navigate = function(f){
            $(".w3-display").each(function(){
                var sectionPos = $(this).offset().top;
                var jWindow = $(window);
                var windowH = jWindow.height();
                var scrollPos = jWindow.scrollTop();
                //console.log("sectionPos=",sectionPos);
                //console.log("scrollPos=",scrollPos);
                //console.log(Math.abs( scrollPos - sectionPos),windowH/2);
                if(Math.abs( scrollPos  + f*windowH - sectionPos) < windowH/2){ 
                    jWindow.scrollTop(sectionPos);
                    return false;
                    //console.log(sectionPos,windowH,scrollPos);
                }
                    
                

            })
            return false;
        }

    </script>
    <!-- file still short enough to keep inline style and script -->
    <style>
        * {
            box-sizing: border-box;
        }

        html,body {
            font-family: 'Roboto', sans-serif;
            font-family: 'Open Sans', sans-serif;
            scroll-snap-type: y proximity; /* scrollpoints for chrome and opera */
        }

        section {
            scroll-snap-align: start; /* scrollpoints for chrome and opera */
            border-bottom: 1px solid black;
            background-color: white;
            padding-top: calc(1.5 * 18px + 19px);
            height: 100vh;
            position: relative;
        }


        nav {
            position: sticky;
            top: 0;
            height: calc(1.5 * 18px + 19px);
            overflow: visible;
            z-index: 10
        }

        
        .myBar{
            position: absolute; /* necessary so the dropdown overlaps even when the nav is still in inital position */
            top: 0;
            left: 0;
            height:46px;
            overflow: visible;
            z-index: 11;
        }

        .sentenceContainer{
            width: 90vw; /*for firefox...*/
            width: fit-content; /* firefox doesn't know this */
            max-width: 90vw;            
        }
        
        .targetSentence{
            color: #2b5797;
        }
        
        .correct{
            color: green;
        }
        
        .incorrect{
            color: red;
        }
        
        .placeholder{
            visibility: hidden;
        }
        
        .correct, .incorrect{
            display: none;
        }
        
        .targetSentence, .finSentence, .correct, .incorrect, .placeholder{
            padding: 20px;
        }
        
        .fa {
            -webkit-text-stroke-width: 2px;
            -webkit-text-stroke-color: black;
        }
        
        @media (max-width: 600px) {
            .myBar{
                height: unset;
            }
            .myDropdown{
                margin: unset !important;
            }
        }
        

    </style>


</head>

<?php
    require "db.php";
    //function to create the exercise HTML as string from the database-row
    function printExercise($dbRow){
        $ret = "";
        $array = json_decode($dbRow["valinnat"]);
        //var_dump($array);
        $sentenceEn = $dbRow["kaanos"];
        $sentence = "";
        $ret .= "<div class=\"sentenceContainer w3-display-middle w3-xlarge w3-card-4 w3-light-grey\">\n";
        $ret .= "<div class='targetSentence w3-center'>$sentenceEn</div>\n";
        $ret .= "<div class='finSentence w3-bar w3-border'>";
        foreach($array as $select){
            $sentence .= $select[0]; //original array is expected to have right answer at index 0. $sentence is all the right words together with no space.
            shuffle($select); //randomize so that right answer isn't always first answer.
            $ret .= "<select class=\"w3-bar-item w3-mobile w3-center\">\n";
            foreach($select as $option){
                $ret .= "<option>$option</option>\n";
            }
            $ret .= "</select>\n";
        }
        //md5-hash for checking correctness so that students cant cheat with source-code.
        $ret .= '<button class="myDropdown w3-bar-item w3-button w3-metro-dark-blue w3-mobile w3-margin-left w3-ripple w3-hover-shadow" onclick="check($(this),\''.md5($sentence).'\');">Tarkista</button>'; 
        $ret .= "</div>\n";
        $ret .= "<div class='placeholder w3-center'>Placeholder</div>\n"; //always invisible
        $ret .= "<div class='incorrect w3-center'><i class=\"fa fa-times\"></i> Not correct.</div>\n";
        $ret .= "<div class='correct w3-center'><i class=\"fa fa-check\"></i> Well done!</div>\n";
        $ret .= "</div>\n";
        return $ret;
    }  

    $sql = "SELECT * FROM tehtavat"; //for now just print all the exercises. posibility to only print easiest or hardest by using the "tyypi"-field from the database.
    $result = mysqli_query($conn, $sql) or die("Kysely oli virheellinen" . mysqli_error($conn));
    $linklist = ""; //this will contain the dropdown a-href-#-links as HTML-string
    $exercises = ""; //this will contain the exercises as HTML-string
    $i = 0;
    
    while($col = mysqli_fetch_assoc($result)){
        $i++;
        if (mysqli_num_rows($result) == 0)
        {
            die("ei tehtäviä");
        }else{
            $exercises .= "<section id=\"exercise-$i\" class=\"w3-display\">\n"; //bare minimal. posibility to add hints, counters, statistics and more.
            $exercises .= "<h4 class=\"w3-display-topmiddle\" style=\"margin-top: 46px;\">Tehtävä $i</h4>\n";
            $exercises .= printExercise($col);
            $exercises .= "</section>\n";
            $linklist .= "<a href=\"#exercise-$i\" class=\"w3-bar-item w3-button w3-mobile\">Tehtävä $i</a>\n";
        }
    }
    
    ?>
    
    
    
    
<body>


    <header class="w3-container w3-metro-blue">
        <h1>Suomalaisia lauseita</h1>
    </header>
    <nav>
        <div class="myBar w3-bar w3-light-grey w3-border w3-large" style="">

            <a href="#" class="w3-bar-item w3-button w3-mobile w3-metro-green">Alkuun</a>
            <a href="#prev" class="w3-bar-item w3-button w3-mobile" onclick="return navigate(-1);"><i class="fa fa-caret-left"></i>&nbsp;&nbsp;Edellinen</a>
            <a href="#next" class="w3-bar-item w3-button w3-mobile" onclick="return navigate(1);">Seuraava&nbsp;&nbsp;<i class="fa fa-caret-right"></i></a>
            <div class="w3-dropdown-hover w3-mobile">
                <button class="w3-button">Tehtävät <i class="fa fa-caret-down"></i></button>
                <div class="w3-dropdown-content w3-bar-block w3-dark-grey" style="position: relative; overflow: hidden;">
                    <?php echo $linklist; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <?php echo $exercises; ?>
    </main>

</body>

</html>


