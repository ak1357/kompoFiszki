<?php

namespace App\Components\Quiz;
use App\Interfaces;

interface QuizInterface extends Interfaces\iCollection, Interfaces\iUser
{
	
	public function getQuizWords ();
	
}

class main implements QuizInterface
{
    
    private $db;
    private $errors;
    private $template;
    private $userId;
	private $collectionId;	
    private $componentName;
    private $componentVersion;
    
	
    /**
     * Konstruktor klasy komponentu
     * @param array $config konfiguracja modułu, np. nazwa i wersja
     * @param object $db obiekt bazy danych
     * @param array $uriParams tablica paramertów z adresu uri
     */
	
    public function __construct ($config, $db, $uriParams = null)
    {
		
        $this->db = $db;
        $this->errors = [];
        $this->template = null;
        $this->userId = (!empty($_SESSION['user'])) ? (int) $_SESSION['user'] : 0;
		$this->collectionId = (!empty($uriParams[1])) ? (int) $uriParams[1] : 0;
        $this->componentName = (!empty($config['name'])) ? $config['name'] : '';
        $this->componentVersion = (!empty($config['version'])) ? $config['version'] : '';
        
        $this->controller($uriParams);
        $this->loadAssets();
		
    }
    
    
    /**
     * Kontroler komponentu
     * @param array $uriParams tablica paramertów z adresu uri
     */
	
    public function controller ($uriParams)
    {
        
        global $context;
        
        switch ((!empty($uriParams[0])) ? $uriParams[0] : null)
        {
            
			case 'collection' :	
				$this->template = 'collectionQuiz';
                break;
                
            default:
                return;
                
        }
        
        $templateFile = $_SERVER['DOCUMENT_ROOT'] . $context . '/Components/' .
						$this->componentName . '/' . $this->componentVersion . '/views/' . $this->template . '.php';
        
        if (file_exists($templateFile))
            require_once $templateFile;
		
        else
            require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/style/template/404.php';
        
    }
    
    
    /**
     * Funkcja ładująca dodatkowe zasoby komponentu, np. skrypty javascript
     */
    
    public function loadAssets () 
    {
        
        global $context;
        
        $componentDir = $_SERVER['DOCUMENT_ROOT'] . $context .'/Components/' .
                        $this->componentName . '/' . $this->componentVersion;
                        
        if (file_exists($componentDir .'/scripts.js.php'))
            require_once $componentDir . '/scripts.js.php';
        
    }
	
	
    /**
     * Getter pola $userId;
     * @return int wartość pola $userId
     */
    
    public function getUserId ()
    {
        return (int) $this->userId;
    }
	
	
    /**
     * Getter pola $collectionId;
     * @return int wartość pola $collectionId
     */
    
    public function getCollectionId ()
    {
        return (int) $this->collectionId;
    }
	
	
    /**
     * Getter pola $errors;
     * @return array wartość pola $errors
     */
    
    public function getErrors ()
    {
        
        return (is_array($this->errors)) ? $this->errors : [];
        
    }
    
	
    /**
     * Pobranie fiszek z pojedynczego zestawu na potrzeby quizu
     * @return array tablica reprezentująca fiszki
     */
    
    public function getQuizWords ()
    {
        
        $result = [];
        $params = [
            $this->getCollectionId(),
            $this->getUserId()
        ];
        
        $q = $this->db->prepare(
				'SELECT * 
				FROM words 
				WHERE collection_id = ? 
				AND collection_id IN (
					SELECT collection_id 
					FROM collections 
					WHERE user_id = ? 
					OR collection_is_public = 1
				)'
			);
        
        if ($q && $q->execute($params)) {
            
            foreach ($q as $word) {
                $result[$word['word_id']] = [
                    'word_name' => $word['word_name'],
                    'word_translation' => $word['word_translation']
                ];
            }
        }
        
        return $result;
    }
	
	
    /**
     * Metoda sprawdzająca, czy zalogowany użytkownik jest administratorem 
     * @return bool true jeśli jest, false jeśli nie jest
     */
    
    public function isAdmin ()
    {
        $params = [
            ((!empty($_SESSION['user'])) ? (int) $_SESSION['user'] : 0)
        ];
        
        $q = $this->db->prepare(
                'SELECT COUNT(*) AS sum
                FROM users 
                WHERE user_id = ?
                AND user_login = "admin"');
        
        return  ($q && $q->execute($params) && $q->fetch()['sum'] > 0) ? true : false;
    }
    
}