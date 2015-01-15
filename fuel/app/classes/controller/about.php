<?php

class Controller_About extends Controller_Base
{
    public function action_index()
    {
        $this->reddo('content/about.twig', array(
            'benefactors' => Model_Benefactor::find()->order_by('name', 'asc')->get(),
        ));
    }
}
