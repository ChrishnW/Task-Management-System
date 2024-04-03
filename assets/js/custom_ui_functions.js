function addzero(val)
{
    var value=val;
    var size=value.length;
    if(value.length==5)
    {
        return value;
    }
    else if(value.length==4)
    {
        return '0'+value;
    }
    else
    {  
        return value;
    }
}
