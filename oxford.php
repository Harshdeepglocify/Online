<?php
include "config/config.php";

$dict = trim($_REQUEST['dict']);
$term = trim($_REQUEST['term']);

$error = '';
if($dict == '' || $term == ''){
    $error = 1;
}

if(empty($error) && $error == ''){

if(!empty($term) && $term != NULL) { 

    $file = fopen(ADMIN_URL."profane.csv","r");
    while(! feof($file)) {
        $line_of_text[] = fgetcsv($file, 1024);
        
    }

    foreach ($line_of_text as $value) {        
        foreach ((array) $value as $new_value) {
            $new_arr[] = $new_value;  
        } 
    }

    //echo "<pre>"; print_r(array_filter($new_arr)); 
    if(in_array(strtolower($term), array_map('strtolower', array_map('trim', $new_arr)))) {
        //echo "Found - " . $term . "<br>";
        echo "Please find another word!";
        exit();
    } 

    fclose($file);

    /* LAST CODING
    $qry = "SELECT * FROM profane_words WHERE FIND_IN_SET('$term', word)";
    $res = mysqli_query($con, $qry);
   
    if (mysqli_num_rows($res) > 0) {
        echo "Please find another word!";
        exit();
    } */
} 

$curl = curl_init();

if ($dict == 'define') {
//    $url = "https://od-api.oxforddictionaries.com/api/v1/entries/en/" . $term;
    $url = "https://od-api.oxforddictionaries.com/api/v2/entries/en-gb/".$term."?strictMatch=false";
    
}

if ($dict == 'thesaurus') {
    $url = "https://od-api.oxforddictionaries.com:443/api/v1/entries/en/" . $term . "/synonyms;antonyms";
//    $url = "https://od-api.oxforddictionaries.com:443/api/v2/thesaurus/en-us/" . $term . "?fields=synonyms%2Cantonyms&strictMatch=false";
//    $url = "https://od-api.oxforddictionaries.com/api/v2/thesaurus/en/".$term."?fields=synonyms%2Cantonyms&strictMatch=false";
}
//echo $url; exit;
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
//        'Content-Type:application/json',
        'Accept: application/json',
        "app_id: 4a73bd61",
        "app_key: f9ebe8e1bf9d8fde58db52d13b445f72",
//        "app_key: 384e536c47a51dc9ec625010b488d223",
//        "authorization: Basic YWNjZXNzaWJ5dGU6T3hmb3JkMyE0QA==",
        "cache-control: no-cache",
        "postman-token: 3a58d98f-906d-7427-67c8-c1e25feea615"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
//echo '<pre>';
if ($err) {
    echo "cURL Error #:" . $err;
    exit;
} else {
    $result = json_decode($response);
    
    if(!empty($result)){
        if ($dict == 'thesaurus') {

            $lexicalCategory_array = array();
            $html = 'success~' . $term . '~';
            if (!empty($result->results)) {
                foreach ($result->results as $val) {
                    if (!empty($val->lexicalEntries)) {
                        foreach ($val->lexicalEntries as $v) {
                            if (!empty($v->entries)) {
                                foreach ($v->entries as $ent_val) {
                                    if (!empty($ent_val->senses)) {

                                        foreach ($ent_val->senses as $sens_val) {
                                            if (isset($sens_val->antonyms)) {
                                                $lexicalCategory_array[$v->lexicalCategory]['antonyms'][] = $sens_val->antonyms[0]->text; // v1
//                                                $lexicalCategory_array[$v->lexicalCategory->text]['antonyms'][] = $sens_val->antonyms[0]->text; // v2
                                            }

                                            if (isset($sens_val->synonyms)) {
                                                $lexicalCategory_array[$v->lexicalCategory]['synonyms'][] = $sens_val->synonyms[0]->text; // v1
//                                                $lexicalCategory_array[$v->lexicalCategory->text]['synonyms'][] = $sens_val->synonyms[0]->text; // v2
                                            }

                                            if(isset($sens_val->subsenses)){
                                                if(!empty($sens_val->subsenses)){
                                                    foreach($sens_val->subsenses as $subsence_value){
                                                        if(isset($subsence_value->synonyms) && !isset($subsence_value->registers)){
                                                            $lexicalCategory_array[$v->lexicalCategory]['synonyms'][] = $subsence_value->synonyms[0]->text;   // v1
//                                                            $lexicalCategory_array[$v->lexicalCategory->text]['synonyms'][] = $subsence_value->synonyms[0]->text;   // v2
                                                        }

                                                        if (isset($subsence_value->antonyms) && !isset($subsence_value->registers)) {
                                                            $lexicalCategory_array[$v->lexicalCategory]['antonyms'][] = $subsence_value->antonyms[0]->text;   // v1
//                                                            $lexicalCategory_array[$v->lexicalCategory->text]['antonyms'][] = $subsence_value->antonyms[0]->text;   // v2
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $change_key = '';
            $lexicate_array = array();
            $html1 = '';
            if (!empty($lexicalCategory_array)) {
                foreach ($lexicalCategory_array as $key => $value) {
                    $html1 .= $key . '|';
                    $change_key = $key;
                    if (isset($value['synonyms'])) {
                        $html1 .= implode('`', array_unique($value['synonyms']));
                    }
                    if (isset($value['antonyms'])) {
                        $html1 .= '|';
                        $html1 .= implode('`', array_unique($value['antonyms']));
                    }
                    $lexicate_array[] = $html1;
                    $html1 = '';
                }

            $thesaurus_result = $html.implode('~', $lexicate_array);
//            header('Content-Type:application/json');
//            echo json_encode($thesaurus_result);
            echo $thesaurus_result;
            exit;
            
            }else{
                echo 'no data found.';
                exit;
        }
            
        }
        if ($dict == 'define') {
        
        $html_define = '';
        $lexicalCategory_define_array = array();
        if (!empty($result->results)) {
            foreach ($result->results as $val) {
                if (!empty($val->lexicalEntries)) {
                    foreach ($val->lexicalEntries as $v) {
                        if (!empty($v->entries)) {
                            foreach ($v->entries as $ent_val) {
                                if (!empty($ent_val->senses)) {
                                    foreach ($ent_val->senses as $sens_val) {
                                        if (isset($sens_val->definitions)) {
                                            $lexicalCategory_define_array[$v->lexicalCategory->text][] = $sens_val->definitions[0];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if(!empty($lexicalCategory_define_array)){
            foreach($lexicalCategory_define_array as $key=>$val){
                if($key == 'Noun'){
                    $html_define .= 'success' ;
                    if(!empty($val)){
                        foreach($val as $value){
                            $html_define .= '~'. $term .'|Noun|'.$value;
                        }
                    }
                }
                
            }
            
            echo $html_define;
            exit;
        }else{
            echo 'no data found.';
            exit;
        }
        
//        header('Content-Type:application/json');
//        echo json_encode($html_define);
        
        
    }
    }else{
        echo 'no data found.';
        exit;
    }
}
    
}else{
    echo 'fail';
    exit;
}