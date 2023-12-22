<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;

//Test sur des elements du noyau = KernelTestCase
//Test sur des controlleurs = WebTestCase
class AppTest extends TestCase{

    public function  testBaseEquals(){
        //ici 2 est le resultat de 1 + 1
        //Sinon on trigger une erreur
        $this->assertEquals(2, 1 + 1);

        $est_majeur = true;
        $this->assertTrue($est_majeur, 'La variable $est_majeur doit retourner TRUE !');
    }
}