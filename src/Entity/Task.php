<?php
namespace App\Entity;

class Task
{
    protected $task;
    protected $dueDate;

    public function getTask(){
        return $this->task;
    }

    public function setTask($task){
        $this->task = $task;
    }

    public function getDueDate(){
        return $this->task;
    }

    public function setDueDate(\Datetime $dueDate = null){
        $this->$dueDate = $dueDate;
    }
}