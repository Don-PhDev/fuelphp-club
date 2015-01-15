<?php

class Controller_Nonprofit extends Controller_Base
{
    public function action_index()
    {
        $this->reddo('content/nonprofit.twig', array(
        ));
    }
}
