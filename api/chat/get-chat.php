<?php
session_start();
require '../../database/dbconfig.php';
$db = new DatabaseConnection();
$outgoing_id = $_SESSION['user']['id'];
$incoming_id = $_POST['incoming_id'];
$output = "";
$sql = "SELECT msgs.message, msgs.outgoing_id, msgs.incoming_id, usr.display_name, msgs.created_at FROM messages msgs LEFT JOIN users usr ON usr.id = msgs.outgoing_id
            WHERE (msgs.outgoing_id = :outgoing_id AND msgs.incoming_id = :incoming_id)
            OR (msgs.outgoing_id = :incoming_id AND msgs.incoming_id = :outgoing_id) ORDER BY msgs.created_at ASC";
$queryArgs = [
    ':incoming_id' => $incoming_id,
    ':outgoing_id' => $outgoing_id
];
$statement = $db->executePreparedQuery($sql, $queryArgs);
if($statement->rowCount() > 0){
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row){
        if($row['outgoing_id'] == $outgoing_id){
            $output .= '<li class="clearfix">
                            <div class="message-data align-right">
                                <span class="message-data-time">'. date('h:i A, j M, Y', strtotime($row['created_at'])) .'</span> &nbsp; &nbsp;
                                <span class="message-data-name">'. $row['display_name'] . '</span> <i class="fa fa-circle me"></i>
                            </div>
                            <div class="message other-message float-right">
                                '. $row['message'] . '
                            </div>
                        </li>';
        }else{
            $output .= '<li>
                            <div class="message-data">
                                <span class="message-data-name"><i class="fa fa-circle online"></i>'. $row['display_name'] . '</span>
                                <span class="message-data-time">'. date('h:i A, j M, Y', strtotime($row['created_at'])) .'</span>
                            </div>
                            <div class="message my-message">
                                '. $row['message'] . '
                            </div>
                        </li>';
        }
    }
}else{
    $output .= '<li class="clearfix">
                    <div class="message other-message float-right">
                        No messages are available. Once you send message they will appear here.
                    </div>
                </li>';
}
echo $output;
