</main>

<script>
$(document).ready(function() {
    
    <?php if (!empty($_SESSION['dialogbox']['text']) && in_array($_SESSION['dialogbox']['color'], ['green', 'orange', 'red'])) { ?>
    $('.container__header').append('<div class="alert alert--<?= $_SESSION['dialogbox']['color'];?> mt10"><?= htmlspecialchars($_SESSION['dialogbox']['text']); ?></div>');
    <?php } ?>
    
    
	
	var isSuccess = false;
	var isError = false;
	
	
    if (window.location.href.indexOf('?success') != -1) {
        
        var i = window.location.href.indexOf('?success') + 9;
		
		if (typeof infoboxes['<?= ((!empty(key($uri))) ? key($uri) : ''); ?>']['success'][window.location.href.substr(i, 2)] !== 'undefined')
			$('.container__header').after('<div class="alert alert--green mb10">'
										   + infoboxes['<?= ((!empty(key($uri))) ? key($uri) : ''); ?>']['success'][window.location.href.substr(i, 2)]
										   + '</div>');
		
    } else if (window.location.href.indexOf('?error') != -1) {
        
        var i = window.location.href.indexOf('?error') + 7;
        
		if (typeof infoboxes['<?= ((!empty(key($uri))) ? key($uri) : ''); ?>']['error'][window.location.href.substr(i, 2)] !== 'undefined')
        $('.container__header').after('<div class="alert alert--red mb10">'
                                       + infoboxes['<?= ((!empty(key($uri))) ? key($uri) : ''); ?>']['error'][window.location.href.substr(i, 2)]
                                       + '</div>');
		
    }
    

	
	// Czyszczenie ? z url 
	// history.pushState("", document.title, window.location.pathname);
										 
	// window.location.href = '/kompo/1';
	
});
</script>
</body>
</html>