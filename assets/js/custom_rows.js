var row_id =2;
function addRow1(tableID)
{
    
    var table = document.getElementById(tableID);
    var rowCount = table.rows.length;
    //ROW ID BASED ON TABLE ROW
    var row = table.insertRow(rowCount);
    row.id = 'id' + row_id;
    var x = document.getElementById(tableID).rows[0].cells.length;
    
    var colCount = table.rows[0].cells.length;
    for(var i=0; i<colCount; i++) 
    {
             var newcell = row.insertCell(i);
             newcell.innerHTML = table.rows[0].cells[i].innerHTML;
             var div=newcell.childNodes[0];
          
             //=====ITEMS INSIDE THE DIV
             var html_items=div.childNodes[0];

             
             if(i==1)
             {
                var select=html_items.childNodes[2];
                select.id="type_"+row_id;
                $('#type_'+row_id).selectpicker('refresh');
                $('#type_'+row_id).selectpicker("val","");
                $('#type_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]);  
             }
             if(i==2)
             {
                var select=html_items.childNodes[2];
                select.id="block_"+row_id;
                $('#block_'+row_id).selectpicker('refresh');
                $('#block_'+row_id).selectpicker("val","");
                $('#block_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]);
             }

            //CHECK NODE BASED ON name=""
            switch(html_items.name) 
            {
                case "chk[]":
                div.childNodes[0].setAttribute("checked","checked");
                div.childNodes[0].id = "chk_"+row_id;
                break;
            } 
     
    }
            $('#count').val(row_id); 
            row_id++;        
            
}





function deleteRow1(tableID) 
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
        alert(e);
    }  
}



