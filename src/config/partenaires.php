<?php

function list_partenaires(): array

{
    $infos = [
        'ctg' => ['nom' => 'COLLECTIVITE TERRITORIALE DE LA GUYANE', 'image' => '/assets/image/CTG.png', 'url' => 'https://www.ctguyane.fr/', 'class' => 'active'],
        'akto' => ['nom' => 'AKTO', 'image' => '/assets/image/AKTO.png', 'url' => 'https://www.akto.fr/', 'class' => ''],
        'france-travail' => ['nom' => 'FRANCE TRAVAIL', 'image' => '/assets/image/FT.png', 'url' => 'https://www.francetravail.fr/accueil/', 'class' => ''],
        'ocapiat' => ['nom' => 'OCAPIAT', 'image' => '/assets/image/OCAPIAT.png', 'url' => 'https://www.ocapiat.fr/', 'class' => ''],
        'mlg' => ['nom' => 'MISSION LOCALE GUYANE', 'image' => '/assets/image/MLG.png', 'url' => 'https://missionlocaleguyane.fr/', 'class' => ''],
        'qualiopi' => ['nom' => 'QUALIOPI', 'image' => '/assets/image/QV1.JPG', 'url' => 'https://travail-emploi.gouv.fr/formation-professionnelle/acteurs-cadre-et-qualite-de-la-formation-professionnelle/article/qualiopi-marque-de-certification-qualite-des-prestataires-de-formation', 'class' => ''],
        'opco' => ['nom' => 'OPCO', 'image' => '/assets/image/OPCO.png', 'url' => 'https://www.opcoep.fr/', 'class' => ''],
        'les-entreprises-engagent' => ['nom' => "LES ENTREPRISES S'ENGAGENT", 'image' => '/assets/image/LES.jpg', 'url' => 'https://lesentreprises-sengagent.gouv.fr/', 'class' => ''],
        'immersion-facilitee' => ['nom' => 'IMMERSION FACILITÉE', 'image' => '/assets/image/ISF.jpg', 'url' => 'https://immersion-facile.beta.gouv.fr/', 'class' => ''],
        'crf' => ['nom' => 'CROIX ROUGE FRANÇAISE', 'image' => '/assets/image/CR.jpg', 'url' => 'https://www.croix-rouge.fr/', 'class' => ''],
        'dreets' => ['nom' => 'DREETS', 'image' => '/assets/image/DREETS.jpg', 'url' => 'https://dreets.gouv.fr/', 'class' => ''],
        'epnak' => ['nom' => 'EPNAK', 'image' => '/assets/image/EPNAK.png', 'url' => 'https://www.epnak.org/', 'class' => ''],
        'handi-bienveillant' => ['nom' => 'HANDI BIENVEILLANT', 'image' => '/assets/image/HANDI.png', 'url' => '', 'class' => ''],
    ];
    return $infos;
}
