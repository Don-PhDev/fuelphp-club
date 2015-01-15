<?php

class Controller_Field extends Controller_Base
{
    public function action_index()
    {
        $this->reddo('content/field.twig', array(
            'fields' => Model_Profession::query()->order_by('field', 'asc')->get(),
        ));
    }
}
