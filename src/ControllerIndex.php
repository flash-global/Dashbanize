<?php

namespace ProjectPHP;


class ControllerIndex
{

    /**
     * @var callAPI callAPI
     */
    public $callApi;

    public function __construct()
    {
        $this->callApi = new callAPI();
        $this->Tasks = new Tasks(); // à poursuivre..
    }

    public function tridate($a,$b)
    {
        $a = strtotime($a['updatedat']);
        $b = strtotime($b['updatedat']);
        if ($a == $b) {
            return 0;
        }

        return ($a < $b) ? -1 : 1;
    }

    public function allTasks($boardid,$fromdate,$todate){
        $endPoint ='get_all_tasks/boardid/' . $boardid .'/fromdate/'. str_replace('/','-',$fromdate) .
            '/todate/' . str_replace('/','-',$todate) . '/format/json';
        $result = $this->callApi->call($endPoint);
        $tabResult = json_decode($result,true);
        usort($tabResult,[$this, 'tridate']);
        $taille=sizeof($tabResult);
        if ( (strtotime($tabResult[0]['updatedat']) < strtotime($fromdate) ) && (strtotime($tabResult[$taille-1]['updatedat']) < strtotime($todate)) ){
            return "No card for this selection of date";
        }
        else {
            return $result;
        }

        return $result;
    }


    public function tasksDetails($boardid,$taskid){
        $endPoint ='get_task_details/boardid/' . $boardid . '/taskid/' . $taskid. '/format/json';
        $result = $this->callApi->call($endPoint);

        return $result;
    }

}