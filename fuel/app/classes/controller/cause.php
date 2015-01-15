<?php

class Controller_Cause extends Controller_Base
{
    public function action_index()
    {
        $this->reddo('content/cause.twig', array(
            'causes' => Model_Cause::query()->order_by('cause', 'asc')->get(),
        ));
    }
}
