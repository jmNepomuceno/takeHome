<?php
    include("../database/connection2.php");
    session_start();
    
    $search_lname = $_POST['search_lname'];
    $search_fname = $_POST['search_fname'];
    $search_mname = $_POST['search_mname'];

    $sql = "none";
    $search_lname = filter_input(INPUT_POST, 'search_lname');
    $search_fname = filter_input(INPUT_POST, 'search_fname');
    $search_mname = filter_input(INPUT_POST, 'search_mname');

    $hpatcode = $_SESSION['hospital_code'];
    $hpatcode = (string) $hpatcode;
    $conditions = array();

    if (!empty($search_lname)) {
        $conditions[] = "patlast LIKE :search_lname";
    }

    if (!empty($search_fname)) {
        $conditions[] = "patfirst LIKE :search_fname";
    }

    if (!empty($search_mname)) {
        $conditions[] = "patmiddle LIKE :search_mname";
    }

    $sql = "SELECT patfirst, patlast, patmiddle, hpercode, patbdate, status FROM hperson";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    if($hpatcode != '1437'){
        $sql .= " AND hpatcode=:hpatcode;";
    }
    $stmt = $pdo->prepare($sql);

    if (!empty($search_lname)) {
        $search_lname_param = "%$search_lname%";
        $stmt->bindParam(':search_lname', $search_lname_param, PDO::PARAM_STR);
    }

    if (!empty($search_fname)) {
        $search_fname_param = "%$search_fname%";
        $stmt->bindParam(':search_fname', $search_fname_param, PDO::PARAM_STR);
    }

    if (!empty($search_mname)) {
        $search_mname_param = "%$search_mname%";
        $stmt->bindParam(':search_mname', $search_mname_param, PDO::PARAM_STR);
    }

    
    if($hpatcode != '1437'){
        $stmt->bindParam(':hpatcode', $hpatcode, PDO::PARAM_STR);   
    }
    
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $finalJsonString = json_encode($data);
    // echo $finalJsonString;
    
    if(count($data) >= 1){
        for($i = 0; $i < count($data); $i++){
            if ($i % 2 == 0) {
                $bg_color = "#526c7a";
            } else {
                $bg_color = "transparent";
            }

            echo '<div id="search-sub-div" class="search-sub-div" style="background: '. $bg_color .'">';
            echo ' <div id="upper-part-sub-div">';
            echo    '<h1 id="pat-id-h1" class="search-sub-code">'. $data[$i]['hpercode'] .'</h1>';
            echo      '<div>';
            echo          '<h1">'. $data[$i]['patbdate'] .'</h1>';
            echo           '<span class="fa-solid fa-user"></span>';
            echo     ' </div>';
            echo    '</div>';
            echo ' <div id="lower-part-sub-div">';
            echo     ' <h3 id="pat-name">'. $data[$i]['patlast'] . ", " . $data[$i]['patfirst'] . " " . $data[$i]['patmiddle'] .'</h3>';
            echo      '<h3 id="pat-stat">' . (isset($data[$i]['status']) ? "Status: Referred-" . $data[$i]['status'] : "Status: Not yet referred") . '</h3>';
            echo  '</div>';
            echo'</div>';
        }
    }else{
        echo "No User Found";
    }
?>