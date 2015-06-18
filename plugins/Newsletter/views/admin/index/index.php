<?php
$head = array('bodyclass' => 'newsletter-index',
              'title' => html_escape(__('Newsletter - Administration')));
echo head($head);
?>

<?php if(empty($contacts)):?>
    <p>Je hebt nog geen contacten in je lijst.</p>
    <p>Voeg ze toe door items aan te maken van het type 'Newsletter-contact',
    of door mensen te laten registreren via je de <a href="<?php echo url('newsletter/register');?>">publieke pagina</a></p>
<?php else:?>    
    
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
    <hr>
    <h2>Mailinglijst</h2>
    
    <input id="search_field" type="search" placeholder="Zoeken"><br>
    <p><b>Opgelet</b></p>
    <ul>
        <li>Veranderingen worden meteen opgeslagen.</li>
        <li> Om een contact te verwijderen klik je met de rechtermuisknop op een rij en kies je 'Remove row'.</li>
    </ul>
    <div id="example1"></div>
    <p>
    <button id="exportToCsv">Export to CSV</button>
</p>
<pre id="csv"></pre>
  
    <pre id="example1console" class="console"></pre>

    
   
    <script>
        jQuery(document).ready(function () {
        var $container = jQuery("#example1");
        var $console = jQuery("#example1console");
        var $parent = $container.parent();
        var data = <?php echo $data;?>;
        var sendbox = jQuery(".send-box");
        var boxnieuws = jQuery("#checknieuws");
        var boxact = jQuery("#checkact");
        
        
        var autosaveNotification;
        $container.handsontable({
          data: data,          
          colHeaders: <?php echo $colheaders;?>, 
          columns: <?php echo $columns;?>,
          columnSorting: true,
          removeRowPlugin: true,         
          contextMenu: ['remove_row'],
          width: 790,
          height: 300,
                    
          afterChange: function (change, source) {
            if (source === 'loadData') {
              return; //don't save this change
            }
            
            clearTimeout(autosaveNotification);
            jQuery.ajax({
              url: "<?php echo url('newsletter/index/save');?>",              
              type: "POST",
              data: {changes: change,table:jQuery('#example1').handsontable('getInstance').getData()}, //contains changed cells' data
              complete: function (data) {
                $console.text('Autosaved (' + change.length + ' ' +
                  'cell' + (change.length > 1 ? 's' : '') + ')');
                autosaveNotification = setTimeout(function () {
                  $console.text('Veranderingen opgeslagen');
                }, 1000);
              }
            });            
          },
          beforeRemoveRow: function(index){
              jQuery.ajax({
              url: "<?php echo url('newsletter/index/delete');?>",              
              type: "POST",
              data: {index: index,table:jQuery('#example1').handsontable('getInstance').getData()}, //contains changed cells' data
              complete: function (data) {
                $console.text('Deleted row');
                autosaveNotification = setTimeout(function () {
                  $console.text('Changes will be autosaved');
                }, 1000);
              }
            }); 
          }
        });
        
        var onlyExactMatch = function (queryStr, value) {
            return queryStr.toString() === value.toString();
        };

        jQuery('#exportToCsv').on('click', function(){
           
            var instance = jQuery('#example1').handsontable('getInstance');
            var headers = instance.getColHeader();
        
            var csv = "";
            csv += headers.join("|") + "\n";
        
            for (var i = 0; i < instance.countRows(); i++) {
                var row = [];
                for (var h in headers) {
                    var prop = instance.colToProp(h)
                    var value = instance.getDataAtRowProp(i, prop)
                    row.push(value)
                }

            csv += row.join("|")
            csv += "\n";
        }
        
       
            
            window.location.href = 'data:text/csv;charset=UTF-8,'
                            + encodeURIComponent(csv);
                    
                    
              
        });
    
        jQuery('#search_field').on('keyup',function(event){
            var value = ('' + this.value).toLowerCase(),row,col,r_len,c_len,td;
            var example = jQuery('#example1');
            var data = <?php echo $data;?>;
          
            var searcharray = [];
            if(value){
                                   
                for(row=0,r_len = data.length;row< r_len;row++){
                   var r = jQuery.map(data[row], function(el) { return el; });
                   
                   for(col=0,c_len = r.length;col < c_len; col++){                      
                          if(r[col] == null){
                           continue;
                          }
                         if(('' + r[col]).toLowerCase().indexOf(value) > -1){
                           searcharray.push(data[row]);
                           break;
                          }
                       }
                    }
                  example.handsontable('loadData',searcharray);
              }
              else{
                   example.handsontable('loadData', <?php echo $data;?>);
              }
         });

        

        sendbox.click(function() {
                if (boxnieuws.is(":checked") || boxact.is(":checked")) {
                    jQuery("#send-button").removeAttr("disabled");
                } else {
                    jQuery("#send-button").attr("disabled", "disabled");
                }
            });
         
        }); 
    </script>
<?php endif; ?>




<?php
echo foot();
?>