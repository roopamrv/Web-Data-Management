import javax.xml.parsers.*;
import org.w3c.dom.*;
import javax.xml.transform.*;
import javax.xml.transform.dom.*;
import javax.xml.transform.stream.*;
import java.io.*;


class XSLT {
    public static void main ( String argv[] ) throws Exception {
	File stylesheet = new File("math.xsl");
	File xmlfile  = new File("reed.xml");
	OutputStream myhtmlfile = new FileOutputStream("math_reed.html");
	DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
	DocumentBuilder db = dbf.newDocumentBuilder();
	Document document = db.parse(xmlfile);
	StreamSource stylesource = new StreamSource(stylesheet);
	TransformerFactory tf = TransformerFactory.newInstance();
	Transformer transformer = tf.newTransformer(stylesource);
	DOMSource source = new DOMSource(document);
	StreamResult result = new StreamResult(myhtmlfile);
	transformer.transform(source,result);
    }
}
