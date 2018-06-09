<?php

namespace App\EventHandler;


use Sse\Event;

class FooEventHandler implements Event {
    public function update(){
        //Here's the place to send data
        return 'Foo';
    }
    
    public function check(){
        //Here's the place to check when the data needs update
        return true;
    }
}