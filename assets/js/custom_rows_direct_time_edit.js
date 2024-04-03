

var row_id =0;

function addRowLot(tableID)
{
    //TABLE VARIABLE
    var table = document.getElementById(tableID);
    //ROW COUNT OF ALL ITEMS IN THE TABLE
    var rowCount = table.rows.length;
    //ROW ID BASED ON TABLE ROW
    var row = table.insertRow(rowCount);
    //ROW ID VALUE
    row_id = Number($('#last').val())+1;
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
            if(i==3)
            {
                    var chk1=div.childNodes[0];
                    chk1.id = "chk_"+row_id;
                    var chk_value=div.childNodes[1];
                    chk_value.id = "chkval_"+row_id;   
            }

            switch(html_items.name) 
            {
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
                        dropdown: false
                    }); 
                    $("#start_"+row_id).keydown(prevent);
                }
            
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
                break; 
            }
            
         }       
         $('#last').val(row_id);
         row_id++;
                

               
           
         
}


function ontimechangejs()
{   
  
    var el_id=$(this).attr("id");
    var closest_row= $(this).closest('tr');                                                            // GET THE CLOSEST TABLE ROW
    var next_start_id=closest_row.next().find('td:nth-child(5)').children().children().attr('id');    // GET THE NEXT TABLE START TIME SELECTION
    $("#"+next_start_id).val($(this).val());                                                         // SET THE INSERTED START TIME ID BASED ON THE PREVIOUS END TIME

    var pattern=new RegExp("^(2[0-3]|[01]?[0-9]):([0-5]?[0-9])$");
    var hasError = !$("#"+el_id).val().match(pattern);

    var numb = el_id.match(/\d/g);
    numb = numb.join("");
  
    var start=$('#start_'+numb).val();
    var end=$('#end_'+numb).val();
   
    if(start>=end||checkwrongtime($("#"+el_id).val())==1||hasError)
    {
            $("#"+el_id).addClass('form-control-warning');
            $("#"+el_id).parent().addClass('has-warning');

    }
    else if (checkwrongtime($("#"+el_id).val())==0)
    {
            $("#"+el_id).removeClass('form-control-warning');
            $("#"+el_id).parent().removeClass('has-warning');  
            
    }
    else
    {
    }
}
 

function closetime(element)
{
    $(element).on('show.bs.select', function () 
    {
    if($('.ui-timepicker').parent().hasClass("ui-helper-hidden ui-timepicker-hidden"))
    {
    }
    else
    {
    $('.ui-timepicker').parent().addClass("ui-helper-hidden ui-timepicker-hidden");
    }
    });
}

function checkwrongtime(value)
{
    if((value>='00:00' && value <='05:59') ||
        (value>='07:56' && value <='07:59') ||
        (value>='10:01' && value <='10:09') ||
        (value>='12:16' && value <='13:14') ||
        (value>='15:01' && value <='15:14') ||
        (value>='17:01' && value <='17:14') ||
        (value>='20:01' && value <='23:59'))
        {
           return 1;
        }
        else
        {
            return 0;
        }
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

