<?php global $context; ?>

<script type="text/javascript">
	
// Uzupełnienie globalnej tablicy komunikatów dla użytkownika

infoboxes['collection'] = {
	success: {
		0: 'Utworzono zestaw fiszek',
		1: 'Zaktualizowano parametry zestawu fiszek',
		2: 'Usunięto zestaw fiszek',
		3: 'Utworzono fiszkę',
		4: 'Zaktualizowano fiszkę',
		5: 'Usunięto fiszkę'
	},
	error: {
		0: 'Nie udało się utworzyć zestawu fiszek',
		1: 'Nie udało się zaktualizować zestawu fiszek',
		2: 'Nie udało się usunąć zestawu fiszek'
	}
};
	
$(document).ready(function() {
    
    var menuItems = [];
    
    <?php if (!empty($_SESSION['user'])) { ?> 
    
    menuItems.push('<li class="navbar__menu-item">'
                 +  '<a href="<?= $context; ?>/collection/private-list" class="navbar__menu-link">Moje zestawy fiszek</a>'
                 + '</li>');
                    
    <?php } ?>
    
    menuItems.push('<li class="navbar__menu-item">'
                 +  '<a href="<?= $context; ?>/collection/public-list" class="navbar__menu-link">Publiczne zestawy fiszek</a>'
                 + '</li>');
    
    for (var index in menuItems)
        $('.navbar__menu').append(menuItems[index]);
        
});
</script>