<?php

class Database {
	private $pdo;

	public function __construct() {

	    $this->pdo = new PDO
		(
			'mysql:host=localhost;dbname=prends-ton-dessin-en-main;charset=UTF8',
			'root',
			'',
			[
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			]
		);

		$this->pdo->exec('SET NAMES UTF8');
	}

    public function simpleSql($sql) {

        $this->pdo->prepare($sql);

        return $this->pdo->lastInsertId();
    }

	public function sql($sql, array $values = array(), $checkExecution = true) {

		$query = $this->pdo->prepare($sql);

		$hasExecuted = $query->execute($values);

		if ($checkExecution === true)
		{
			return $hasExecuted;
		}

		return $this->pdo->lastInsertId();
	}

    public function querySimpleOne($sql) {

        $query = $this->pdo->prepare($sql);

        return $query->fetch();
    }

    public function queryOne($sql, array $criteria = array()) {

        $query = $this->pdo->prepare($sql);

        $query->execute($criteria);

        return $query->fetch();
	}

    public function querySimpleAll($sql) {

        $query = $this->pdo->prepare($sql);

        return $query->fetchAll();
    }

	public function queryAll($sql, array $criteria = array()) {

        $query = $this->pdo->prepare($sql);

        $query->execute($criteria);

        return $query->fetchAll();
	}

	// Pour personnaliser totalement la requête si besoin est
    public function getPdo(): PDO {

        return $this->pdo;
    }
}