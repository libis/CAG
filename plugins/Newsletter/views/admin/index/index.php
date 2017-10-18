<?php
$head = array('bodyclass' => 'newsletter-index',
              'title' => html_escape(__('Newsletter - Administration')));
echo head($head);
?>

    <h2>Verzenden</h2>
    <form name="versturen" action="<?php echo url('newsletter/index/send');?>" method="POST">
        <div class="field">
            <label>Welke pagina?</label>
            <select name="page" style="width:250px;">
                <?php foreach($pages as $page):?>
                <option value="<?php echo $page->id;?>"><?php echo $page->title;?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="field">
            <label>Nieuwsbrief</label>
            <input type="checkbox" name="Nieuwsbrief" class="send-box" id="checknieuws"><br>
        </div>
        <div class="field">
            <label>Activiteiten</label>
            <input type="checkbox" name="Activiteiten" class="send-box" id="checkact">
        </div>
        <div class="field">
            <input type="submit" id="send-button" class="green" value="Verstuur" disabled="disabled">
            <input type="submit" id="send-button-test" name="send_test" class="green" value="Verstuur test">
        </div>

    </form>

    <script>
        jQuery(document).ready(function () {

        var sendbox = jQuery(".send-box");
        var boxnieuws = jQuery("#checknieuws");
        var boxact = jQuery("#checkact");

        sendbox.click(function() {
                if (boxnieuws.is(":checked") || boxact.is(":checked")) {
                    jQuery("#send-button").removeAttr("disabled");
                } else {
                    jQuery("#send-button").attr("disabled", "disabled");
                }
            });

        });
    </script>
<?php echo foot();?>
