<?php

use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));        
        $I->see('My Company');
        
        $I->seeLink('registrate');
        $I->click('registrate');
        $I->wait(2); // wait for page to be opened
        
        $I->see('This is the registrate page.');
    }
}
