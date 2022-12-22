<queryresults_4>
{
    for $result in distinct-values(doc("reed.xml")//course/instructor)
        return <instructor> 
        {"Name:", $result, ",  Count:" ,count(doc("reed.xml")//course[instructor=$result])}
        <br/>
        </instructor>
}
</queryresults_4>