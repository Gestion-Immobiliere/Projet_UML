<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrat de location</title>
    <style>
        body { font-family: sans-serif; }
    </style>
</head>
<body>
    <h1>Contrat de location</h1>
    <p><strong>Date :</strong> {{ $contrat->dateCreation }}</p>
    <p><strong>Locataire :</strong> {{ $contrat->locataire->prenom }} {{ $contrat->locataire->nom }}</p>
    <p><strong>Bien :</strong> {{ $contrat->bien->type }} à {{ $contrat->bien->adresse }}</p>
    <p><strong>Montant :</strong> {{ $contrat->bien->montant }} FCFA</p>
    <p><strong>Conditions :</strong> Paiement mensuel. Signature numérique dès validation.</p>
</body>
</html>
