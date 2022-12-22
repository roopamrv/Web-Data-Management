<queryresults_1>   
    {
        for $crse in doc("reed.xml")//course
        where $crse/subj = "MATH" and $crse/place/building = "LIB" and $crse/place/room = "204" 
        (:~ <p>For each MATH course taught in room LIB 204</p> ~:)
        return <course>
                    Course Title: { $crse/title } 
                    Instructor: { $crse/instructor }  
                    Start Time: { $crse/time/start_time } 
                    End Time: { $crse/time/end_time }
                </course>
    }
</queryresults_1>

        