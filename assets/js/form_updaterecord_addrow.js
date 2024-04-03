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
           
          
            if(i==1)
            {   
                var select=html_items.childNodes[2];
                select.id="type_"+row_id;
                $('#type_'+row_id).selectpicker('refresh');
                $('#type_'+row_id).selectpicker("val","");
                $('#type_'+row_id).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]); 
            }

        

            switch(html_items.name) 
            {
               case "chk[]":
               div.childNodes[0].id="chk_"+row_id;   
               div.childNodes[0].setAttribute("checked","checked"); 
               break;

               case "quantity[]":
               div.childNodes[0].id = "quantity_" + row_id;
               break; 

               case "serial[]":
               div.childNodes[0].id = "serial_" + row_id;    
            }
            
         }       
         $('#last').val(row_id);
         row_id++;  
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

