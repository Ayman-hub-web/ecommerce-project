<?php 

//Dieses File wurde in composer.json eingetragen  und so kann die Funktionen hier überall im Projekt benutzen
// Man run für das Projekt machen und dann kann man die Funktionen benutzen
// man gibt im Terminal composer dump-autoload

function get_languages(){
    return App\Models\Language::active()->selection()->get();
}

function getDefaultLang(){
    return Config::get('app.locale');
}

function uploadImage($folder, $image){
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/'.$folder.'/'.$filename;
    return $path; 
}

// make notification
// php artisan make:notification VendorCreated
// unetr app wird danach ein Verzeichnis notifivation erstellt 
// wir änder die Datei VendorCreated so wie es hier steht
// Wir fügen auch Informazionen in der env Datei wie es hier steht
// dann geben wir den Befahl .. php artisan config:cach
// In der Klasse Vender dann folgt eingeben use Illuminate\Notifications\Notifiable; und use Notifiable; innerhalb der Klasse
// Nach der speicherunf des neuen Vendors geben wir die Zeile mit der eine Email gesendet wird.