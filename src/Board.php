<?php

namespace ProjectPHP;


class Board
{

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;


    public function __construct(){
        $this-> id = new Project();
        $this-> name = new Project();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

}