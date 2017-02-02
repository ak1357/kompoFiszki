<?php

namespace App\Logic;
use App\Components;


interface ClientInterface
{
    
    public function setDB();
    public function getDB();
	public function setCollection(Components\Collection\CollectionInterface $collectionImplementation);
	public function setQuiz(Components\Quiz\QuizInterface $quizImplementation);
	public function setSheet(Components\Sheet\SheetInterface $sheetImplementation);
    public function setUser(Components\User\UserInterface $userImplementation);
    public function setWord(Components\Word\WordInterface $wordImplementation);
    
}


class Client implements ClientInterface
{
    
    private $db;
    private $collection;
	private $quiz;
    private $sheet;	
	private $user;
    private $word;
    
	
    public function setDB ()
    {
        
        global $context;
        
        $this->db = $db = new \PDO('sqlite:' . $_SERVER['DOCUMENT_ROOT'] . $context .'/logic/db/database.sqlite3');
    }
    
    
    public function getDB ()
    {
        return $this->db;
    }
    
	
    public function setCollection(Components\Collection\CollectionInterface $collectionImplementation)
    {
        $this->collection = $collectionImplementation;
    }
	
	
    public function setQuiz(Components\Quiz\QuizInterface $quizImplementation)
    {
        $this->user = $quizImplementation;
    }
	
	
    public function setSheet(Components\Sheet\SheetInterface $sheetImplementation)
    {
        $this->sheet = $sheetImplementation;
    }
	
	
    public function setUser(Components\User\UserInterface $userImplementation)
    {
        $this->user = $userImplementation;
    }
    
	
    public function setWord(Components\Word\WordInterface $wordImplementation)
    {
        $this->word = $wordImplementation;
    }
    
}