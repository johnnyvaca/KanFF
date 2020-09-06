<?php
ob_start();
$title = "Créer un compte";
$instanceinfos = getInstanceInfos();

//Define css classes for repetitives markups, to change it quickly
$cssForSpan = "col-md-5 col-sm-5 box-verticalaligncenter";
$cssForInput = "col-md-5 col-sm-7 form-control";
$cssForDivZone = "pl-3";
$cssForDivField = "row pt-1";
?>
<p class="aligncenter"><?= $instanceinfos['collective']['msg'] ?></p>

<div class="form-group">
    <h1 class="aligncenter"><?= $title ?></h1>
    <form style="align-self: auto" class="" action="/?action=signin" method="POST">
        <h5 class="pt-3">Informations principales:</h5>
        <div class="<?= $cssForDivZone ?>">
            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Prénom</span>
                <input class="<?= $cssForInput ?> textFieldToCheck" minlength="2" maxlength="100" type="text"
                       name="firstname" placeholder="Josette" required/>
                <p id="pCounterFirstname" class="m-2"></p>
            </div>
            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Nom</span>
                <input class="<?= $cssForInput ?> textFieldToCheck" minlength="2" maxlength="100" type="text"
                       name="lastname"
                       placeholder="Richard" required/>
                <p id="pCounterLastname" class="m-2"></p>
            </div>
            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Initiales </span>
                <input class="<?= $cssForInput ?>" type="text" placeholder="JRD" readonly/>
                <img title="Les initiales sont uniques et générées automatiquement.
                Format: première lettre du prénom + la première lettre
                du nom + la dernière lettre du nom/2ème lettre du nom
                (en cas de conflit)."
                     src="view/medias/icons/point.png" alt="50px" width="35" height="35" class="">
            </div>
        </div>
        <h5 class="pt-3">Identification:
        </h5>
        <div class="<?= $cssForDivZone ?>">
            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Nom d'utilisateur/trice</span>
                <input class="<?= $cssForInput ?> textFieldToCheck" minlength="4" maxlength="15" type="text"
                       name="username"
                       placeholder="josette27" required/>
                <p id="pCounterUsername" class="m-2"></p>
            </div>

            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Mot de passe</span>
                <input class="<?= $cssForInput ?>" type="password" name="password" placeholder="" required/>
                <img title="Les critères de sécurité du mot de passe sont:
                - yy caractères
                - caractères minuscules, majuscules, spéciaux, chiffres.
                - ... TBD" src="view/medias/icons/point.png" alt="50px" width="35" height="35" class="">
            </div>

            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Confirmation</span>
                <input class="<?= $cssForInput ?>" type="password" name="passwordc" placeholder="" required
                       title="Confirmation du mot de passe"/>
            </div>
        </div>

        <h5 class="pt-3">Champs facultatifs:</h5>
        <div class="<?= $cssForDivZone ?>">
            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Email</span>
                <input class="<?= $cssForInput ?>" type="email" name="email" minlength="5" maxlength="254"
                       placeholder="josette.richard@assoc.ch"/>
            </div>

            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">N°téléphone</span>
                <input class="<?= $cssForInput ?>" type="string" name="phonenumber" placeholder="+41 088 965 35 56"
                       minlength="4" maxlength="20"/>
            </div>

            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Lien messagerie instantanée</span>
                <input class="<?= $cssForInput ?>" type="text" name="chat_link" placeholder="t.me/josette27"/>
            </div>

            <div class="<?= $cssForDivField ?>">
                <span class="<?= $cssForSpan ?>">Biographie</span>
                <span class="fullwidth col-lg-12">
                    <textarea name="biography" id="txtBiography" rows="2" maxlength="2000"
                              placeholder="tbd"
                              class="fullwidth form-control textFieldToCheck "
                              title="Votre biographie">
                    </textarea>
                </span>
                <p id="pCounterBiography" class="mt-2 mb-2 col-lg-12"></p>
            </div>
            
        </div>
        <?= flashMessage(); ?>
        <div class="vertical-center box-alignright pt-3">
            <button type="submit" class="btn btn-primary">Création du compte</button>
        </div>

    </form>
    <p>Déjà un compte ? <a href="/?action=login">Connexion.</a></p>
    <div class="vertical-center box-alignright pt-3">
</div>

<?php
$contenttype = "restricted";
$content = ob_get_clean();

require "gabarit.php";
?>


