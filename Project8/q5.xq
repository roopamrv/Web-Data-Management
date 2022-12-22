<queryresults_5>
{
    for $result in distinct-values(doc("reed.xml")//course/instructor)
        return <instructor>
            <name>NAME: {$result} </name> 
                <titles>
                    {
                        for $subresult in distinct-values(doc("reed.xml")//course[instructor=$result]/title)
                            return <p>   TITLE: {$subresult} </p>
                    }
                </titles>
        </instructor>
}
</queryresults_5>


