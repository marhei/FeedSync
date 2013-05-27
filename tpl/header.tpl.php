<!DOCTYPE html>
<html lang="de">
	<head>
		<title>FeedSync</title>
		<meta charset="utf-8">
		
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
		<!-- Eigenes CSS -->
		<link href="css/addition.min.css" rel="stylesheet" media="screen">
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<div class="pull-right">
					<div class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i><b class="caret"></b></a>
						<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
							<? if(isset(self::$moduleVars['siteOptions'])): ?>
								<? foreach(self::$moduleVars['siteOptions'] as $link => $name): ?>
									<li><a href="<?= $link ?>"><?= \Core\Format::string($name) ?></a></li>
								<? endforeach; ?>
								<li class="divider"></li>
							<? endif; ?>
							<li><a href="<?= self::cml() ?>"><?= \Core\Language::main()->get('header', 'refreshPage') ?></a></li>
						</ul>
					</div>
				</div>
				<h1>FeedSync <small><?= \Core\Language::main()->get('header', 'titleAddition') ?></small></h1>
			</div>
			
			<p><?= \Core\Language::main()->get('header', 'intro') ?></p>
			
			<ul class="nav nav-tabs">
				<? foreach(self::$backendModules as $currentModule): ?>
					<li <? if($currentModule == self::getModuleName()): ?> class="active" <? endif; ?>>
						<a href="<?= self::cml(array(), $currentModule) ?>"><?= \Core\Language::main()->get('backendModules',$currentModule) ?></a>
					</li>
				<? endforeach; ?>
			</ul>
			
			<? if(isset(self::$moduleVars['error'])): ?>
				<div class="alert alert-error">
					<strong><?= \Core\Language::main()->get('header', 'errorIntro') ?></strong>
					<?= \Core\Format::string(self::$moduleVars['error']) ?>
				</div>
			<? endif; ?>