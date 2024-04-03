var row_id =2;

function addRowLot(param)
{
    //TABLE VARIABLE
    var table = document.getElementById('dataTable');
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
            // var div=div2.childNodes[0];
          
            //=====ITEMS INSIDE THE DIV
            var html_items=div.childNodes[0];
            //CHECK NODE BASED ON name defined on the HTML element tags//

            if(i==2)
            {   
                var select=html_items.childNodes[2];
                select.id="type_"+row_id;
                $('#type_'+row_id).selectpicker('refresh');
                $('#type_'+row_id).selectpicker("val","");
                $('#type_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]); 
            }

            if(param=="true")
            {
                if(i==4)
                {   
                    var select=html_items.childNodes[2];
                    select.id="block_"+row_id;
                    $('#block_'+row_id).selectpicker('refresh');
                    $('#block_'+row_id).selectpicker("val","");
                    $('#block_'+row_id).selectpicker('refresh');
                    html_items.removeChild(html_items.childNodes[0]); 
                }
            }
            
            switch(html_items.name) 
            {   
                case "chk[]":
                // div.childNodes[0].setAttribute("checked","checked");
                div.childNodes[0].id = "chk_"+row_id;
                break;

                case "lotno[]":
                // div.childNodes[0].setAttribute("checked","checked");
                div.childNodes[0].id = "lot_"+row_id;
                break;

                case "lotqty[]":
                div.childNodes[0].id = "lotqty_"+row_id;
                break;
                
                case "ddate[]":
                div.childNodes[0].id = "date_"+row_id;
                break;

                case "serialno[]":
                div.childNodes[0].id = "serial_"+row_id;
                break;
            }
            
         }      
                 row_id++;    
}




function deleteRowLot(tableID) 
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

function deleteRowAll(tableID) 
{
    try 
    {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;
        if(rowCount!=1) 
        {
            for(var i=0; i<rowCount; i++) 
            {
                var row    = table.rows[i];
                var div    = row.cells[0].childNodes[0];
                var chkbox = div.childNodes[0];
                if(null != chkbox && true == chkbox.checked) 
                {   
                    if(i!=0)
                    { 
                        table.deleteRow(i);
                        rowCount--;
                        i--;
                    }
                }
            }
        }
    }
    catch(e)
    {
        // alert(e);
    }  
}

