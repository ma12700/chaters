<?php
    include 'connectDB.php';
    $sql = "SELECT `Q_id`,`question` FROM `questions`;";
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        $hint = '';
        while($row = $result->fetch_assoc()) {
            $hint .= '<option value="'. $row["Q_id"] .'">'. $row["question"] .'</option><br>';
          }
        echo $hint;
    }else{
        echo '<option value="0">error with database please check internet</option><br>';
    }

?>