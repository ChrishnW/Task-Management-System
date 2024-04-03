
// RETURN THE NUMBER FROM STRING
function GetElementNumber(element_id)
{
    var numb = element_id.match(/\d/g);
    numb = numb.join("");
    return numb;
}

// RETURN THE LENGTH OF THE THE ARRAY
function GetLength(element_id)
{
    var el=document.getElementById(element_id);
    return el.length;
}

//CHECK IF STRING CONTAINS LETTERS
function CheckLetters(string)
{
    if(string.match(/[a-z]/i))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function CheckModel(sec_id,array)
{
  for(var i=0;i<array.length;i++)
  {
    if(array[i]==sec_id)
    {
      return true;
    }
  }
}

//SET INPUT WITH SINGLE INPUT PATTERN AND COMPLETED INPUT PATTERN
function setInputPattern(element,p1,p2)
{
  var el=document.getElementById(element);
  el.addEventListener('input', function()
  {
    var value=document.getElementById(element).value;
    var pattern=new RegExp(p1);
    if(pattern.test(value))
    {
      document.getElementById(element).value = value;
    }
    else
    {
      var txt = value.slice(0, -1);
      document.getElementById(element).value = txt;
    }

    var pattern2=new RegExp(p2);
    if(pattern2.test(value))
    {
        $("#"+element).removeClass('form-control-warning');
        $("#"+element).parent().removeClass('has-warning');  
    }
    else
    {
        $("#"+element).addClass('form-control-warning');
        $("#"+element).parent().addClass('has-warning');
    }
  });

  el.addEventListener('paste', function(evt)
  {
    var value=evt.clipboardData.getData('text/plain');
    var pattern=new RegExp(p2);
    if(pattern!='')
    {
      if(pattern.test(value))
      {
        document.getElementById(element).value += string.value;
      }
      else
      {
          evt.preventDefault();
      }
    }
  });

}


//CHECK IF PATTERN IS CORRECT
function checkCorrectPattern(value,pat)
{
  var pattern=new RegExp(pat);
  if(pattern.test(value))
  {
    return true;
  }
  else
  {
    return false;
  }
}