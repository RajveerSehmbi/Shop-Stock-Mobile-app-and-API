<?php

class Database {


    public function __construct(private string $host,
                                private string $name, 
                                private string $user, 
                                private string $password) {}
    public function getConnection(): PDO {

        $dsn = "pgsql:host={$this->host};dbname={$this->name};options=endpoint%3Dep-winter-violet-50230661";

        return new PDO($dsn, $this->user, $this->password, 
        [PDO::ATTR_EMULATE_PREPARES => false, 
        PDO::ATTR_STRINGIFY_FETCHES => false]
        );
    }
}

?>