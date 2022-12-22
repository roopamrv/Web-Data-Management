import java.io.File;
import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

public class dom{
    public static void main(String[] args){
    try{
        File inputFile = new File("reed.xml");
        DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
        DocumentBuilder dbBuilder = dbFactory.newDocumentBuilder();
        Document doc = dbBuilder.parse(inputFile);
        doc.getDocumentElement().normalize();
        System.out.println("Root element: " + doc.getDocumentElement().getNodeName() );
        NodeList nList = doc.getElementsByTagName("course");
        
        for(int temp =0;temp<nList.getLength();temp++){
        Node nNode = nList.item(temp);
        //System.out.println("\n Current: " + nNode.getNodeName());
        
        if (nNode.getNodeType() == Node.ELEMENT_NODE) {
            Element eElement = (Element) nNode;
            String subject = eElement
                  .getElementsByTagName("subj")
                  .item(0)
                  .getTextContent();  
            
            String building = eElement
            .getElementsByTagName("building")
            .item(0)
            .getTextContent(); 

            String room = eElement
            .getElementsByTagName("room")
            .item(0)
            .getTextContent(); 
            //System.out.println(subject);

        if(subject.equals("MATH") && room.equals("204") 
            && building.equals("LIB")){
            String title = eElement
            .getElementsByTagName("title")
            .item(0)
            .getTextContent();
            System.out.println("Title: "+title);
        }   
        }
        }
    }
    catch(Exception e){
        e.printStackTrace();
    }
}
}
