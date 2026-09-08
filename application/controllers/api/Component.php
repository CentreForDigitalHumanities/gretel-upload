<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH.'/libraries/CORS_header.php';
require APPPATH.'/libraries/REST_Controller.php';

class Component extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the metadata of a Component, given its title.
     *
     * @param int    $treebank_id the id of the Treebank
     * @param string $title       the title of the Component
     *
     * @return JSON response
     */
    public function metadata_get($treebank_id, $title)
    {
        $treebank = $this->treebank_model->get_treebank_by_id($treebank_id);
        if (!$treebank) {
            $this->response();
        }

        if ($treebank->public || $treebank->user_id == current_user_id()) {
            $component = $this->component_model->get_component_by_treebank_title($treebank_id, $title);

            if (!$component) {
                $this->response();
            }

            $this->response($this->metadata_model->get_metadata_by_component($component->id, false));
        }

        $this->response(null, 403);
    }
}
