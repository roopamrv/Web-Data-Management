<queryresults_2>
        {
            for $result in distinct-values(doc("reed.xml")//course/title)
            return <course> 
                <p>Title: {$result}</p> <br/>
                <instructors> 
                {for $subresults in distinct-values(doc("reed.xml")//course[title= $result]/instructor)
                    return <instructor>
                    Instructor: {$subresults} <br/>
                    </instructor>
                }
                </instructors>
                </course>
        }
    </queryresults_2>