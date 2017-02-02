<?php global $context; ?>
<?php $user = self::getUser(); ?>
<?php $isAdmin =self::isAdmin(); ?>

<script>
$(document).ready(function() {
    
    var menuItems = [];
    
    <?php if ($isAdmin) { ?>
        
    menuItems.push('<li class="navbar__menu-item">'
                 +   '<a href="<?= $context; ?>/user/list" class="navbar__menu-link">Użytkownicy</a>'
                 + '</li>');
                
    <?php } ?>
    
    for (var index in menuItems)
        $('.navbar__menu').append(menuItems[index]);
    
    $('.navbar__user')
        .attr('href', '<?= $context; ?>/user/edit')
        .text('<?= ((!empty($user['user_login'])) ? $user['user_login'] : ''); ?>');
    
	
    // Uzupełnienie globalnej tablicy komunikatów dla użytkownika
    
    infoboxes['user'] = {
        success: {
            0: 'Utworzono konto',
            1: 'Zaktualizowano konto',
			2: 'Usunięto konto'
        },
        error: {
			0: 'Nie udało się utworzyć konta',
			1: 'Nie udało się zaktualizować konta',
			2: 'Nie udało się usunąć konta',
			3: 'Nie udało się zalogować'
        }
    };
	
});
</script>