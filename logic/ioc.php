<?php

namespace App\Logic;
use App\Components;


class Ioc
{
    
    private $client = null;
    
    public function __construct($appConfig)
    {
        
        global $context, $uri;
		
		
        // Obsługa adresu
		
		$redirectUrl = ((!empty($_SERVER['REDIRECT_URL'])) ? $_SERVER['REDIRECT_URL'] : '');
        $uri = array_values(array_filter(explode('/', str_replace($context, '', $redirectUrl))));
        
        if (!empty($uri)) {
            $uriComponent = $uri[0];
            $uri = [$uriComponent => $uri];
            array_shift($uri[$uriComponent]);
        	
			
            // Obsługa błędu po wywołaniu nierozpoznanego komponentu
            
            if (!in_array($uriComponent, array_keys($appConfig)))
                require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/style/template/404.php';
            
        } else
            require_once $_SERVER['DOCUMENT_ROOT'] . $context . '/style/template/home.php';
        
		
        // Uruchomienie klas
        
        $this->client = new client;
        $this->client->setDB();
        
        $this->client->setCollection(new Components\Collection\main(['name' => 'collection', 'version' => $appConfig['collection']], 
                                                                    $this->client->getDB(),
                                                                    (!empty($uri['collection'])) ? $uri['collection'] : []));
																	
        $this->client->setQuiz(new Components\Quiz\main(['name' => 'quiz', 'version' => $appConfig['quiz']], 
                                                                    $this->client->getDB(),
                                                                    (!empty($uri['quiz'])) ? $uri['quiz'] : []));
																	
        $this->client->setSheet(new Components\Sheet\main(['name' => 'sheet', 'version' => $appConfig['sheet']], 
                                                                    $this->client->getDB(),
                                                                    (!empty($uri['sheet'])) ? $uri['sheet'] : []));
																	
        $this->client->setUser(new Components\User\main(['name' => 'user', 'version' => $appConfig['user']],
                                                        $this->client->getDB(),
                                                        (!empty($uri['user'])) ? $uri['user'] : []));
                                                                
        $this->client->setWord(new Components\Word\main(['name' => 'word', 'version' => $appConfig['word']],
                                                        $this->client->getDB(),
                                                        (!empty($uri['word'])) ? $uri['word'] : []));
        
    }
    
}