<?php global $context; ?>
<?php if (!self::isAdmin()) header('Location: ' . $context . '/user/login'); ?>
<?php $users = self::getUsers(); ?>

<h2 class="container__header">Lista użytkowników</h2>

<table class="mb10">
	
	<thead>
		<tr>
        	<th width="30" class="align--center">Lp.</th>
        	<th width="750" class="align--left">Nazwa</th>
        	<th width="150" class="align--center">Opcje</th>
    	</tr>
	</thead>
   
   <tbody>
        
        <?php
        $i = 0;
        
        if (is_array($users) && sizeof($users) > 0) {
            foreach ($users as $userId => $user) {
				$i++;
	    		?>
                
			    <tr>
                    <td class="align--center"><?= $i; ?></td>
                    <td class="align--left"><?= $user['user_login']; ?></td>
                    <td class="align--center">
						<a href="<?= $context; ?>/user/edit/<?= $userId; ?>">Edytuj</a>
                        <a href="<?= $context; ?>/user/delete/<?= $userId; ?>">Usuń</a>
					</td>
                </tr>
                
                <?php
            }
        } else { ?>
			
			<tr>
				<td colspan="3" class="align--center">
					<em>Brak użytkowników do wyświetlenia</em>
				</td>
			</tr>
			
        <?php } ?>
    </tbody>
</table>