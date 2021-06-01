<?php

class Paginator
{
    private $table;

    public $class;
    public $page;
    public $per_page;
    public $total_pages;
    public $total_count;
    public $data;

    public function __construct($table, $page=1, $per_page=9)
    {
        $this->table = $table;
        $this->class = $table->class;
        $this->page = $page;
        $this->per_page = $per_page;
        $this->total_count = $table->count();
        $this->total_pages = $this->getTotalPages();
        $this->data = $this->getData();
    }

    private function getTotalPages()
    {
        return ceil($this->total_count / $this->per_page);
    }

    private function getData()
    {
        $data = [];
        $start = $this->per_page * ($this->page - 1);

        for ($i=$start; $i < $start + $this->per_page && $i < $this->table->count(); $i++)
            array_push($data, $this->table->get($i));

        return $data;
    }
}
