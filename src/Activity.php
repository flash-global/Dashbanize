<?php
/**
 * Created by PhpStorm.
 * User: emelyne
 * Date: 26.05.17
 * Time: 14:17
 */

namespace ProjectPHP;


class Activity
{

    private $cardId;
    private $author;
    private $event;
    private $date;
    private $text;

    public function __construct(){
        $this-> cardId = new Project();
        $this-> author = new Project();
        $this-> event = new Project();
        $this-> date = new Project();
        $this-> text = new Project();

    }

    public function getCardId()
    {
        return $this->cardId;
    }

    public function setCardId($CiD)
    {
        $this-> cardId = $CiD;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($Author)
    {
        $this-> author = $Author;
    }

    public function getDate(): Project
    {
        return $this->date;
    }

    public function setDate(Project $date)
    {
        $this->date = $date;
    }

    public function getEvent(): Project
    {
        return $this->event;
    }

    public function setEvent(Project $event)
    {
        $this->event = $event;
    }

    public function getText(): Project
    {
        return $this->text;
    }

    public function setText(Project $text)
    {
        $this->text = $text;
    }

}