<?php
if (isset($_POST['id'])) {
	$exitEmploye = new EmployesController();
	$employe = $exitEmploye->getOneEmploye();
} else {
	Redirect::to('home');
}
if (isset($_POST['submit'])) {
	$exitEmploye = new EmployesController();
	$exitEmploye->updateEmploye();
}
?>
<div class="container">
	<div class="row my-4">
		<div class="col-md-8 mx-auto">
			<div class="card">
				<div class="card-header">Modifier user</div>
				<div class="card-body bg-light">
					<a href="<?php echo BASE_URL; ?>" class="btn btn-sm btn-secondary mr-2 mb-2">
						<i class="fas fa-home"></i>
					</a>
					<form method="post">
						<div class="form-group">
							<label for="nom">Nom*</label>
							<input type="text" name="nom" class="form-control" placeholder="Nom"
								value="<?php echo $employe->nom; ?>">
						</div>
						<div class="form-group">
							<label for="prenom">Prénom*</label>
							<input type="text" name="prenom" class="form-control" placeholder="Prénom"
								value="<?php echo $employe->prenom; ?>">
						</div>
						<div class="form-group">
							<label for="mat">ID*</label>
							<input type="text" name="mat" class="form-control" placeholder="Matricule"
								value="<?php echo $employe->matricule; ?>">
						</div>
						
						
						<div class="form-group">
							<label for="date_emb">Date *</label>
							<input type="date" name="date_emb" class="form-control">
						</div>
						<div class="form-group">
							<select class="form-control" name="statut">
								<option value="1" <?php echo $employe->statut ? 'selected' : ''; ?>>Admin</option>
								<option value="0" <?php echo !$employe->statut ? 'selected' : ''; ?>>Client</option>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="submit">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>