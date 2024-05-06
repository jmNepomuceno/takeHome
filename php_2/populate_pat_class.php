<?php 
    session_start();
    include("../database/connection2.php");

    $sql = "SELECT classifications FROM classifications";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    $classification_arr = array();
    for($i = 0; $i < count($data); $i++){
        array_push($classification_arr, $data[$i]['classifications']);
    }

    // $finalJsonString = json_encode($data);
    // echo $finalJsonString;
    for($i = 0; $i < count($classification_arr) + 1; $i++){
        if($i < count($classification_arr)){
            echo '<div class="classification-sub-div w-auto h-[40px] p-2 m-2 rounded-xl font-bold text-white bg-[#1f292e] tracking-widest cursor-pointer hover:border-4 border-red-600">'.$classification_arr[$i].'</div>';
        }else{
            echo 
            '
                <div id="dynamic-width-div" class="h-[40px] p-2 m-2 rounded-xl font-bold text-white bg-[#1f292e] flex flex-row justify-center items-center overflow-hidden">
                    <i id="add-classification-icon" class="fa-solid fa-circle-plus text-lg cursor-pointer"></i> 
                    <input type="text" id="add-classification-input" class="hidden p-2 w-[90px] bg-transparent outline-none" placeholder="Input text" autocomplete="off" />
                </div>
            ';
        }
    }
?>