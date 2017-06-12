<?php
ini_set('display_errors', 1);
require_once __DIR__."/vendor/autoload.php";

switch($_POST['action']) {
    case 'getBoardActivities':
        $resultatBoard= new ProjectPHP\ControllerBoard();
        if (isset($_POST['boardId']) && isset($_POST['fromdate']) && isset($_POST['todate'])) {
            $resultatActivityBoard= $resultatBoard->activityBoard($_POST['boardId'],$_POST['fromdate'],$_POST['todate']);
            $result = [
                'status' => 'success',
                'result' => $resultatActivityBoard
            ];
            echo json_encode($result);
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'result' => 'Missing Parameter boardId'
                ]
            );
        }

        break;
    case 'getTaskByBoard':
        if (isset($_POST['boardId']) && isset($_POST['fromdate']) && isset($_POST['todate'])) {
            $resultatTasks = new ProjectPHP\ControllerIndex();
            $resultatTasksByBoard= $resultatTasks->allTasks($_POST['boardId'],$_POST['fromdate'],$_POST['todate']);
            $result = [
                'status' => 'success',
                'result' => json_decode($resultatTasksByBoard)
            ];
            echo json_encode($result);
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'result' => 'Missing Parameter boardId'
                ]
            );
        }

        break;
    case 'getTaskDetails':
        if (isset($_POST['taskId']) & isset($_POST['boardId'])) {
            $resultatTasksDetails = new ProjectPHP\ControllerIndex();
            $resultatTasksInDetails= $resultatTasksDetails->tasksDetails($_POST['boardId'],$_POST['taskId']);
            $result = [
                'status' => 'success',
                'result' => json_decode($resultatTasksInDetails)
            ];
            echo json_encode($result);
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'result' => 'Missing Parameter boardId or TaskId'
                ]
            );
        }

        break;
    case "sendEmail":
        if (isset($_POST['board']) && (isset($_POST['loggedTime'])) && (isset($_POST['fromdate'])) && (isset($_POST['todate']))){
            $mailGun = new \ProjectPHP\callMailGun();
            $mail = $mailGun->callMail($_POST['board'],$_POST['loggedTime'],$_POST['fromdate'],$_POST['todate']);
            $result = [
                'status' => 'success',
                'result' => $mail
            ];
            echo json_encode($result);
        }
        else {
            echo json_encode(
                [
                    'status' => 'error',
                    'result' => 'Missing Parameter boardId'
                ]
            );

        }
        break;

    default:

}


