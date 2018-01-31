<?php

namespace app\models;

interface ISearching {

    public function setWord($word);

    public function run();
    
    public function getResults();
    
}
