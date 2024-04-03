var row_id =2;

function addRowOut(param)
{
    //TABLE VARIABLE
    var table = document.getElementById(param);
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
           
            if(i==1)
            {   
                var select=html_items.childNodes[2];
                select.id="lot_"+row_id;
                $('#lot_'+row_id).selectpicker('refresh');
                $('#lot_'+row_id).selectpicker("val","");
                $('#lot_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]); 

            }

            if(i==2)
            {   
                var select=html_items.childNodes[2];
                select.id="type_"+row_id;
                $('#type_'+row_id).selectpicker('refresh');
                $('#type_'+row_id).selectpicker("val","");
                $('#type_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]);
                var input_text=div.childNodes[1];
                input_text.id="typeValue_"+row_id; 
            }

            if(i==3)
            {   
                var select=html_items.childNodes[2];
                select.id="block_"+row_id;
                $('#block_'+row_id).selectpicker('refresh');
                $('#block_'+row_id).selectpicker("val","");
                $('#block_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]); 
                var input_text=div.childNodes[1];
                input_text.id="blockValue_"+row_id;
                
            }
            
            
            switch(html_items.name) 
            {   
                case "chk[]":
                div.childNodes[0].setAttribute("checked","checked");
                div.childNodes[0].id = "chk_"+row_id;
                break;

                case "ac_qty[]":
                div.childNodes[0].id = "ac_qty_"+row_id;
                break;
                
                case "sdate[]":
                div.childNodes[0].id = "sdate_"+row_id;
                break;

                case "pprob[]":
                div.childNodes[0].id = "pprob_"+row_id;
                break;

                case "aprob[]":
                div.childNodes[0].id = "aprob_"+row_id;
                break;
                case "qprob[]":
                div.childNodes[0].id = "qprob_"+row_id;
                break;

                case "fprob[]":
                div.childNodes[0].id = "fprob_"+row_id;
                break;

                case "oprob[]":
                div.childNodes[0].id = "oprob_"+row_id;
                break;

                
            }
            
         }      
                $('#last').val(row_id);
                // alert(row_id);
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

function deleteRow(tableID) 
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

