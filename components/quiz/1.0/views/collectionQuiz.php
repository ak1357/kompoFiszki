<?php $words = self::getQuizWords(); 

if (is_array($words) && sizeof($words) > 0) { ?>

	<h2 class="container__header">Quiz</h2>
	
	<div class="alert alert--green mb10">Quiz zakończony. Gratulujemy</div>
	
	<form method="post" action="" class="form">
		
		<div class="quiz">
			Fiszka:
			<span class="quiz__word"></span> 
			<span class="quiz__wrong">Nieprawidłowa odpowiedź</span>
			<span class="quiz__success">Dobrze</span>
		</div>
		
		<input type="text" name="name" placeholder="Odpowiedź" class="form__input" />
		
		<div class="form__buttons">
			<button class="btn btn--blue btn__accept">Odpowiedz</button>
			<button class="btn btn--gray btn__skip">Pomiń</button>
		</div>
	</form>    
	
	
	<style>
	.quiz {
		width: 280px;
		padding: 15px 10px;
		background: #eee;
	}
	
	.quiz__word {
		font-weight: bold;
	}
	
	.quiz__wrong, .quiz__success {	
		font-size: 10px;
		float: right;
		margin-top: 15px;
		display: none;
	}
	
	.quiz__wrong {
		color: #f00;
	}
	
	.quiz__success {
		color: #54bd34;
	}
	
	.alert {
		display: none;
	}
	
	</style>
	
	
	<script>
		
		var questions = new Object;
		var index = 1; // niech to będzie "id" fiszki
		var isActive = false;
		
		function loadWord ()
		{
			
			if (typeof questions[index] !== 'undefined') {
				
				if ($('.form__input').val() == questions[index]['t']) {
					
					if (isActive) {
						$('.quiz__wrong').hide();
						$('.quiz__success').fadeIn();
					}
					
					setTimeout(function() {
						
						var i = Object.keys(questions).indexOf(index + '');
						delete questions[index];
						
						index = Object.keys(questions)[i];
						
						$('.form__input').val('');
						
						if (isActive && typeof questions[index] == 'undefined') {
							$('.form').hide();
							$('.alert--green').show();
							
						} else
							$('.quiz__word').text(questions[index]['n']);
						
					}, 600);
					
					$('.quiz__success').fadeOut();
					
				} else {
					if (isActive) {
						$('.quiz__success').hide();
						$('.quiz__wrong').fadeIn();
					}
				}
				
			} else {
				
				for (var firstIndex in questions) break;
				index = firstIndex;
				
			}
			
			return false;
		}
		
		
		// Inicjalizacja zbioru fiszek 
		
		<?php
		$i = 0;
		foreach (((is_array($words)) ? $words : []) as $word) {
			$i++; ?>
			
			questions[<?= $i; ?>] = {
				n: '<?= ((!empty($word['word_name'])) ? $word['word_name'] : ''); ?>',
				t: '<?= ((!empty($word['word_translation'])) ? $word['word_translation'] : ''); ?>'
			};
			
			<?php
		}
		?>
		
		$(document).ready(function() {
			
			index = 1;
			$('.btn').on('click', function() {
				
				if ($(this).hasClass('btn__skip'))
					index = parseInt(index) + 1;
				
				loadWord();	
				$('.quiz__word').text(questions[index]['n']);
				isActive = true;
				
				return false;
			});
			
			$('.btn__accept').trigger('click');
			
		});
	</script>


<?php } else { ?>
	
	<div class="alert alert--red mb10"><b>Uwaga</b>: Brak fiszek do rozpoczęcia quizu</div>
	
<?php } ?>