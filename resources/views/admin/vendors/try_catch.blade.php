<?php 

// Diese folgende Struktur wird hier immer benutzt um mehr Sicherheit für die Transactions zu gewerleisten

try{
    DB::beginTransaction();
    //code here transaction DB
    DB::commit();

} catch(\Exception $ex){
    DB::rollback();
}