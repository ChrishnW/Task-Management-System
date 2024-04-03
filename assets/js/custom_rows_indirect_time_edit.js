var ind_row_id=0;



function addRowLotInd(tableID)
{
    //TABLE VARIABLE
    var table = document.getElementById(tableID);
    //ROW COUNT OF ALL ITEMS IN THE TABLE
    var rowCount = table.rows.length;
    //ROW ID BASED ON TABLE ROW
    var row = table.insertRow(rowCount);
    //ROW ID VALUE
    ind_row_id = Number($('#ind_last').val())+1;
    row.id = 'id' + ind_row_id;

    //==========================CLOSE SELECT PICKER DIALOG===============================//
   
    $('[data-toggle="dropdown"]').parent().removeClass('open');
  
    var colCount = table.rows[0].cells.length;
        for(var i=0; i<colCount; i++) 
        {
            var newcell = row.insertCell(i);

             //======TD ELEMENT====//
             newcell.innerHTML = table.rows[0].cells[i].innerHTML;
             
            //======DIV ELEMENT=====//
            var div=newcell.childNodes[0];
          
            //=====ITEMS INSIDE THE DIV
            var html_items=div.childNodes[0];

            //CHECK NODE BASED ON name defined on the HTML element tags//
         
            if(i==0)
            {   
                var select=html_items.childNodes[2];
                select.id="indirectcode_"+ind_row_id;
                var jCode="#indirectcode_"+ind_row_id;
                $(jCode).selectpicker('refresh');
                $(jCode).selectpicker("val","");
                $(jCode).selectpicker('refresh');
                html_items.removeChild(html_items.childNodes[0]); 
                closetime(jCode);    
            }
            if(i==1)
            {
                div.childNodes[0].id = "indirectlot_" + ind_row_id;
                div.childNodes[0].value="";
                $("#indirectlot_"+ind_row_id).keydown(prevent);
                div.childNodes[1].id = "indlot_" + ind_row_id;
                div.childNodes[1].value="";
            }
            if(i==2)
            {   
                div.childNodes[0].id = "indirecttype_" + ind_row_id;
                div.childNodes[0].value="";
                $("#indirecttype_"+ind_row_id).keydown(prevent);
                div.childNodes[1].id = "indtype_" + ind_row_id;
                div.childNodes[1].value="";
            }
            if(i==3)
            {   
                div.childNodes[0].id = "indirectblock_" + ind_row_id;
                div.childNodes[0].value="";
                $("#indirectblock_"+ind_row_id).keydown(prevent);
                div.childNodes[1].id = "indblock_" + ind_row_id;
                div.childNodes[1].value="";
            }
            if(i==4)
            {
                    var chk1=div.childNodes[0];
                    chk1.id = "indirectchk_"+ind_row_id;
                    var chk_value=div.childNodes[1];
                    chk_value.id = "indchkval_"+ind_row_id;  
            }
          

            switch(html_items.name) 
            {
                case "indirectcode[]":
                div.childNodes[0].selectedIndex = 0;
                div.childNodes[0].id = "indirectcode_" + ind_row_id;
                break; 

                case "indirectstart[]":
                div.childNodes[0].id = "indirectstart_" + ind_row_id;

                break;

                case "indirectend[]":
                div.childNodes[0].id = "indirectend_" + ind_row_id;
                break; 

                case "remarks[]":
                div.childNodes[0].id = "remarks_" + ind_row_id;
                break; 
            }       
            
        }
          
           $('#ind_last').val(ind_row_id);
           ind_row_id++;  
         
}




function deleteRowIndirect(tableID) 
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

function prevent(event)
{
    event.preventDefault();
    if (event.keyCode === 13) 
    {
        event.preventDefault();
    }
}





