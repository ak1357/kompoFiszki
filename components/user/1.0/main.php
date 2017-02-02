<?php

namespace App\Components\User;
use App\Interfaces;

interface UserInterface extends Interfaces\iUser
{
	
	public function validateUser();
	public function addUser();
	public function updateUser();
	public function deleteUser();
	public function getUser();
	public function getUsers();
	public function loginUser();
	public function logoutUser();
}

class main implements UserInterface

// class main implements UserInterface
{
    
    private $db;
    private $errors;
    private $template;
    private $userId;
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
            
            case 'register' :
                
                $this->template = 'registerUser';
                
                if (isset($_POST['sent'])) {
                    
                    if ($this->validateUser() && $this->addUser())            
                        header('Location: ' . $context . '/user/login?success_0');
                    else
                        header('Location: ?error_0');
                	
                }
                break;
				
            case 'edit' :
				
                $this->template = 'editUser';
                
                if (isset($_POST['sent'])) {
                    
                    if (!empty($uriParams[1]) && (int) $uriParams[1] > 0 && $this->isAdmin())
                        $this->userId = $uriParams[1];
					
                    if ($this->validateUser() && $this->updateUser()) {
						
						if ($this->isAdmin())
							header('Location: ' . $context . '/user/list?success_1');
						
						else
                        	header('Location: ' . $context . '/user/edit' . ((!empty($uriParams[1])) ? '/' . $uriParams[1] : '') . '?success_1');
                    	
					} else
                        header('Location: ?error_1');
                    
                }                
                break;
                
            case 'delete' :
                
                $this->template = 'deleteUser';
                
                if (isset($_POST['sent'])) { 
                    
                    if (!empty($uriParams[1]) && (int) $uriParams[1] > 0 && $this->isAdmin())
                        $this->userId = $uriParams[1];
                    
                    if ($this->deleteUser() && ((!$this->isAdmin() && $this->logoutUser()) || true))
                        header('Location: ' . $context . '/user/list?success_2');
                    else
                        header('Location: ?error_2');
                    
                }
                break;
				
                
            case 'login' :
                
                $this->template = 'loginUser';
                
                if (isset($_POST['sent'])) {
					
					
                    if ($this->loginUser())
                        header('Location: http://' . $_SERVER['SERVER_NAME'] . $context . '/collection/private-list');
					
                    else
                        header('Location: ?error_3');
						
						
                }                
                break;
            
            case 'logout' :
                $this->template = 'loginUser';
                $this->logoutUser();
                header('Location: ' . $context . '/user/login');
                break;
            
            case 'list' :
                $this->template = 'listUser';
                break;
			
            default : return;
                
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
     * Getter pola $errors;
     * @return array wartość pola $errors
     */
    
    public function getErrors ()
    {
        
        return (is_array($this->errors)) ? $this->errors : [];
        
    }
	
    
    /**
     * Walidacja formularza dodawania/edycji użytkownika
     * @return bool true jeśli formularz poprawny, false jeśli nie
     */
    
    public function validateUser ()
    {
        
        if (empty($_POST['login']))
            $this->error = [ 'emptyLogin' ];
        
        $params = [$_POST['login']];
        if ($this->getUserId() > 0)
            $params[] = $this->getUserId();
        
        $q = $this->db->prepare('SELECT count(*) AS sum FROM users WHERE user_login = ?' . (($this->getUserId() > 0) ? ' AND user_id != ?' : ''));
        $q->execute($params);
        
        if ((int) $q->fetch()['sum'] > 0)
            $this->errors[] = 'loginUsed';

        if ($this->getUserId() > 0) { // Użytkownik posiadający konto
            
            $tmpUserId = $this->getUserId();
            $user = $this->getUser();
            
            if (!empty($_SESSION['user'])) // Zmiana id użytkownika na id admina, 
                $this->userId = $_SESSION['user']; // żeby mógł zmieniać hasło bez znajomości bieżącego
            
            if (!empty($_POST['password_n']) && (empty($_POST['password_c']) || empty($_POST['password_r'])))
                $this->errors[] = 'passwordIncomplete';
            
            if (!empty($_POST['password_n']) && !$this->isAdmin() && sha1($_POST['password_c']) !== ((!empty($user['user_password'])) ? $user['user_password'] : ''))
                $this->errors[] = 'passwordIncorrect';
            
            if (!empty($_POST['password_n']) && (mb_strlen($_POST['password_n']) <=3 || $_POST['password_n'] !== $_POST['password_r']))
                $this->errors[] = 'passwordMismatch';
            
            $this->userId = $tmpUserId; // Przywrócenie id użytkownika
            
        } else { // Użytkownik rejestrujący się
            
            if (empty($_POST['password']) || empty($_POST['password_r']))
                $this->errors[] = 'passwordEmpty';
            
            if (!empty($_POST['password']) && (mb_strlen($_POST['password']) <=3 || $_POST['password'] !== $_POST['password_r']))
                $this->errors[] = 'passwordMismatch';
            
        }
        
        return (is_array($this->errors) && sizeof($this->errors) == 0) ? true : false;
    }
    
	
    /**
     * Dodawanie użytkownika
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
	
    public function addUser ()
    {
		
        $params = [
			null,
			$_POST['login'],
			sha1($_POST['password']),
			0
		];
		
        $q = $this->db->prepare('SELECT count(*) AS sum FROM users WHERE user_login = ?');
        
        if ($q && $q->execute([$_POST['login']]) && (int) $q->fetch()['sum'] < 1) {
                
            $q = $this->db->prepare(
					'INSERT INTO users (
						user_id,
						user_login,
						user_password,
						user_permissions
					) VALUES (?, ?, ?, ?)'
				);
			
            return ($q && $q->execute($params)) ? true : false;
        }
        
        return false;
    }
    
    
    /**
     * Aktualizacja użytkownika
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
	
    public function updateUser ()
    {
		
        $params = [
			(($this->isAdmin() && $this->getUserId() == ((!empty($_SESSION['user'])) ? $_SESSION['user'] : -1 ))
			 ? 'admin'
			 : $_POST['login'])
		];
		
		
		
		
        if (!empty($_POST['password_n']))
            $params[] = sha1($_POST['password_n']);
        
        $params[] = $this->getUserId();
        
        $q = $this->db->prepare(
				'UPDATE users
				SET user_login = ?' . ((!empty($_POST['password_n'])) ? ', user_password = ?' : '') . '
				WHERE user_id = ?'
			);
			
        return ($q && $q->execute($params)) ? true : false;
    }
    
    
    /**
     * Usuwanie użytkownika
     * @return bool true w przypadku powodzenia, false w przypadku porażki
     */
	
    public function deleteUser ()
    {
        
        if ($this->getUserId() > 0 && !($this->isAdmin() && $this->getUserId() == $_SESSION['user'])) {
            
            $q = $this->db->prepare(
					'DELETE FROM words 
					WHERE collection_id IN ( 
						SELECT collection_id 
						FROM collections 
						WHERE user_id = ?
					)'
				);
			
            if (!$q || !$q->execute([$this->getUserId()]))
                return false;
            
            $q = $this->db->prepare('DELETE FROM collections WHERE user_id = ?');
            if (!$q || !$q->execute([$this->getUserId()]))
                return false;
            
            $q = $this->db->prepare('DELETE FROM users WHERE user_id = ?');
            if (!$q || !$q->execute([$this->getUserId()]))
                return false;
            
            return true;
        }
        
        return false;
    }
    
	
    /**
     * Pobieranie konfiguracji pojedynczego użytkownika
     * @return array tablica z konfiguracją użytkownika
     */
    
    public function getUser ()
    {
		
        $result = [];
		$params = [
			$this->getUserId()
		];
		
        $q = $this->db->prepare('SELECT * FROM users WHERE user_id = ?');
        
		if ($q && $q->execute($params))
			$result = $q->fetch();
		
		return $result;
    }
    
	
	
	/**
	 * Pobranie wszystkich użytkowników
	 * @return array tablica reprezentująca konfigurację użytkowników
	 */
    
    public function getUsers ()
    {
        
        $result = [];
        $q = $this->db->prepare('SELECT user_id, user_login FROM users');
        
		if ($q && $q->execute()) {
			foreach ($q as $user) {
				$result[$user['user_id']] = [
					'user_login' => $user['user_login']
				];
			}
		}
        
        return $result;
    }
    
	
	/**
	 * Metoda logująca użytkownika
	 * @return bool true w przypadku powodzenia, false w przypadku porażki
	 */
	
    public function loginUser ()
    {
        
		$params = [
			$_POST['login'],
			sha1($_POST['password'])
		];
		
        $q = $this->db->prepare('SELECT * FROM users WHERE user_login = ? AND user_password = ? LIMIT 1');
        
		if ($q && $q->execute($params)) {
			
			$userId = (int) $q->fetch()['user_id'];
			
			if ($userId > 0) {
				$_SESSION['user'] = $userId;
				return true;
			}
		}
		
        return false;
    }
    
	
	/**
	 * Metoda wylogowująca użytkownika
	 */
	
    public function logoutUser ()
    {
        unset($_SESSION['user']);
		return true;
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