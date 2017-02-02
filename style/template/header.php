<!DOCTYPE html>
<head>
    <title>Test</title>
    <link href="<?= $context; ?>/style/template/css/style.css" rel="stylesheet" />
    <script src="<?= $context; ?>/style/template/js/jquery-3.1.1.min.js"></script>
    <script>
        infoboxes = new Object;
    </script>
</head>
<body>

    <header class="navbar">
        <nav class="navbar__nav">
            <ul class="navbar__menu">
                
                <li class="navbar__menu-item">
                    <a href="<?= $context; ?>" class="navbar__menu-link navbar--brand-link">KompoFiszki</a>
                </li>
                
                <li class="navbar__menu-item fr">
                   <?php
                    print (empty($_SESSION['user'])) 
                        ? '<a href="' . $context . '/user/login" class="navbar__menu-link">Zaloguj się</a>'
                        : '<a href="' . $context . '/user/logout" class="navbar__menu-link">Wyloguj się</a>';
                    ?>
                </li>
                
                <li class="navbar__menu-item fr">
                    <a href="#" class="navbar__user navbar__menu-link"></a>
                </li>
                
            </ul>
        </nav>    
    </header>
    
    <main class="container">