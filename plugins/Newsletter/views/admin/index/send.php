<?php
$head = array('bodyclass' => 'newsletter-index',
              'title' => html_escape(__('Newsletter - Administration')));
echo head($head);
?>
<p class="explanation">Volgende boodschap werd verzonden:</p>

<h3><?php echo $page->title;?></h3>
<?php echo $page->text;?>

<?php
echo foot();
?>
