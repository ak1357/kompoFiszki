<?php

namespace App\Components\Word;
use App\Interfaces;

interface WordInterface extends Interfaces\iUser
{
    
    public function getWordId();
    public function validateWord();
    public function addWord();
    public function updateWord();
    public function deleteWord();
    public function getWord();
    public function getWordCollections();
	
}


class main implements WordInterface
{
    
    private $db;
    private $errors;
    private $template;
    private $userId;
    private $wordId;
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
        $this->wordId = (!empty($uriParams[1])) ? (int) $uriParams[1] : 0;
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
            
            case 'add' :
                
                $this->template = 'addWord';
                
                if (isset($_POST['sent'])) {
                    
                    if ($this->validateWord() && $this->addWord())
                        header('Location: ' . $context . '/collection/view/' . (int) $_POST['collection'] . '?success_3');
					
                    else
                        header('Location: ?error_0');
                    
                }
                break;
                
            case 'edit' :
                
                $this->template = 'editWord';
                
                if (isset($_POST['sent'])) {
                    
                    if ($this->validateWord() && $this->updateWord())
                        header('Location: ' . $context . '/collection/view/' . (int) $_POST['collection'] . '?success_4');
					
                    else
                        header('Location: ?error_1');
                    
                }
                break;
                
            case 'delete' :
				
                $this->template = 'deleteWord';
                
                if (isset($_POST['sent'])) {
                    
                    if ($this->deleteWord())
                        header('Location: ' . $context . '/collection/view/' . (int) $_POST['collection'] . '?success_5');
					
                    else
                        header('Location: ?error_2');
                    
                }
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
     * Getter pola $wordId;
     * @return int wartość pola $wordId
     */
    
    public function getWordId ()
    {
        return (int) $this->wordId;
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
     * Walidacja formularza dodawania/edycji fiszki
     * @return bool true jeśli formularz poprawny, false jeśli nie
     */
	
    public function validateWord ()
    {
        
		if (empty($_POST['name']))
			$this->errors[] = 'emptyName';
		
		if (empty($_POST['translation']))
			$this->errors[] = 'emptyTranslation';
		
		if (empty($_POST['collection']) || (int) $_POST['collection'] < 1)
			$this->errors[] = 'emptyCollection';
        
        return (is_array($this->getErrors()) && sizeof($this->getErrors()) == 0) ? true : false;
    }
	
    
    /**
     * Dodawanie fiszki
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */	
	
    public function addWord ()
    {
        
		$params = [
			null,
			$_POST['name'],
			$_POST['translation'],
			$_POST['collection']
		];
		
        $q = $this->db->prepare(
				'INSERT INTO words (
					word_id,
					word_name,
					word_translation,
					collection_id
				) VALUES (?, ?, ?, ?)'
			);
		
        return ($q && $q->execute($params)) ? true : false;
    }
    
	
    /**
     * Aktualizacja fiszki
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
	
    public function updateWord ()
    {
		
        $params = [
			$_POST['name'],
			$_POST['translation'],
			$_POST['collection'],
			(int) $this->getWordId()
		];
		
        $q = $this->db->prepare(
				'UPDATE words
				SET word_name = ?,
					word_translation = ?,
					collection_id = ?
				WHERE word_id = ?'
			);
		
        return ($q && $q->execute($params)) ? true : false;
    }
	
	
    /**
     * Usuwanie fiszki
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
	
    public function deleteWord ()
    {
		
		$isAdmin = $this->isAdmin();
		$params = [
			$this->getWordId()
		];
		
		if (!$isAdmin)
			$params[] = $this->getUserId();
		
        $q = $this->db->prepare(
				'DELETE FROM words
				WHERE word_id = ?
				AND collection_id IN (
					SELECT collection_id
					FROM collections
					WHERE ' . ((!$isAdmin) ? 'user_id = ?' : 'collection_is_public = 1') .'
				)'
			);
		
		return ($q && $q->execute($params)) ? true : false;
    }
	
	
    /**
     * Pobieranie konfiguracji pojedynczej fiszki
     * @return array tablica z konfiguracją fiszki
     */
	
    public function getWord ()
    {
    	
		$result = [];
		$params = [
			$this->getWordId()
		];
		
        $q = $this->db->prepare('SELECT * FROM words WHERE word_id = ?');
        
		if ($q && $q->execute($params))
        	$result = $q->fetch();
		
        return $result;
    }
    
    
    /**
     * Pobranie wszystkich nazw kolekcji
     * @return array tablica reprezentująca kolekcje
     */
	
    public function getWordCollections ()
    {
        
        $params = [];
        $isAdmin = $this->isAdmin();
        $limiter = ($isAdmin)? 'WHERE collection_is_public = 1' : 'WHERE user_id = ?';
        
        if (!$isAdmin)
            $params[] = $this->getUserId();
        
        $result = [];
        $q = $this->db->prepare('SELECT collection_id, collection_name FROM collections ' . $limiter);
        $q->execute($params);
        
        foreach ($q as $collection) {
            $result[$collection['collection_id']] = [
                'collection_name' => $collection['collection_name']
            ];
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