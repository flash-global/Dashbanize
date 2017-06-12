<?php

namespace ProjectPHP;

use GuzzleHttp\Exception\ClientException;

class ControllerBoard
{

    /**
     * @var callAPI callAPI
     */
    public $callApi;

    public function __construct()
    {
        $this->callApi = new callAPI();
        $this->Project = new Project();
    }

    public function projectBoard(){
        $endPoint ='get_projects_and_boards/format/json';
        $result = $this->callApi->call($endPoint);
        $result = json_decode($result,true);
        $projects = $result['projects'];
        $listProject=[];
        foreach ($projects as $project){
            $proj = new \ProjectPHP\Project();
            $boards = [];
            $proj->setId($project['id']);
            $proj->setName($project['name']);

            foreach ($project['boards'] as $board){
                $b = new Board();
                $b->setId($board['id']);
                $b->setName($board['name']);
                $boards[] = $b;
            }
            $proj->setBoards($boards);
            $listProject[]=$proj;
        }

        return $listProject;
    }


    public function callByName($boardid,$fromdate,$todate,$name){
        $numPage = 1;
        $tabJson=[];
        while ($numPage <= 3){
            $endPoint ='get_board_activities/boardid/' . $boardid .'/fromdate/'. str_replace('/','-',$fromdate) .
                '/todate/' . str_replace('/','-',$todate) . '/format/json/page/'.$numPage.'/author/'.$name.'';
            try{
                $result = $this->callApi->call($endPoint);
                $tabJson[]=$result;
            }
            catch (ClientException $clientException){
                $numPage=4;
                break;
            }
            $numPage ++;
        }

        return $tabJson;
    }

    public function activityBoard($boardid,$fromdate,$todate){
        $config = include 'conf.local.php';
        $resultat=[];
        $listName=$config['DEV'];
        foreach ($listName as $name){
            $result = $this->callByName($boardid,$fromdate,$todate,$name);
            $resultat[]=$result;
        }

        return $resultat;
    }

}











