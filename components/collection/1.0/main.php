<?php

namespace App\Components\Collection;
use App\Interfaces;

interface CollectionInterface extends Interfaces\iCollection, Interfaces\iUser
{
    
    public function getUserId();
    public function getCollectionId();
    public function validateCollection();
    public function addCollection();
    public function updateCollection();
    public function deleteCollection();
    public function getCollection();
    public function getCollectionWords();
    public function getCollections($publicCollections);
    
}


class main implements CollectionInterface
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
            
            case 'add' :
                
                $this->template = 'addCollection';
                
                if (isset($_POST['sent'])) {
                    
                    if ($this->validateCollection() && $this->addCollection())
                        header('Location: ' . $context . '/collection/private-list?success_0');
                        
                    else
                        header('Location: ?error_0');
                    
                }
                break;
                
            case 'edit' :
                $this->template = 'editCollection';
                
                if (isset($_POST['sent'])) {
                    
                    if ($this->validateCollection() && $this->updateCollection())
                        header('Location: ' . $context . '/collection/private-list?success_1');
                    
                    else
                        header('Location: ?error_1');
                    
                }
                
                break;
                
            case 'delete' :
                
                $this->template = 'deleteCollection';
                
                if (isset($_POST['sent'])) {
                    
                    if ($this->deleteCollection())
                        header('Location: ' . $context . '/collection/private-list?success_2');
                    
                    else
                        header('Location: ?error_2');
                    
                }
                
                break;
                
            case 'view' :
                $this->template = 'viewCollection';
                break;
                
            case 'private-list' :
                $this->template = 'privateCollection';
                break;
                
            case 'public-list' :
                $this->template = 'publicCollection';
                break;
                
            default:
                return;
                
        }
        
        $templateFile = $_SERVER['DOCUMENT_ROOT'] . $context . '/Components/' . $this->componentName . '/' . $this->componentVersion . '/views/' . $this->template . '.php';
        
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
     * Walidacja formularza dodawania/edycji zbioru
     * @return bool true
     */
    
    public function validateCollection ()
    {
        
        if (empty($_POST['name']))
            $this->errors[] = 'emptyName';
        
        return (is_array($this->getErrors()) && sizeof($this->getErrors()) == 0) ? true : false;
    }
    
    
    /**
     * Dodawanie pustego zbioru zbioru
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
    
    public function addCollection ()
    {
        
        if ($this->getUserId() < 1)
            return false;
        
        $params = [
            null,
            $_POST['name'],
            $_POST['desc'],
            $this->getUserId(),
            (($_POST['is_public'] == 'yes') ? 1 : 0)
        ];
            
        $q = $this->db->prepare(
				'INSERT INTO collections (
					collection_id,
					collection_name, 
					collection_desc,
					user_id,
					collection_is_public
				) VALUES (?, ?, ?, ?, ?)'
			);
		
        return ($q && $q->execute($params)) ? true : false;
    }
    
    
    /**
     * Aktualizacja parametrów zestawu fiszek
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
    
    public function updateCollection ()
    {
        $isAdmin = self::isAdmin();
        $params = [
            $_POST['name'],
            $_POST['desc'],
            (($_POST['is_public'] == 'yes') ? 1 : 0),
            (int) $this->getCollectionId()
        ];
        
        if (!isAdmin)
            $params[] = $this->getUserId();
        
        $q = $this->db->prepare(
				'UPDATE collections 
				SET collection_name = ?,
					collection_desc = ?, 
					collection_is_public = ? 
				WHERE collection_id = ? ' .
				((!isAdmin) ? 'AND user_id = ?' : '')
			);
		
        return ($q && $q->execute($params)) ? true : false;
    }
    
    
    /**
     * Usuwanie zestawu fiszek
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
    
    public function deleteCollection ()
    {
        
        $isAdmin = $this->isAdmin();
        $params = [ $this->getCollectionId() ];
        
        if (!$isAdmin)
            $params[] = $this->getUserId();
        
        $q = $this->db->prepare(
				'DELETE FROM words 
				WHERE collection_id = ?' .
				((!$isAdmin) ? ' AND collection_id IN (
					SELECT collection_id 
					FROM collections 
					WHERE user_id = ?)' : '')
			);
        
        if ($q && $q->execute($params)) {
            
            $q = $this->db->prepare(
					'DELETE FROM collections 
					WHERE collection_id = ?' 
					. ((!$isAdmin) ? ' AND user_id =?' : '')
				);
            
            if ($q && $q->execute($params))
                return true;
        }
        
        return false;
    }
    
    
    /**
     * Pobieranie konfiguracji pojedynczego zestawu fiszek
     * @return array tablica z konfiguracją zestawu
     */
    
    public function getCollection ()
    {
        
        if ($this->getUserId() < 1)
            return false;
        
        $result = [];
        $isAdmin = $this->isAdmin();
        $params = [
            $this->getCollectionId()
        ];
        
        if (!$isAdmin)
            $params[] = $this->getUserId();
        
        $q = $this->db->prepare(
				'SELECT * 
				FROM collections 
				WHERE collection_id = ? ' .
				((!$isAdmin) ? 'AND user_id = ?' : '')
			);
        
        if ($q && $q->execute($params))
            $result = $q->fetch();
        
        return $result;
    }
    
    
    /**
     * Pobranie wszystkich fiszek z pojedynczego zestawu
     * @return array tablica reprezentująca fiszki
     */
    
    public function getCollectionWords ()
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
     * Pobranie wszystkich kolekcji
     * @param bool $publicCollections przełącznik trybu, 
        wartość true powoduje wyszukiwanie publicznych kolekcji
     * @return array tablica reprezentująca konfigurację kolekcji
     */
    
    public function getCollections ($publicCollections = false)
    {
        
        $q;
        $result = [];
        
        if (!$publicCollections && $this->getUserId() < 1)
            return false;
        
        if ($publicCollections)
            
            $q = $this->db->prepare(
                    'SELECT 
                        collections.*,
                        users.user_login 
                    FROM collections 
                    LEFT JOIN users ON collections.user_id = users.user_id
                    WHERE collections.collection_is_public = 1' . 
                    (($this->getUserId() > 0) ? ' AND collections.user_id != ?' : '')
                );
            
        else
            $q = $this->db->prepare('SELECT * FROM collections WHERE user_id = ?');
        
        if ($q && $q->execute(($this->getUserId() > 0) ? [$this->getUserId()] : [])) {
        
            foreach ($q as $collection) {
                $result[$collection['collection_id']] = [ 
                    'collection_name' => $collection['collection_name'],
                    'collection_desc' => $collection['collection_desc'],
                    'collection_is_public' => $collection['collection_is_public'],
                    'user_id' => $collection['user_id'],
                    'user_login' => ((!empty($collection['user_login'])) ? $collection['user_login'] : '')
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