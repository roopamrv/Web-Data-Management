import javax.xml.xpath.*;
import org.xml.sax.InputSource;
import org.w3c.dom.*;

public class xpath {
    
    public static void fetch(String query, String doc) throws Exception{
        XPathFactory xpfactory = XPathFactory.newInstance();
        XPath xpath = xpfactory.newXPath();
        InputSource inputsrc = new InputSource(doc);

        NodeList nlist = (NodeList) xpath.evaluate(query, inputsrc, XPathConstants.NODESET);

        for(int i = 0; i< nlist.getLength();i++){
            System.out.println(nlist.item(i).getTextContent());
        }
    }

    public static void main(String[] args) {
        try{
            System.out.println("TITLE of Subject MATH taught in LAB 204");
            fetch("//root//course[subj=\"MATH\" and place//building =\"LIB\" and place//room =\"204\"]//title","reed.xml");
            System.out.println();

            System.out.println("INSTRUCTOR who Teaches subject MATH");
            fetch("//root//course[subj=\"MATH\" and crse=\"412\"]//instructor","reed.xml");
            System.out.println();

            System.out.println("TITLES of subject taught by WIETING");
            fetch("//root//course[instructor=\"Wieting\"]//title", "reed.xml");
            System.out.println();
        }
        catch(Exception e){
            e.printStackTrace();
        }
    }
}
