The following code had to be added to exhibitBuilder/plugin.php in order
for the functions in custom.php of this of theme to work:

// Hook the 'filter_items_by_exhibit' into the Items SQL.
add_plugin_hook('item_browse_sql', 'filter_items_by_exhibit');

function filter_items_by_exhibit($select, $params)
{
    $db = get_db();
    if ($exhibit = $params['exhibit']) {
        $select->joinInner(
            array('isp' => $db->ExhibitPageEntry),
            'isp.item_id = i.id',
            array()
            );

        $select->joinInner(
            array('sp' => $db->ExhibitPage),
            'sp.id = isp.page_id',
            array()
            );

        $select->joinInner(
            array('s' => $db->ExhibitSection),
            's.id = sp.section_id',
            array()
            );

        $select->joinInner(
            array('e' => $db->Exhibit),
            'e.id = s.exhibit_id',
            array()
            );

        if ($exhibit instanceof Exhibit) {
            $select->where('e.id = ?', $exhibit->id);
        } elseif (is_numeric($exhibit)) {
            $select->where('e.id = ?', $exhibit);
        }
    }

    return $select;
}
