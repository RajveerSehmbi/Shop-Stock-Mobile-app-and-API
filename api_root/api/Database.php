<?php

class Database {


    public function __construct(private string $host,
                                private string $name, 
                                private string $user, 
                                private string $password) {}
    public function getConnection(): PDO {

        $dsn = "pgsql:host={$this->host};port=5432;dbname={$this->name};sslmode=require;options=endpoint=ep-winter-violet-50230661";

        return new PDO($dsn, $this->user, $this->password, 
        [PDO::ATTR_EMULATE_PREPARES => false, 
        PDO::ATTR_STRINGIFY_FETCHES => false]
        );
    }
}

?>