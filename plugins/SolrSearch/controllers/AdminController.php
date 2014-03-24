<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=80; */

/**
 * @package     omeka
 * @subpackage  solr-search
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


class SolrSearch_AdminController
    extends Omeka_Controller_AbstractActionController
{


    /**
     * Display the "Solr Configuration" form.
     */
    public function serverAction()
    {

        $form = new SolrSearch_Form_Server();

        // If a valid form was submitted.
        if ($this->_request->isPost() && $form->isValid($_POST)) {

            // Set the options.
            foreach ($form->getValues() as $option => $value) {
                set_option($option, $value);
            }

        }

        // Are the current parameters valid?
        if (SolrSearch_Helpers_Index::pingSolrServer()) {

            // Notify valid connection.
            $this->_helper->flashMessenger(
                __('Solr connection is valid.'), 'success'
            );

        }

        // Notify invalid connection.
        else $this->_helper->flashMessenger(
            __('Solr connection is invalid.'), 'error'
        );

        $this->view->form = $form;

    }


    /**
     * Display the "Field Configuration" form.
     */
    public function fieldsAction()
    {

        // Get the facet mapping table.
        $facetTable = $this->_helper->db->getTable('SolrSearchField');

        // If the form was submitted.
        if ($this->_request->isPost()) {

            // Get facets from POST.
            $facets = $this->_request->getPost();
            $facets = $facets['facets'];

            // Save the facets.
            foreach ($facets as $name => $data) {

                // Were "Is Indexed?" and "Is Facet?" checked?
                $indexed = array_key_exists('is_indexed', $data) ? 1 : 0;
                $faceted = array_key_exists('is_facet', $data) ? 1 : 0;

                // Load the facet mapping.
                $facet = $facetTable->findByName($name);

                // Apply the updated values.
                $facet->label       = $data['label'];
                $facet->is_indexed  = $indexed;
                $facet->is_facet    = $faceted;
                $facet->save();

            }

            // Flash success.
            $this->_helper->flashMessenger(
                __('Fields successfully updated! Be sure to reindex.'),
                'success'
            );

        }

        // Assign the facet groups.
        $this->view->groups = $facetTable->groupByElementSet();

    }


    /**
     * Display the "Hit Highlighting Options" form.
     */
    public function highlightAction()
    {

        $form = new SolrSearch_Form_Highlight();

        // If a valid form was submitted.
        if ($this->_request->isPost() && $form->isValid($_POST)) {

            // Set the options.
            foreach ($form->getValues() as $option => $value) {
                set_option($option, $value);
            }

            // Flash success.
            $this->_helper->flashMessenger(
                __('Highlighting options successfully saved!'), 'success'
            );

        }	

        $this->view->form = $form;

    }


    /**
     * Display the "Index Items" form.
     */
    public function reindexAction()
    {

        $form = new SolrSearch_Form_Reindex();

        if ($this->_request->isPost()) {
            try {

                // Clear and reindex.
                SolrSearch_Helpers_Index::deleteAll();
                SolrSearch_Helpers_Index::indexAll();

                // Flash success.
                $this->_helper->flashMessenger(
                    __('Reindexing finished.'), 'success'
                );

            } catch (Exception $err) {

                // Flash error.
                $this->_helper->flashMessenger(
                    $err->getMessage(), 'error'
                );

            }
        }	

        $this->view->form = $form;

    }


}
