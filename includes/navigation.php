<?php
$base = $base ?? '';
$currentPage = basename($_SERVER['PHP_SELF']);
// Visitors following an induction link only see a label, not the admin links.
$isVisitorPage = $isVisitorPage ?? false;
$navLinks = [
	'host-form.php' => 'Invite a Visitor',
	'admin-dashboard.php' => 'Admin Dashboard',
];
?>
<div class="navigation-holder">
	<div class="container pb-0">
		<?php if ($isVisitorPage): ?>
			<p class="nav-label">Visitor Induction</p>
		<?php else: ?>
			<nav class="navbar navbar-expand-lg navbar-light pb-0" aria-label="Main navigation">
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
					aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarNavDropdown">
					<ul class="navbar-nav">
						<?php foreach ($navLinks as $href => $label): ?>
							<li class="nav-item<?php echo $currentPage === $href ? ' active' : ''; ?>">
								<a class="nav-link" href="<?php echo $base . $href; ?>" <?php echo $currentPage === $href ? 'aria-current="page"' : ''; ?>>
									<div><?php echo $label; ?></div>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</nav>
		<?php endif; ?>
	</div>
</div>
