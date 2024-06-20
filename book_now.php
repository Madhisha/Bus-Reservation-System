<?php 
include 'db_connect.php';
extract($_POST);

$data = 'schedule_id = '.$sid.' ';
$data .= ', name = "'.$name.'" ';
$data .= ', qty = "'.$qty.'" ';
if(!empty($bid)){
    $data .= ', status = "'.$status.'" ';
    $update = $conn->query("UPDATE booked set ".$data." where id =".$bid);
    if($update){
        if ($status == 1) {
            $update_availability = $conn->query("UPDATE schedule_list SET availability = availability - $qty WHERE id = $sid");
            if (!$update_availability) {
                echo json_encode(array('status'=> 0, 'message' => 'Error updating availability.'));
                exit;
            }
        }
        echo json_encode(array('status'=> 1));
    }
    exit;
}

$i = 1;
$ref = '';
while($i == 1){
    $ref = date('Ymd').mt_rand(1,9999);
    $data .= ', ref_no = "'.$ref.'" ';
    $chk = $conn->query("SELECT * FROM booked where ref_no=".$ref)->num_rows;
    if($chk <=0)
        $i = 0;
}

$insert = $conn->query("INSERT INTO booked set ".$data);
if($insert){
    $update_availability = $conn->query("UPDATE schedule_list SET availability = availability - $qty WHERE id = $sid");
    if (!$update_availability) {
        echo json_encode(array('status'=> 0, 'message' => 'Error updating availability.'));
        exit;
    }
    echo json_encode(array('status'=> 1,'ref'=>$ref));
}
?>
