<?php

class Controller_Celebrities extends Controller_Base
{
    public function action_index($letter = null)
    {
        $count = Model_Celebrity::find()->count();
        $cause_data['causes'] = Model_Cause::query()->order_by('cause', 'asc')->get();

        $this->template->top_scripts[] = 'jquery.my-slider.js';

        $this->reddo('content/celebrity.twig', array(
            'count' => floor($count / 100) * 100,
            'reddo_cause' => View::forge('content/cause-div-links.twig', $cause_data),
        ));
    }

    public function action_profile($id = null)
    {
        if (is_null($id)) return $this->action_404();

        $count = Model_Celebrity::find()->count();
        $celebrity = Model_Celebrity::find($id);
        $cause_data['causes'] = Model_Cause::query()->order_by('cause', 'asc')->get();

        $this->template->top_scripts[] = 'jquery.my-slider.js';

        $this->reddo('content/celebrity.twig', array(
            'count' => floor($count / 100) * 100,
            'random_celebrities' => array($celebrity),
            // 'celebrities' => Model_Celebrity::get_celebrities_on_letters(substr($celebrity->first_name, 0, 1)),
            'reddo_cause' => View::forge('content/cause-div-links.twig', $cause_data),
        ));
    }

    public function action_cause($cause_id = null)
    {
        if (is_null($cause_id)) return $this->action_404();

        $causes = Model_Cause::find($cause_id);
        $cause_data['causes'] = Model_Cause::query()->order_by('cause', 'asc')->get();

        $this->template->top_scripts[] = 'jquery.my-slider.js';

        $this->reddo('content/celebrity.twig', array(
            'page_title' => $causes->cause,
            'page_title_more' => 'Celebrity campaigners',
            // 'random_celebrities' => Model_Celebrity::top_celebrities_on_cause($cause_id, 10),
            'celebrities' => Model_Celebrity::get_celebrities_on_cause($cause_id),
            'reddo_cause' => View::forge('content/cause-div-links.twig', $cause_data),
            'links_first' => true,
        ));
    }

    public function action_field($field_id = null)
    {
        if (is_null($field_id)) return $this->action_404();

        $fields = Model_Profession::find($field_id);
        $cause_data['causes'] = Model_Cause::query()->order_by('cause', 'asc')->get();

        $this->template->top_scripts[] = 'jquery.my-slider.js';

        $this->reddo('content/celebrity.twig', array(
            'page_title' => $fields->field,
            'page_title_more' => 'Charitable celebrities in this field',
            // 'random_celebrities' => Model_Celebrity::top_celebrities_on_field($field_id, 10),
            'celebrities' => Model_Celebrity::get_celebrities_on_field($field_id),
            'reddo_cause' => View::forge('content/cause-div-links.twig', $cause_data),
            'links_first' => true,
        ));
    }

    // public function action_search()
    // {
    //     $search_str = trim(Input::get('query'));

    //     $this->reddo('content/celebrity.twig', array(
    //         'page_title' => 'Results for query "' . $search_str . '"',
    //         'random_celebrities' => Model_Celebrity::get_celebrities_on_letters($search_str, 10),
    //         'celebrities' => Model_Celebrity::get_celebrities_on_letters($search_str),
    //     ));
    // }
}
