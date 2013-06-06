			<hr>
			<ul class="inline pull-right">
				<li><?= \Core\Format::string(\Config\VERSION) ?> (API <?= \Core\Format::number(\Config\API_VERSION) ?>)</li>
				<li>&middot;</li>
				<li><a href="#helpModal" data-toggle="modal"><?= \Core\Language::main()->get('help', 'title') ?></a></li>
				<li>&middot;</li>
				<li><a href="<?= \Config\SITE ?>"><?= \Core\Language::main()->get('footer', 'projectPage') ?></a></li>
				<li>&middot;</li>
				<li>&copy; 2013 Marcel Heisinger</li>
			</ul>
		</div>
		
		<div id="helpModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel"><?= \Core\Language::main()->get('help', 'title') ?></h3>
		</div>
			
		<div class="modal-body">
			<p><?= \Core\Language::main()->get('help', 'intro') ?></p>
			
			<h4><?= \Core\Language::main()->get('help', 'readability_title') ?></h4>
			<p><?= \Core\Language::main()->get('help', 'readability_p_1') ?></p>
			<img src="img/help/readability_parse_token.png" alt="Parse-Token">
			<p><?= \Core\Language::main()->get('help', 'readability_p_2') ?></p>
			
			<h4><?= \Core\Language::main()->get('help', 'cronjob_title') ?></h4>
			<p><?= \Core\Language::main()->get('help', 'cronjob_p') ?></p>
			<p><pre>curl -L -s http://domain.com/FeedSync/?refresh</pre></p>
			
			<h4><?= \Core\Language::main()->get('help', 'api_title') ?></h4>
			<p><?= \Core\Language::main()->get('help', 'api_p') ?></p>
		</div>
	</div>
	</body>
</html>