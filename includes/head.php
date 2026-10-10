<?php $base = $base ?? ''; ?>
<link rel="icon" type="image/png" href="<?php echo $base; ?>images/favicon.ico">
<link rel="stylesheet" href="<?php echo $base; ?>css/linear-full.css">
<link rel="stylesheet" href="<?php echo $base; ?>css/linearicons.css" type="text/css">
<link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base; ?>css/main.css?v1.0.0" rel="stylesheet" type="text/css" />
<link href="<?php echo $base; ?>css/production.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base; ?>css/frame.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $base; ?>js/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base; ?>js/main.js"></script>
<script type="text/javascript" src="<?php echo $base; ?>js/bootstrap.bundle.min.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Text highlighter -->
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>css/masha.css">
<script type="text/javascript" src="<?php echo $base; ?>js/masha.js"></script>
<script>
	function init_masha() {
		MaSha.instance = new MaSha({
			'ignored': '.ignored',
			'validate': true
		});
	}
	if (window.addEventListener) {
		window.addEventListener('load', init_masha, false);
	} else {
		window.attachEvent('onload', init_masha);
	}
</script>

<link href="<?php echo $base; ?>css/app-styles.css?v2" rel="stylesheet" type="text/css" />
