<?php
/**
 * Created by PhpStorm.
 * User: emelyne
 * Date: 26.05.17
 * Time: 14:17
 */

namespace ProjectPHP;


class Tasks
{

    private $tasksId;
    private $title;
    private $assignee;
    private $column;
    private $lane;
    private $size;
    private $timeLogged;
    private $productOwner;
    private $allocatedTo;
    private $rapport;


    public function getTasksId(): Project
    {
        return $this->tasksId;
    }

    public function setTasksId(Project $tasksId)
    {
        $this->tasksId = $tasksId;
    }

    public function getTitle(): Project
    {
        return $this->title;
    }

    public function setTitle(Project $title)
    {
        $this->title = $title;
    }

    public function getAssignee(): Project
    {
        return $this->assignee;
    }

    public function setAssignee(Project $assignee)
    {
        $this->assignee = $assignee;
    }

    public function getColumn(): Project
    {
        return $this->column;
    }

    public function setColumn(Project $column)
    {
        $this->column = $column;
    }

    public function getLane(): Project
    {
        return $this->lane;
    }

    public function setLane(Project $lane)
    {
        $this->lane = $lane;
    }

    public function getSize(): Project
    {
        return $this->size;
    }

    public function setSize(Project $size)
    {
        $this->size = $size;
    }

    public function getTimeLogged(): Project
    {
        return $this->timeLogged;
    }

    public function setTimeLogged(Project $timeLogged)
    {
        $this->timeLogged = $timeLogged;
    }

    public function getProductOwner(): Project
    {
        return $this->productOwner;
    }

    public function setProductOwner(Project $productOwner)
    {
        $this->productOwner = $productOwner;
    }

    public function getAllocatedTo(): Project
    {
        return $this->allocatedTo;
    }

    public function setAllocatedTo(Project $allocatedTo)
    {
        $this->allocatedTo = $allocatedTo;
    }

    public function getRapport(): Project
    {
        return $this->rapport;
    }

    public function setRapport(Project $rapport)
    {
        $this->rapport = $rapport;
    }


}