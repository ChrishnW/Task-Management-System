var row_id =2;



function addRowLot(tableID)
{
    //TABLE VARIABLE
    var table = document.getElementById(tableID);
    //ROW COUNT OF ALL ITEMS IN THE TABLE
    var rowCount = table.rows.length;
    //ROW ID BASED ON TABLE ROW
    var row = table.insertRow(rowCount);
    //ROW ID VALUE
    row.id = 'id' + row_id;

     //==========================CLOSE SELECT PICKER DIALOG===============================//
    $('[data-toggle="dropdown"]').parent().removeClass('open');

 
    var colCount = table.rows[0].cells.length;
        for(var i=0; i<colCount; i++) 
        {
            var newcell = row.insertCell(i);

            //========================================================TD ELEMENT=============================================//
             newcell.innerHTML = table.rows[0].cells[i].innerHTML;
             
            //========================================================DIV ELEMENT============================================//
            var div=newcell.childNodes[0];
          
            //=====ITEMS INSIDE THE DIV
            var html_items=div.childNodes[0];
            //CHECK NODE BASED ON name defined on the HTML element tags//

       

            //REMOVE CLONED ITEM FOR TYPE, LOT AND BLOCK
           
            if(i==0)
            {   
                var select=html_items.childNodes[2];
                select.id="lot_"+row_id;
                $('#lot_'+row_id).selectpicker('refresh');
                $('#lot_'+row_id).selectpicker("val","");
                $('#lot_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]);
                closetime('#lot_'+row_id);     
            }
            if(i==1)
            {   
                var select=html_items.childNodes[2];
                select.id="type_"+row_id;
                $('#type_'+row_id).selectpicker('refresh');
                $('#type_'+row_id).selectpicker("val","");
                $('#type_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]); 
                closetime('#type_'+row_id); 
            }

            if(i==2)
            {   
                var select=html_items.childNodes[2];
                select.id="block_"+row_id;
                $('#block_'+row_id).selectpicker('refresh');
                $('#block_'+row_id).selectpicker("val","");
                $('#block_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]);
                closetime('#block_'+row_id);     
            }

            switch(html_items.name) 
            {
               
                case "chk[]":
                // div.childNodes[0].setAttribute("checked","checked");
                div.childNodes[0].id = "chk_"+row_id;
                break;
                
                case "start[]":
                div.childNodes[0].id = "start_" + row_id;
                var start_id="#start_" + row_id;
                var frow=$('#first').val();
                if(row_id!=frow)
                {
                    //NOT EQUAL READONLY
                    $("#start_"+row_id).timepicker(
                    {
                        timeFormat: 'HH:mm',
                        dynamic: false,
                        dropdown: false,
                    }); 
                    $("#start_"+row_id).keydown(prevent);
                }
                error_check(start_id);
                $("#start_"+row_id).parent().removeClass('has-warning');
                $("#start_"+row_id).removeClass('form-control-warning');
                break; 



                case "end[]":
                div.childNodes[0].id = "end_" + row_id;
                var end_id="#end_"+row_id;
                $("#end_"+row_id).timepicker(
                 {
                    timeFormat: 'HH:mm',
                    interval: 60,
                    minTime: '06:00',
                    maxTime: '20:00',
                    defaultTime: '17',
                    startTime: '06:00',
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true,
                    change: ontimechangejs  
                 }); 
                
                 // GET THE PREVIOUS TABLE ROW
                var closest_row= $(start_id).closest('tr');
                // GET THE PREVIOUS TABLE END TIME SELECTION ID
                var prev_end_id=closest_row.prev().find('td:nth-child(6)').children().children().attr('id');
                // SET THE INSERTED START TIME ID BASED ON THE PREVIOUS END TIME
                $(start_id).val($('#'+prev_end_id).val());

                error_check(end_id);
                break; 
            }
            
         }      
                 $('#last').val(row_id);
                 row_id++;

               
           
         
}

function error_check(element)
{
    var pattern = "[0-9]{2}:[0-9]{2}";
    var hasError = !$(element).val().match(pattern);

    if(hasError)
    {
        $(element).closest('div').addClass('has-error');
        $(element).next('.help-block').addClass('with-errors');
    }
    else
    {
        $(element).closest('div').removeClass('has-error');
        $(element).next('.help-block').removeClass('with-errors');
    }
}


 
function ontimechangejs()
{   
    var el_id=$(this).attr("id");
    var end_id="#"+el_id;   
    var closest_row= $(this).closest('tr');                                                            // GET THE CLOSEST TABLE ROW
    var next_start_id=closest_row.next().find('td:nth-child(5)').children().children().attr('id');    // GET THE NEXT TABLE START TIME SELECTION
    $("#"+next_start_id).val($(this).val());                                                         // SET THE INSERTED START TIME ID BASED ON THE PREVIOUS END TIME
    checkpattern($(this));
  
}


function checkpattern(element)
{
    var el=$(element).attr('id');
    var pattern = "[0-9]{2}:[0-9]{2}";
    var hasError = !$("#"+el).val().match(pattern);

    if(hasError)
    {
        $("#"+el).closest('div').addClass('has-error');
        $("#"+el).next('.help-block').addClass('with-errors');
    }
    else
    {
        $("#"+el).closest('div').removeClass('has-error');
        $("#"+el).next('.help-block').removeClass('with-errors');
    }

    
}

function closetime(element)
{
    $(element).on('show.bs.select', function () 
    {
    if($('.ui-timepicker').parent().hasClass("ui-helper-hidden ui-timepicker-hidden"))
    {
        alert("meron")
    }
    else
    {
    $('.ui-timepicker').parent().addClass("ui-helper-hidden ui-timepicker-hidden");
    }
    });
}




function prevent(event)
{
    event.preventDefault();
    if (event.keyCode === 13) 
    {
        event.preventDefault();
    }
}


function deleteRowDirect(tableID) 
{
try 
{
var table = document.getElementById(tableID);
var rowCount = table.rows.length;
if(rowCount!=1)
{
    table.deleteRow(rowCount-1);
}

}
catch(e)
{
alert(e);
}  
}

