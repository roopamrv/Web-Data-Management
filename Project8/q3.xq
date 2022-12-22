<queryresults_3>
    {
        for $result in distinct-values(doc("reed.xml")//course/subj)
            return <dept>{$result , "-", count(distinct-values(doc("reed.xml")//course[subj=$result]//title))}
            <br/>
            </dept>
    }
</queryresults_3>