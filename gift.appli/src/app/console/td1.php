<?php
declare(strict_types=1);
require_once '../../vendor/autoload.php';


use gift\appli\core\domain\entities\Box;
use gift\appli\core\domain\entities\Categorie;
use gift\appli\core\domain\entities\Prestation;
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection(parse_ini_file('../../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent(); // On initialise la connexion à la base de données

echo 'Question 1'."\n";
echo "\n";
$prestations = Prestation::get();
foreach ($prestations as $prestation) {
    echo $prestation->libelle . "\n";
    echo $prestation->description . "\n";
    echo $prestation->tarif . "\n";
    echo $prestation->unite . "\n";
}
echo "\n";
echo 'Question 2'."\n";
echo "\n";
$prestations = Prestation::with('categorie')->get();
foreach ($prestations as $prestation) {
    echo $prestation->libelle . "\n";
    echo $prestation->description . "\n";
    echo $prestation->tarif . "\n";
    echo $prestation->unite . "\n";
    echo $prestation->categorie->libelle."\n";
    echo "\n";
}


echo "\n";
echo 'Question 3'."\n";
echo "\n";

$categorie = Categorie::find(3);
$prestations = $categorie->with('prestations')->get();
echo $categorie->libelle . "\n";
foreach ($prestations as $prestation) {
    echo $prestation->libelle . "\n";
    echo $prestation->description . "\n";
    echo $prestation->tarif . "\n";
    echo $prestation->unite . "\n";
    echo "\n";
}

echo "\n";
echo 'Question 4'."\n";
echo "\n";

$box = Box::where('id', '=', '360bb4cc-e092-3f00-9eae-774053730cb2')->first();
echo $box->libelle . "\n";
echo $box->description . "\n";
echo $box->montant . "\n";
echo "\n";


echo "\n";
echo 'Question 5'."\n";
echo "\n";

$box = Box::where('id', '=', '360bb4cc-e092-3f00-9eae-774053730cb2')->first();
$prestations = $box->box2presta()->withPivot('quantite')->get();

echo $box->libelle . "\n";
echo $box->description . "\n";
echo $box->montant . "\n";

foreach ($prestations as $prestation) {
    echo $prestation->libelle . "\n";
    echo $prestation->description . "\n";
    echo $prestation->tarif . "\n";
    echo $prestation->unite . "\n";
    echo "Quantité: " . $prestation->pivot->quantite . "\n";
    echo "\n";
}





echo "\n";
echo 'Question 6'."\n";
echo "\n";
// Créer une box et lui ajouter 3 prestations. L’identifiant de la box est un UUID

$box  = new Box();
$box->libelle = 'Box 2';
$box->description = 'Description Box 2';
$box->montant = 200;
$box->token = '360bb4cc-e092-3f00-9eae-774053730cb2';
$box->kdo = 1;
$box->message_kdo = 'Message KDO';
$box->statut = 1;

$box->save();

$prestation1 = Prestation::find(1);
$box->box2presta()->attach($prestation1, ['quantite' => 1]);

$prestation2 = Prestation::find(2);
$box->box2presta()->attach($prestation2, ['quantite' => 2]);

$prestation3 = Prestation::find(3);
$box->box2presta()->attach($prestation3, ['quantite' => 3]);


$box = Box::findOrFail('1d2b0679-bde9-313a-aab8-47618b21219b');
$prestation = $box->box2presta()->where('presta_id', '38888d1e-d408-4e01-a0e6-05a966e348ea')->first();

if ($prestation) {
    $prestation->pivot->quantite = 3;
    $prestation->pivot->save();
}

$totalPrice = $box->box2presta->sum(function ($prestation) {
    return $prestation->pivot->quantite * $prestation->prix;
});

echo 'aaaaaa'. $totalPrice;
