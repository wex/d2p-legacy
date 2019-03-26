<?php

class TestCommand extends \Wex\Command
{
    public $table;

    public function __invoke()
    {
        try {
            $result = \Wex\App::$db->query('SELECT 1');
            $result->execute();
            $this->success("Database connection OK.");
        } catch (\Exception $e) {
            $this->error("No database connection: %s", $e->getMessage());
        }
    }
}